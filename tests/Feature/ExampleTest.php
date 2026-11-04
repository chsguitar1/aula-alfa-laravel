<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('usuario autenticado ve a pagina inicial', function () {
    $this->withoutVite();

    $this->actingAs(User::factory()->create())->get('/')->assertStatus(200);
});
