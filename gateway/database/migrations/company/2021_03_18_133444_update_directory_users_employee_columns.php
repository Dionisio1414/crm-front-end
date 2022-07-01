<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDirectoryUsersEmployeeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_users', function (Blueprint $table) {
            $table->dropColumn('employee_id');
            $table->unsignedBigInteger('directory_employee_id')->nullable(true)->after('gateway_user_id');
        });

        Schema::table('directory_employees', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->unsignedBigInteger('directory_user_id')->nullable(true)->after('is_user');
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
