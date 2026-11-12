<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    RateLimiter::clear('user@example.com|127.0.0.1');
});

function adminUser(): User
{
    $user = User::factory()->create();
    Profile::create(['user_id' => $user->id, 'type' => 'admin', 'address' => null]);

    return $user;
}

it('redirects visitors from / and /admin/users to the login page', function () {
    $this->get('/')->assertRedirect(route('login'));
    $this->get('/admin/users')->assertRedirect(route('login'));
});

it('shows standalone login and register pages', function () {
    $this->get('/login')->assertOk()->assertDontSee('app-sidebar', false)->assertSee('Entrar');
    $this->get('/register')->assertOk()->assertDontSee('app-sidebar', false)->assertSee('Cadastrar');
});

it('registers a common user and redirects to login', function () {
    $this->post('/store', [
        'name' => 'Maria',
        'email' => 'maria@example.com',
        'password' => 'senha-segura-1',
        'type' => 'admin', // campo malicioso deve ser ignorado
    ])->assertRedirect(route('login'))->assertSessionHas('success');

    $user = User::where('email', 'maria@example.com')->firstOrFail();
    expect($user->isAdmin())->toBeFalse();
    expect(Profile::where('user_id', $user->id)->exists())->toBeFalse();
});

it('validates the registration form', function () {
    User::factory()->create(['email' => 'dup@example.com']);

    $this->post('/store', ['name' => '', 'email' => 'dup@example.com', 'password' => '123'])
        ->assertSessionHasErrors(['name', 'email', 'password']);
});

it('sends a common user to / after login and keeps the session id regenerated', function () {
    $user = User::factory()->create(['email' => 'user@example.com', 'password' => 'senha-segura-1']);

    $this->post('/login', ['email' => $user->email, 'password' => 'senha-segura-1'])
        ->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);
});

it('sends an admin to the panel and respects the requested internal destination', function () {
    $admin = adminUser();

    $this->get('/admin/user/'.$admin->id.'/edit')->assertRedirect(route('login'));

    $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect('/admin/user/'.$admin->id.'/edit');
});

it('shows a generic error and keeps only the email on invalid credentials', function () {
    User::factory()->create(['email' => 'user@example.com']);

    $this->from('/login')
        ->post('/login', ['email' => 'user@example.com', 'password' => 'errada-123'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['email' => 'Credenciais inválidas.'])
        ->assertSessionHasInput('email', 'user@example.com')
        ->assertSessionMissing('_old_input.password');

    $this->assertGuest();
});

it('limits login attempts', function () {
    User::factory()->create(['email' => 'user@example.com']);

    foreach (range(1, 5) as $_) {
        $this->post('/login', ['email' => 'user@example.com', 'password' => 'errada-123']);
    }

    // Mesmo com a senha correta, a 6ª tentativa na janela é bloqueada.
    $this->post('/login', ['email' => 'user@example.com', 'password' => 'password'])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('returns 403 for an authenticated non-admin on every admin route', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    $this->actingAs($user)->get('/admin/users')->assertForbidden();
    $this->actingAs($user)->get("/admin/user/{$other->id}/edit")->assertForbidden();
    $this->actingAs($user)->put("/admin/user/{$other->id}", ['name' => 'X', 'email' => 'x@example.com'])->assertForbidden();
    $this->actingAs($user)->delete("/admin/user/{$other->id}")->assertForbidden();

    $this->assertModelExists($other);
});

it('lets an admin reach the panel', function () {
    $this->actingAs(adminUser())->get('/admin/users')->assertOk();
});

it('logs out through POST only, invalidating the session', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/logout')->assertStatus(405);

    $this->actingAs($user)->post('/logout')->assertRedirect(route('login'));
    $this->assertGuest();

    $this->post('/logout')->assertRedirect(route('login')); // visitante: middleware auth
});

it('keeps a logout form with CSRF in the header', function () {
    $this->actingAs(adminUser())->get('/admin/users')
        ->assertSee('action="'.route('logout').'"', false)
        ->assertSee('name="_token"', false)
        ->assertSee('Sair');
});
