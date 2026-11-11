<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->withoutVite());

test('visitante é redirecionado ao login em /admin/users', function () {
    $this->get('/admin/users')->assertRedirect('/login');
});

test('usuário autenticado sem perfil admin recebe 403', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/admin/users')->assertForbidden();
});

test('usuário com perfil que não é admin (PF/PJ) recebe 403', function () {
    $user = User::factory()->create();
    Profile::create(['user_id' => $user->id, 'type' => 'PF']);

    $this->actingAs($user)->get('/admin/users')->assertForbidden();
});

test('usuário com perfil admin acessa o painel', function () {
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => 'admin']);

    $this->actingAs($admin)->get('/admin/users')
        ->assertOk()
        ->assertSee('Listar Usuário');
});

test('todas as rotas /admin/* passam pelo middleware admin', function () {
    $rotasAdmin = collect(Route::getRoutes()->getRoutes())
        ->filter(fn ($route) => str_starts_with($route->uri(), 'admin'));

    expect($rotasAdmin)->not->toBeEmpty();

    foreach ($rotasAdmin as $route) {
        expect($route->gatherMiddleware())->toContain('admin');
    }
});

test('usuário comum não consegue executar ações administrativas', function () {
    $user = User::factory()->create();
    $alvo = User::factory()->create();

    $this->actingAs($user)->get("/admin/user/{$alvo->id}/edit")->assertForbidden();
    $this->actingAs($user)->put("/admin/user/{$alvo->id}", ['name' => 'X', 'email' => 'x@example.test'])->assertForbidden();
    $this->actingAs($user)->delete("/admin/user/{$alvo->id}")->assertForbidden();

    expect(User::find($alvo->id))->not->toBeNull();
});

test('admin consegue editar e excluir usuários', function () {
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => 'admin']);
    $alvo = User::factory()->create();

    $this->actingAs($admin)->get("/admin/user/{$alvo->id}/edit")->assertOk();

    $this->actingAs($admin)
        ->put("/admin/user/{$alvo->id}", ['name' => 'Novo Nome', 'email' => $alvo->email])
        ->assertRedirect(route('admin.users.index'));

    expect($alvo->fresh()->name)->toBe('Novo Nome');

    $this->actingAs($admin)->delete("/admin/user/{$alvo->id}")->assertRedirect(route('admin.users.index'));
    expect(User::find($alvo->id))->toBeNull();
});

test('o perfil aceita endereço (coluna address existe e aceita vazio)', function () {
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => 'admin']);
    $alvo = User::factory()->create();

    $this->actingAs($admin)
        ->put("/admin/user/{$alvo->id}/profile", ['type' => 'PF', 'address' => 'Rua A, 10'])
        ->assertRedirect(route('admin.users.index'));

    expect($alvo->fresh()->profile->address)->toBe('Rua A, 10');

    $this->actingAs($admin)
        ->put("/admin/user/{$alvo->id}/profile", ['type' => 'PJ', 'address' => ''])
        ->assertRedirect(route('admin.users.index'));

    expect($alvo->fresh()->profile->address)->toBeNull();
});

test('o perfil não pode ser promovido a admin pelo painel', function () {
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => 'admin']);
    $alvo = User::factory()->create();

    $this->actingAs($admin)
        ->put("/admin/user/{$alvo->id}/profile", ['type' => 'admin'])
        ->assertSessionHasErrors('type');
});

test('as linguagens de programação não existem mais na edição', function () {
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => 'admin']);
    $alvo = User::factory()->create();

    $this->actingAs($admin)->get("/admin/user/{$alvo->id}/edit")
        ->assertOk()
        ->assertDontSee('Linguagens de programação');

    $this->actingAs($admin)->put("/admin/user/{$alvo->id}/languages", ['languages' => ['PHP']])
        ->assertNotFound();
});

test('admin vê o botão Novo usuário na lista e a tela de cadastro', function () {
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => 'admin']);

    $this->actingAs($admin)->get('/admin/users')
        ->assertOk()
        ->assertSee('Novo usuário')
        ->assertSee(route('admin.user.create'), false);

    $this->actingAs($admin)->get('/admin/user/create')
        ->assertOk()
        ->assertSee('Cadastrar novo usuário')
        ->assertSee('action="'.route('users.store').'"', false)
        ->assertSee('name="_token"', false);
});

test('admin cadastra um novo usuário comum e volta para a lista', function () {
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => 'admin']);

    $this->actingAs($admin)
        ->from('/admin/user/create')
        ->post('/store', ['name' => 'Novo Aluno', 'email' => 'novo.aluno@example.test', 'password' => 'senha-segura-1'])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success', 'Usuário criado com sucesso!');

    $criado = User::where('email', 'novo.aluno@example.test')->firstOrFail();
    expect($criado->isAdmin())->toBeFalse();
});

test('cadastro pelo admin mostra erros e mantém o que foi digitado, menos a senha', function () {
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => 'admin']);
    User::factory()->create(['email' => 'repetido@example.test']);

    $this->actingAs($admin)
        ->from('/admin/user/create')
        ->post('/store', ['name' => 'Fulano', 'email' => 'repetido@example.test', 'password' => '123'])
        ->assertRedirect('/admin/user/create')
        ->assertSessionHasErrors(['email', 'password'])
        ->assertSessionHasInput('name', 'Fulano')
        ->assertSessionMissing('_old_input.password');
});

test('só admin acessa a tela de novo usuário', function () {
    $this->get('/admin/user/create')->assertRedirect('/login');

    $comum = User::factory()->create();
    $this->actingAs($comum)->get('/admin/user/create')->assertForbidden();
});
