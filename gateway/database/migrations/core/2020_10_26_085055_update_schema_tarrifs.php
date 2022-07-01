<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSchemaTarrifs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_columns_tariffs', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
            $table->dropColumn('language_id');
            $table->dropColumn('value');
            $table->json('title')->change()->nullable(true);
        });

        Schema::table('tariffs', function (Blueprint $table) {
            $table->json('steps')->after('cost')->nullable(true);
            $table->json('custom_column')->after('cost')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_columns_tariffs', function (Blueprint $table) {
            $table->string('title')->change();
            $table->integer('value')->after('title')->nullable(true);

            $table->unsignedBigInteger('language_id')->after('id');
            $table->foreign('language_id')
                ->references('id')
                ->on('languages')
                ->onDelete('cascade');
        });

        Schema::table('tariffs', function (Blueprint $table) {
            $table->dropColumn('steps');
            $table->dropColumn('custom_column');
        });
    }
}
