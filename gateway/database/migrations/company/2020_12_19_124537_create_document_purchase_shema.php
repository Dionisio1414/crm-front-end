<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentPurchaseShema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_purchases_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::create('document_purchases', function (Blueprint $table) {
            $table->id();

            $table->string('organization_convert_id')->nullable(true);

            $table->unsignedBigInteger('organization_id')->nullable(true);

            $table->unsignedBigInteger('document_id')->nullable(true);
            $table->unsignedBigInteger('warehouse_id')->nullable(true);
            $table->unsignedBigInteger('supplier_id')->nullable(true);
            $table->unsignedBigInteger('contract_id')->nullable(true);
            $table->unsignedBigInteger('currency_id')->nullable(true);
            $table->unsignedBigInteger('status_product_id')->nullable(true);
            $table->unsignedBigInteger('status_payment_id')->nullable(true);

            $table->dateTime('receipt_date_scheduled')->nullable(true);
            $table->dateTime('payment_date_scheduled')->nullable(true);
            $table->dateTime('receipt_date_actual')->nullable(true);

            $table->unsignedBigInteger('terms_payment_id')->nullable(true);
            $table->unsignedBigInteger('form_payment_id')->nullable(true);

            $table->unsignedBigInteger('directory_cancell_id')->nullable(true);
            $table->unsignedBigInteger('directory_return_id')->nullable(true);

            $table->dateTime('supplier_document_date')->nullable(true);
            $table->string('supplier_document_number')->nullable(true);

            $table->text('description')->nullable(true);

            $table->dateTime('create_document_date')->nullable(true);

            $table->double('document_amount')->default(0);

            $table->boolean('archive')->default(0);

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

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('cascade');

            $table->foreign('contract_id')
                ->references('id')
                ->on('directory_counterparties_contracts')
                ->onDelete('cascade');

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
        });

        Schema::create('document_purchases_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('purchase_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('document_purchases_headers')
                ->onDelete('cascade');

            $table->foreign('purchase_id')
                ->references('id')
                ->on('document_purchases')
                ->onDelete('cascade');
        });

        Schema::create('document_purchases_deliveries', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('document_purchase_id');


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

            $table->foreign('document_purchase_id')
                ->references('id')
                ->on('document_purchases')
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
        Schema::dropIfExists('document_purchases_details');
        Schema::dropIfExists('document_purchases_deliveries');
        Schema::dropIfExists('document_purchases');
        Schema::dropIfExists('document_purchases_headers');
    }
}
