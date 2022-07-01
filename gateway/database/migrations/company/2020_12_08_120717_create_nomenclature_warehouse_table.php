<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNomenclatureWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nomenclature_warehouse', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomenclature_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->bigInteger('balance')->default(0);
            $table->bigInteger('reserve')->default(0);

            $table->foreign('nomenclature_id')
                ->references('id')
                ->on('nomenclatures')
                ->onDelete('cascade');


            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
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
        Schema::dropIfExists('nomenclature_warehouse');
    }
}
