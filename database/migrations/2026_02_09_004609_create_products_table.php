<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menu.php est supprimé — ses champs sont intégrés ici directement.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('name', 150);
            $table->string('slug')->unique(); // généré automatiquement par boot()

            $table->text('description')->nullable();

            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();

            $table->unsignedInteger('stock')->default(0);

            $table->string('image')->nullable(); // chemin relatif — URL via accessor

            // Champs issus de Menu (fusion)
            $table->string('service_type', 50)->nullable()
                  ->comment('restauration | traiteur | fast_food');
            $table->unsignedSmallInteger('preparation_time')->nullable()
                  ->comment('En minutes');
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_traiteur')->default(false);

            $table->timestamps();

            // Index — couvre les requêtes les plus fréquentes
            $table->index('is_active');
            $table->index('service_type');
            $table->index('sort_order');
            $table->index(['is_active', 'stock']);           // scopeAvailable()
            $table->index(['is_active', 'is_featured']);     // page d'accueil
            $table->index(['is_active', 'is_popular']);      // section populaire
            $table->index(['category_id', 'is_active']);     // filtre par catégorie
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
