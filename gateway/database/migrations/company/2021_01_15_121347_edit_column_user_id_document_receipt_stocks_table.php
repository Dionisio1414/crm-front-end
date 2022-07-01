<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditColumnUserIdDocumentReceiptStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_receipt_stocks', function (Blueprint $table) {
            //$table->dropForeign('employee_id');
           // $table->dropColumn('employee_id');

            //$table->renameColumn('employee_id', 'user_id');
            $table->unsignedBigInteger('user_id')->after('currency_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('directory_users')
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
