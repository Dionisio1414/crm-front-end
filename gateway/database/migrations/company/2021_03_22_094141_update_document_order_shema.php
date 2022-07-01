<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDocumentOrderShema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('counterparty_id')->nullable(true)->after('currency_id');
            $table->unsignedBigInteger('price_id')->nullable(true)->after('currency_id');

            $table->foreign('counterparty_id')
                ->references('id')
                ->on('directory_counterparties')
                ->onDelete('cascade');
            $table->foreign('price_id')
                ->references('id')
                ->on('directory_types_prices')
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
