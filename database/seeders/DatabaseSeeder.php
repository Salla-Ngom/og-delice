<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ══════════════════════════════════════
        // UTILISATEURS
        // ══════════════════════════════════════

        User::create([
            'name'              => 'Mamadou Diallo',
            'email'             => 'admin@ogdelice.sn',
            'password'          => Hash::make('Admin@1234'),
            'role'              => 'admin',
            'is_active'         => true,
            'phone'             => '771234567',
            'delivery_address'  => 'Almadies, Dakar',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name'              => 'Fatou Ndiaye',
            'email'             => 'vendeur@ogdelice.sn',
            'password'          => Hash::make('Vendeur@1234'),
            'role'              => 'vendeur',
            'is_active'         => true,
            'phone'             => '781122334',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name'              => 'Salla Ngom',
            'email'             => 'salla@gmail.com',
            'password'          => Hash::make('Client@1234'),
            'role'              => 'client',
            'is_active'         => true,
            'phone'             => '761234567',
            'delivery_address'  => 'Sacré-Cœur 3, Villa 12, Dakar',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name'              => 'Ibrahima Seck',
            'email'             => 'ibrahima@gmail.com',
            'password'          => Hash::make('Client@1234'),
            'role'              => 'client',
            'is_active'         => true,
            'phone'             => '701122334',
            'delivery_address'  => 'Mermoz, Dakar',
            'email_verified_at' => now(),
        ]);

        // ══════════════════════════════════════
        // CATÉGORIES
        // ══════════════════════════════════════

        Category::create(['name' => 'Plats sénégalais', 'slug' => 'plats-senegalais', 'is_active' => true]);
        Category::create(['name' => 'Fast-Food',         'slug' => 'fast-food',         'is_active' => true]);
        Category::create(['name' => 'Boissons',          'slug' => 'boissons',           'is_active' => true]);
        Category::create(['name' => 'Desserts',          'slug' => 'desserts',           'is_active' => true]);

        $this->command->info('✅ Seeder terminé :');
        $this->command->info('   👤 Admin    : admin@ogdelice.sn    / Admin@1234');
        $this->command->info('   👤 Vendeur  : vendeur@ogdelice.sn  / Vendeur@1234');
        $this->command->info('   👤 Client 1 : salla@gmail.com      / Client@1234');
        $this->command->info('   👤 Client 2 : ibrahima@gmail.com   / Client@1234');
        $this->command->info('   📂 4 catégories créées');
    }
}
