<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL : modifier un ENUM nécessite de redéfinir toutes les valeurs
        DB::statement("
            ALTER TABLE orders
            MODIFY COLUMN status ENUM('en_attente','en_preparation','prete','livree','annulee')
            NOT NULL DEFAULT 'en_attente'
        ");
    }

    public function down(): void
    {
        // Repasse à l'ancien enum sans 'livree'
        // ⚠️ Les lignes avec status='livree' causeront une erreur — vider d'abord si nécessaire
        DB::statement("
            ALTER TABLE orders
            MODIFY COLUMN status ENUM('en_attente','en_preparation','prete','annulee')
            NOT NULL DEFAULT 'en_attente'
        ");
    }
};
