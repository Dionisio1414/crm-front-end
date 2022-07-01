<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacteristicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characteristics', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique()->nullable();
            $table->integer('order')->default(2);
            $table->boolean('archive')->default(0);
            $table->timestamps();
        });

        DB::table('characteristics')->insert([
            ['id' => 1, 'title' => 'Цвет', 'order' => 0],
            ['id' => 2, 'title' => 'Размер', 'order' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('characteristics');
    }
}
