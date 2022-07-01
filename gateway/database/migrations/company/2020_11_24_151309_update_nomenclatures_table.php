<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNomenclaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nomenclatures', function (Blueprint $table) {
            $table->tinyInteger('is_visible')->after('order')->default(1);
            $table->tinyInteger('is_actual')->after('order')->default(1);
            $table->tinyInteger('archive')->after('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nomenclatures', function (Blueprint $table) {
            $table->dropColumn('is_visible');
            $table->dropColumn('is_actual');
            $table->dropColumn('archive');
        });
    }
}
