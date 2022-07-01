<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRemovedSupplierIndividualsDirectoryEmployeeDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_employee_documents', function (Blueprint $table) {
            $table->dropForeign(['supplier_individual_id']);
            $table->dropForeign(['counterparty_id']);
            $table->dropColumn('supplier_individual_id');
            $table->dropColumn('counterparty_id');
        });

        Schema::table('directory_employee_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->nullable(true)->after('id');

            $table->foreign('employee_id')
                ->references('id')
                ->on('directory_employees')
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
