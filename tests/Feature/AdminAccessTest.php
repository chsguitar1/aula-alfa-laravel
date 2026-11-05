<?php

use App\Models\Profile;
use App\Models\User;

test('authenticated user without admin profile gets 403 on every admin route', function () {
    $user = User::factory()->create();
    $target = User::factory()->create();

    $this->actingAs($user);

    $this->get('/admin/users')->assertForbidden();
    $this->get("/admin/users/{$target->id}")->assertForbidden();
    $this->get("/admin/users/{$target->id}/edit")->assertForbidden();
    $this->put("/admin/users/{$target->id}", ['name' => 'x', 'email' => 'x@x.com'])->assertForbidden();
    $this->put("/admin/users/{$target->id}/profile", ['type' => 'PF'])->assertForbidden();
    $this->put("/admin/users/{$target->id}/languages", [])->assertForbidden();
    $this->delete("/admin/users/{$target->id}")->assertForbidden();

    expect(User::find($target->id))->not->toBeNull();
});

test('user whose profile type is not admin gets 403', function () {
    $user = User::factory()->create();
    Profile::create(['user_id' => $user->id, 'type' => 'PF']);

    $this->actingAs($user)->get('/admin/users')->assertForbidden();
});

test('admin profile can use the existing panel features', function () {
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => 'admin']);
    $target = User::factory()->create();

    $this->actingAs($admin);

    $this->get('/admin/users')->assertOk()->assertSee($target->email);
    $this->get("/admin/users/{$target->id}/edit")->assertOk();

    $this->put("/admin/users/{$target->id}", ['name' => 'Novo Nome', 'email' => $target->email])
        ->assertRedirect(route('admin.users.index'));
    expect($target->fresh()->name)->toBe('Novo Nome');

    $this->put("/admin/users/{$target->id}/profile", ['type' => 'PF', 'address' => 'Rua A, 10'])
        ->assertRedirect(route('admin.users.index'));
    expect($target->fresh()->profile->address)->toBe('Rua A, 10');

    $this->put("/admin/users/{$target->id}/languages", ['languages' => ['PHP', 'Python']])
        ->assertRedirect(route('admin.users.index'));
    expect($target->languages()->count())->toBe(2);

    $this->delete("/admin/users/{$target->id}")->assertRedirect(route('admin.users.index'));
    expect(User::find($target->id))->toBeNull();
});

test('every route under admin is protected by the admin middleware', function () {
    $routes = collect(app('router')->getRoutes()->getRoutes())
        ->filter(fn ($route) => str_starts_with($route->uri(), 'admin/'));

    expect($routes)->not->toBeEmpty();

    $routes->each(fn ($route) => expect($route->gatherMiddleware())
        ->toContain(\App\Http\Middleware\EnsureAdmin::class));
});
