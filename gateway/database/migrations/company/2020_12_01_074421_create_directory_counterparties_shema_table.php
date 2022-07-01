<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryCounterpartiesShemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_counterparties_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::create('directory_counterparties', function (Blueprint $table) {
            $table->id();
            $table->boolean('archive')->default(0);

            $table->timestamps();
        });

        Schema::create('directory_counterparties_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('counterparty_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('directory_counterparties_headers')
                ->onDelete('cascade');

            $table->foreign('counterparty_id')
                ->references('id')
                ->on('directory_counterparties')
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
        Schema::dropIfExists('directory_counterparties_details');
        Schema::dropIfExists('directory_counterparties');
        Schema::dropIfExists('directory_counterparties_headers');
    }
}
