<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

it('exibe os dois formularios', function () {
    $this->get(route('password.request'))->assertOk()->assertSee('Enviar link de recuperação');
    $this->get(route('password.reset', ['token' => 'teste', 'email' => 'aluno@example.com']))
        ->assertOk()->assertSee('password_confirmation');
});

it('envia notificacao com link para usuario cadastrado', function () {
    Notification::fake();
    $user = User::factory()->create();
    $this->post(route('password.email'), ['email' => $user->email])->assertSessionHas('status');
    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        expect(Password::tokenExists($user, $notification->token))->toBeTrue();
        expect($notification->toMail($user)->actionUrl)->toContain('/reset-password/');

        return true;
    });
});

it('nao revela se um email existe', function () {
    $this->post(route('password.email'), ['email' => 'inexistente@example.com'])
        ->assertSessionHas('status')->assertSessionHasNoErrors();
});

it('rejeita email invalido', function () {
    $this->post(route('password.email'), ['email' => 'invalido'])->assertSessionHasErrors('email');
});

it('altera senha com token e impede reutilizacao', function () {
    $user = User::factory()->create();
    $oldHash = $user->password;
    $token = Password::createToken($user);
    $data = ['email' => $user->email, 'token' => $token, 'password' => 'NovaSenha123!', 'password_confirmation' => 'NovaSenha123!'];
    $this->post(route('password.update'), $data)->assertRedirect(route('password.request'))->assertSessionHasNoErrors();
    expect(Hash::check('NovaSenha123!', $user->fresh()->password))->toBeTrue();
    expect($user->fresh()->password)->not->toBe($oldHash);
    $this->post(route('password.update'), $data)->assertSessionHasErrors('email');
});

it('rejeita token invalido e preserva senha', function () {
    $user = User::factory()->create();
    $hash = $user->password;
    $this->post(route('password.update'), ['email' => $user->email, 'token' => 'invalido', 'password' => 'NovaSenha123!', 'password_confirmation' => 'NovaSenha123!'])
        ->assertSessionHasErrors('email');
    expect($user->fresh()->password)->toBe($hash);
});

it('rejeita token expirado', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);
    $this->travel(61)->minutes();
    $this->post(route('password.update'), ['email' => $user->email, 'token' => $token, 'password' => 'NovaSenha123!', 'password_confirmation' => 'NovaSenha123!'])
        ->assertSessionHasErrors('email');
});

it('valida tamanho e confirmacao da senha', function (string $password, string $confirmation) {
    $user = User::factory()->create();
    $token = Password::createToken($user);
    $this->post(route('password.update'), ['email' => $user->email, 'token' => $token, 'password' => $password, 'password_confirmation' => $confirmation])
        ->assertSessionHasErrors('password');
})->with([['curta', 'curta'], ['NovaSenha123!', 'OutraSenha123!']]);
