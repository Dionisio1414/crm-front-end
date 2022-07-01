<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomColumnsTariffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_columns_tariffs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tariff_id');
            $table->unsignedBigInteger('language_id');
            $table->string('title');
            $table->integer('value')->nullable(true);

            $table->foreign('tariff_id')
                ->references('id')
                ->on('tariffs')
                ->onDelete('cascade');

            $table->foreign('language_id')
                ->references('id')
                ->on('languages')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_columns_tariffs');
    }
}
