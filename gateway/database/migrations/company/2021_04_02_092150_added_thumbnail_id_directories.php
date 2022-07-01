<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedThumbnailIdDirectories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_users', function (Blueprint $table) {
            $table->dropColumn('thumbnail');
            $table->unsignedBigInteger('thumbnail_id')->nullable(true)->after('id');
        });

        Schema::table('directory_employees', function (Blueprint $table) {
            $table->dropColumn('thumbnail');
            $table->unsignedBigInteger('thumbnail_id')->nullable(true)->after('id');
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
