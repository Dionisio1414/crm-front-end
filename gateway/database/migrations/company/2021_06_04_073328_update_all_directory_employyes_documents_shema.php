<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAllDirectoryEmployyesDocumentsShema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_employees_details_documents', function (Blueprint $table) {

            $table->dropColumn('directory_document_id');
            $table->dropColumn('serial');
            $table->dropColumn('serial_number');

            $table->dropColumn('passport_issued');
            $table->dropColumn('passport_issued_date');
            $table->dropColumn('validity_date');


            $table->boolean('archive')->default(0);
            $table->string('document_number')->nullable();
            $table->boolean('is_personal_identity')->default(0);
            $table->dateTime('document_issued_date')->nullable();
            $table->dateTime('document_validity_date')->nullable();
            $table->string('document_issued')->nullable();
            $table->dateTime('date_create_document')->nullable();


            $table->unsignedBigInteger('document_type_id')->nullable();
            $table->boolean('is_default')->default(0);

            $table->timestamps();
        });

        Schema::table('directory_employee_documents_details', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropIndex('directory_employee_documents_details_employee_id_foreign');
            $table->dropColumn('employee_id');

            $table->unsignedBigInteger('employee_document_id')->nullable()->after('header_id');


            $table->foreign('employee_document_id', 'emp_id')
                ->references('id')
                ->on('directory_employees_details_documents')
                ->onDelete('cascade');
        });

        Schema::dropIfExists('directory_employee_documents');
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
