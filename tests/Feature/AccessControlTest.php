<?php

use App\Models\Profile;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function criarAdmin(): User
{
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => 'admin', 'address' => 'Rua A']);

    return $admin;
}

test('visitante em / é redirecionado ao login', function () {
    $this->get('/')->assertRedirect(route('login'));
});

test('visitante em /admin/users é redirecionado ao login', function () {
    $this->get('/admin/users')->assertRedirect(route('login'));
});

test('usuário comum recebe 403 em /admin/users', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin/users')
        ->assertForbidden();
});

test('admin acessa /admin/users com 200', function () {
    $this->withoutVite()->actingAs(criarAdmin())
        ->get('/admin/users')
        ->assertOk();
});

test('logout invalida a sessão e o admin volta a ser barrado', function () {
    $this->actingAs(criarAdmin())->post(route('logout'))->assertRedirect(route('login'));

    $this->assertGuest();
    $this->get('/admin/users')->assertRedirect(route('login'));
});

test('cadastro público não cria admin', function () {
    $this->post(route('users.store'), [
        'name' => 'Maria',
        'email' => 'maria@example.com',
        'password' => 'senha1234',
        'type' => 'admin',
    ]);

    $user = User::where('email', 'maria@example.com')->first();

    expect($user->profile)->toBeNull();
});

test('seeder cria o admin só em ambiente local', function () {
    app()->detectEnvironment(fn () => 'production');
    (new AdminUserSeeder)->run();
    $this->assertDatabaseMissing('users', ['email' => 'admin@example.com']);

    app()->detectEnvironment(fn () => 'local');
    (new AdminUserSeeder)->run();

    $admin = User::where('email', 'admin@example.com')->first();
    expect($admin->profile->type)->toBe('admin');
});

test('admin abre a edição de usuário e salva linguagens', function () {
    $admin = criarAdmin();

    $this->withoutVite()->actingAs($admin)
        ->get(route('admin.user.edit', $admin->id))
        ->assertOk();

    $this->actingAs($admin)
        ->put(route('admin.user.updateLanguages', $admin->id), ['languages' => ['PHP']])
        ->assertRedirect(route('admin.users.index'));

    $this->assertDatabaseHas('user_languages', ['user_id' => $admin->id, 'language' => 'PHP']);
});
