<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@local.test'],
            ['name' => 'Admin Local', 'password' => 'admin12345']
        );

        $user->profile()->updateOrCreate([], [
            'type' => 'admin',
            'description' => 'Administrador local',
        ]);
    }
}
