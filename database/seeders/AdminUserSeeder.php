<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Cria o usuário administrador apenas em ambiente local.
     * A senha é de desenvolvimento e nunca deve ser usada em produção.
     */
    public function run(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Administrador', 'password' => 'admin12345']
        );

        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            ['type' => 'admin', 'address' => 'Endereço de desenvolvimento']
        );
    }
}
