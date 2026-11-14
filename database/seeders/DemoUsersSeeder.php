<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        $admin = User::firstOrCreate(['email' => 'admin@example.test'], [
            'name' => 'Administrador de demonstração', 'password' => 'DemoLocal123!',
        ]);
        $admin->profile()->updateOrCreate([], ['type' => 'admin', 'address' => null]);
        User::firstOrCreate(['email' => 'user@example.test'], [
            'name' => 'Usuário de demonstração', 'password' => 'DemoLocal123!',
        ]);
    }
}
