<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.key' => 'base64:'.base64_encode(random_bytes(32))]);
});

test('it sends a password reset link without changing the password', function () {
    Notification::fake();

    $user = User::factory()->create();
    $originalPassword = $user->password;

    $this->post(route('users.password-email'), [
        'email' => $user->email,
    ])->assertRedirect();

    Notification::assertSentTo($user, ResetPassword::class);
    expect($user->fresh()->password)->toBe($originalPassword);
});

test('it renders both password reset forms', function () {
    $this->withoutVite();

    $this->get(route('users.forgot-password'))
        ->assertOk()
        ->assertSee('Solicitar nova senha');

    $this->get(route('password.reset', [
        'token' => 'reset-token',
        'email' => 'user@example.com',
    ]))
        ->assertOk()
        ->assertSee('Alterar senha');
});

test('it resets the password with a valid token', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'nova-senha-segura',
        'password_confirmation' => 'nova-senha-segura',
    ])->assertRedirect(route('users.forgot-password'));

    expect(Hash::check('nova-senha-segura', $user->fresh()->password))->toBeTrue();
    $this->assertDatabaseMissing('password_reset_tokens', [
        'email' => $user->email,
    ]);
});
