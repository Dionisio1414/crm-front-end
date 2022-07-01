<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNomenclatureCharacteristicColorValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nomenclature_characteristic_color_value', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomenclature_id')->nullable();
            $table->unsignedBigInteger('characteristic_color_value_id')->nullable();

            $table->foreign('nomenclature_id')
                ->references('id')
                ->on('nomenclatures')
                ->onDelete('cascade');

            $table->foreign('characteristic_color_value_id', 'ccv_id_foreign')
                ->references('id')
                ->on('characteristic_color_value')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nomenclature_characteristic_color_value');
    }
}
