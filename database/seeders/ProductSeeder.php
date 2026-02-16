<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        

        $fastFood = Category::where('name', 'Fast Food')->first();
        $plats = Category::where('name', 'Plats traditionnels')->first();
        $boissons = Category::where('name', 'Boissons')->first();
        $desserts = Category::where('name', 'Desserts')->first();

        // FAST FOOD
        Product::create([
            'category_id' => $fastFood->id,
            'name' => 'Burger O’G Spécial',
            'description' => 'Burger maison avec steak, fromage et sauce spéciale',
            'price' => 3500,
            'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $fastFood->id,
            'name' => 'Poulet Frit',
            'description' => 'Poulet croustillant servi avec frites',
            'price' => 4000,
            'image' => null,
            'is_active' => true,
        ]);

        // PLATS
        Product::create([
            'category_id' => $plats->id,
            'name' => 'Riz au Poulet',
            'description' => 'Riz parfumé accompagné de poulet braisé',
            'price' => 3000,
            'image' => null,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $plats->id,
            'name' => 'Thiéboudienne',
            'description' => 'Plat traditionnel sénégalais au poisson',
            'price' => 3500,
            'image' => null,
            'is_active' => true,
        ]);

        // BOISSONS
        Product::create([
            'category_id' => $boissons->id,
            'name' => 'Jus de Bissap',
            'description' => 'Boisson naturelle rafraîchissante',
            'price' => 1000,
            'image' => null,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $boissons->id,
            'name' => 'Soda',
            'description' => 'Boisson gazeuse fraîche',
            'price' => 800,
            'image' => null,
            'is_active' => true,
        ]);

        // DESSERTS
        Product::create([
            'category_id' => $desserts->id,
            'name' => 'Gâteau au chocolat',
            'description' => 'Dessert fondant au chocolat',
            'price' => 2000,
            'image' => null,
            'is_active' => true,
        ]);
    }
}
