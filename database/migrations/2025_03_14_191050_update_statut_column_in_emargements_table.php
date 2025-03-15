<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatutColumnInEmargementsTable extends Migration
{
    public function up()
    {
        Schema::table('emargements', function (Blueprint $table) {
            $table->string('statut')->default('pending')->change(); // Ajoute "pending" comme valeur par défaut
        });
    }

    public function down()
    {
        Schema::table('emargements', function (Blueprint $table) {
            $table->string('statut')->default('présent')->change(); // Retour à l’ancienne valeur par défaut si rollback
        });
    }
}
