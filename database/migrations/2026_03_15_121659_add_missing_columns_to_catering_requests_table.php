<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catering_requests', function (Blueprint $table) {

            // ✅ Colonnes contact — manquantes dans la migration originale
            $table->string('name')->after('user_id');
            $table->string('email')->after('name');
            $table->string('phone', 20)->nullable()->after('email');

            // ✅ Type d'événement — manquant
            $table->string('event_type')->after('phone');

            // ✅ Budget — manquant
            $table->unsignedBigInteger('budget')->nullable()->after('guests');

            // ✅ Réponse admin — manquante
            $table->text('admin_response')->nullable()->after('message');
            $table->foreignId('responded_by')
                ->nullable()
                ->after('admin_response')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('responded_at')->nullable()->after('responded_by');

            // ✅ Mettre user_id nullable (visiteur sans compte)
            // + changer la contrainte cascadeOnDelete → nullOnDelete
            $table->dropForeign(['user_id']);
            $table->foreignId('user_id')->nullable()->change();

            // ✅ Nouveaux statuts alignés avec le modèle
            // enum original : ['en_attente','confirme','refuse']
            // nouvel enum   : ['nouvelle','en_cours','acceptee','refusee']
            $table->index('email');
        });

        // Modifier user_id pour être nullable avec nullOnDelete
        DB::statement('ALTER TABLE catering_requests MODIFY user_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE catering_requests ADD CONSTRAINT catering_requests_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');

        // Modifier l'enum status pour les nouveaux statuts
        DB::statement("
            ALTER TABLE catering_requests
            MODIFY COLUMN status ENUM('nouvelle','en_cours','acceptee','refusee')
            NOT NULL DEFAULT 'nouvelle'
        ");

        // Migrer les anciennes données vers les nouveaux statuts
        DB::statement("UPDATE catering_requests SET status = 'nouvelle'  WHERE status = 'en_attente'");
        DB::statement("UPDATE catering_requests SET status = 'acceptee'  WHERE status = 'confirme'");
        DB::statement("UPDATE catering_requests SET status = 'refusee'   WHERE status = 'refuse'");

        // Modifier event_date de date → datetime
        DB::statement('ALTER TABLE catering_requests MODIFY event_date DATETIME NOT NULL');

        // Modifier guests de smallint → int unsigned
        DB::statement('ALTER TABLE catering_requests MODIFY guests INT UNSIGNED NOT NULL');
    }

    public function down(): void
    {
        Schema::table('catering_requests', function (Blueprint $table) {
            $table->dropForeign(['responded_by']);
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'name', 'email', 'phone', 'event_type',
                'budget', 'admin_response', 'responded_by', 'responded_at'
            ]);
        });

        DB::statement('ALTER TABLE catering_requests MODIFY user_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE catering_requests ADD CONSTRAINT catering_requests_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');

        DB::statement("
            ALTER TABLE catering_requests
            MODIFY COLUMN status ENUM('en_attente','confirme','refuse')
            NOT NULL DEFAULT 'en_attente'
        ");

        DB::statement('ALTER TABLE catering_requests MODIFY event_date DATE NOT NULL');
    }
};
