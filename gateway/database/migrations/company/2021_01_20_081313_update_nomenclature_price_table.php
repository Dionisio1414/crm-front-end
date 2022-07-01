<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNomenclaturePriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nomenclature_price', function (Blueprint $table) {
            $table->dateTime('date')->after('value')->nullable();
            $table->unsignedBigInteger('supplier_id')->after('value')->nullable();
            $table->unsignedBigInteger('unit_id')->after('value')->nullable();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
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
        //
    }
}
