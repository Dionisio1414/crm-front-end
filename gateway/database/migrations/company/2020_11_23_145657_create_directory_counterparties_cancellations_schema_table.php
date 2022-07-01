<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryCounterpartiesCancellationsSchemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_counterparties_cancellations_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::create('directory_counterparties_cancellations', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('is_editable')->default(0);
            $table->boolean('archive')->default(0);

            $table->timestamps();
        });

        Schema::create('directory_counterparties_cancellations_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('cancel_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('directory_counterparties_cancellations_headers')
                ->onDelete('cascade');

            $table->foreign('cancel_id')
                ->references('id')
                ->on('directory_counterparties_cancellations')
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
        Schema::dropIfExists('directory_counterparties_cancellations');
        Schema::dropIfExists('directory_counterparties_cancellations_details');
        Schema::dropIfExists('directory_counterparties_cancellations_headers');
    }
}
