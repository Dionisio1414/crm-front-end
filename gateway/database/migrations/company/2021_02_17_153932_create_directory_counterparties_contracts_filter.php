<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryCounterpartiesContractsFilter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_counterparties_contracts_filter', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('selects_type_contract')->nullable(true);
            $table->dateTime('periods_from_period_date')->nullable(true);
            $table->dateTime('periods_to_period_date')->nullable(true);

            $table->json('lists_organization_id')->nullable(true);
            $table->json('lists_price_type_id')->nullable(true);
            $table->json('lists_currency_id')->nullable(true);

            $table->boolean('booleans_is_contract_signed')->nullable(true);
            $table->boolean('booleans_status_contract_id')->nullable(true);

            $table->unsignedBigInteger('ranges_due_date_from')->nullable(true);
            $table->unsignedBigInteger('ranges_due_date_to')->nullable(true);

            $table->string('ranges_percent_from')->nullable(true);
            $table->string('ranges_percent_to')->nullable(true);

            $table->boolean('is_active_filter')->nullable(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('directory_counterparties_contracts_filter');
    }
}
