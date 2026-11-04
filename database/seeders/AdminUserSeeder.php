<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Provisiona o administrador de DEMONSTRAÇÃO, somente em ambiente local/testes.
     * Não existe rota pública que conceda type = admin.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            $this->command?->warn('AdminUserSeeder ignorado: só roda em ambiente local/testing.');

            return;
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador Local',
                // Senha apenas de desenvolvimento; pode ser trocada via .env (SEED_ADMIN_PASSWORD).
                'password' => env('SEED_ADMIN_PASSWORD', 'admin-local-123'),
            ]
        );

        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            ['type' => Profile::TYPE_ADMIN, 'address' => 'Ambiente local']
        );
    }
}
