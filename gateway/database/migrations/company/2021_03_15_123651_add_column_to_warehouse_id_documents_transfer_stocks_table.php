<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToWarehouseIdDocumentsTransferStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_transfer_stocks', function (Blueprint $table) {
                $table->unsignedBigInteger('to_warehouse_id')->nullable(true)->after('currency_id');
                $table->foreign('to_warehouse_id')
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
        Schema::table('warehouse_id_documents_transfer_stocks', function (Blueprint $table) {
            //
        });
    }
}
