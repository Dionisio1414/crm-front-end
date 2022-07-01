<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnImageIdCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('image_id')->nullable(true)->after('order');
            $table->dropColumn('image');

            $table->foreign('image_id')
                ->references('id')
                ->on('file_manager')
                ->onDelete('cascade');
        });

        Schema::table('characteristic_color_value', function (Blueprint $table) {
            $table->unsignedBigInteger('image_id')->nullable(true)->after('color');
            $table->dropColumn('image');

            $table->foreign('image_id')
                ->references('id')
                ->on('file_manager')
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
        //
    }
}
