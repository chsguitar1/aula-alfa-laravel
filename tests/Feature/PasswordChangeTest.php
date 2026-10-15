<?php

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;

uses(LazilyRefreshDatabase::class);

it('renders the direct password change form without scripts', function () {
    $this->get('/reset-password')
        ->assertSee('Alterar senha')
        ->assertDontSee('<script', false)
        ->assertDontSee('resources/js/app.js', false);
});

it('removes the password reset by email page', function () {
    $this->get('/forgot-password')->assertNotFound();
});

it('changes the password when the current credentials are valid', function () {
    $user = User::factory()->create();
    $rememberToken = $user->remember_token;
    Event::fake([PasswordReset::class]);

    $response = $this->from('/reset-password')->post('/reset-password', [
        'email' => $user->email,
        'current_password' => 'password',
        'password' => 'New-secure-password-123',
        'password_confirmation' => 'New-secure-password-123',
    ]);

    $response->assertRedirectToRoute('password.edit')->assertSessionHas('status');
    expect(Hash::check('New-secure-password-123', $user->fresh()->password))->toBeTrue();
    expect($user->fresh()->remember_token)->not->toBe($rememberToken);
    Event::assertDispatched(PasswordReset::class);
});

it('keeps the password when the current password is incorrect', function () {
    $user = User::factory()->create();
    $currentPasswordHash = $user->password;

    $response = $this->from('/reset-password')->post('/reset-password', [
        'email' => $user->email,
        'current_password' => 'incorrect-password',
        'password' => 'New-secure-password-123',
        'password_confirmation' => 'New-secure-password-123',
    ]);

    $response->assertRedirectBackWithErrors(['current_password']);
    expect($user->fresh()->password)->toBe($currentPasswordHash);
});

it('does not reveal whether an email is registered', function () {
    User::factory()->create();

    $response = $this->from('/reset-password')->post('/reset-password', [
        'email' => 'missing@example.com',
        'current_password' => 'password',
        'password' => 'New-secure-password-123',
        'password_confirmation' => 'New-secure-password-123',
    ]);

    $response->assertRedirectBackWithErrors([
        'current_password' => 'E-mail ou senha atual incorretos.',
    ]);
});
