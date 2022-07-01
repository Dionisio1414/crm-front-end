<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryEmployeeDocumentsShemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_employee_documents_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::create('directory_employee_documents', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('is_editable')->default(0);
            $table->boolean('archive')->default(0);

            $table->timestamps();
        });

        Schema::create('directory_employee_documents_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('employee_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('directory_employee_documents_headers')
                ->onDelete('cascade');

            $table->foreign('employee_id')
                ->references('id')
                ->on('directory_employee_documents')
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
        Schema::dropIfExists('directory_employee_documents');
        Schema::dropIfExists('directory_employee_documents_details');
        Schema::dropIfExists('directory_employee_documents_headers');
    }
}
