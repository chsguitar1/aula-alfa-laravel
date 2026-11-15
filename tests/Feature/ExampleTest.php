<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

test('the application returns a successful response for an authenticated user', function () {
    $response = $this->actingAs(User::factory()->create())->get('/');

    $response->assertStatus(200);
});
