<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRelatedNomenclaturesInNomenclatureHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_related_nomenclatures_in_nomenclature_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);
            $table->string('value')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_related_nomenclatures_in_nomenclature_headers');
    }
}
