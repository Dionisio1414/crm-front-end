<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('title_customer')->unique()->nullable();
            $table->string('title_company')->unique()->nullable();

            $table->string('title_customer_middle_name')->nullable(true);
            $table->string('title_customer_last_name')->nullable(true);
            $table->string('title_customer_first_name')->nullable(true);

            $table->unsignedBigInteger('partner_type_id')->nullable(true);
            $table->unsignedBigInteger('manager_id')->nullable(true);
//            $table->unsignedBigInteger('group_id')->nullable(true);

            $table->boolean('is_delivery_equal_actual_address')->default(0);
            $table->boolean('is_legal_equal_actual_address')->default(0);
            $table->dateTime('date_of_birth')->nullable(true);

            $table->unsignedBigInteger('sex_id')->nullable(true);

            $table->boolean('is_foreign_company')->default(0);
            $table->boolean('archive')->default(0);

            $table->unsignedBigInteger('currency_id')->nullable(true);

            $table->string('legal_address_street')->nullable(true);
            $table->string('legal_address_number_housing')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->string('email')->nullable(true);

            $table->integer('partner_inn')->nullable(true);
            $table->integer('partner_edrpou')->nullable(true);

            $table->timestamps();

            $table->foreign('currency_id')
                ->references('id')
                ->on('directory_currencies')
                ->onDelete('set null');

//            $table->foreign('group_id')
//                ->references('id')
//                ->on('customer_groups')
//                ->onDelete('set null');

            $table->foreign('manager_id')
                ->references('id')
                ->on('directory_employees')
                ->onDelete('cascade');


            //Need Created BD microservic with countries and regions https://github.com/x88/i18nGeoNamesDB
            $table->unsignedBigInteger('legal_address_country_id')->nullable();
            $table->unsignedBigInteger('legal_address_region_id')->nullable();
            $table->unsignedBigInteger('legal_city_id')->nullable(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
