<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedPercentDirecoryCounterpartiesContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_counterparties_contracts', function (Blueprint $table) {
            $table->string('percent')->nullable(true)->after('price_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directory_counterparties_contracts', function (Blueprint $table) {
            $table->dropColumn('percent');
        });
    }
}
