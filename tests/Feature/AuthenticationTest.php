<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

function makeAdmin(): User
{
    $admin = User::factory()->create(['password' => 'secret-pass-123']);
    Profile::create(['user_id' => $admin->id, 'type' => 'admin']);

    return $admin;
}

test('login form is public and independent of the admin layout', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Entre com seu email e senha')
        ->assertSee('name="_token"', false)
        ->assertDontSee('app-sidebar', false)
        ->assertDontSee('app-header', false);
});

test('register form is public and independent of the admin layout', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSee('Preencha os campos para se cadastrar')
        ->assertDontSee('app-sidebar', false);
});

test('visitor is redirected to login on home and admin routes', function () {
    $this->get('/')->assertRedirect(route('login'));
    $this->get('/admin/users')->assertRedirect(route('login'));
});

test('common user logs in, lands on home and session id is regenerated', function () {
    $user = User::factory()->create(['password' => 'secret-pass-123']);

    $this->get('/login');
    $before = session()->getId();

    $this->post('/login', ['email' => $user->email, 'password' => 'secret-pass-123'])
        ->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);
    expect(session()->getId())->not->toBe($before);
    $this->get('/')->assertOk();
});

test('common user does not follow a previously requested admin destination', function () {
    $user = User::factory()->create(['password' => 'secret-pass-123']);

    $this->get('/admin/users')->assertRedirect(route('login'));

    $this->post('/login', ['email' => $user->email, 'password' => 'secret-pass-123'])
        ->assertRedirect(route('home'));
});

test('admin goes to the panel and respects the requested internal destination', function () {
    $admin = makeAdmin();

    $this->post('/login', ['email' => $admin->email, 'password' => 'secret-pass-123'])
        ->assertRedirect(route('admin.users.index'));

    auth()->logout();
    $this->flushSession();

    $this->get('/admin/users/'.$admin->id.'/edit')->assertRedirect(route('login'));
    $this->post('/login', ['email' => $admin->email, 'password' => 'secret-pass-123'])
        ->assertRedirect(route('admin.user.edit', $admin->id));
});

test('invalid credentials show a generic error, keep the email and never the password', function () {
    $user = User::factory()->create(['password' => 'secret-pass-123']);

    $this->from('/login')
        ->post('/login', ['email' => $user->email, 'password' => 'wrong-password'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['email' => 'Credenciais inválidas.'])
        ->assertSessionHasInput('email', $user->email)
        ->assertSessionMissing('_old_input.password');

    $this->assertGuest();

    $this->from('/login')
        ->post('/login', ['email' => 'nobody@example.com', 'password' => 'whatever'])
        ->assertSessionHasErrors(['email' => 'Credenciais inválidas.']);
});

test('login validates required fields', function () {
    $this->post('/login', [])->assertSessionHasErrors(['email', 'password']);
});

test('login is throttled after 5 attempts', function () {
    RateLimiter::clear('nobody@example.com|127.0.0.1');

    foreach (range(1, 5) as $attempt) {
        $this->from('/login')
            ->post('/login', ['email' => 'nobody@example.com', 'password' => 'x'])
            ->assertSessionHasErrors(['email' => 'Credenciais inválidas.']);
    }

    $this->from('/login')
        ->post('/login', ['email' => 'nobody@example.com', 'password' => 'x'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');

    expect(session('errors')->first('email'))->toContain('Muitas tentativas');
});

test('logout works only via POST, invalidates the session and redirects to login', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/logout')->assertStatus(405);

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

test('guest cannot post to logout', function () {
    $this->post('/logout')->assertRedirect(route('login'));
});

test('header shows a POST logout form with csrf for authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/')
        ->assertOk()
        ->assertSee('action="'.route('logout').'"', false)
        ->assertSee('method="POST"', false)
        ->assertSee('Sair');
});
