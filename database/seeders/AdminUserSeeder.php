<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@teste.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('Admin@123456'),
            ]
        );
        $admin->profile()->firstOrCreate(
            [],
            [
                'type' => 'admin',
                'description' => "Administrador de sistema"
            ]
        );
    }
}
