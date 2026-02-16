<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        

        Category::create([
            'name' => 'Fast Food',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Plats traditionnels',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Boissons',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Desserts',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Traiteur',
            'is_active' => true,
        ]);
    }
}
