<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMinPriceProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            $table->bigInteger('min_price')->after('lower_limit')->nullable();

//            $table->unsignedBigInteger('volume_id')->after('supplier_id')->nullable();
//            $table->unsignedBigInteger('weight_id')->after('supplier_id')->nullable();
//
//            $table->foreign('volume_id')
//                ->references('id')
//                ->on('directory_units')
//                ->onDelete('cascade');
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
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('min_price');
        });
    }
}
