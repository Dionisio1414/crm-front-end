<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCharacteristicBaseValueProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

//            $table->unsignedBigInteger('characteristic_value_id')->after('supplier_id')->nullable();
//            $table->unsignedBigInteger('characteristic_color_value_id')->after('supplier_id')->nullable();
              $table->tinyInteger('is_groups')->after('supplier_id')->default(1);

//            $table->foreign('characteristic_value_id')
//                ->references('id')
//                ->on('characteristic_value')
//                ->onDelete('cascade');
//
//            $table->foreign('characteristic_color_value_id')
//                ->references('id')
//                ->on('characteristic_color_value')
//                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_groups');
//            $table->dropColumn('characteristic_value_id');
//            $table->dropColumn('characteristic_color_value_id');
//
//            $table->dropForeign('characteristic_value_id');
//            $table->dropForeign('characteristic_color_value_id');
        });
    }
}
