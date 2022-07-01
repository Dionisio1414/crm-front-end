<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedColumnTitlePriceListTypePricesDirectory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_types_prices', function (Blueprint $table) {
            $table->json('title_price_list')->nullable(true)->after('archive');
            $table->json('core_title')->nullable(true)->after('id');
            $table->string('margin_percent')->nullable(true)->after('archive');
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
