<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacteristicNomenclatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characteristic_nomenclature', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('characteristic_id')->nullable();
            $table->unsignedBigInteger('nomenclature_id')->nullable();
            $table->tinyInteger('is_base')->default(0);


            $table->foreign('nomenclature_id')
                ->references('id')
                ->on('nomenclatures')
                ->onDelete('cascade');

            $table->foreign('characteristic_id')
                ->references('id')
                ->on('characteristics')
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
        Schema::dropIfExists('characteristic_nomenclature');
    }
}
