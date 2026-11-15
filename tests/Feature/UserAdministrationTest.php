<?php

use App\Models\User;
use Database\Seeders\DemoAdminSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    expect(config('database.default'))->toBe('sqlite');
    expect(config('database.connections.sqlite.database'))->toBe(':memory:');
    expect(config('session.driver'))->toBe('array');
    $this->withoutVite();
});

function administrationUser(): User
{
    $user = User::factory()->create();
    $user->profile()->create(['type' => 'admin']);

    return $user;
}

test('registration is independent and creates only a hashed ordinary account', function () {
    $this->get('/register')->assertOk()->assertSee('Sonho do Caçador')
        ->assertSee('name="_token"', false)->assertDontSee('app-sidebar');

    $this->post('/store', [
        'name' => 'Lady Maria', 'email' => 'maria@example.test', 'password' => 'test-password',
        'type' => 'admin', 'profile' => ['type' => 'admin'], 'is_admin' => true,
    ])->assertRedirectToRoute('login')->assertSessionHas('success');

    $user = User::where('email', 'maria@example.test')->firstOrFail();
    expect(Hash::check('test-password', $user->password))->toBeTrue();
    expect($user->profile)->toBeNull();
    $this->assertGuest();
    $this->post('/login', ['email' => $user->email, 'password' => 'test-password'])->assertRedirect('/');
    $this->get('/')->assertOk();
    $this->get('/admin/users')->assertForbidden();
    $this->post('/logout')->assertRedirectToRoute('login');
});

test('registration rejects duplicate email and preserves only nonsensitive input', function () {
    $user = User::factory()->create();
    $this->from('/register')->post('/store', [
        'name' => 'Gehrman', 'email' => $user->email, 'password' => 'test-password',
    ])->assertRedirect('/register')->assertSessionHasErrors('email')
        ->assertSessionHas('_old_input.name', 'Gehrman')
        ->assertSessionHas('_old_input.email', $user->email)
        ->assertSessionMissing('_old_input.password');
    $this->assertDatabaseCount('users', 1);
    $this->withCookie(session()->getName(), session()->getId())->get('/register')
        ->assertSee('is-invalid')->assertSee('Gehrman')->assertDontSee('test-password');
});

test('registration validates required fields email and password length', function (array $input, array $errors) {
    $this->post('/store', $input)->assertSessionHasErrors($errors);
    $this->assertDatabaseCount('users', 0);
})->with([
    'missing' => [[], ['name', 'email', 'password']],
    'short password' => [['name' => 'Hunter', 'email' => 'hunter@example.test', 'password' => 'short'], ['password']],
    'invalid email' => [['name' => 'Hunter', 'email' => 'bad', 'password' => 'test-password'], ['email']],
]);

test('all administrative endpoints redirect guests and forbid ordinary accounts', function (string $method, string $path) {
    $target = User::factory()->create();
    $path = str_replace('{id}', (string) $target->id, $path);
    $this->call($method, $path)->assertRedirectToRoute('login');
    $this->actingAs($target)->call($method, $path)->assertForbidden();
})->with([
    ['GET', '/admin/users'], ['GET', '/admin/users/create'],
    ['GET', '/admin/users/{id}'], ['GET', '/admin/users/{id}/edit'],
    ['PUT', '/admin/users/{id}'], ['PUT', '/admin/users/{id}/profile'],
    ['PUT', '/admin/users/{id}/languages'], ['DELETE', '/admin/users/{id}'],
]);

test('administrator can log in browse create edit profiles replace languages and delete users', function () {
    $admin = administrationUser();
    $this->post('/login', ['email' => $admin->email, 'password' => 'password'])->assertRedirect('/admin/users');
    $this->get('/admin/users')->assertOk()->assertSee('Registro dos Caçadores');
    $this->get('/admin/users/create')->assertOk();
    $this->post('/store', ['name' => 'Hunter', 'email' => 'new@example.test', 'password' => 'test-password'])
        ->assertRedirect('/admin/users')->assertSessionHas('success');
    $user = User::where('email', 'new@example.test')->firstOrFail();
    $this->get("/admin/users/{$user->id}/edit")->assertOk();
    $this->get("/admin/users/{$user->id}")->assertOk();
    $this->put("/admin/users/{$user->id}", ['name' => 'Eileen', 'email' => $user->email])->assertRedirect('/admin/users');
    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Eileen']);
    $this->put("/admin/users/{$user->id}/profile", ['type' => 'PF', 'address' => 'Yharnam'])->assertRedirect('/admin/users');
    $this->assertDatabaseHas('profile', ['user_id' => $user->id, 'address' => 'Yharnam', 'type' => 'PF']);
    $this->put("/admin/users/{$user->id}/languages", ['languages' => ['PHP', 'Python']])->assertRedirect('/admin/users');
    $this->put("/admin/users/{$user->id}/languages", ['languages' => ['PHP']])->assertRedirect('/admin/users');
    expect($user->languages()->pluck('language')->all())->toBe(['PHP']);
    $this->delete("/admin/users/{$user->id}")->assertRedirect('/admin/users');
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
    $this->assertDatabaseMissing('profile', ['user_id' => $user->id]);
    $this->assertDatabaseMissing('user_languages', ['user_id' => $user->id]);
});

test('profile editing preserves admin status and cannot promote ordinary users', function () {
    $admin = administrationUser();
    $user = User::factory()->create();
    $this->actingAs($admin)->put("/admin/users/{$admin->id}/profile", ['type' => 'PF', 'address' => 'Dream'])
        ->assertRedirect('/admin/users');
    expect($admin->profile()->first()->type)->toBe('admin');
    $this->put("/admin/users/{$user->id}/profile", ['type' => 'admin'])->assertSessionHasErrors('type');
    expect($user->profile)->toBeNull();
});

test('registration requires csrf', function () {
    $this->app['env'] = 'local';
    $this->post('/store', ['name' => 'Hunter', 'email' => 'hunter@example.test', 'password' => 'test-password'])
        ->assertStatus(419);
    $this->assertDatabaseCount('users', 0);
});

test('demo seeder is local only', function () {
    $this->app['env'] = 'production';
    expect(fn () => app(DemoAdminSeeder::class)->run())->toThrow(RuntimeException::class);
    $this->assertDatabaseCount('users', 0);
});

test('demo seeder requires configured credentials', function () {
    $this->app['env'] = 'local';
    config(['demo.admin_email' => null, 'demo.admin_password' => null]);
    expect(fn () => app(DemoAdminSeeder::class)->run())->toThrow(\Illuminate\Validation\ValidationException::class);
    $this->assertDatabaseCount('users', 0);
});

test('address migration preserves existing profile descriptions', function () {
    $user = User::factory()->create();
    \Illuminate\Support\Facades\DB::table('profile')->insert([
        'user_id' => $user->id, 'type' => 'PF', 'description' => 'Legacy description',
    ]);
    $migration = require database_path('migrations/2026_11_15_032718_add_address_to_profile_table.php');
    $migration->down();
    $migration->up();
    $this->assertDatabaseHas('profile', [
        'user_id' => $user->id, 'description' => 'Legacy description', 'address' => null,
    ]);
});

test('demo seeder creates an administrator and does not reset its password on rerun', function () {
    $this->app['env'] = 'local';
    config(['demo.admin_email' => 'demo@example.test', 'demo.admin_password' => 'test-password-123']);
    $this->seed(DemoAdminSeeder::class);
    $admin = User::where('email', 'demo@example.test')->firstOrFail();
    expect($admin->profile->type)->toBe('admin');
    expect(Hash::check('test-password-123', $admin->password))->toBeTrue();
    config(['demo.admin_password' => 'different-password-123']);
    $this->seed(DemoAdminSeeder::class);
    expect($admin->fresh()->password)->toBe($admin->password);
    $this->assertDatabaseCount('users', 1);
    $this->app['env'] = 'testing';
    $this->post('/login', ['email' => $admin->email, 'password' => 'test-password-123'])->assertRedirect('/admin/users');
    $this->get('/admin/users')->assertOk();
});

test('demo seeder refuses to promote an existing ordinary account', function () {
    $user = User::factory()->create();
    $this->app['env'] = 'local';
    config(['demo.admin_email' => $user->email, 'demo.admin_password' => 'test-password-123']);
    expect(fn () => $this->seed(DemoAdminSeeder::class))->toThrow(RuntimeException::class);
    expect($user->fresh()->profile)->toBeNull();
});
