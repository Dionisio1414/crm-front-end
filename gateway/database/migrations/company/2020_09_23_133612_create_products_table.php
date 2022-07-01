<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('desc');
            $table->string('sku')->unique()->nullable();
            $table->bigInteger('lower_limit');
            $table->unsignedBigInteger('nomenclature_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->timestamps();

            $table->foreign('nomenclature_id')
                ->references('id')
                ->on('nomenclatures')
                ->onDelete('set null');

            $table->foreign('unit_id')
                ->references('id')
                ->on('directory_units')
                ->onDelete('set null');

            $table->foreign('manufacturer_id')
                ->references('id')
                ->on('manufacturers')
                ->onDelete('set null');

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
