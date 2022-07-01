<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersEmployyesDirectoryShema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_users', function (Blueprint $table) {
            $table->dropColumn('thumbnail_id');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('middle_name');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('sex_id');
            $table->dropForeign(['company_department_id']);
            $table->dropForeign(['position_id']);

            $table->dropColumn('company_department_id');
            $table->dropColumn('position_id');
            $table->dropColumn('is_employee');
        });

        Schema::dropIfExists('directory_users_details_contacts');
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
