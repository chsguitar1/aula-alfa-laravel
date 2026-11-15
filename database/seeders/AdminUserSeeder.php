<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Provisiona o administrador de demonstração. Só roda em ambiente local:
 * o cadastro público nunca cria administradores.
 */
class AdminUserSeeder extends Seeder
{
    public const EMAIL = 'admin@aula-alfa.test';

    // Senha apenas para desenvolvimento local; nunca use em produção.
    public const PASSWORD = 'senha-local-123';

    public function run(): void
    {
        if (! app()->isLocal()) {
            $this->command?->warn('AdminUserSeeder ignorado: só roda em ambiente local.');

            return;
        }

        $admin = User::updateOrCreate(
            ['email' => self::EMAIL],
            ['name' => 'Administrador Local', 'password' => self::PASSWORD],
        );

        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            ['type' => Profile::TYPE_ADMIN, 'address' => null],
        );
    }
}
