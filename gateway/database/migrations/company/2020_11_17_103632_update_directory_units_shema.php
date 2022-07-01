<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDirectoryUnitsShema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_units_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::table('directory_units', function (Blueprint $table) {
            $table->tinyInteger('is_default')->default(0)->after('title');
            $table->tinyInteger('is_editable')->default(0)->after('title');
            $table->dropColumn('title');
        });

        Schema::create('directory_units_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('unit_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('directory_units_headers')
                ->onDelete('cascade');

            $table->foreign('unit_id')
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
        Schema::table('directory_units', function (Blueprint $table) {
            $table->string('title')->nullable(true);
            $table->dropColumn('is_default');
            $table->dropColumn('is_editable');
        });

        Schema::dropIfExists('directory_units_details');
        Schema::dropIfExists('directory_units_headers');
    }
}
