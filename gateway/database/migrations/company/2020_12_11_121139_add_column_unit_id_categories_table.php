<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUnitIdCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {

            $table->unsignedBigInteger('unit_id')->after('identifier_successful')->nullable();

            $table->foreign('unit_id')
                ->references('id')
                ->on('directory_units')
                ->onDelete('cascade');
            //volume, weight
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('unit_id');
            $table->dropForeign('unit_id');
        });
    }
}
