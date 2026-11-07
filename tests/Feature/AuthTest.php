<?php

use App\Models\Profile;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

function adminUser(): User
{
    $user = User::factory()->create();
    $user->profile()->create(['type' => Profile::TYPE_ADMIN]);

    return $user;
}

// ---------------------------------------------------------------- visitante

test('visitante é redirecionado para o login ao abrir / e /admin/users', function () {
    $this->get('/')->assertRedirect(route('login'));
    $this->get('/admin/users')->assertRedirect(route('login'));
});

test('todas as rotas administrativas exigem login', function () {
    $user = User::factory()->create();

    $this->get("/admin/user/{$user->id}")->assertRedirect(route('login'));
    $this->get("/admin/user/{$user->id}/edit")->assertRedirect(route('login'));
    $this->put("/admin/user/{$user->id}", [])->assertRedirect(route('login'));
    $this->put("/admin/user/{$user->id}/profile", [])->assertRedirect(route('login'));
    $this->put("/admin/user/{$user->id}/languages", [])->assertRedirect(route('login'));
    $this->delete("/admin/user/{$user->id}")->assertRedirect(route('login'));

    expect(User::find($user->id))->not->toBeNull();
});

test('telas de login e cadastro são independentes do layout administrativo', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Entre com seu email e senha')
        ->assertSee('Cadastre-se')
        ->assertDontSee('app-sidebar', false);

    $this->get('/register')
        ->assertOk()
        ->assertSee('Preencha os campos para se cadastrar')
        ->assertDontSee('app-sidebar', false);
});

// ---------------------------------------------------------------- cadastro

test('cadastro público cria usuário comum e volta ao login com confirmação', function () {
    $this->post('/store', [
        'name' => 'Maria',
        'email' => 'maria@example.com',
        'password' => 'segredo123',
        'type' => 'admin', // tentativa de escalar privilégio deve ser ignorada
    ])->assertRedirect(route('login'))
        ->assertSessionHas('success');

    $user = User::where('email', 'maria@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->profile)->toBeNull()
        ->and($user->isAdmin())->toBeFalse();
    $this->assertGuest();
});

test('cadastro valida email único e senha mínima preservando os dados não sensíveis', function () {
    User::factory()->create(['email' => 'repetido@example.com']);

    $this->from('/register')->post('/store', [
        'name' => 'João',
        'email' => 'repetido@example.com',
        'password' => '123',
    ])->assertRedirect('/register')
        ->assertSessionHasErrors(['email', 'password'])
        ->assertSessionHasInput('name', 'João')
        ->assertSessionHasInput('email', 'repetido@example.com')
        ->assertSessionMissing('_old_input.password');
});

test('admin que cadastra pelo painel volta para a lista administrativa', function () {
    $this->actingAs(adminUser())
        ->post('/store', ['name' => 'Novo', 'email' => 'novo@example.com', 'password' => 'segredo123'])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success', 'Usuário criado com sucesso!');
});

// ---------------------------------------------------------------- login / logout

test('usuário comum faz login, vai para / e regenera a sessão', function () {
    $user = User::factory()->create(['password' => 'segredo123']);

    $this->get('/login');
    $oldSession = session()->getId();

    $this->post('/login', ['email' => $user->email, 'password' => 'segredo123'])
        ->assertRedirect('/');

    $this->assertAuthenticatedAs($user);
    expect(session()->getId())->not->toBe($oldSession);
    $this->get('/')->assertOk()->assertSee('Sair');
});

test('admin faz login e vai para o painel', function () {
    $admin = adminUser();

    $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect(route('admin.users.index'));
});

test('admin é levado ao destino administrativo pedido antes do login', function () {
    $admin = adminUser();
    $target = "/admin/user/{$admin->id}/edit";

    $this->get($target)->assertRedirect(route('login'));

    $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect($target);
});

test('usuário comum não é levado a destino administrativo guardado', function () {
    $user = User::factory()->create();

    $this->get('/admin/users');

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect('/');
});

test('credenciais inválidas mostram mensagem genérica e preservam somente o email', function () {
    $user = User::factory()->create();

    $this->from('/login')
        ->post('/login', ['email' => $user->email, 'password' => 'errada'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['email' => 'Email ou senha inválidos.'])
        ->assertSessionHasInput('email', $user->email)
        ->assertSessionMissing('_old_input.password');

    $this->assertGuest();
});

test('login exige email e senha', function () {
    $this->from('/login')->post('/login', [])
        ->assertSessionHasErrors(['email', 'password']);
});

test('login é limitado a 5 tentativas por minuto', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $i) {
        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'errada']);
    }

    // Mesmo com a senha correta, a 6ª tentativa é bloqueada.
    $this->from('/login')
        ->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');

    expect(session('errors')->first('email'))->toContain('Muitas tentativas');
    $this->assertGuest();
});

test('logout por POST encerra a sessão e volta ao login', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/logout')->assertRedirect(route('login'));

    $this->assertGuest();
    $this->get('/')->assertRedirect(route('login'));
});

test('logout não aceita GET', function () {
    $this->actingAs(User::factory()->create())->get('/logout')->assertStatus(405);
});

// ---------------------------------------------------------------- autorização

test('usuário comum autenticado recebe 403 no painel', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    $this->actingAs($user);
    $this->get('/admin/users')->assertForbidden();
    $this->get("/admin/user/{$other->id}/edit")->assertForbidden();
    $this->delete("/admin/user/{$other->id}")->assertForbidden();

    expect(User::find($other->id))->not->toBeNull();
});

test('usuário com perfil não administrativo recebe 403', function () {
    $user = User::factory()->create();
    $user->profile()->create(['type' => 'PF']);

    $this->actingAs($user)->get('/admin/users')->assertForbidden();
});

test('admin acessa as funcionalidades do painel', function () {
    $admin = adminUser();
    $other = User::factory()->create();

    $this->actingAs($admin);
    $this->get('/admin/users')->assertOk()->assertSee($other->email)->assertSee('Sair');
    $this->get("/admin/user/{$other->id}/edit")->assertOk();
    $this->put("/admin/user/{$other->id}/profile", ['type' => 'PF', 'address' => 'Rua A, 10'])
        ->assertRedirect(route('admin.users.index'));

    expect($other->fresh()->profile->address)->toBe('Rua A, 10');
});

test('formulário de perfil não permite promover usuário a admin', function () {
    $other = User::factory()->create();

    $this->actingAs(adminUser())
        ->put("/admin/user/{$other->id}/profile", ['type' => 'admin'])
        ->assertSessionHasErrors('type');
});

test('seeder provisiona o admin de demonstração', function () {
    $this->seed(AdminUserSeeder::class);

    expect(User::where('email', 'admin@example.com')->first()->isAdmin())->toBeTrue()
        ->and(User::where('email', 'comum@example.com')->first()->isAdmin())->toBeFalse();
});
