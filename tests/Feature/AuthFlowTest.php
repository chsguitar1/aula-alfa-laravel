<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('registers a common user without accepting administrative profile data', function () {
    $this->post(route('users.store'), [
        'name' => 'Pessoa Usuária',
        'email' => 'pessoa@example.test',
        'password' => 'senha-segura-123',
        'type' => 'admin',
    ])
        ->assertRedirectToRoute('login')
        ->assertSessionHas('success', 'Cadastro realizado com sucesso. Faça login para continuar.');

    $user = User::where('email', 'pessoa@example.test')->firstOrFail();

    expect(Hash::check('senha-segura-123', $user->password))->toBeTrue();

    $this->assertDatabaseMissing('profile', ['user_id' => $user->id]);
});

it('validates unique emails and minimum password length during registration', function () {
    User::factory()->create(['email' => 'ocupado@example.test']);

    $this->from(route('register'))
        ->post(route('users.store'), [
            'name' => 'Outra pessoa',
            'email' => 'ocupado@example.test',
            'password' => 'curta',
        ])
        ->assertRedirectToRoute('register')
        ->assertSessionHasErrors(['email', 'password']);

    $this->assertDatabaseCount('users', 1);
});

it('returns an authenticated common user to the home page and logs them out', function () {
    $user = User::factory()->create([
        'email' => 'pessoa@example.test',
        'password' => 'senha-segura-123',
    ]);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'senha-segura-123',
    ])->assertRedirectToRoute('home');

    $this->get('/')
        ->assertOk();

    $this->post(route('logout'))
        ->assertRedirectToRoute('login');

    $this->get('/')
        ->assertRedirectToRoute('login');
});

it('shows a generic login error and preserves only the email after invalid credentials', function () {
    User::factory()->create([
        'email' => 'pessoa@example.test',
        'password' => 'senha-correta-123',
    ]);

    $this->followingRedirects()
        ->from(route('login'))
        ->post(route('login.store'), [
            'email' => 'pessoa@example.test',
            'password' => 'senha-incorreta-123',
        ])
        ->assertOk()
        ->assertSee('value="pessoa@example.test"', false)
        ->assertDontSee('value="senha-incorreta-123"', false)
        ->assertSee('As credenciais informadas são inválidas.');
});

it('redirects an admin to the previously requested protected page', function () {
    $user = User::factory()->create([
        'email' => 'admin@example.test',
        'password' => 'senha-segura-123',
    ]);
    $user->profile()->create([
        'type' => 'admin',
        'description' => '',
    ]);

    $this->get(route('admin.user.edit', $user->id))
        ->assertRedirectToRoute('login');

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'senha-segura-123',
    ])->assertRedirect(route('admin.user.edit', $user->id));

    $this->get(route('admin.user.edit', $user->id))
        ->assertOk()
        ->assertSee('Dados do usuário');
});

it('does not redirect admins to an external intended URL', function () {
    $user = User::factory()->create([
        'email' => 'admin@example.test',
        'password' => 'senha-segura-123',
    ]);
    $user->profile()->create([
        'type' => 'admin',
        'description' => '',
    ]);

    $this->withSession(['url.intended' => 'https://evil.example/'])
        ->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'senha-segura-123',
        ])
        ->assertRedirectToRoute('admin.users.index');
});

it('redirects admin-area guests to login and forbids authenticated common users', function () {
    $this->get(route('admin.users.index'))
        ->assertRedirectToRoute('login');

    $this->actingAs(User::factory()->create())
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

it('allows admin users to open the protected user list', function () {
    $admin = User::factory()->create();
    $admin->profile()->create([
        'type' => 'admin',
        'description' => '',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee('Ações')
        ->assertSee('action="'.route('logout').'" method="POST"', false)
        ->assertSee('Sair');
});

it('returns admin-created accounts to the administrative user list', function () {
    $admin = User::factory()->create();
    $admin->profile()->create([
        'type' => 'admin',
        'description' => '',
    ]);

    $this->actingAs($admin)
        ->post(route('users.store'), [
            'name' => 'Novo usuário',
            'email' => 'novo@example.test',
            'password' => 'senha-segura-123',
        ])
        ->assertRedirectToRoute('admin.users.index')
        ->assertSessionHas('success', 'Usuário criado com sucesso!');

    $this->assertDatabaseHas('users', ['email' => 'novo@example.test']);
    $this->assertDatabaseMissing('profile', [
        'user_id' => User::where('email', 'novo@example.test')->value('id'),
    ]);
});

it('updates profile address and selected programming languages through admin routes', function () {
    $admin = User::factory()->create();
    $admin->profile()->create([
        'type' => 'admin',
        'description' => '',
    ]);

    $user = User::factory()->create();
    $user->languages()->create(['language' => 'Java']);

    $this->actingAs($admin)
        ->put(route('admin.user.updateProfile', $user), [
            'type' => 'PF',
            'address' => 'Rua das Flores, 10',
        ])
        ->assertRedirectToRoute('admin.users.index');

    $this->assertDatabaseHas('profile', [
        'user_id' => $user->id,
        'type' => 'PF',
        'address' => 'Rua das Flores, 10',
    ]);

    $this->actingAs($admin)
        ->put(route('admin.user.updateLanguages', $user), [
            'languages' => ['PHP', 'Python'],
        ])
        ->assertRedirectToRoute('admin.users.index');

    $this->assertDatabaseHas('user_languages', [
        'user_id' => $user->id,
        'language' => 'PHP',
    ]);
    $this->assertDatabaseMissing('user_languages', [
        'user_id' => $user->id,
        'language' => 'Java',
    ]);
    $this->assertDatabaseCount('user_languages', 2);
});

it('limits login requests to five per minute for the same email and IP', function () {
    $credentials = [
        'email' => 'pessoa@example.test',
        'password' => 'senha-incorreta-123',
    ];

    foreach (range(1, 5) as $attempt) {
        $this->from(route('login'))
            ->post(route('login.store'), $credentials)
            ->assertRedirectToRoute('login');
    }

    $this->from(route('login'))
        ->post(route('login.store'), $credentials)
        ->assertTooManyRequests();
});
