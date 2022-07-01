<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoriesRegionsShemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_regions_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::create('directory_regions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('country_id')->nullable(true);
            $table->boolean('is_default')->default(0);
            $table->boolean('is_editable')->default(0);
            $table->boolean('archive')->default(0);

            $table->timestamps();
        });

        Schema::create('directory_regions_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('region_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('directory_regions_headers')
                ->onDelete('cascade');

            $table->foreign('region_id')
                ->references('id')
                ->on('directory_regions')
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
        Schema::dropIfExists('directory_regions_details');
        Schema::dropIfExists('directory_regions');
        Schema::dropIfExists('directory_regions_headers');
    }
}
