<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacteristicSizeValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characteristic_size_value', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('characteristic_id')->nullable()->default(2);;
            $table->string('title')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('check')->default(0);


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
        Schema::dropIfExists('characteristic_size_value');
    }
}
