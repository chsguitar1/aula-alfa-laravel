<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Provisiona o administrador de DESENVOLVIMENTO. Roda só pelo terminal
 * (php artisan db:seed) e nunca em produção. Não existe formulário público
 * que aceite type = admin.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->warn('AdminUserSeeder ignorado em produção.');

            return;
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@aula.test'],
            [
                'name' => 'Administrador Local',
                // Senha apenas para ambiente local/demonstração.
                'password' => 'admin-local-123',
            ]
        );

        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            ['type' => 'admin', 'address' => null]
        );
    }
}
