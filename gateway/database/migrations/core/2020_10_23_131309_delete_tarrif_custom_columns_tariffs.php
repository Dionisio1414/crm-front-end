<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteTarrifCustomColumnsTariffs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_columns_tariffs', function (Blueprint $table) {
            $table->dropForeign(['tariff_id']);
            $table->dropColumn('tariff_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_columns_tariffs', function (Blueprint $table) {
            $table->unsignedBigInteger('tariff_id');

            $table->foreign('tariff_id')
                ->references('id')
                ->on('tariffs')
                ->onDelete('cascade');
        });
    }
}
