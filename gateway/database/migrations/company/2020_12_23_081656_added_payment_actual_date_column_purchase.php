<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedPaymentActualDateColumnPurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_purchases', function (Blueprint $table) {
            $table->dateTime('payment_date_actual')->nullable(true)->after('payment_date_scheduled');
            $table->string('responsible')->nullable(true)->after('payment_date_scheduled');
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
