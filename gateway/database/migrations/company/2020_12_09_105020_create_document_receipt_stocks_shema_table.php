<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentReceiptStocksShemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->boolean('archive')->default(0);
        });

        Schema::create('document_receipt_stocks_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);
            $table->string('value')->nullable(true);
        });

        Schema::create('document_receipt_stocks', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('document_id')->nullable(true);
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('is_editable')->default(0);
            $table->unsignedBigInteger('warehouse_id')->nullable(true);
            $table->unsignedBigInteger('currency_id')->nullable(true);
            $table->unsignedBigInteger('employee_id')->nullable(true);
            $table->text('comments')->nullable(true);
            $table->boolean('archive')->default(0);

            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->onDelete('cascade');

            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onDelete('cascade');

            $table->foreign('currency_id')
                ->references('id')
                ->on('directory_currencies')
                ->onDelete('cascade');

            $table->foreign('employee_id')
                ->references('id')
                ->on('directory_employees')
                ->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('document_receipt_stocks_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('receipt_stock_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('document_receipt_stocks_headers')
                ->onDelete('cascade');

            $table->foreign('receipt_stock_id')
                ->references('id')
                ->on('document_receipt_stocks')
                ->onDelete('cascade');
        });

        Schema::create('document_warehouse', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('warehouse_id');

            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->onDelete('cascade');

            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
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
        Schema::dropIfExists('documents');
        Schema::dropIfExists('document_receipt_stocks');
        Schema::dropIfExists('document_receipt_stocks_details');
        Schema::dropIfExists('document_receipt_stocks_headers');

    }
}
