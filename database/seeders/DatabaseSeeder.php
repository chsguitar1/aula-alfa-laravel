<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Provisiona contas de demonstração para o ambiente local:
     * - Administradores (type = admin)
     * - Usuários Comuns (type = user)
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Cristiano',
                'email' => 'admin@alfa.com',
                'password' => 'senha123',
                'type' => 'admin',
                'description' => 'Administrador do Sistema',
            ],
            [
                'name' => 'CRISTIANO HERCULANO DA SILVA',
                'email' => 'cristianos@ciandt.com',
                'password' => 'senha123',
                'type' => 'admin',
                'description' => 'Perfil Administrativo',
            ],
            [
                'name' => 'Leonardo Nogueira',
                'email' => 'cristianos@ciandt.com.br',
                'password' => 'senha123',
                'type' => 'user',
                'description' => 'Usuário Comum',
            ],
            [
                'name' => 'Usuário Comum',
                'email' => 'usuario@alfa.com',
                'password' => 'senha123',
                'type' => 'user',
                'description' => 'Usuário Comum',
            ],
        ];

        foreach ($accounts as $acc) {
            $user = User::updateOrCreate(
                ['email' => $acc['email']],
                [
                    'name' => $acc['name'],
                    'password' => Hash::make($acc['password']),
                    'address' => 'Umuarama - PR',
                ]
            );

            Profile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'type' => $acc['type'],
                    'description' => $acc['description'],
                ]
            );
        }
    }
}
