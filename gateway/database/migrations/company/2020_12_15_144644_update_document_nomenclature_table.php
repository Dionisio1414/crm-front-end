<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDocumentNomenclatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_nomenclature', function (Blueprint $table) {
            $table->bigInteger('balance')->default(0)->after('nomenclature_id');
            $table->bigInteger('capitalized_balance')->default(0)->after('nomenclature_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_nomenclature', function (Blueprint $table) {
            $table->dropColumn('balance');
            $table->dropColumn('capitalized_balance');
        });    
    }
}
