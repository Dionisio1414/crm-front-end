<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTariffsShemas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_tariffs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('tooltip_info')->nullable(true);
            $table->unsignedBigInteger('parent_category_id')->nullable(true);

            $table->timestamps();
        });

        Schema::create('detail_category_tariffs', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->unsignedBigInteger('category_tariff_id');

            $table->foreign('category_tariff_id')
                ->references('id')
                ->on('category_tariffs')
                ->onDelete('cascade');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_category_tariffs');
        Schema::dropIfExists('category_tariffs');
    }
}
