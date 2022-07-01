<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryCurrenciesShemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_currencies_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::table('directory_currencies', function (Blueprint $table) {
            $table->dropColumn('title');

            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('is_editable')->default(0);
            $table->boolean('is_manual_currency')->default(1);
            $table->boolean('is_download_currency')->default(0);
            $table->boolean('is_other_currency')->default(0);

            $table->boolean('archive')->default(0);
        });

        Schema::create('directory_currencies_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('currency_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('directory_currencies_headers')
                ->onDelete('cascade');

            $table->foreign('currency_id')
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
        Schema::dropIfExists('directory_currencies');
        Schema::dropIfExists('directory_currencies_details');
        Schema::dropIfExists('directory_currencies_headers');
    }
}
