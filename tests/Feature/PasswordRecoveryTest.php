<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

test('visitante consegue abrir a página de recuperação', function () {
    $this->get('/forgot-password')
        ->assertOk();
});

test('recuperação exige um email válido', function () {
    $this->post('/forgot-password', [
        'email' => 'email-invalido',
    ])->assertSessionHasErrors('email');
});

test('usuário cadastrado pode solicitar recuperação', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/forgot-password', [
        'email' => $user->email,
    ])->assertSessionHas('status');

    Notification::assertSentTo(
        $user,
        ResetPassword::class
    );
});

test('email inexistente não revela se a conta existe', function () {
    Notification::fake();

    $this->post('/forgot-password', [
        'email' => 'inexistente@exemplo.com',
    ])->assertSessionHas('status');

    Notification::assertNothingSent();
});

test('token válido permite redefinir a senha', function () {
    $user = User::factory()->create([
        'password' => Hash::make('SenhaAntiga123'),
    ]);

    $token = Password::createToken($user);

    $this->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'SenhaNova123',
        'password_confirmation' => 'SenhaNova123',
    ])->assertRedirect(route('login'));

    expect(
        Hash::check('SenhaNova123', $user->fresh()->password)
    )->toBeTrue();
});

test('token inválido não altera a senha', function () {
    $user = User::factory()->create([
        'password' => Hash::make('SenhaAntiga123'),
    ]);

    $this->post('/reset-password', [
        'token' => 'token-invalido',
        'email' => $user->email,
        'password' => 'SenhaNova123',
        'password_confirmation' => 'SenhaNova123',
    ])->assertSessionHasErrors('email');

    expect(
        Hash::check('SenhaAntiga123', $user->fresh()->password)
    )->toBeTrue();
});

test('confirmação de senha diferente é rejeitada', function () {
    $user = User::factory()->create();

    $token = Password::createToken($user);

    $this->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'SenhaNova123',
        'password_confirmation' => 'SenhaDiferente123',
    ])->assertSessionHasErrors('password');
});
