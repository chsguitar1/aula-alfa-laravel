<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

// Provisiona o administrador de demonstração. Só roda em ambiente local
// (ver DatabaseSeeder); a senha vem do .env, sem valor de produção.
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => env('ADMIN_SEED_EMAIL', 'admin@example.com')],
            [
                'name' => 'Administrador Local',
                'password' => env('ADMIN_SEED_PASSWORD', 'senha-local-123'),
            ]
        );

        // `type` não é aceito por nenhum formulário público: só este seeder define admin.
        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            ['type' => 'admin', 'address' => 'Ambiente local']
        );
    }
}
