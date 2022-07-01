<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDirectoryIndividualsRemovedColumnSupplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_individuals_documents', function (Blueprint $table) {
            $table->dropForeign(['supplier_individual_id']);
            $table->dropColumn('supplier_individual_id');

            $table->unsignedBigInteger('individual_id')->after('counterparty_id')->nullable(true);
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
