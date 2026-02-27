<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catering_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->date('event_date');
            $table->unsignedSmallInteger('guests');
            $table->text('message')->nullable();

            $table->enum('status', ['en_attente', 'confirme', 'refuse'])
                  ->default('en_attente');

            $table->timestamps();

            // Index pour les filtres admin
            $table->index('status');
            $table->index('event_date');
            $table->index(['status', 'event_date']); // filtre combiné le plus fréquent
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catering_requests');
    }
};
