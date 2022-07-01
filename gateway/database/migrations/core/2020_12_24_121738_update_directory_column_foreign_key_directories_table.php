<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDirectoryColumnForeignKeyDirectoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directories', function (Blueprint $table) {
            $table->dropForeign(['header_id']);

            $table->foreign('header_id')
                ->references('id')
                ->on('directories_headers')
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
        Schema::table('directories', function (Blueprint $table) {
            $table->dropForeign(['header_id']);

            $table->foreign('header_id')
                ->references('id')
                ->on('directories')
                ->onDelete('cascade');
        });
    }
}
