<?php

use App\Models\User;

test('visitante é redirecionado para o login', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});

test('usuário autenticado consegue acessar a página inicial', function () {
    $user = User::factory()->make();

    $response = $this
        ->actingAs($user)
        ->get('/');

    $response->assertStatus(200);
});
