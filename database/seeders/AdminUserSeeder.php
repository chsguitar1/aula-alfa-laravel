<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Provisiona contas de DEMONSTRAÇÃO para ambiente local.
 *
 * O perfil "admin" só é criado por aqui (php artisan db:seed), nunca pelo
 * cadastro público. As senhas abaixo são apenas para desenvolvimento.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            $this->command?->warn('AdminUserSeeder ignorado: disponível apenas em ambiente local/testing.');

            return;
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Administrador Demo', 'password' => 'admin12345'],
        );

        $admin->profile()->updateOrCreate([], [
            'type' => Profile::TYPE_ADMIN,
            'address' => null,
        ]);

        User::updateOrCreate(
            ['email' => 'comum@example.com'],
            ['name' => 'Usuário Comum Demo', 'password' => 'comum12345'],
        );
    }
}
