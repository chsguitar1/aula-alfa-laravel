<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(20)->create();

        // Conta de demonstração local. Troque a senha antes de qualquer uso real.
        $admin = User::firstOrCreate(
            ['email' => 'admin@exemplo.com'],
            ['name' => 'Administrador', 'password' => Hash::make('Admin123!')]
        );

        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            ['type' => 'admin', 'description' => 'Administrador de demonstração']
        );
    }
}
