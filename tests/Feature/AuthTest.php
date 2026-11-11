<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('senha errada mostra erro genérico e mantém só o email', function () {
    $user = User::factory()->create();

    $response = $this->from(route('login'))->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'senha-errada',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors(['email' => 'Credenciais inválidas']);
    $response->assertSessionHasInput('email', $user->email);
    $response->assertSessionMissing('_old_input.password');
    $this->assertGuest();
});

test('email inexistente retorna a mesma mensagem de senha errada', function () {
    $response = $this->from(route('login'))->post(route('login.store'), [
        'email' => 'ninguem@example.com',
        'password' => 'qualquer-coisa',
    ]);

    $response->assertSessionHasErrors(['email' => 'Credenciais inválidas']);
});

test('campos obrigatórios são validados', function () {
    $this->post(route('login.store'), [])->assertSessionHasErrors(['email', 'password']);
});

test('login de usuário comum redireciona para a home', function () {
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect('/');

    $this->assertAuthenticatedAs($user);
});

test('a sexta tentativa seguida retorna 429', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $attempt) {
        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'senha-errada',
        ])->assertStatus(302);
    }

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'senha-errada',
    ])->assertStatus(429);
});

test('logout encerra a sessão e redireciona ao login', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

test('home carrega para usuário logado e mostra o botão Sair', function () {
    $user = User::factory()->create();

    $this->withoutVite()->actingAs($user)
        ->get('/')
        ->assertOk()
        ->assertSee('Sair')
        ->assertSee(route('logout'));
});
