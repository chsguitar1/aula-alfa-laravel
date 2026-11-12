<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Conta exclusiva para demonstração local. Altere a senha após o primeiro uso.
        $admin = User::firstOrCreate(
            ['email' => 'admin@teste.com'],
            ['name' => 'Administrador', 'password' => 'Admin@123']
        );

        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            ['type' => 'admin', 'description' => 'Administrador local']
        );
    }
}
