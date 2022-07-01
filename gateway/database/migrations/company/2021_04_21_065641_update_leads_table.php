<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->renameColumn('data', 'date');
            $table->unsignedBigInteger('terms_payment_id')->nullable(true)->after('title');
            $table->unsignedBigInteger('form_payment_id')->nullable(true)->after('title');
            $table->string('title_first_name')->nullable(true)->after('title');
            $table->string('title_last_name')->nullable(true)->after('title');
            $table->string('title_middle_name')->nullable(true)->after('title');
            $table->date('date_of_birth')->nullable(true)->after('title');
            $table->unsignedBigInteger('partner_type_id')->nullable(true)->after('title');
            $table->unsignedBigInteger('sex_id')->nullable(true)->after('title');
            $table->integer('partner_inn')->nullable(true)->after('title');
            $table->integer('partner_edrpou')->nullable(true)->after('title');
            $table->unsignedBigInteger('price_id')->nullable(true)->after('title');
            $table->unsignedBigInteger('currency_id')->nullable(true)->after('title');
            $table->boolean('is_failure')->default(false)->after('title');
            $table->string('failure_text')->nullable(true)->after('title');
            $table->foreign('price_id')
                ->references('id')
                ->on('directory_types_prices')
                ->onDelete('cascade');
            $table->foreign('currency_id')
                ->references('id')
                ->on('directory_currencies')
                ->onDelete('cascade');
        });

        Schema::table('nomenclature_lead', function (Blueprint $table) {
            $table->boolean('is_packing')->default(false)->after('count');
            $table->renameColumn('amount', 'price');
            $table->renameColumn('count', 'balance');
            $table->renameColumn('offered_discount', 'percent');
        });

        Schema::create('leads_deliveries', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('lead_id');

            $table->unsignedBigInteger('delivery_methods_id')->nullable(true);
            $table->unsignedBigInteger('type_deliveries_id')->nullable(true);

            $table->unsignedBigInteger('department_city_id')->nullable(true);
            $table->unsignedBigInteger('department_id')->nullable(true);

            $table->string('ttn_number')->nullable(true);
            $table->dateTime('ttn_date')->nullable(true);

            $table->boolean('is_power_of_attorney')->nullable(true);

            $table->dateTime('time_power_of_attorney')->nullable(true);
            $table->string('number_power_of_attorney')->nullable(true);
            $table->unsignedBigInteger('confidant_id')->nullable(true);

            $table->timestamps();

            $table->foreign('lead_id')
                ->references('id')
                ->on('leads')
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
        Schema::table('leads', function (Blueprint $table) {
            $table->renameColumn('date', 'data');
        });
    }
}
