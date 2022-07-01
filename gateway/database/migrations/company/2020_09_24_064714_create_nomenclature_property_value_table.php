<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNomenclaturePropertyValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nomenclature_property_value', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomenclature_id')->nullable();
            $table->unsignedBigInteger('property_value_id')->nullable();

            $table->foreign('nomenclature_id')
                ->references('id')
                ->on('nomenclatures')
                ->onDelete('cascade');

            $table->foreign('property_value_id')
                ->references('id')
                ->on('property_value')
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
        Schema::dropIfExists('nomenclature_property_value');
    }
}
