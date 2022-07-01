<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNomenclatureCharacteristicValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nomenclature_characteristic_color_value', function (Blueprint $table) {
            $table->tinyInteger('is_base')->after('characteristic_color_value_id')->default(0);
        });

        Schema::table('nomenclature_characteristic_value', function (Blueprint $table) {
            $table->tinyInteger('is_base')->after('characteristic_value_id')->default(0);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nomenclature_characteristic_color_value', function (Blueprint $table) {
            $table->dropColumn('is_base');
        });

        Schema::table('nomenclature_characteristic_value', function (Blueprint $table) {
            $table->dropColumn('is_base');
        });
    }
}
