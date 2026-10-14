<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

test('password recovery forms are available', function () {
    $this->get(route('password.request'))
        ->assertOk()
        ->assertSee('Enviar link de recuperação');

    $this->get(route('password.reset', ['token' => 'example-token', 'email' => 'user@example.com']))
        ->assertOk()
        ->assertSee('Alterar senha');
});

test('a user can request a password reset link', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email])
        ->assertSessionHas('status');

    Notification::assertSentTo($user, ResetPassword::class);
});

test('a valid token allows a user to change their password', function () {
    $user = User::factory()->create(['password' => 'old-password']);
    $token = Password::createToken($user);

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new-password-123',
        'password_confirmation' => 'new-password-123',
    ])->assertRedirect(route('password.request'));

    expect(Hash::check('new-password-123', $user->fresh()->password))->toBeTrue();
});

test('an invalid token cannot change the password', function () {
    $user = User::factory()->create(['password' => 'old-password']);

    $this->post(route('password.update'), [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'new-password-123',
        'password_confirmation' => 'new-password-123',
    ])->assertSessionHasErrors('email');

    expect(Hash::check('old-password', $user->fresh()->password))->toBeTrue();
});
