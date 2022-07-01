<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelatedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('related_products');

        Schema::create('related_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomenclature_id')->nullable();
            $table->timestamps();

            $table->foreign('nomenclature_id')
                ->references('id')
                ->on('nomenclatures')
                ->onDelete('cascade');
        });

        Schema::create('nomenclature_related', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('nomenclature_id')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();

            $table->foreign('nomenclature_id')
                ->references('id')
                ->on('nomenclatures')
                ->onDelete('cascade');


            $table->foreign('related_id')
                ->references('id')
                ->on('related_products')
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
        Schema::dropIfExists('related_products');
    }
}
