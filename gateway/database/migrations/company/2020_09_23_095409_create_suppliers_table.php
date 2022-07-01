<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('title_supplier')->unique()->nullable();
            $table->string('title_company')->unique()->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->boolean('supplier_status');
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->string('legal_address_street');
            $table->string('legal_address_number_housing', 50);
            $table->json('phone');
            $table->json('email');
            $table->boolean('partner_type');
            $table->integer('partner_inn');
            $table->integer('partner_edrpou');
            $table->timestamps();

            $table->foreign('currency_id')
                ->references('id')
                ->on('directory_currencies')
                ->onDelete('set null');

            $table->foreign('group_id')
                ->references('id')
                ->on('supplier_groups')
                ->onDelete('set null');

            $table->foreign('manager_id')
                ->references('id')
                ->on('supplier_managers')
                ->onDelete('set null');

            //Need Created BD microservic with countries and regions https://github.com/x88/i18nGeoNamesDB
            $table->unsignedBigInteger('legal_address_country_id')->nullable();
            $table->unsignedBigInteger('legal_address_region_id')->nullable();
            $table->unsignedBigInteger('partner_country_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
