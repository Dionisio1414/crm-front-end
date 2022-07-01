<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileNomenclatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_nomenclature', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_id')->nullable();
            $table->unsignedBigInteger('nomenclature_id')->nullable();

            $table->foreign('file_id')
                ->references('id')
                ->on('files')
                ->onDelete('cascade');

            $table->foreign('nomenclature_id')
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
        Schema::dropIfExists('file_nomenclature');
    }
}
