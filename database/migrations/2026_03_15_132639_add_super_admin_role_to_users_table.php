<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter 'super_admin' à la colonne role (string, pas enum — déjà flexible)
        // La colonne role est un VARCHAR dans ce projet — aucune modification de schéma nécessaire
        // Il suffit de mettre à jour le premier admin en super_admin

        // Passer le premier admin créé en super_admin
        DB::statement("
            UPDATE users
            SET role = 'super_admin'
            WHERE role = 'admin'
            ORDER BY id ASC
            LIMIT 1
        ");
    }

    public function down(): void
    {
        DB::statement("
            UPDATE users
            SET role = 'admin'
            WHERE role = 'super_admin'
        ");
    }
};
