<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierDeliveryAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_delivery_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('street');
            $table->string('number_housing', 50);
            $table->timestamps();
            //Need Created BD microservic with countries and regions https://github.com/x88/i18nGeoNamesDB
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_delivery_addresses');
    }
}
