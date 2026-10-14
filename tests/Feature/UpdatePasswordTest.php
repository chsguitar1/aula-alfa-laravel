<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('updates the password of the user with the given email', function () {
    $user = User::factory()->create();

    $this->post(route('password.update'), [
        'email' => $user->email,
        'password' => 'nova-senha-123',
    ])->assertRedirect(route('admin.users.index'));

    expect(Hash::check('nova-senha-123', $user->fresh()->password))->toBeTrue();
});

it('returns an error when the email does not exist', function () {
    $this->post(route('password.update'), [
        'email' => 'naoexiste@example.com',
        'password' => 'nova-senha-123',
    ])->assertSessionHasErrors('email');
});

it('renders the forgot password form', function () {
    $this->get(route('password.forgot'))
        ->assertOk()
        ->assertSee(route('password.update'));
});
