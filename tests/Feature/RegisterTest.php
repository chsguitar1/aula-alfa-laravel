<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('visitante se cadastra e vai para o login com mensagem de sucesso', function () {
    $this->post(route('users.store'), [
        'name' => 'Maria',
        'email' => 'maria@example.com',
        'password' => 'senha1234',
    ])->assertRedirect(route('login'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('users', ['email' => 'maria@example.com']);
});

test('enviar type=admin por fora do formulário não cria perfil', function () {
    $this->post(route('users.store'), [
        'name' => 'Maria',
        'email' => 'maria@example.com',
        'password' => 'senha1234',
        'type' => 'admin',
    ]);

    $user = User::where('email', 'maria@example.com')->first();

    expect($user->profile)->toBeNull();
    $this->assertDatabaseCount('profile', 0);
});

test('admin logado volta para a listagem de usuários', function () {
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => 'admin']);

    $this->actingAs($admin)->post(route('users.store'), [
        'name' => 'Maria',
        'email' => 'maria@example.com',
        'password' => 'senha1234',
    ])->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success');
});

test('email repetido e senha curta mostram erro e mantêm nome e email', function () {
    $existing = User::factory()->create();

    $response = $this->from(route('register'))->post(route('users.store'), [
        'name' => 'Maria',
        'email' => $existing->email,
        'password' => '123',
    ]);

    $response->assertRedirect(route('register'));
    $response->assertSessionHasErrors(['email', 'password']);
    $response->assertSessionHasInput('name', 'Maria');
    $response->assertSessionHasInput('email', $existing->email);
});
