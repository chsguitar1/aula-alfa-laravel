<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin só para ambiente local. Senha de desenvolvimento, não de produção.
        $admin = User::updateOrCreate(
            ['email' => 'admin@local.test'],
            ['name' => 'Admin Demo', 'password' => 'admin-local-123']
        );

        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            ['type' => 'admin', 'address' => 'Ambiente local']
        );
    }
}
