<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsNullableSuppliersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->unsignedBigInteger('partner_type_id')->nullable(true)->change();
            $table->dropColumn('supplier_status');

            $table->string('legal_address_street')->nullable(true)->change();
            $table->string('legal_address_number_housing')->nullable(true)->change();
            $table->integer('partner_inn')->nullable(true)->change();

            $table->unsignedBigInteger('manager_id')->nullable(true)->after('partner_type_id');

            $table->foreign('manager_id')
                ->references('id')
                ->on('directory_employees')
                ->onDelete('cascade');
        });

        Schema::table('supplier_delivery_addresses', function (Blueprint $table) {
            $table->string('street')->nullable(true)->change();
            $table->string('number_housing')->nullable(true)->change();
        });

        Schema::table('supplier_individuals_addresses', function (Blueprint $table) {
            $table->string('street')->nullable(true)->change();
            $table->string('number_housing')->nullable(true)->change();
        });

        Schema::table('supplier_contacts', function (Blueprint $table) {

            $table->unsignedBigInteger('supplier_id')->after('position_id');

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
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
