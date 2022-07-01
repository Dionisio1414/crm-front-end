<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedColumnsDirectoryIndividualsDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_individuals_documents', function (Blueprint $table) {

            $table->unsignedBigInteger('supplier_individual_id')->nullable(true);
            $table->unsignedBigInteger('counterparty_id')->nullable(true);
            $table->unsignedBigInteger('document_type_id')->nullable(true);
            $table->string('document_serial')->nullable(true);
            $table->bigInteger('document_number')->nullable(true);
            $table->dateTime('document_issued_date')->nullable(true);
            $table->dateTime('document_validity_date')->nullable(true);
            $table->string('document_issued')->nullable(true);

            $table->foreign('supplier_individual_id')
                ->references('id')
                ->on('suppliers')
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
        //
    }
}
