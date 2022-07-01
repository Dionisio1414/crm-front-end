<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kits', function (Blueprint $table) {
            $table->id();
            $table->string('short_title')->nullable();
            $table->string('convert_id')->nullable();
            $table->string('dock_title')->nullable();
            $table->text('desc')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->bigInteger('lower_limit')->default(0);
            $table->bigInteger('min_price')->nullable();
            $table->bigInteger('weight')->nullable();
            $table->bigInteger('volume')->nullable();
            $table->bigInteger('packing')->nullable();
            $table->unsignedBigInteger('nomenclature_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('weight_id')->nullable();
            $table->timestamps();

            $table->foreign('nomenclature_id')
                ->references('id')
                ->on('nomenclatures')
                ->onDelete('cascade');

            $table->foreign('unit_id')
                ->references('id')
                ->on('directory_units')
                ->onDelete('cascade');

            $table->foreign('manufacturer_id')
                ->references('id')
                ->on('manufacturers')
                ->onDelete('cascade');

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('cascade');

            $table->foreign('country_id')
                ->references('id')
                ->on('directory_countries')
                ->onDelete('cascade');

            $table->foreign('weight_id')
                ->references('id')
                ->on('directory_units')
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
        Schema::dropIfExists('kits');
    }
}
