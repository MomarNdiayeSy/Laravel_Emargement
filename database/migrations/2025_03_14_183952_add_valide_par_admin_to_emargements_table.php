<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValideParAdminToEmargementsTable extends Migration
{
    public function up()
    {
        Schema::table('emargements', function (Blueprint $table) {
            $table->boolean('valide_par_admin')->default(false)->after('statut');
        });
    }

    public function down()
    {
        Schema::table('emargements', function (Blueprint $table) {
            $table->dropColumn('valide_par_admin');
        });
    }
}
