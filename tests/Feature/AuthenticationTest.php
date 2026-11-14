<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

function makeAdmin(): User
{
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => 'admin']);

    return $admin;
}

beforeEach(fn () => RateLimiter::clear('login'));

// ---------- Visitante ----------

test('visitor is redirected to login from home and admin area', function (string $path) {
    $this->get($path)->assertRedirect(route('login'));
})->with(['/', '/admin/users', '/admin/users/1/edit']);

test('login and register pages are standalone forms', function () {
    $this->get('/login')->assertOk()
        ->assertSee('name="email"', false)->assertSee('name="_token"', false)
        ->assertDontSee('app-sidebar', false)->assertDontSee('app-header', false)
        ->assertSee(route('register'), false);

    $this->get('/register')->assertOk()
        ->assertSee('name="name"', false)->assertSee(route('login'), false)
        ->assertDontSee('app-sidebar', false)->assertDontSee('app-header', false);
});

// ---------- Login ----------

test('regular user logs in and lands on home', function () {
    $user = User::factory()->create(['password' => 'secret-pass-1']);

    $this->post('/login', ['email' => $user->email, 'password' => 'secret-pass-1'])
        ->assertRedirect('/');

    $this->assertAuthenticatedAs($user);
    $this->get('/')->assertOk();
});

test('regular user ignores stored admin destination', function () {
    $user = User::factory()->create(['password' => 'secret-pass-1']);

    $this->get('/admin/users')->assertRedirect(route('login'));
    $this->post('/login', ['email' => $user->email, 'password' => 'secret-pass-1'])->assertRedirect('/');
});

test('admin logs in to panel and honors intended internal url', function () {
    $admin = makeAdmin();
    $admin->update(['password' => 'secret-pass-1']);

    $this->post('/login', ['email' => $admin->email, 'password' => 'secret-pass-1'])
        ->assertRedirect(route('admin.users.index'));

    auth()->logout();
    $this->flushSession();

    $this->get("/admin/users/{$admin->id}/edit")->assertRedirect(route('login'));
    $this->post('/login', ['email' => $admin->email, 'password' => 'secret-pass-1'])
        ->assertRedirect("/admin/users/{$admin->id}/edit");
});

test('login regenerates the session id', function () {
    $user = User::factory()->create(['password' => 'secret-pass-1']);
    $this->get('/login');
    $before = session()->getId();

    $this->post('/login', ['email' => $user->email, 'password' => 'secret-pass-1']);

    expect(session()->getId())->not->toBe($before);
});

test('invalid credentials give generic error, keep email, never repopulate password', function () {
    $user = User::factory()->create(['password' => 'secret-pass-1']);

    $response = $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'wrong-pass']);

    $response->assertRedirect('/login')
        ->assertSessionHasErrors(['email' => 'As credenciais informadas não conferem.']);
    $this->assertGuest();

    $page = $this->get('/login');
    $page->assertSee('value="'.$user->email.'"', false)->assertDontSee('wrong-pass');

    // Email inexistente recebe exatamente a mesma mensagem.
    $this->from('/login')->post('/login', ['email' => 'nobody@example.test', 'password' => 'x'])
        ->assertSessionHasErrors(['email' => 'As credenciais informadas não conferem.']);
});

test('login validates required fields', function () {
    $this->post('/login', [])->assertSessionHasErrors(['email', 'password']);
});

test('login is throttled after five attempts', function () {
    $user = User::factory()->create(['password' => 'secret-pass-1']);

    foreach (range(1, 5) as $attempt) {
        $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])->assertStatus(302);
    }

    $this->post('/login', ['email' => $user->email, 'password' => 'secret-pass-1'])
        ->assertStatus(429);
    $this->assertGuest();
});

// ---------- Logout ----------

test('logout is POST only, invalidates session and redirects to login', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $oldToken = csrf_token();

    $this->get('/logout')->assertStatus(405);

    $this->post('/logout')->assertRedirect(route('login'));
    $this->assertGuest();
    expect(csrf_token())->not->toBe($oldToken);
});

test('logout requires authentication', function () {
    $this->post('/logout')->assertRedirect(route('login'));
});

test('header exposes Sair as POST form with csrf', function () {
    $this->actingAs(User::factory()->create())->get('/')
        ->assertOk()
        ->assertSee('action="'.route('logout').'"', false)
        ->assertSee('Sair');
});

// ---------- Autorização ----------

test('authenticated non-admin gets 403 on every admin route', function () {
    $user = User::factory()->create();
    Profile::create(['user_id' => $user->id, 'type' => 'PF']);
    $this->actingAs($user);

    $this->get('/admin/users')->assertForbidden();
    $this->get('/admin/users/1/edit')->assertForbidden();
    $this->put('/admin/users/1', [])->assertForbidden();
    $this->put('/admin/users/1/profile', ['type' => 'PF'])->assertForbidden();
    $this->put('/admin/users/1/languages', [])->assertForbidden();
    $this->delete('/admin/users/1')->assertForbidden();
});

test('user without profile gets 403', function () {
    $this->actingAs(User::factory()->create())->get('/admin/users')->assertForbidden();
});

test('every admin route uses the admin middleware', function () {
    $adminRoutes = collect(app('router')->getRoutes()->getRoutes())
        ->filter(fn ($route) => str_starts_with($route->uri(), 'admin'));

    expect($adminRoutes)->not->toBeEmpty();
    $adminRoutes->each(fn ($route) => expect($route->gatherMiddleware())->toContain('admin'));
});

test('admin can access the panel', function () {
    $this->actingAs(makeAdmin())->get('/admin/users')->assertOk()->assertSee('Lista de usuários');
});

test('admin can open edit page', function () {
    $admin = makeAdmin();

    $this->actingAs($admin)->get("/admin/users/{$admin->id}/edit")->assertOk();
});

test('admin profile update persists address and cannot grant admin type', function () {
    $admin = makeAdmin();
    $target = User::factory()->create();
    $this->actingAs($admin);

    $this->put("/admin/users/{$target->id}/profile", ['type' => 'PF', 'address' => 'Rua A, 10'])
        ->assertRedirect(route('admin.users.index'));
    expect($target->fresh()->profile->address)->toBe('Rua A, 10');

    $this->put("/admin/users/{$target->id}/profile", ['type' => 'admin'])->assertSessionHasErrors('type');
});

// ---------- Cadastro ----------

test('public registration creates a regular user and redirects to login', function () {
    $this->post('/store', ['name' => 'Ana', 'email' => 'ana@example.test', 'password' => 'password123'])
        ->assertRedirect(route('login'))
        ->assertSessionHas('success');

    $user = User::where('email', 'ana@example.test')->first();
    expect($user)->not->toBeNull()
        ->and($user->isAdmin())->toBeFalse()
        ->and($user->password)->not->toBe('password123');
});

test('registration cannot create admin even when type is submitted', function () {
    $this->post('/store', [
        'name' => 'Eve', 'email' => 'eve@example.test', 'password' => 'password123', 'type' => 'admin',
    ]);

    expect(User::where('email', 'eve@example.test')->first()->isAdmin())->toBeFalse();
    expect(Profile::where('type', 'admin')->count())->toBe(0);
});

test('registration validates input, keeps non-sensitive values', function () {
    User::factory()->create(['email' => 'taken@example.test']);

    $this->from('/register')
        ->post('/store', ['name' => 'Bob', 'email' => 'taken@example.test', 'password' => 'short'])
        ->assertRedirect('/register')
        ->assertSessionHasErrors(['email', 'password'])
        ->assertSessionHasInput('name', 'Bob');

    $this->get('/register')->assertSee('value="Bob"', false)->assertDontSee('short');
});

test('admin creating a user returns to the admin list', function () {
    $this->actingAs(makeAdmin())
        ->post('/store', ['name' => 'Novo', 'email' => 'novo@example.test', 'password' => 'password123'])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success', 'Usuário criado com sucesso!');
});

// ---------- Seeder / schema ----------

test('admin seeder provisions an admin and is idempotent', function () {
    $this->seed(\Database\Seeders\AdminUserSeeder::class);
    $this->seed(\Database\Seeders\AdminUserSeeder::class);

    $admin = User::where('email', 'admin@example.test')->first();
    expect($admin->isAdmin())->toBeTrue()
        ->and(Profile::where('user_id', $admin->id)->count())->toBe(1);

    $this->post('/login', ['email' => 'admin@example.test', 'password' => 'admin-local-123'])
        ->assertRedirect(route('admin.users.index'));
});

test('profile table has nullable address column', function () {
    expect(\Illuminate\Support\Facades\Schema::hasColumn('profile', 'address'))->toBeTrue()
        ->and(\Illuminate\Support\Facades\Schema::hasColumn('profile', 'description'))->toBeFalse();

    $user = User::factory()->create();
    expect(Profile::create(['user_id' => $user->id, 'type' => 'PF'])->address)->toBeNull();
});

// ---------- Painel existente continua funcional ----------

test('admin can update languages using the real form field names', function () {
    $admin = makeAdmin();
    $target = User::factory()->create();
    $this->actingAs($admin);

    $this->put("/admin/users/{$target->id}/languages", ['languages' => ['PHP', 'Python']])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHasNoErrors();
    expect($target->languages()->pluck('language')->all())->toBe(['PHP', 'Python']);

    // Reenviar o formulário substitui a seleção, sem duplicar.
    $this->put("/admin/users/{$target->id}/languages", ['languages' => ['PHP']]);
    expect($target->languages()->pluck('language')->all())->toBe(['PHP']);
});

test('edit form posts language checkboxes as a flat array', function () {
    $admin = makeAdmin();

    $this->actingAs($admin)->get("/admin/users/{$admin->id}/edit")
        ->assertSee('name="languages[]"', false)
        ->assertDontSee('languages[][language]', false);
});

test('every named route used by the views exists', function () {
    $names = collect(glob(resource_path('views/{,*/}*.blade.php'), GLOB_BRACE))
        ->flatMap(function ($file) {
            preg_match_all("/route\('([\w.]+)'/", file_get_contents($file), $m);

            return $m[1];
        })->unique();

    expect($names)->not->toBeEmpty();
    $names->each(fn ($name) => expect(\Illuminate\Support\Facades\Route::has($name))->toBeTrue("rota {$name} ausente"));
});

test('login throttle also caps attempts per ip when rotating emails', function () {
    foreach (range(1, 20) as $i) {
        $this->post('/login', ['email' => "rotating{$i}@example.test", 'password' => 'x'])->assertStatus(302);
    }

    $this->post('/login', ['email' => 'rotating99@example.test', 'password' => 'x'])->assertStatus(429);
});
