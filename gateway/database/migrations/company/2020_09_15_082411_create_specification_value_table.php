<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecificationValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specification_value', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('specification_id')->nullable();
            $table->string('title')->nullable();
            $table->string('image');
            $table->integer('order')->default(0);

            $table->foreign('specification_id')
                ->references('id')
                ->on('specifications')
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
        Schema::dropIfExists('specification_value');
    }
}
