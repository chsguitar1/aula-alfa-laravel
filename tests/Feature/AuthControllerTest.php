<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    // Fail closed before any migration if the test environment is misconfigured.
    expect(config('database.default'))->toBe('sqlite');
    expect(config('database.connections.sqlite.database'))->toBe(':memory:');
    expect(config('cache.default'))->toBe('array');
    expect(config('session.driver'))->toBe('array');
    $this->withoutVite();
});

test('login renders an independent form with csrf and registration link', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('name="_token"', false)
        ->assertSee('action="'.route('login.store').'"', false)
        ->assertSee(route('register'))
        ->assertDontSee('app-sidebar')
        ->assertDontSee('app-header');
});

test('login rejects missing or malformed credentials', function (array $payload, array $errors) {
    $this->from('/login')->post('/login', $payload)
        ->assertRedirect('/login')
        ->assertSessionHasErrors($errors);

    $this->assertGuest();
})->with([
    'required fields' => [[], ['email', 'password']],
    'invalid email' => [['email' => 'invalid', 'password' => 'password'], ['email']],
    'non-string password' => [['email' => 'hunter@example.test', 'password' => ['password']], ['password']],
]);

test('invalid credentials show a generic error and preserve only the email', function (bool $existing) {
    $email = 'hunter@example.test';
    if ($existing) {
        User::factory()->create(['email' => $email]);
    }

    $this->from('/login')->post('/login', ['email' => $email, 'password' => 'wrong-secret'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['email' => 'Email ou senha incorretos.'])
        ->assertSessionHas('_old_input.email', $email)
        ->assertSessionMissing('_old_input.password');

    $this->assertGuest();
    $this->withCookie(session()->getName(), session()->getId())
        ->get('/login')->assertSee('Email ou senha incorretos.')
        ->assertSee($email)->assertDontSee('wrong-secret');
})->with(['unknown account' => false, 'wrong password' => true]);

test('ordinary login rotates the session and redirects home regardless of an admin destination', function () {
    $user = User::factory()->create();
    $this->withSession(['url.intended' => url('/admin/users')]);
    $previousId = session()->getId();

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect('/');

    $this->assertAuthenticatedAs($user);
    expect(session()->getId())->not->toBe($previousId);
    Auth::forgetGuards();
    $this->get('/')->assertOk()->assertSee('Arquivos de Yharnam')
        ->assertSee($user->name)->assertSee('action="'.route('logout').'"', false)
        ->assertSee('name="_token"', false);
});

test('admin login redirects to the dashboard or the previously requested internal page', function (?string $intended, string $destination) {
    $user = User::factory()->create();
    // Match the current schema without changing the profile implementation owned by Dev 2.
    DB::table('profile')->insert(['user_id' => $user->id, 'type' => 'admin', 'description' => 'Test administrator']);
    if ($intended !== null) {
        $this->withSession(['url.intended' => url($intended)]);
    }

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect($destination);

    $this->assertAuthenticatedAs($user);
})->with([
    'default dashboard' => [null, '/admin/users'],
    'intended home' => ['/', '/'],
    'intended dashboard' => ['/admin/users', '/admin/users'],
]);

test('five failed attempts block even a valid password until the first failure expires', function () {
    $this->freezeTime();
    $user = User::factory()->create();
    for ($attempt = 0; $attempt < 5; $attempt++) {
        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors(['email' => 'Email ou senha incorretos.']);
    }

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertSessionHasErrors(['email' => 'Muitas tentativas. Tente novamente em 60 segundos.']);
    $this->assertGuest();
    $this->travel(59)->seconds();
    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertSessionHasErrors(['email' => 'Muitas tentativas. Tente novamente em 1 segundos.']);
    $this->travel(1)->seconds();
    $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/');
    $this->assertAuthenticatedAs($user);
});

test('the attempt limit groups email case variants but isolates other emails and IPs', function () {
    $this->freezeTime();
    $user = User::factory()->create(['email' => 'hunter@example.test']);
    $other = User::factory()->create();
    for ($attempt = 0; $attempt < 5; $attempt++) {
        $this->post('/login', ['email' => strtoupper($user->email), 'password' => 'wrong']);
    }

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertSessionHasErrors(['email' => 'Muitas tentativas. Tente novamente em 60 segundos.']);
    $this->assertGuest();
    $this->post('/login', ['email' => $other->email, 'password' => 'password'])->assertRedirect('/');
    $this->assertAuthenticatedAs($other);
    $this->post('/logout');
    $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.10'])
        ->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/');
    $this->assertAuthenticatedAs($user);
});

test('successful login clears previous failed attempts', function () {
    $user = User::factory()->create();
    for ($attempt = 0; $attempt < 4; $attempt++) {
        $this->post('/login', ['email' => $user->email, 'password' => 'wrong']);
    }
    $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/');
    $this->post('/logout');

    for ($attempt = 0; $attempt < 5; $attempt++) {
        $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors(['email' => 'Email ou senha incorretos.']);
    }
    $this->assertGuest();
});

test('logout clears session data rotates csrf and prevents subsequent home access', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->withSession(['private-marker' => 'secret', '_token' => 'old-token']);
    $previousId = session()->getId();

    $this->post('/logout')->assertRedirectToRoute('login')->assertSessionMissing('private-marker');

    $this->assertGuest();
    expect(session()->getId())->not->toBe($previousId);
    expect(session()->token())->not->toBe('old-token')->not->toBeEmpty();
    Auth::forgetGuards();
    $this->get('/')->assertRedirectToRoute('login');
});

test('logout requires authentication and does not accept get', function () {
    $this->post('/logout')->assertRedirectToRoute('login');
    $this->get('/logout')->assertMethodNotAllowed();
});

test('login and logout reject requests without csrf tokens', function () {
    $user = User::factory()->create();
    // Exercise the real web middleware, whose CSRF check is normally skipped in tests.
    $this->app['env'] = 'local';

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertStatus(419);
    $this->assertGuest();
    $this->actingAs($user)->post('/logout')->assertStatus(419);
    $this->assertAuthenticatedAs($user);
});

test('shared layout does not load dashboard scripts outside the home page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->view('layouts.default')->assertSee('Arquivos de Yharnam')
        ->assertDontSee('apexcharts')->assertDontSee('Sortable')
        ->assertDontSee('./js/adminlte.js')->assertDontSee('./css/adminlte.css')
        ->assertDontSee('bootstrap.min.js');
});
