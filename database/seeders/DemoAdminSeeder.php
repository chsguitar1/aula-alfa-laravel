<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class DemoAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            throw new RuntimeException('O administrador de demonstração só pode ser criado no ambiente local.');
        }

        $input = Validator::make([
            'name' => config('demo.admin_name'),
            'email' => config('demo.admin_email'),
            'password' => config('demo.admin_password'),
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:12'],
        ])->validate();

        DB::transaction(function () use ($input) {
            $existing = User::where('email', $input['email'])->first();
            if ($existing) {
                if ($existing->profile?->type !== 'admin') {
                    throw new RuntimeException('Este email já pertence a uma conta comum; escolha outro email.');
                }

                return; // Do not reset an existing administrator's password.
            }

            $user = User::create($input);
            $user->profile()->create(['type' => 'admin']);
        });
    }
}
