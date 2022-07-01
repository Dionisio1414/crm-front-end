<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSuppliersShema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('partner_type');
            $table->unsignedBigInteger('partner_type_id')->after('title_company');

            $table->boolean('archive')->default(0)->after('group_id');

            $table->string('title_supplier_first_name')->nullable(true)->after('title_company');
            $table->string('title_supplier_last_name')->nullable(true)->after('title_company');
            $table->string('title_supplier_middle_name')->nullable(true)->after('title_company');

            $table->boolean('is_foreign_company')->default(0)->after('group_id');

            $table->dropForeign(['manager_id']);
            $table->dropColumn('manager_id');

            $table->string('phone')->nullable(true)->change();
            $table->string('email')->nullable(true)->change();

            $table->string('partner_edrpou')->nullable(true)->change();

            $table->unsignedBigInteger('sex_id')->after('group_id')->nullable(true);

            $table->dateTime('date_of_birth')->after('group_id')->nullable(true);

            $table->unsignedBigInteger('legal_city_id')->nullable(true)->after('legal_address_region_id');

            $table->dropColumn('partner_country_id');

            $table->boolean('is_delivery_equal_actual_address')->after('group_id')->default(0);
            $table->boolean('is_legal_equal_actual_address')->after('group_id')->default(0);
        });

        Schema::dropIfExists('supplier_managers');

        Schema::table('supplier_delivery_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('city_id')->nullable(true)->after('region_id');
        });

        Schema::table('supplier_individuals_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('city_id')->nullable(true)->after('region_id');
        });

        Schema::table('supplier_contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('position_id')->nullable(true)->after('id');
            $table->unsignedBigInteger('sex_id')->nullable(true)->after('id');

            $table->unsignedBigInteger('document_id')->nullable(true)->after('id');

            $table->string('number_document')->nullable(true)->after('id');
            $table->unsignedBigInteger('source_attractions_id')->nullable(true)->after('id');

            $table->string('phone')->nullable(true)->after('id');
            $table->string('email')->nullable(true)->after('id');
            $table->string('date_of_birth')->nullable(true)->after('id');


            $table->string('first_name')->nullable(true)->after('id');
            $table->string('last_name')->nullable(true)->after('id');
            $table->string('middle_name')->nullable(true)->after('id');


            $table->foreign('position_id')
                ->references('id')
                ->on('directory_positions')
                ->onDelete('cascade');
        });

        Schema::create('suppliers_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::create('suppliers_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('supplier_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('suppliers_headers')
                ->onDelete('cascade');

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
