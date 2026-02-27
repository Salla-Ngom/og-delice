<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained()
                  ->cascadeOnDelete();   // si la commande est supprimée, ses lignes aussi ✅

            // ✅ restrictOnDelete — JAMAIS cascadeOnDelete sur product_id
            // Supprimer un produit qui a des commandes doit être INTERDIT
            // sinon l'historique des commandes est détruit silencieusement
            $table->foreignId('product_id')
                  ->constrained()
                  ->restrictOnDelete();

            $table->unsignedSmallInteger('quantity');

            // ✅ Snapshot du prix au moment de la commande
            // Modifier le prix d'un produit n'affecte JAMAIS les commandes passées
            $table->decimal('unit_price', 10, 2)
                  ->comment('Prix snapshot au moment de la commande');

            $table->decimal('unit_price_promo', 10, 2)
                  ->nullable()
                  ->comment('Prix promo snapshot — null si pas de promo');

            $table->timestamps();

            // Index pour les jointures fréquentes
            $table->index('order_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
