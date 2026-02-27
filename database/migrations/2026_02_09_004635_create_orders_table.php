<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->decimal('total_price', 10, 2);

            $table->enum('status', ['en_attente', 'en_preparation', 'prete', 'annulee'])
                  ->default('en_attente');

            $table->timestamps();

            // Index — le dashboard filtre et trie en permanence sur ces colonnes
            $table->index('status');
            $table->index('created_at');
            $table->index(['user_id', 'status']);        // commandes d'un client par statut
            $table->index(['status', 'created_at']);     // dashboard admin trié + filtré
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
