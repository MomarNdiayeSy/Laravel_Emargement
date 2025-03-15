<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateEmargementsStatutEnumToIncludePending extends Migration
{
    public function up(): void
    {
        // Supprimer la contrainte existante si elle existe
        DB::statement("ALTER TABLE emargements DROP CONSTRAINT IF EXISTS emargements_statut_check");

        // Changer le type de la colonne en VARCHAR temporairement pour éviter les problèmes d’enum
        DB::statement("ALTER TABLE emargements ALTER COLUMN statut TYPE VARCHAR(255) USING (statut::VARCHAR)");

        // Ajouter la nouvelle contrainte avec 'pending'
        DB::statement("ALTER TABLE emargements ADD CONSTRAINT emargements_statut_check CHECK (statut IN ('pending', 'présent', 'absent'))");
    }

    public function down(): void
    {
        // Supprimer la nouvelle contrainte
        DB::statement("ALTER TABLE emargements DROP CONSTRAINT IF EXISTS emargements_statut_check");

        // Restaurer l’ancienne contrainte
        DB::statement("ALTER TABLE emargements ADD CONSTRAINT emargements_statut_check CHECK (statut IN ('présent', 'absent'))");
    }
}
