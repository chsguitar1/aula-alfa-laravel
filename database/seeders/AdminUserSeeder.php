<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

// Somente para ambiente local: php artisan db:seed --class=AdminUserSeeder
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            $this->command?->warn('AdminUserSeeder ignorado: só roda em ambiente local.');

            return;
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.test'],
            ['name' => 'Admin Local', 'password' => 'admin-local-123']
        );

        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            ['type' => 'admin', 'address' => null]
        );
    }
}
