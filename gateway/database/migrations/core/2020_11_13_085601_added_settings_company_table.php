<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedSettingsCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedBigInteger('currency_id')->nullable(true)->after('language_interface_id');

            $table->unsignedTinyInteger('is_kits')->default(1)->after('domain');
            $table->unsignedTinyInteger('is_labels_price_tags')->default(1)->after('domain');
            $table->unsignedTinyInteger('is_residue_control')->default(1)->after('domain');
            $table->unsignedTinyInteger('is_сhange_history')->default(1)->after('domain');

            $table->integer('archive_cleare')->nullable(true)->after('domain');
            $table->dateTime('data_prohibition')->nullable(true)->after('domain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('currency_id');
            $table->dropColumn('is_kits');
            $table->dropColumn('is_labels_price_tags');
            $table->dropColumn('is_residue_control');
            $table->dropColumn('is_сhange_history');
            $table->dropColumn('archive_cleare');
            $table->dropColumn('data_prohibition');
        });
    }
}
