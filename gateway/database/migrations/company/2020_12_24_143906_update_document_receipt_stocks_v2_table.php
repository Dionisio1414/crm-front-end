<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDocumentReceiptStocksV2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_receipt_stocks', function (Blueprint $table) {

            $table->dropColumn('date');
            $table->dateTime('receipt_date_scheduled')->nullable(true)->after('organization_id');
            $table->dateTime('receipt_date_actual')->nullable(true)->after('organization_id');
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
