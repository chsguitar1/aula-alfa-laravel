<?php

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('visitante é redirecionado ao login ao acessar a página inicial', function () {
    $this->get('/')
        ->assertRedirect(route('login'));
});

test('visitante é redirecionado ao login ao acessar o painel administrativo', function () {
    $this->get('/admin/users')
        ->assertRedirect(route('login'));
});

test('página de login é pública', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('email')
        ->assertSee('password');
});

test('página de cadastro é pública', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSee('Cadastrar');
});

test('cadastro público cria usuário comum', function () {
    $response = $this->post('/store', [
        'name' => 'Usuário Comum',
        'email' => 'comum@exemplo.com',
        'password' => 'Teste@123456',
    ]);

    $response->assertRedirect(route('login'));

    $user = User::where('email', 'comum@exemplo.com')->first();

    expect($user)->not->toBeNull();
    expect($user->profile->type)->toBe('PF');

    $this->assertDatabaseHas('profile', [
        'user_id' => $user->id,
        'type' => 'PF',
    ]);
});

test('usuário comum não consegue acessar o painel administrativo', function () {
    $user = User::factory()->create();

    Profile::create([
        'user_id' => $user->id,
        'type' => 'PF',
        'description' => 'Usuário comum',
    ]);

    $this->actingAs($user)
        ->get('/admin/users')
        ->assertForbidden();
});

test('administrador consegue acessar o painel administrativo', function () {
    $admin = User::factory()->create();

    Profile::create([
        'user_id' => $admin->id,
        'type' => 'admin',
        'description' => 'Administrador de teste',
    ]);

    $this->actingAs($admin)
        ->get('/admin/users')
        ->assertOk();
});

test('logout encerra a sessão do usuário', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

test('cadastro rejeita email duplicado', function () {
    User::factory()->create([
        'email' => 'duplicado@exemplo.com',
    ]);

    $this->post('/store', [
        'name' => 'Outro Usuário',
        'email' => 'duplicado@exemplo.com',
        'password' => 'Teste@123456',
    ])->assertSessionHasErrors('email');
});
