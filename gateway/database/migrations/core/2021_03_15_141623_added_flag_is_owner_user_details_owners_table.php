<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedFlagIsOwnerUserDetailsOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedMediumInteger('session_life_day')->default(7)->after('verification_token');
            $table->boolean('auth_flag')->default(0)->after('verification_token');
        });

        Schema::table('user_detail_owners', function (Blueprint $table) {
            $table->boolean('is_owner')->default(1)->after('user_id');
        });

        Schema::table('session_users', function (Blueprint $table) {
            $table->string('token_id')->nullable(true)->after('id');
            $table->dateTime('expires_at')->nullable(true);
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
            $table->dropColumn('auth_flag');
            $table->dropColumn('session_life_day');
        });

        Schema::table('user_detail_owners', function (Blueprint $table) {
            $table->dropColumn('is_owner');
        });

        Schema::table('session_users', function (Blueprint $table) {
            $table->dropColumn('token_id');
            $table->dropColumn('expires_at');
        });
    }
}
