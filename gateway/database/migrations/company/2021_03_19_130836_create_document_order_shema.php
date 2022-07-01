<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentOrderShema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_orders_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::create('document_orders', function (Blueprint $table) {
            $table->id();

            $table->string('organization_convert_id')->nullable(true);

            $table->unsignedBigInteger('organization_id')->nullable(true);

            $table->unsignedBigInteger('document_id')->nullable(true);
            $table->unsignedBigInteger('warehouse_id')->nullable(true);

            $table->unsignedBigInteger('currency_id')->nullable(true);
            $table->unsignedBigInteger('status_product_id')->nullable(true);
            $table->unsignedBigInteger('status_payment_id')->nullable(true);
            //
            $table->dateTime('payment_date_scheduled')->nullable(true);
            $table->dateTime('shipment_date_scheduled')->nullable(true);
            $table->dateTime('shipment_date_actual')->nullable(true);

            $table->unsignedBigInteger('terms_payment_id')->nullable(true);
            $table->unsignedBigInteger('form_payment_id')->nullable(true);

            $table->unsignedBigInteger('directory_cancell_id')->nullable(true);
            $table->unsignedBigInteger('directory_return_id')->nullable(true);
            $table->boolean('is_reserve')->default(true);
            $table->boolean('is_discount')->default(true);

//            $table->dateTime('supplier_document_date')->nullable(true);
//            $table->string('supplier_document_number')->nullable(true);

            $table->text('comments')->nullable(true);
            $table->unsignedBigInteger('responsible_id')->nullable(true);
            $table->unsignedBigInteger('employee_id')->nullable(true);

            $table->timestamps();

            $table->foreign('organization_id')
                ->references('id')
                ->on('directory_organizations')
                ->onDelete('cascade');

            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->onDelete('cascade');

            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onDelete('cascade');

//            $table->foreign('supplier_id')
//                ->references('id')
//                ->on('suppliers')
//                ->onDelete('cascade');

//            $table->foreign('contract_id')
//                ->references('id')
//                ->on('directory_counterparties_contracts')
//                ->onDelete('cascade');

            $table->foreign('currency_id')
                ->references('id')
                ->on('directory_currencies')
                ->onDelete('cascade');

            $table->foreign('directory_cancell_id')
                ->references('id')
                ->on('directory_counterparties_cancellations')
                ->onDelete('cascade');

            $table->foreign('directory_return_id')
                ->references('id')
                ->on('directory_counterparties_returns')
                ->onDelete('cascade');

            $table->foreign('responsible_id')
                ->references('id')
                ->on('directory_users')
                ->onDelete('cascade');

            $table->foreign('employee_id')
                ->references('id')
                ->on('directory_employees')
                ->onDelete('cascade');
        });

        Schema::create('document_orders_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('order_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('document_orders_headers')
                ->onDelete('cascade');

            $table->foreign('order_id')
                ->references('id')
                ->on('document_orders')
                ->onDelete('cascade');
        });

        Schema::create('document_orders_deliveries', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('document_order_id');


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

            $table->foreign('document_order_id')
                ->references('id')
                ->on('document_orders')
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
        Schema::dropIfExists('document_orders_headers');
        Schema::dropIfExists('document_orders');
        Schema::dropIfExists('document_orders_details');
        Schema::dropIfExists('document_orders_deliveries');

    }
}
