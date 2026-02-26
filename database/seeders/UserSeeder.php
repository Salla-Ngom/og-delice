<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 🔐 Super Admin
        User::updateOrCreate(
            ['email' => 'admin@ogdelice.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 🛒 Vendeur
        User::updateOrCreate(
            ['email' => 'vendeur@ogdelice.com'],
            [
                'name' => 'Vendeur O\'G',
                'password' => Hash::make('password'),
                'role' => 'vendeur',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        User::updateOrCreate(
            ['email' => 'client@ogdelice.com'],
            [
                'name' => 'Delmontero',
                'password' => Hash::make('password'),
                'role' => 'client',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
