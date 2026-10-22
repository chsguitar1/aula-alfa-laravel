<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

it('updates the user when the edit form is submitted', function () {
    $user = User::factory()->create([
        'name' => 'Nome antigo',
        'email' => 'user@example.com',
    ]);

    $editResponse = $this->get(route('admin.user.edit', $user));

    $editResponse
        ->assertOk()
        ->assertSee('name="_method" value="PUT"', false);

    $csrfToken = 'edit-form-token';
    $response = $this
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
