<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryIndividualsDocumentsShemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_individuals_documents_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::create('directory_individuals_documents', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_personal_identity')->default(0);
            $table->dateTime('date_create_document')->nullable(true);
            $table->boolean('archive')->default(0);

            $table->timestamps();
        });

        Schema::create('directory_individuals_documents_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('directory_individual_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('directory_individuals_documents_headers')
                ->onDelete('cascade');

            $table->foreign('directory_individual_id', 'di_id')
                ->references('id')
                ->on('directory_individuals_documents')
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
        Schema::dropIfExists('directory_individuals_documents_details');
        Schema::dropIfExists('directory_individuals_documents');
        Schema::dropIfExists('directory_individuals_documents_headers');
    }
}
