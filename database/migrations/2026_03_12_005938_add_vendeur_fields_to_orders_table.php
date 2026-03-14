<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Vendeur qui a créé la vente POS (null = commande en ligne client)
            $table->foreignId('vendeur_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();

            // Nom du client walk-in (pas forcément inscrit sur le site)
            $table->string('customer_name')->nullable()->after('vendeur_id');

            // 'online' | 'pos'
            $table->string('source')->default('online')->after('customer_name');

            // Note vendeur sur la commande
            $table->text('note')->nullable()->after('source');

            $table->index('vendeur_id');
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['vendeur_id']);
            $table->dropColumn(['vendeur_id', 'customer_name', 'source', 'note']);
        });
    }
};
