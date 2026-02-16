<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Vider la table d'abord
      

        $menus = [
            // ========== RESTAURATION TRADITIONNELLE ==========
            [
                'name' => 'Magret de Canard aux Figues',
                'slug' => 'magret-canard-figues',
                'description' => 'Magret de canard grillé, sauce aux figues fraîches, purée de pommes de terre.',
                'price' => 24.90,
                'category' => 'plats',
                'service_type' => 'restauration',
                'image_url' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d',
                'available' => true,
                'featured' => true,
                'is_popular' => false,
                'is_traiteur' => true,
                'preparation_time' => 25,
                'sort_order' => 1,
            ],
            [
                'name' => 'Assiette de Charcuterie Artisanale',
                'slug' => 'assiette-charcuterie',
                'description' => 'Sélection de charcuterie artisanale, cornichons, pain de campagne.',
                'price' => 16.50,
                'category' => 'entrees',
                'service_type' => 'restauration',
                'image_url' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1',
                'available' => true,
                'featured' => false,
                'is_popular' => true,
                'is_traiteur' => true,
                'preparation_time' => 10,
                'sort_order' => 2,
            ],

            // ========== SERVICE TRAITEUR ==========
            [
                'name' => 'Buffet Traiteur Événementiel',
                'slug' => 'buffet-traiteur',
                'description' => 'Buffet complet pour événements (mariages, séminaires) - sur devis.',
                'price' => 45.00,
                'category' => 'plats',
                'service_type' => 'traiteur',
                'image_url' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136',
                'available' => true,
                'featured' => true,
                'is_popular' => false,
                'is_traiteur' => true,
                'preparation_time' => null,
                'sort_order' => 1,
            ],
            [
                'name' => 'Plateau Repas d\'Affaires',
                'slug' => 'plateau-repas-affaires',
                'description' => 'Plateau repas équilibré pour vos réunions d\'affaires.',
                'price' => 18.90,
                'category' => 'plats',
                'service_type' => 'traiteur',
                'image_url' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38',
                'available' => true,
                'featured' => false,
                'is_popular' => true,
                'is_traiteur' => true,
                'preparation_time' => 15,
                'sort_order' => 2,
            ],
            [
                'name' => 'Cocktail Dinatoire (30 personnes)',
                'slug' => 'cocktail-dinatoire',
                'description' => 'Service cocktail dinatoire pour 30 personnes - sur devis.',
                'price' => 850.00,
                'category' => 'entrees',
                'service_type' => 'traiteur',
                'image_url' => 'https://images.unsplash.com/photo-1578474846511-04ba529f0b88',
                'available' => true,
                'featured' => true,
                'is_popular' => false,
                'is_traiteur' => true,
                'preparation_time' => null,
                'sort_order' => 3,
            ],

            // ========== FAST FOOD ==========
            [
                'name' => 'Burger O\'G Delice',
                'slug' => 'burger-og-delice',
                'description' => 'Burger maison avec steak haché, cheddar, bacon, salade, sauce spéciale.',
                'price' => 12.90,
                'category' => 'plats',
                'service_type' => 'fast_food',
                'image_url' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd',
                'available' => true,
                'featured' => true,
                'is_popular' => true,
                'is_traiteur' => false,
                'preparation_time' => 8,
                'sort_order' => 1,
            ],
            [
                'name' => 'Frites Maison',
                'slug' => 'frites-maison',
                'description' => 'Frites coupées maison, croustillantes à l\'extérieur, fondantes à l\'intérieur.',
                'price' => 4.50,
                'category' => 'snacks',
                'service_type' => 'fast_food',
                'image_url' => 'https://images.unsplash.com/photo-1576107232684-1279f7b4b724',
                'available' => true,
                'featured' => false,
                'is_popular' => true,
                'is_traiteur' => false,
                'preparation_time' => 5,
                'sort_order' => 2,
            ],
            [
                'name' => 'Wrap Poulet Caesar',
                'slug' => 'wrap-poulet-caesar',
                'description' => 'Wrap garni de poulet grillé, salade, parmesan et sauce caesar.',
                'price' => 9.90,
                'category' => 'snacks',
                'service_type' => 'fast_food',
                'image_url' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8',
                'available' => true,
                'featured' => true,
                'is_popular' => true,
                'is_traiteur' => false,
                'preparation_time' => 7,
                'sort_order' => 3,
            ],
            [
                'name' => 'Nuggets de Poulet (10 pièces)',
                'slug' => 'nuggets-poulet',
                'description' => 'Nuggets de poulet croustillants, accompagnés de sauce au choix.',
                'price' => 6.90,
                'category' => 'snacks',
                'service_type' => 'fast_food',
                'image_url' => 'https://images.unsplash.com/photo-1562967914-608f82629710',
                'available' => true,
                'featured' => false,
                'is_popular' => true,
                'is_traiteur' => false,
                'preparation_time' => 6,
                'sort_order' => 4,
            ],

            // ========== BOISSONS ==========
            [
                'name' => 'Coca-Cola / Fanta / Sprite',
                'slug' => 'soda-33cl',
                'description' => 'Canette de soda 33cl au choix.',
                'price' => 3.50,
                'category' => 'boissons',
                'service_type' => 'fast_food',
                'image_url' => 'https://images.unsplash.com/photo-1624517452488-04869289c4ca',
                'available' => true,
                'featured' => false,
                'is_popular' => true,
                'is_traiteur' => false,
                'preparation_time' => 1,
                'sort_order' => 1,
            ],
            [
                'name' => 'Vin Rouge Maison',
                'slug' => 'vin-rouge-maison',
                'description' => 'Verre de vin rouge de la maison.',
                'price' => 5.50,
                'category' => 'boissons',
                'service_type' => 'restauration',
                'image_url' => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3',
                'available' => true,
                'featured' => true,
                'is_popular' => false,
                'is_traiteur' => true,
                'preparation_time' => 2,
                'sort_order' => 2,
            ],
            [
                'name' => 'Café Expresso',
                'slug' => 'cafe-expresso',
                'description' => 'Café expresso italien.',
                'price' => 2.50,
                'category' => 'boissons',
                'service_type' => 'restauration',
                'image_url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085',
                'available' => true,
                'featured' => false,
                'is_popular' => true,
                'is_traiteur' => false,
                'preparation_time' => 3,
                'sort_order' => 3,
            ],

            // ========== DESSERTS ==========
            [
                'name' => 'Fondant au Chocolat',
                'slug' => 'fondant-chocolat',
                'description' => 'Fondant au chocolat noir cœur coulant, glace vanille.',
                'price' => 7.90,
                'category' => 'desserts',
                'service_type' => 'restauration',
                'image_url' => 'https://images.unsplash.com/photo-1624353365286-3f8d62dadadf',
                'available' => true,
                'featured' => true,
                'is_popular' => true,
                'is_traiteur' => true,
                'preparation_time' => 10,
                'sort_order' => 1,
            ],
            [
                'name' => 'Milkshake Fraise/Vanille/Chocolat',
                'slug' => 'milkshake',
                'description' => 'Milkshake frappé au choix de parfum.',
                'price' => 5.90,
                'category' => 'desserts',
                'service_type' => 'fast_food',
                'image_url' => 'https://images.unsplash.com/photo-1577805947697-89e18249d767',
                'available' => true,
                'featured' => false,
                'is_popular' => true,
                'is_traiteur' => false,
                'preparation_time' => 5,
                'sort_order' => 2,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }

        $this->command->info(count($menus) . ' plats créés pour O\'G DELICE !');
        $this->command->info('Services: Restauration, Traiteur, Fast Food');
    }
}
