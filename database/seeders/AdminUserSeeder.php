<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            throw new RuntimeException(
                'Este Seeder só pode ser executado em ambiente local.'
            );
        }

        $email = env('ADMIN_SEED_EMAIL');
        $password = env('ADMIN_SEED_PASSWORD');

        if (empty($email) || empty($password)) {
            throw new RuntimeException(
                'Configure ADMIN_SEED_EMAIL e ADMIN_SEED_PASSWORD no .env.'
            );
        }

        DB::transaction(function () use ($email, $password) {
            $user = User::where('email', $email)->first();

            if ($user) {
                if ($user->profile?->type !== 'admin') {
                    throw new RuntimeException(
                        'O email informado já pertence a uma conta não administrativa.'
                    );
                }
            } else {
                $user = User::create([
                    'name' => 'Administrador Teste',
                    'email' => $email,
                    'password' => $password,
                ]);

                $user->profile()->create([
                    'type' => 'admin',
                    'description' => 'Administrador do sistema',
                ]);
            }
        });

        $this->command->info(
            'Administrador local configurado com sucesso.'
        );
    }
}
