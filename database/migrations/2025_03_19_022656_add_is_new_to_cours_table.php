<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsNewToCoursTable extends Migration
{
    public function up()
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->boolean('is_new')->default(true); // Par défaut, un cours est "nouveau"
        });
    }

    public function down()
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->dropColumn('is_new');
        });
    }
}
