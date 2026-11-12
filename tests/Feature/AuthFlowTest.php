<?php

use App\Models\User;

it('exibe a página de login para visitantes', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Entre com seu email e senha');
});

it('preserva somente o email quando as credenciais de login são inválidas', function () {
    $this->followingRedirects()
        ->from('/login')
        ->post('/login', [
            'email' => 'nao-existe@example.com',
            'password' => 'senha-privada',
        ])
        ->assertOk()
        ->assertSee('nao-existe@example.com')
        ->assertSee('Credenciais inválidas.')
        ->assertDontSee('senha-privada');
});

it('exibe o cadastro público em uma página independente', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSee('Preencha os campos para se cadastrar')
        ->assertSee('name="name"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="password"', false)
        ->assertDontSee('app-sidebar');
});

it('cria usuário comum pelo cadastro público e redireciona para o login', function () {
    $response = $this->post('/store', [
        'name' => 'Maria Silva',
        'email' => 'maria@example.com',
        'password' => 'senha123',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHas('success', 'Cadastro realizado com sucesso. Faça login para continuar.');

    $this->assertDatabaseHas('users', ['email' => 'maria@example.com']);
    $user = User::query()->where('email', 'maria@example.com')->firstOrFail();
    $this->assertDatabaseMissing('profile', ['user_id' => $user->id, 'type' => 'admin']);
});

it('retorna erros de validação e mantém os valores não sensíveis no cadastro', function () {
    User::factory()->create(['email' => 'duplicado@example.com']);

    $this->followingRedirects()
        ->from('/register')
        ->post('/store', [
            'name' => 'Nome preservado',
            'email' => 'duplicado@example.com',
            'password' => 'curta',
        ])
        ->assertOk()
        ->assertSee('Nome preservado')
        ->assertSee('duplicado@example.com')
        ->assertDontSee('value="curta"')
        ->assertSee('The email has already been taken.')
        ->assertSee('The password field must be at least 8 characters.');
});

it('nega o acesso administrativo para usuário comum e permite para admin', function () {
    $common = User::factory()->create();
    $common->profile()->create([
        'type' => 'user',
        'address' => 'Rua das Flores, 321',
        'description' => '',
    ]);

    $this->actingAs($common)->get('/admin/users')->assertForbidden();

    $admin = User::factory()->create([
        'email' => 'admin-flow@example.com',
    ]);
    $admin->profile()->create([
        'type' => 'admin',
        'address' => 'Avenida Central, 99',
        'description' => '',
    ]);

    $this->actingAs($admin)->get('/admin/users')->assertOk()->assertSee('Lista de usuários do Sistema');
});

it('redireciona visitantes para o login ao acessar a área administrativa', function () {
    $this->get('/admin/users')
        ->assertRedirect('/login');
});

it('limita tentativas inválidas de login a cinco por minuto', function () {
    for ($attempt = 0; $attempt < 5; $attempt++) {
        $this->from('/login')
            ->post('/login', [
                'email' => 'nao-existe@example.com',
                'password' => 'senha-incorreta',
            ])
            ->assertRedirect('/login');
    }

    $this->from('/login')
        ->post('/login', [
            'email' => 'nao-existe@example.com',
            'password' => 'senha-incorreta',
        ])
        ->assertStatus(429);
});

it('loga o usuário com perfil administrativo no painel', function () {
    $admin = User::factory()->create([
        'email' => 'admin-login@example.com',
        'password' => 'senha123',
    ]);
    $admin->profile()->create([
        'type' => 'admin',
        'address' => 'Avenida do Admin',
        'description' => '',
    ]);

    $this->post('/login', [
        'email' => 'admin-login@example.com',
        'password' => 'senha123',
    ])->assertRedirect('/admin/users');

    $this->assertAuthenticatedAs($admin);
});

it('mantém o destino administrativo pretendido ao autenticar um administrador', function () {
    $admin = User::factory()->create([
        'email' => 'admin-intended@example.com',
        'password' => 'senha123',
    ]);
    $admin->profile()->create([
        'type' => 'admin',
        'address' => 'Avenida Central',
        'description' => '',
    ]);

    $this->get('/admin/users')->assertRedirect('/login');

    $this->post('/login', [
        'email' => 'admin-intended@example.com',
        'password' => 'senha123',
    ])->assertRedirect('/admin/users');
});

it('encerra a sessão ao receber uma requisição post de logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect('/login');

    $this->assertGuest();
});

it('redireciona usuário comum para a página inicial mesmo após tentar acessar a área administrativa', function () {
    $common = User::factory()->create([
        'email' => 'common-intended@example.com',
        'password' => 'senha123',
    ]);
    $common->profile()->create([
        'type' => 'user',
        'address' => 'Rua das Flores, 321',
        'description' => '',
    ]);

    $this->get('/admin/users')->assertRedirect('/login');

    $this->post('/login', [
        'email' => 'common-intended@example.com',
        'password' => 'senha123',
    ])->assertRedirect('/');

    $this->get('/')->assertOk();
});

it('permite que um administrador cadastre usuário pelo painel', function () {
    $admin = User::factory()->create();
    $admin->profile()->create([
        'type' => 'admin',
        'address' => 'Avenida Central',
        'description' => '',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.users.create'))
        ->assertOk()
        ->assertSee('Preencha os campos para se cadastrar');

    $this->post(route('users.store'), [
        'name' => 'Usuário criado pelo admin',
        'email' => 'admin-created@example.com',
        'password' => 'senha123',
    ])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success', 'Usuário criado com sucesso!');

    $this->assertDatabaseHas('users', ['email' => 'admin-created@example.com']);
    $this->assertDatabaseMissing('profile', [
        'user_id' => User::query()->where('email', 'admin-created@example.com')->value('id'),
        'type' => 'admin',
    ]);
});

it('nega cadastro pelo endpoint compartilhado para usuário autenticado comum', function () {
    $common = User::factory()->create();

    $this->actingAs($common)
        ->post(route('users.store'), [
            'name' => 'Cadastro não permitido',
            'email' => 'blocked-created@example.com',
            'password' => 'senha123',
        ])
        ->assertForbidden();

    $this->assertDatabaseMissing('users', ['email' => 'blocked-created@example.com']);
});

it('cria ou atualiza o perfil do usuário sem perder o campo descrição do schema existente', function () {
    $admin = User::factory()->create();
    $admin->profile()->create([
        'type' => 'admin',
        'address' => 'Avenida Central',
        'description' => '',
    ]);

    $user = User::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.user.updateProfile', $user), [
            'type' => 'PF',
            'address' => 'Rua Atualizada, 10',
        ])
        ->assertRedirect(route('admin.users.index'));

    $this->assertDatabaseHas('profile', [
        'user_id' => $user->id,
        'type' => 'PF',
        'address' => 'Rua Atualizada, 10',
        'description' => '',
    ]);
});

it('substitui as linguagens do usuário pelas seleções enviadas', function () {
    $admin = User::factory()->create();
    $admin->profile()->create([
        'type' => 'admin',
        'address' => 'Avenida Central',
        'description' => '',
    ]);

    $user = User::factory()->create();
    $user->languages()->createMany([
        ['language' => 'Java'],
        ['language' => 'PHP'],
    ]);

    $this->actingAs($admin)
        ->put(route('admin.user.updateLanguages', $user), [
            'languages' => ['Python', 'C#'],
        ])
        ->assertRedirect(route('admin.users.index'));

    $this->assertDatabaseMissing('user_languages', [
        'user_id' => $user->id,
        'language' => 'Java',
    ]);
    $this->assertDatabaseHas('user_languages', [
        'user_id' => $user->id,
        'language' => 'Python',
    ]);
    $this->assertDatabaseHas('user_languages', [
        'user_id' => $user->id,
        'language' => 'C#',
    ]);
});

it('exclui um usuário pelo painel administrativo', function () {
    $admin = User::factory()->create();
    $admin->profile()->create([
        'type' => 'admin',
        'address' => 'Avenida Central',
        'description' => '',
    ]);

    $user = User::factory()->create();

    $response = $this->actingAs($admin)
        ->from(route('admin.users.index'))
        ->delete(route('admin.user.destroy', $user));

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});
