<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnCharacteristicColorValueImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('characteristic_color_value', function (Blueprint $table) {
            $table->renameColumn('selectValue', 'color');
            $table->string('image')->after('code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::table('characteristic_color_value', function (Blueprint $table) {
            $table->renameColumn('color', 'selectValue');
        });
    }
}
