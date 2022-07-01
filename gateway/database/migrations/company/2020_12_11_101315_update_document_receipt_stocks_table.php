<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDocumentReceiptStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_receipt_stocks', function (Blueprint $table) {

            $table->date('date')->after('comments')->nullable();
            $table->unsignedBigInteger('organization_id')->after('comments')->nullable();
            $table->foreign('organization_id')
                ->references('id')
                ->on('directory_organizations')
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
        Schema::table('document_receipt_stocks', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('organization_id');
            $table->dropForeign('organization_id');
        });
    }
}
