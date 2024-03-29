<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoriesShema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directories_headers', function (Blueprint $table) {
            $table->id();

            $table->json('title')->nullable(true);
            $table->string('value')->nullable(true);

            $table->timestamps();
        });

        Schema::create('directories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('directory_id');

            $table->json('title')->nullable(true);
            $table->unsignedBigInteger('header_id')->nullable(true);

            $table->foreign('header_id')
                ->references('id')
                ->on('directories')
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
        Schema::dropIfExists('directories');
        Schema::dropIfExists('directories_headers');
    }
}
