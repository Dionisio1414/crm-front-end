<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNomenclaturePriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nomenclature_price', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('nomenclature_id')->nullable();
            $table->unsignedBigInteger('price_id')->nullable();
            $table->bigInteger('value')->default(0);

            $table->foreign('nomenclature_id')
                ->references('id')
                ->on('nomenclatures')
                ->onDelete('cascade');

            $table->foreign('price_id')
                ->references('id')
                ->on('directory_prices')
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
        Schema::dropIfExists('nomenclature_price');
    }
}
