<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    RateLimiter::clear('login');
});

function makeAdmin(): User
{
    $user = User::factory()->create();
    Profile::create(['user_id' => $user->id, 'type' => 'admin']);

    return $user;
}

test('a tela de login é pública e não usa o layout do painel', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Entre com seu email e senha')
        ->assertSee('Cadastre-se')
        ->assertDontSee('app-sidebar', false)
        ->assertDontSee('app-header', false);
});

test('a tela de cadastro é pública e não usa o layout do painel', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSee('Preencha os campos para se cadastrar')
        ->assertDontSee('app-sidebar', false)
        ->assertDontSee('app-header', false);
});

test('visitante é enviado ao login ao abrir / e /admin/users', function () {
    $this->get('/')->assertRedirect('/login');
    $this->get('/admin/users')->assertRedirect('/login');
});

test('usuário comum entra e vai para a página inicial', function () {
    $user = User::factory()->create();

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect('/');

    $this->assertAuthenticatedAs($user);
    $this->get('/')->assertOk();
});

test('login regenera o identificador da sessão', function () {
    $user = User::factory()->create();

    $this->get('/login');
    $antes = session()->getId();

    $this->post('/login', ['email' => $user->email, 'password' => 'password']);

    expect(session()->getId())->not->toBe($antes);
});

test('admin entra e vai para o painel', function () {
    $admin = makeAdmin();

    $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect('/admin/users');
});

test('admin volta ao destino interno pedido antes do login', function () {
    $admin = makeAdmin();

    $this->get('/admin/users')->assertRedirect('/login');

    $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect('/admin/users');
});

test('usuário comum não é levado a uma página admin guardada', function () {
    $user = User::factory()->create();

    $this->get('/admin/users')->assertRedirect('/login');

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect('/');
});

test('credenciais inválidas mostram mensagem genérica, mantêm o email e nunca a senha', function () {
    $user = User::factory()->create();

    $this->from('/login')
        ->post('/login', ['email' => $user->email, 'password' => 'senha-errada'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['email' => 'Credenciais inválidas.'])
        ->assertSessionHasInput('email', $user->email)
        ->assertSessionMissing('_old_input.password');

    $this->assertGuest();
});

test('o formulário de login exige email e senha', function () {
    $this->from('/login')
        ->post('/login', [])
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['email', 'password']);
});

test('o login tem limite de tentativas', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $tentativa) {
        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'errada']);
    }

    // 6ª tentativa (mesmo com a senha certa) é bloqueada.
    $this->from('/login')
        ->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('logout só por POST: invalida a sessão e volta ao login', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/logout')->assertStatus(405);
    $this->assertAuthenticated();

    $this->post('/logout')->assertRedirect('/login');
    $this->assertGuest();
});

test('visitante não consegue fazer logout', function () {
    $this->post('/logout')->assertRedirect('/login');
});

test('o botão Sair é um formulário POST com CSRF no header', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/')
        ->assertOk()
        ->assertSee('action="'.route('logout').'"', false)
        ->assertSee('name="_token"', false)
        ->assertSee('Sair');
});

test('cadastro público cria usuário comum e manda para o login', function () {
    $this->post('/store', [
        'name' => 'Maria',
        'email' => 'maria@example.test',
        'password' => 'senha-segura-1',
        'type' => 'admin', // tentativa de se promover: deve ser ignorada
    ])->assertRedirect('/login')
        ->assertSessionHas('success');

    $user = User::where('email', 'maria@example.test')->firstOrFail();

    expect($user->isAdmin())->toBeFalse()
        ->and($user->profile)->toBeNull();
});

test('cadastro valida email único e senha mínima, preservando os campos não sensíveis', function () {
    User::factory()->create(['email' => 'repetido@example.test']);

    $this->from('/register')
        ->post('/store', ['name' => 'Ana', 'email' => 'repetido@example.test', 'password' => '123'])
        ->assertRedirect('/register')
        ->assertSessionHasErrors(['email', 'password'])
        ->assertSessionHasInput('name', 'Ana')
        ->assertSessionMissing('_old_input.password');
});

test('admin que cadastra pelo painel volta à lista administrativa', function () {
    $admin = makeAdmin();

    $this->actingAs($admin)
        ->post('/store', ['name' => 'Novo', 'email' => 'novo@example.test', 'password' => 'senha-segura-1'])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success', 'Usuário criado com sucesso!');
});
