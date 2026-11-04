<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

// Provisiona um administrador SOMENTE em desenvolvimento local.
// Credenciais de demonstração (não são de produção): admin@example.test / admin-local-123
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            $this->command?->warn('AdminUserSeeder ignorado: só executa em APP_ENV=local.');

            return;
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.test'],
            ['name' => 'Administrador Local', 'password' => 'admin-local-123'],
        );

        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            ['type' => 'admin', 'address' => null],
        );
    }
}
