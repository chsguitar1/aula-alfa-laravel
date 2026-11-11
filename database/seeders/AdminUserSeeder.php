<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Cria o administrador de DEMONSTRAÇÃO, somente em ambiente local.
 * Nunca use esta senha fora do seu computador.
 *
 * Rodar: php artisan db:seed --class=AdminUserSeeder
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            $this->command?->warn('AdminUserSeeder ignorado: só roda com APP_ENV=local.');

            return;
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.test'],
            ['name' => 'Administrador Local', 'password' => 'senha-local-123'],
        );

        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            ['type' => 'admin', 'address' => null],
        );
    }
}
