<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDirectoryPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_positions', function (Blueprint $table) {
            $table->json('title')->change();
            $table->unsignedBigInteger('default')->default(0)->after('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directory_positions', function (Blueprint $table) {
            $table->string('title')->change();
            $table->dropColumn('default');
        });
    }
}
