<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDirectoriesTypePricesColumnsTypePriceMarginPercentAndOther extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_types_prices', function (Blueprint $table) {
            $table->unsignedBigInteger('core_type_price_calculate_margin_percent_id')->nullable(true)->after('archive');
            $table->boolean('is_step_with')->nullable(true)->after('archive');
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
