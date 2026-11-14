<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('visitors are sent to login from protected pages', function (string $path) {
    $this->get($path)->assertRedirect('/login');
})->with(['/', '/admin/users']);

test('login and registration render independently of the admin layout', function (string $path) {
    $this->get($path)->assertOk()->assertSee('name="_token"', false)
        ->assertDontSee('app-sidebar')->assertDontSee('app-header');
})->with(['/login', '/register']);

test('ordinary users log in to home and cannot open the panel', function () {
    $user = User::factory()->create();
    $this->withSession(['url.intended' => url('/admin/users')])
        ->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/');
    $this->assertAuthenticatedAs($user);
    $this->get('/')->assertOk()->assertSee('Sair');
    $this->get('/admin/users')->assertForbidden();
});

test('administrators log in and can render all existing panel screens', function () {
    $admin = User::factory()->create();
    $admin->profile()->create(['type' => 'admin']);
    $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect('/admin/users');
    $this->assertAuthenticatedAs($admin);
    $this->get('/admin/users')->assertOk()->assertSee($admin->email);
    $this->get('/admin/users/'.$admin->id)->assertOk();
    $this->get('/admin/users/'.$admin->id.'/edit')->assertOk();
});

test('administrators resume internal destinations but never external URLs', function (string $destination, string $expected) {
    $admin = User::factory()->create();
    $admin->profile()->create(['type' => 'admin']);
    $this->withSession(['url.intended' => str_starts_with($destination, '/') ? url($destination) : $destination])
        ->post('/login', ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect($expected);
})->with([
    ['/admin/users?page=2', '/admin/users?page=2'],
    ['https://example.org/', '/admin/users'],
]);

test('invalid credentials preserve email without preserving password', function () {
    $this->from('/login')->post('/login', ['email' => 'unknown@example.test', 'password' => 'wrong-password'])
        ->assertRedirect('/login')->assertSessionHasErrors(['email' => 'Email ou senha inválidos.'])
        ->assertSessionHasInput('email', 'unknown@example.test')->assertSessionMissing('_old_input.password');
    $this->assertGuest();
});

test('login validates required credentials', function () {
    $this->post('/login')->assertSessionHasErrors(['email', 'password']);
    $this->assertGuest();
});

test('successful login rotates the session identifier', function () {
    $user = User::factory()->create();
    $this->withSession(['marker' => 'preserved']);
    $previousId = session()->getId();

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/');

    expect(session()->getId())->not->toBe($previousId);
});

test('the sixth login attempt is rate limited', function () {
    $this->freezeTime();
    for ($attempt = 0; $attempt < 5; $attempt++) {
        $this->post('/login', ['email' => 'limited@example.test', 'password' => 'wrong'])->assertRedirect();
    }
    $this->post('/login', ['email' => 'limited@example.test', 'password' => 'wrong'])->assertStatus(429);
    $this->travel(61)->seconds();
    $this->post('/login', ['email' => 'limited@example.test', 'password' => 'wrong'])->assertRedirect();
});

test('administrators can update profiles and replace programming languages', function () {
    $admin = User::factory()->create();
    $admin->profile()->create(['type' => 'admin']);
    $user = User::factory()->create();

    $this->actingAs($admin)->put('/admin/users/'.$user->id.'/profile', ['type' => 'PF', 'address' => 'Rua Alfa'])
        ->assertRedirect('/admin/users');
    $this->assertDatabaseHas('profile', ['user_id' => $user->id, 'type' => 'PF', 'address' => 'Rua Alfa']);
    $this->put('/admin/users/'.$user->id.'/languages', ['languages' => ['PHP', 'Python']])->assertRedirect('/admin/users');
    $this->put('/admin/users/'.$user->id.'/languages', ['languages' => ['Java']])->assertRedirect('/admin/users');
    $this->assertDatabaseCount('user_languages', 1);
    $this->assertDatabaseHas('user_languages', ['user_id' => $user->id, 'language' => 'Java']);
});

test('public registration hashes the password and ignores administrative input', function () {
    $this->post('/store', [
        'name' => 'Conta comum', 'email' => 'new@example.test', 'password' => 'password123',
        'type' => 'admin', 'profile' => ['type' => 'admin'],
    ])->assertRedirect('/login')->assertSessionHas('success');
    $user = User::where('email', 'new@example.test')->firstOrFail();
    expect(Hash::check('password123', $user->password))->toBeTrue();
    expect($user->profile)->toBeNull();
    $this->assertGuest();
});

test('registration rejects invalid and duplicate data', function () {
    $user = User::factory()->create();
    $this->post('/store', ['name' => '', 'email' => $user->email, 'password' => 'short'])
        ->assertSessionHasErrors(['name', 'email', 'password']);
    $this->assertDatabaseCount('users', 1);
});

test('administrators return to the panel after creating a common user', function () {
    $admin = User::factory()->create();
    $admin->profile()->create(['type' => 'admin']);
    $this->actingAs($admin)->post('/store', [
        'name' => 'Nova conta', 'email' => 'created@example.test', 'password' => 'password123',
    ])->assertRedirect('/admin/users')->assertSessionHas('success');
    $this->assertDatabaseHas('users', ['email' => 'created@example.test']);
    $this->assertDatabaseCount('profile', 1);
});

test('logout clears authentication and session data and rotates csrf token', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->withSession(['private' => 'value', '_token' => 'old-token'])
        ->post('/logout')->assertRedirect('/login')->assertSessionMissing('private');
    $this->assertGuest();
    expect(session()->token())->not->toBe('old-token');
    $this->get('/logout')->assertMethodNotAllowed();
    $this->get('/')->assertRedirect('/login');
});

test('every administrative operation denies ordinary users', function (string $method, string $path) {
    $user = User::factory()->create();
    $user->profile()->create(['type' => 'PF']);
    $this->actingAs($user)->call($method, $path)->assertForbidden();
})->with([
    ['GET', '/admin/users'], ['GET', '/admin/users/1'], ['GET', '/admin/users/1/edit'],
    ['PUT', '/admin/users/1'], ['PUT', '/admin/users/1/profile'],
    ['PUT', '/admin/users/1/languages'], ['DELETE', '/admin/users/1'],
]);
