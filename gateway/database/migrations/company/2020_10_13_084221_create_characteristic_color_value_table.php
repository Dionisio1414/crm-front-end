<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacteristicColorValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characteristic_color_value', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('characteristic_id')->nullable()->default(1);;
            $table->string('title');
            $table->integer('order')->default(0);
            $table->string('code')->nullable();
            $table->string('selectValue')->nullable();

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
        Schema::dropIfExists('characteristic_color_value');
    }
}
