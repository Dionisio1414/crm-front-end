<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAndCreateColumnsSupplierContactsShema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_contacts', function (Blueprint $table) {
            $table->dropColumn('number_document');
            $table->dropColumn('document_id');

            $table->boolean('is_default')->default(0)->after('id');
        });

        Schema::create('supplier_contacts_documents', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('supplier_contact_id');

            $table->boolean('is_personal_identity')->default(0);

            $table->unsignedBigInteger('document_type_id')->nullable(true);
            $table->string('document_number')->nullable(true);
            $table->dateTime('document_issued_date')->nullable(true);
            $table->dateTime('document_validity_date')->nullable(true);
            $table->string('document_issued')->nullable(true);
            $table->dateTime('date_create_document')->nullable(true);

            $table->boolean('archive')->default(0);
            $table->timestamps();

            $table->foreign('supplier_contact_id')
                ->references('id')
                ->on('supplier_contacts')
                ->onDelete('cascade');
        });

        Schema::table('directory_individuals_documents_details', function (Blueprint $table) {
            $table->dropForeign('di_id');
            $table->dropIndex('di_id');
            $table->dropColumn('directory_individual_id');

            $table->unsignedBigInteger('supplier_contact_document_id')->nullable(true);

            $table->foreign('supplier_contact_document_id', 'scd_id')
                ->references('id')
                ->on('supplier_contacts_documents')
                ->onDelete('cascade');
        });

        Schema::dropIfExists('directory_individuals_documents');
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
