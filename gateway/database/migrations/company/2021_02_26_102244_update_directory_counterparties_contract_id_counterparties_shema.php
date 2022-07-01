<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDirectoryCounterpartiesContractIdCounterpartiesShema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_counterparties_contracts', function (Blueprint $table) {
            $table->dropForeign(['counterparty_id']);
            $table->dropColumn('counterparty_id');
            $table->unsignedBigInteger('supplier_id')->nullable(true)->after('to_period_date');
            $table->unsignedBigInteger('customer_id')->nullable(true)->after('to_period_date');
            $table->unsignedBigInteger('other_id')->nullable(true)->after('to_period_date');
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
