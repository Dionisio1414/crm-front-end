<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnGroupIdNomenclatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nomenclatures', function (Blueprint $table) {

//            $table->bigInteger('min_price')->after('lower_limit')->nullable();

            $table->unsignedBigInteger('group_id')->after('category_id')->nullable();
//            $table->unsignedBigInteger('weight_id')->after('supplier_id')->nullable();
//
            $table->foreign('group_id')
                ->references('id')
                ->on('nomenclatures')
                ->onDelete('cascade');
//
//            $table->foreign('weight_id')
//                ->references('id')
//                ->on('directory_units')
//                ->onDelete('cascade');
            //volume, weight
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nomenclatures', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->dropForeign('group_id');
        });
    }
}
