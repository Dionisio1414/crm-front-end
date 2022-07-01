<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDirectoryCounterpartiesAddedSupplierCustomersOtherIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_counterparties', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable(true)->after('archive');
            $table->unsignedBigInteger('customer_id')->nullable(true)->after('archive');
            $table->unsignedBigInteger('other_id')->nullable(true)->after('archive');
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
