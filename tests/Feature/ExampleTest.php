<?php

use App\Models\User;

test('the application returns a successful response', function () {
    $this->actingAs(User::factory()->make());

    $response = $this->get('/');

    $response->assertStatus(200);
});
