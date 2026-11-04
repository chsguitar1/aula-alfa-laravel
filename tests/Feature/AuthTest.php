<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

function adminUser(): User
{
    $user = User::factory()->create();
    Profile::create(['user_id' => $user->id, 'type' => Profile::TYPE_ADMIN, 'address' => 'Local']);

    return $user;
}

// ---------- Acesso de visitantes e de usuários comuns ----------

it('redirects guests from / and /admin/users to the login', function () {
    $this->get('/')->assertRedirect(route('login'));
    $this->get('/admin/users')->assertRedirect(route('login'));
});

it('returns 403 for authenticated users without admin profile on every admin route', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    $this->actingAs($user);

    $this->get('/admin/users')->assertForbidden();
    $this->get("/admin/user/{$other->id}")->assertForbidden();
    $this->get(route('admin.user.edit', $other))->assertForbidden();
    $this->put(route('admin.user.update', $other), ['name' => 'x', 'email' => 'x@x.com'])->assertForbidden();
    $this->put(route('admin.user.updateProfile', $other), ['type' => 'PF'])->assertForbidden();
    $this->put(route('admin.user.updateLanguages', $other), [])->assertForbidden();
    $this->delete(route('admin.user.destroy', $other))->assertForbidden();
});

it('also returns 403 for users whose profile is not admin', function () {
    $user = User::factory()->create();
    Profile::create(['user_id' => $user->id, 'type' => 'PF', 'address' => null]);

    $this->actingAs($user)->get('/admin/users')->assertForbidden();
});

it('lets admins reach the admin panel and the home page', function () {
    $this->actingAs(adminUser())->get('/admin/users')->assertOk();
    $this->actingAs(adminUser())->get('/')->assertOk();
});

// ---------- Login ----------

it('shows a standalone login form with csrf token', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('name="_token"', false)
        ->assertSee('action="'.route('login.store').'"', false)
        ->assertSee(route('register'), false)
        ->assertDontSee('app-sidebar', false)
        ->assertDontSee('app-header', false);
});

it('logs in a regular user and sends them to the home page', function () {
    $user = User::factory()->create(['email' => 'comum@example.com']);

    $this->post('/login', ['email' => 'comum@example.com', 'password' => 'password'])
        ->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);
});

it('sends admins to the panel after login', function () {
    $admin = adminUser();

    $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect(route('admin.users.index'));
});

it('honours the previously requested admin url for admins', function () {
    $admin = adminUser();

    $this->get(route('admin.user.edit', $admin))->assertRedirect(route('login'));

    $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect(route('admin.user.edit', $admin));
});

it('never sends a regular user to an admin destination after login', function () {
    $user = User::factory()->create();

    $this->get('/admin/users')->assertRedirect(route('login'));

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect(route('home'));
});

it('rejects invalid credentials with a generic message and keeps only the email', function () {
    $user = User::factory()->create();

    $this->from('/login')
        ->post('/login', ['email' => $user->email, 'password' => 'senha-errada'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['email' => 'Credenciais inválidas.'])
        ->assertSessionHasInput('email', $user->email)
        ->assertSessionMissing('_old_input.password');

    $this->assertGuest();
});

it('uses the same message for unknown emails', function () {
    $this->from('/login')
        ->post('/login', ['email' => 'ninguem@example.com', 'password' => 'qualquer-senha'])
        ->assertSessionHasErrors(['email' => 'Credenciais inválidas.']);
});

it('validates required login fields', function () {
    $this->from('/login')
        ->post('/login', [])
        ->assertSessionHasErrors(['email', 'password']);
});

it('rate limits login attempts', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $i) {
        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'errada'])
            ->assertSessionHasErrors(['email' => 'Credenciais inválidas.']);
    }

    // 6ª tentativa (mesmo com a senha correta) é bloqueada.
    $this->from('/login')
        ->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');

    expect(session('errors')->first('email'))->toContain('Muitas tentativas');
    $this->assertGuest();
});

// ---------- Logout ----------

it('logs out with POST, invalidates the session and redirects to login', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/logout')->assertRedirect(route('login'));

    $this->assertGuest();
});

it('does not log out via GET', function () {
    $this->actingAs(User::factory()->create())->get('/logout')->assertStatus(405);
    $this->assertAuthenticated();
});

it('requires authentication to log out', function () {
    $this->post('/logout')->assertRedirect(route('login'));
});

it('renders the logout button as a csrf-protected POST form in the header', function () {
    $this->actingAs(adminUser())
        ->get('/admin/users')
        ->assertSee('action="'.route('logout').'"', false)
        ->assertSee('Sair');
});

// ---------- Cadastro ----------

it('shows a standalone register form', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSee('name="_token"', false)
        ->assertSee(route('login'), false)
        ->assertDontSee('app-sidebar', false);
});

it('creates a regular user from the public form and sends them to login', function () {
    $this->post('/store', [
        'name' => 'Maria',
        'email' => 'maria@example.com',
        'password' => 'senha-segura-1',
    ])->assertRedirect(route('login'))->assertSessionHas('success');

    $user = User::where('email', 'maria@example.com')->firstOrFail();
    expect($user->isAdmin())->toBeFalse();
    expect($user->profile)->toBeNull();
});

it('ignores a forged type=admin in the public register', function () {
    $this->post('/store', [
        'name' => 'Mallory',
        'email' => 'mallory@example.com',
        'password' => 'senha-segura-1',
        'type' => 'admin',
        'profile' => ['type' => 'admin'],
    ]);

    $user = User::where('email', 'mallory@example.com')->firstOrFail();
    expect($user->isAdmin())->toBeFalse();
    $this->assertDatabaseMissing('profile', ['type' => 'admin', 'user_id' => $user->id]);
    $this->actingAs($user)->get('/admin/users')->assertForbidden();
});

it('validates register input and keeps non-sensitive values', function () {
    User::factory()->create(['email' => 'dup@example.com']);

    $this->from('/register')
        ->post('/store', ['name' => 'Fulano', 'email' => 'dup@example.com', 'password' => 'curta'])
        ->assertRedirect('/register')
        ->assertSessionHasErrors(['email', 'password'])
        ->assertSessionHasInput('name', 'Fulano')
        ->assertSessionMissing('_old_input.password');
});

it('returns an admin who registers a user to the admin list', function () {
    $this->actingAs(adminUser())
        ->post('/store', ['name' => 'Novo', 'email' => 'novo@example.com', 'password' => 'senha-segura-1'])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success', 'Usuário criado com sucesso!');
});
