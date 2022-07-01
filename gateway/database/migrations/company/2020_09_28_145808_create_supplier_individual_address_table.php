<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierIndividualAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_individual_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('individual_address_id')->nullable();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('set null');

            $table->foreign('individual_address_id')
                ->references('id')
                ->on('supplier_individuals_addresses')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_individual_address');
    }
}
