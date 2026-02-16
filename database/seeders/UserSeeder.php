<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
       

        User::create([
            'name' => 'Admin O’G Délice',
            'email' => 'admin@ogdelice.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Vendeur Principal',
            'email' => 'vendeur@ogdelice.com',
            'password' => Hash::make('password'),
            'role' => 'vendeur',
        ]);

        User::create([
            'name' => 'Client Test',
            'email' => 'client@ogdelice.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);
    }
}
