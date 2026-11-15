<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

function createAdmin(): User
{
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => Profile::TYPE_ADMIN]);

    return $admin;
}

// ---------- visitante ----------

test('visitante é redirecionado ao login ao abrir / e /admin/users', function () {
    $this->get('/')->assertRedirect('/login');
    $this->get('/admin/users')->assertRedirect('/login');
});

test('tela de login é independente do layout do painel', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Entre com seu email e senha')
        ->assertSee('Cadastre-se')
        ->assertDontSee('app-sidebar');
});

// ---------- login ----------

test('usuário comum entra e vai para a página inicial', function () {
    $user = User::factory()->create(['password' => 'senha-segura-1']);

    $this->post('/login', ['email' => $user->email, 'password' => 'senha-segura-1'])
        ->assertRedirect('/');

    $this->assertAuthenticatedAs($user);
});

test('administrador entra e vai para o painel', function () {
    $admin = createAdmin();

    $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect(route('admin.users.index'));

    $this->assertAuthenticatedAs($admin);
});

test('administrador volta ao destino interno pedido antes do login', function () {
    $admin = createAdmin();

    $this->get('/admin/users')->assertRedirect('/login');

    $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect('/admin/users');
});

test('login regenera o identificador da sessão', function () {
    $user = User::factory()->create();
    $this->get('/login');
    $antes = session()->getId();

    $this->post('/login', ['email' => $user->email, 'password' => 'password']);

    expect(session()->getId())->not->toBe($antes);
});

test('credenciais inválidas mostram mensagem genérica e preservam só o email', function () {
    $user = User::factory()->create();

    $this->post('/login', ['email' => $user->email, 'password' => 'errada-123'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email')
        ->assertSessionHasInput('email', $user->email)
        ->assertSessionMissing('_old_input.password');

    $this->assertGuest();
});

test('login tem limite de tentativas', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $tentativa) {
        $this->post('/login', ['email' => $user->email, 'password' => 'errada-123']);
    }

    // A 6ª tentativa é barrada mesmo com a senha correta.
    $response = $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');

    expect(session('errors')->first('email'))->toContain('Muitas tentativas');
    $this->assertGuest();
});

// ---------- logout ----------

test('logout é POST, encerra a sessão e volta ao login', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/logout')->assertRedirect('/login');

    $this->assertGuest();
});

test('logout por GET não existe', function () {
    $this->actingAs(User::factory()->create())->get('/logout')->assertStatus(405);
});

test('visitante não consegue fazer logout', function () {
    $this->post('/logout')->assertRedirect('/login');
});

test('o botão Sair do cabeçalho é um formulário POST com CSRF', function () {
    $this->actingAs(User::factory()->create())
        ->get('/')
        ->assertSee('action="'.route('logout').'"', false)
        ->assertSee('name="_token"', false)
        ->assertSee('Sair');
});

// ---------- autorização ----------

test('usuário autenticado sem perfil admin recebe 403 no painel', function () {
    $this->actingAs(User::factory()->create())->get('/admin/users')->assertForbidden();
});

test('usuário com perfil PF ou PJ também recebe 403 no painel', function () {
    $user = User::factory()->create();
    Profile::create(['user_id' => $user->id, 'type' => 'PF']);

    $this->actingAs($user)->get('/admin/users')->assertForbidden();
});

test('administrador acessa o painel', function () {
    $this->actingAs(createAdmin())->get('/admin/users')->assertOk();
});

test('toda rota /admin usa o middleware admin', function () {
    $rotasAdmin = collect(Route::getRoutes()->getRoutes())
        ->filter(fn ($rota) => str_starts_with($rota->uri(), 'admin'));

    expect($rotasAdmin)->not->toBeEmpty();

    $rotasAdmin->each(fn ($rota) => expect($rota->gatherMiddleware())->toContain('admin'));
});

test('métodos administrativos negam usuário comum', function () {
    $comum = User::factory()->create();
    $alvo = User::factory()->create();

    $this->actingAs($comum)->get("/admin/users/{$alvo->id}/edit")->assertForbidden();
    $this->actingAs($comum)->put("/admin/users/{$alvo->id}", ['name' => 'x', 'email' => 'x@x.com'])->assertForbidden();
    $this->actingAs($comum)->delete("/admin/users/{$alvo->id}")->assertForbidden();
    $this->assertDatabaseHas('users', ['id' => $alvo->id]);
});

// ---------- cadastro ----------

test('tela de cadastro é independente do layout do painel', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSee('Preencha os campos para se cadastrar')
        ->assertDontSee('app-sidebar');
});

test('cadastro público cria usuário comum e leva ao login', function () {
    $this->post('/store', [
        'name' => 'Maria Teste',
        'email' => 'maria@example.com',
        'password' => 'senha-segura-1',
        'type' => 'admin',
    ])->assertRedirect('/login')->assertSessionHas('success');

    $maria = User::where('email', 'maria@example.com')->firstOrFail();

    expect($maria->isAdmin())->toBeFalse()
        ->and(Profile::count())->toBe(0);
});

test('cadastro valida email único, senha mínima e preserva valores não sensíveis', function () {
    User::factory()->create(['email' => 'repetido@example.com']);

    $this->from('/register')
        ->post('/store', ['name' => 'Fulano', 'email' => 'repetido@example.com', 'password' => 'curta'])
        ->assertRedirect('/register')
        ->assertSessionHasErrors(['email', 'password'])
        ->assertSessionHasInput('name', 'Fulano')
        ->assertSessionMissing('_old_input.password');
});

test('administrador autenticado cadastra usuário e volta à lista administrativa', function () {
    $this->actingAs(createAdmin())
        ->post('/store', ['name' => 'Novo', 'email' => 'novo@example.com', 'password' => 'senha-segura-1'])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('users', ['email' => 'novo@example.com']);
});
