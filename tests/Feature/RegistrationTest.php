<?php

use App\Models\Profile;
use App\Models\User;

test('public registration creates a common user and redirects to login with a message', function () {
    $this->post('/store', [
        'name' => 'Maria',
        'email' => 'maria@example.com',
        'password' => 'password123',
    ])->assertRedirect(route('login'))
        ->assertSessionHas('success');

    $user = User::where('email', 'maria@example.com')->firstOrFail();

    expect($user->isAdmin())->toBeFalse()
        ->and($user->profile)->toBeNull()
        ->and($user->password)->not->toBe('password123');
});

test('registration cannot grant admin privileges through extra fields', function () {
    $this->post('/store', [
        'name' => 'Hacker',
        'email' => 'hacker@example.com',
        'password' => 'password123',
        'type' => 'admin',
        'profile' => ['type' => 'admin'],
    ]);

    $user = User::where('email', 'hacker@example.com')->firstOrFail();

    expect($user->isAdmin())->toBeFalse()
        ->and(Profile::where('type', 'admin')->count())->toBe(0);

    $this->actingAs($user)->get('/admin/users')->assertForbidden();
});

test('registration validates fields and keeps non sensitive values', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->from('/register')
        ->post('/store', ['name' => 'Ana', 'email' => 'taken@example.com', 'password' => 'short'])
        ->assertRedirect('/register')
        ->assertSessionHasErrors(['email', 'password'])
        ->assertSessionHasInput('name', 'Ana');
});

test('admin creating a user through the form returns to the admin list', function () {
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => 'admin']);

    $this->actingAs($admin)->post('/store', [
        'name' => 'Novo',
        'email' => 'novo@example.com',
        'password' => 'password123',
    ])->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success');

    expect(User::where('email', 'novo@example.com')->first()->isAdmin())->toBeFalse();
});

test('development seeder provisions an admin outside production', function () {
    $this->seed(\Database\Seeders\AdminUserSeeder::class);

    $admin = User::where('email', 'admin@aula.test')->firstOrFail();

    expect($admin->isAdmin())->toBeTrue();
});
