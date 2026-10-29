<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('login com senha incorreta é rejeitado', function () {
    User::factory()->create([
        'email' => 'teste-login@exemplo.com',
        'password' => Hash::make('SenhaCorreta123'),
    ]);

    $this->from('/login')
        ->post('/login', [
            'email' => 'teste-login@exemplo.com',
            'password' => 'SenhaIncorreta123',
        ])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('login de usuário comum redireciona para a página inicial', function () {
    $user = User::factory()->create([
        'password' => Hash::make('SenhaCorreta123'),
    ]);

    $user->profile()->create([
        'type' => 'PF',
        'description' => 'Usuário comum',
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'SenhaCorreta123',
    ])->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);
});

test('login de administrador redireciona para o painel', function () {
    $admin = User::factory()->create([
        'password' => Hash::make('SenhaCorreta123'),
    ]);

    $admin->profile()->create([
        'type' => 'admin',
        'description' => 'Administrador',
    ]);

    $this->post('/login', [
        'email' => $admin->email,
        'password' => 'SenhaCorreta123',
    ])->assertRedirect(route('admin.users.index'));

    $this->assertAuthenticatedAs($admin);
});

test('administrador não é redirecionado para site externo após login', function () {
    $admin = User::factory()->create([
        'password' => Hash::make('SenhaCorreta123'),
    ]);

    $admin->profile()->create([
        'type' => 'admin',
        'description' => 'Administrador',
    ]);

    $this->withSession([
        'url.intended' => 'https://site-externo.example/alguma-pagina',
    ])->post('/login', [
        'email' => $admin->email,
        'password' => 'SenhaCorreta123',
    ])->assertRedirect(route('admin.users.index'));
});

test('login bloqueia tentativas excessivas', function () {
    $credentials = [
        'email' => 'inexistente@exemplo.com',
        'password' => 'SenhaErrada123',
    ];

    // O projeto limita o login a cinco tentativas por minuto.
    for ($attempt = 1; $attempt <= 5; $attempt++) {
        $this->post('/login', $credentials)
            ->assertSessionHasErrors('email');
    }

    // A sexta tentativa deve ser bloqueada pelo middleware.
    $this->post('/login', $credentials)
        ->assertStatus(429);
});
