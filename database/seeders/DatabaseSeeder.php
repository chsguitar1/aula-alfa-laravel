<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        User::factory(20)->create();

        $commonUser = User::updateOrCreate(
            ['email' => 'usuario@demo.local'],
            [
                'name' => 'Usuário Demo',
                'password' => 'senha123',
            ],
        );
        $commonUser->profile()->updateOrCreate([], [
            'type' => 'user',
            'address' => 'Rua das Flores, 123',
            'description' => '',
        ]);

        $adminUser = User::updateOrCreate(
            ['email' => 'admin@demo.local'],
            [
                'name' => 'Administrador Demo',
                'password' => 'senha123',
            ],
        );
        $adminUser->profile()->updateOrCreate([], [
            'type' => 'admin',
            'address' => 'Av. Principal, 456',
            'description' => '',
        ]);
    }
}
