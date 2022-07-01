<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedColumnBuyInTypePriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_types_prices', function (Blueprint $table) {
            $table->boolean('is_buy')->default(0)->after('is_rounding_with');
            $table->unsignedBigInteger('core_currency_id')->nullable(true)->after('is_rounding_with');
            $table->unsignedBigInteger('core_type_price_id')->nullable(true)->after('is_rounding_with');

            $table->foreign('core_currency_id')
                ->references('id')
                ->on('directory_currencies')
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
        Schema::table('directory_types_prices', function (Blueprint $table) {
            //
        });
    }
}
