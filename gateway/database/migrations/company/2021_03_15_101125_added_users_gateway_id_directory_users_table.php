<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedUsersGatewayIdDirectoryUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_users', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->after('is_employee')->nullable(true);
            $table->unsignedBigInteger('gateway_user_id')->after('is_employee')->nullable(true);
        });

        Schema::table('directory_employees', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('is_user')->nullable(true);
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
