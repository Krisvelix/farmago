<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@farmago.com'],
            [
                'name' => 'Admin Farmago',
                'password' => 'FarmagoAdmin123',
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}