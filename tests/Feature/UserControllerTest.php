<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

function makeAdmin(): User
{
    $admin = User::factory()->create();
    Profile::create(['user_id' => $admin->id, 'type' => Profile::TYPE_ADMIN, 'address' => 'Local']);

    return $admin;
}

it('updates the user when the edit form is submitted', function () {
    $admin = makeAdmin();
    $user = User::factory()->create([
        'name' => 'Nome antigo',
        'email' => 'user@example.com',
    ]);

    $editResponse = $this->actingAs($admin)->get(route('admin.user.edit', $user));

    $editResponse
        ->assertOk()
        ->assertSee('name="_method" value="PUT"', false);

    $csrfToken = 'edit-form-token';
    $response = $this
        ->actingAs($admin)
        ->withSession(['_token' => $csrfToken])
        ->post(route('admin.user.update', $user), [
            '_token' => $csrfToken,
            '_method' => 'PUT',
            'name' => 'Nome atualizado',
            'email' => $user->email,
        ]);

    $response
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success', 'Usuário atualizado com sucesso!');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Nome atualizado',
        'email' => 'user@example.com',
    ]);
});

it('keeps the admin type when an admin profile is saved from the profile form', function () {
    $admin = makeAdmin();

    $this->actingAs($admin)
        ->put(route('admin.user.updateProfile', $admin), ['type' => 'PF', 'address' => 'Rua A'])
        ->assertRedirect(route('admin.users.index'));

    $this->assertDatabaseHas('profile', ['user_id' => $admin->id, 'type' => 'admin', 'address' => 'Rua A']);
});
