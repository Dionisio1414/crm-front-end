<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedColumnsDirectoryCounterpartiesContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_counterparties_contracts', function (Blueprint $table) {

            $table->renameColumn('is_default', 'is_automatically');

            $table->string('title')->nullable(true);
            $table->bigInteger('registration_number')->nullable(true);
            $table->dateTime('contract_date')->nullable(true);
            $table->dateTime('from_period_date')->nullable(true);
            $table->dateTime('to_period_date')->nullable(true);
            $table->unsignedBigInteger('counterparty_id')->nullable(true);
            $table->unsignedBigInteger('type_contract_id')->nullable(true);
            $table->unsignedBigInteger('organization_id')->nullable(true);
            $table->unsignedBigInteger('currency_id')->nullable(true);
            $table->unsignedBigInteger('price_type_id')->nullable(true);
            $table->bigInteger('due_date')->nullable(true);

            $table->boolean('is_contract_signed')->default(0);
            $table->unsignedBigInteger('status_contract_id')->nullable(true);
            $table->text('description')->nullable(true);

            $table->foreign('counterparty_id')
                ->references('id')
                ->on('directory_counterparties')
                ->onDelete('cascade');

            $table->foreign('organization_id')
                ->references('id')
                ->on('directory_organizations')
                ->onDelete('cascade');

            $table->foreign('currency_id')
                ->references('id')
                ->on('directory_currencies')
                ->onDelete('cascade');

            $table->foreign('price_type_id')
                ->references('id')
                ->on('directory_types_prices')
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
