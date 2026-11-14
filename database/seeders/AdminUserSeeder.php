<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Provisiona o administrador de DESENVOLVIMENTO. Não roda em produção.
 *
 * Credenciais locais (apenas para demonstração):
 *   email: admin@example.test
 *   senha: admin-local-123
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
            ['email' => 'admin@example.test'],
            ['name' => 'Administrador Local', 'password' => 'admin-local-123'],
        );

        // "type" não é aceito por nenhum formulário público: só este seeder grava admin.
        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            ['type' => 'admin', 'address' => null],
        );
    }
}
