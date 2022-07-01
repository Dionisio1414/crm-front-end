<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentTransferStocksDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_transfer_stocks_deliveries', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('document_transfer_stock_id');
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
          //  $table->foreign('characteristic_color_value_id', 'ccv_id_foreign')
            $table->foreign('document_transfer_stock_id', 'dts_id_foreign')
                ->references('id')
                ->on('document_transfer_stocks')
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
        Schema::dropIfExists('document_transfer_stocks_deliveries');
    }
}
