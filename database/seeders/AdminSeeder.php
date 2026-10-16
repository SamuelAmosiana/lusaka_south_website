<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@lsc.edu.zm')],
            [
                'name'              => 'Site Administrator',
                'username'          => env('ADMIN_USERNAME', 'super_admin'),
                'email'             => env('ADMIN_EMAIL', 'admin@lsc.edu.zm'),
                'password'          => Hash::make(env('ADMIN_PASSWORD', '@Adm1n')),
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
