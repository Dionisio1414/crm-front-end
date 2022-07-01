<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersCompaniesLanguageSchemas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('language_id')->nullable(true);

            $table->foreign('language_id')
                ->references('id')
                ->on('languages')
                ->onDelete('cascade');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedBigInteger('language_interface_id')->after('owner_id')->nullable(true);

            $table->foreign('language_interface_id')
                ->references('id')
                ->on('languages')
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
            $table->dropColumn('language_id');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['language_interface_id']);
            $table->dropColumn('language_interface_id');
        });
    }
}
