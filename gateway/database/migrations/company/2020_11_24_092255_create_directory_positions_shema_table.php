<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryPositionsShemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_positions_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::table('directory_positions', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('default');

            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('is_editable')->default(0);
            $table->boolean('archive')->default(0);

            $table->timestamps();
        });

        Schema::create('directory_positions_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('position_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('directory_positions_headers')
                ->onDelete('cascade');

            $table->foreign('position_id')
                ->references('id')
                ->on('directory_positions')
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
        Schema::dropIfExists('directory_positions');
        Schema::dropIfExists('directory_positions_details');
        Schema::dropIfExists('directory_positions_headers');
    }
}
