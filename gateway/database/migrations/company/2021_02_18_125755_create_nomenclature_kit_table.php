<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNomenclatureKitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nomenclature_kit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomenclature_id')->nullable();
            $table->unsignedBigInteger('kit_id')->nullable();
            $table->bigInteger('count')->nullable();


            $table->foreign('nomenclature_id')
                ->references('id')
                ->on('nomenclatures')
                ->onDelete('cascade');


            $table->foreign('kit_id')
                ->references('id')
                ->on('nomenclatures')
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
        Schema::dropIfExists('nomenclature_kit');
    }
}
