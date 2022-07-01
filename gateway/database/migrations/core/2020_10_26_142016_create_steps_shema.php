<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepsShema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(true);
            $table->integer('step_number');
            $table->timestamps();
        });

        Schema::create('step_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('step_id');
            $table->json('title')->nullable(true);
            $table->string('value')->nullable(true);

            $table->foreign('step_id')
                ->references('id')
                ->on('steps')
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
        Schema::dropIfExists('step_details');
        Schema::dropIfExists('steps');
    }
}
