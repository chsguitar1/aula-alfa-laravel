<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class LocalAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            throw new RuntimeException('LocalAdminSeeder só pode ser executado no ambiente local.');
        }

        $email = config('demo.local_admin.email');
        $password = config('demo.local_admin.password');

        if (blank($email) || blank($password)) {
            throw new RuntimeException('Defina LOCAL_ADMIN_EMAIL e LOCAL_ADMIN_PASSWORD no .env local.');
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            ['name' => 'Administrador local', 'password' => $password],
        );

        $user->profile()->updateOrCreate([], [
            'type' => 'admin',
            'description' => '',
        ]);
    }
}
