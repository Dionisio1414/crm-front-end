<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnsCounterpartiesContractsFilterEndings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_counterparties_contracts_filter', function (Blueprint $table) {
            $table->dropColumn('lists_organization_id');
            $table->dropColumn('lists_price_type_id');
            $table->dropColumn('lists_currency_id');
            $table->dropColumn('booleans_status_contract_id');
            $table->dropColumn('selects_type_contract');
            $table->dropColumn('periods_from_period_date');
            $table->dropColumn('periods_to_period_date');

            $table->json('lists_organizations_ids')->nullable(true)->after('periods_to_period_date');
            $table->json('lists_price_types_ids')->nullable(true)->after('periods_to_period_date');
            $table->json('lists_currencies_ids')->nullable(true)->after('periods_to_period_date');

            $table->unsignedBigInteger('selects_type_contracts_id')->nullable(true)->after('id');
            $table->boolean('booleans_is_status_contract')->nullable(true)->after('booleans_is_contract_signed');

            $table->dateTime('periods_period_date_from')->nullable(true)->after('id');
            $table->dateTime('periods_period_date_to')->nullable(true)->after('id');

            $table->unsignedBigInteger('user_id')->after('id');
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
