<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryTypesPricesShemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_types_prices_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::create('directory_types_prices', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('is_editable')->default(0);
            $table->boolean('is_rounding_without')->default(1);
            $table->boolean('is_rounding_with')->default(0);

            $table->boolean('archive')->default(0);

            $table->timestamps();
        });

        Schema::create('directory_types_prices_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('type_price_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('directory_types_prices_headers')
                ->onDelete('cascade');

            $table->foreign('type_price_id')
                ->references('id')
                ->on('directory_types_prices')
                ->onDelete('cascade');
        });

        Schema::table('nomenclature_price', function (Blueprint $table) {
            $table->dropForeign(['price_id']);

            $table->foreign('price_id')
                ->references('id')
                ->on('directory_types_prices')
                ->onDelete('set null');
        });

        Schema::dropIfExists('directory_prices');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('directory_types_prices');
        Schema::dropIfExists('directory_types_prices_details');
        Schema::dropIfExists('directory_types_prices_headers');
    }
}
