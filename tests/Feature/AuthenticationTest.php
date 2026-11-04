<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

function makeAdmin(): User
{
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => 'admin']);

    return $admin;
}

test('visitante e redirecionado ao login ao abrir home e painel', function () {
    $this->get('/')->assertRedirect('/login');
    $this->get('/admin/users')->assertRedirect('/login');
});

test('login e cadastro sao paginas independentes do layout administrativo', function () {
    $this->get('/login')->assertOk()->assertDontSee('app-sidebar', false)->assertSee('Entre com seu email e senha');
    $this->get('/register')->assertOk()->assertDontSee('app-sidebar', false)->assertSee('Preencha os campos para se cadastrar');
});

test('cadastro publico cria conta comum e envia ao login', function () {
    $this->post('/store', [
        'name' => 'Maria',
        'email' => 'maria@example.test',
        'password' => 'senha-segura-1',
        'type' => 'admin',
    ])->assertRedirect(route('login'))->assertSessionHas('success');

    $user = User::where('email', 'maria@example.test')->firstOrFail();
    expect($user->isAdmin())->toBeFalse();
    expect(Profile::where('user_id', $user->id)->exists())->toBeFalse();
});

test('cadastro valida email unico e senha minima', function () {
    User::factory()->create(['email' => 'dup@example.test']);

    $this->from('/register')->post('/store', [
        'name' => 'X', 'email' => 'dup@example.test', 'password' => 'curta',
    ])->assertRedirect('/register')->assertSessionHasErrors(['email', 'password']);
});

test('usuario comum faz login, vai para home e nao acessa o painel (403)', function () {
    $user = User::factory()->create(['password' => 'senha-segura-1']);

    $this->post('/login', ['email' => $user->email, 'password' => 'senha-segura-1'])
        ->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($user);

    $this->get('/admin/users')->assertForbidden();
});

test('admin faz login e acessa o painel', function () {
    $admin = makeAdmin();
    $admin->update(['password' => 'senha-segura-1']);

    $this->post('/login', ['email' => $admin->email, 'password' => 'senha-segura-1'])
        ->assertRedirect(route('admin.users.index'));

    $this->get('/admin/users')->assertOk();
});

test('admin retorna ao destino interno solicitado antes do login', function () {
    $admin = makeAdmin();
    $admin->update(['password' => 'senha-segura-1']);

    $this->get('/admin/users')->assertRedirect('/login');
    $this->post('/login', ['email' => $admin->email, 'password' => 'senha-segura-1'])
        ->assertRedirect(url('/admin/users'));
});

test('credenciais invalidas mostram mensagem generica e preservam so o email', function () {
    $user = User::factory()->create();

    $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'errada'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['email' => 'Credenciais inválidas.'])
        ->assertSessionHasInput('email', $user->email)
        ->assertSessionMissing('_old_input.password');

    $this->assertGuest();
});

test('login e limitado apos 5 tentativas falhas', function () {
    $user = User::factory()->create();
    RateLimiter::clear(strtolower($user->email).'|127.0.0.1');

    foreach (range(1, 5) as $i) {
        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'errada']);
    }

    $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('logout via POST encerra a sessao e redireciona ao login', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/logout')->assertRedirect(route('login'));
    $this->assertGuest();
});

test('logout por GET nao existe e visitante nao consegue deslogar', function () {
    $this->actingAs(User::factory()->create())->get('/logout')->assertStatus(405);
    $this->post('/logout')->assertRedirect('/login');
});

test('toda rota administrativa registrada exige o middleware EnsureAdmin', function () {
    $adminRoutes = collect(app('router')->getRoutes()->getRoutes())
        ->filter(fn ($route) => str_starts_with($route->uri(), 'admin'));

    expect($adminRoutes)->not->toBeEmpty();
    $adminRoutes->each(fn ($route) => expect($route->gatherMiddleware())->toContain(\App\Http\Middleware\EnsureAdmin::class));
});
