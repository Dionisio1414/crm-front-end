<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateOrderDirectoryOrganizationsAndDepartmentsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_organizations', function (Blueprint $table) {
            $table->unsignedBigInteger('order')->after('archive')->default(0);
        });

        Schema::table('directory_companies_departments', function (Blueprint $table) {
            $table->unsignedBigInteger('order')->after('archive')->default(0);
        });

        /* Set orders in items */
        $organizations = DB::table('directory_organizations')->get();
        foreach ($organizations as $organization){
            DB::table('directory_organizations')->where('id', $organization->id)->update(['order' => $organization->id]);
        }

        $departments = DB::table('directory_companies_departments')->get();
        foreach ($departments as $department){
            DB::table('directory_companies_departments')->where('id', $department->id)->update(['order' => $department->id]);
        }
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
