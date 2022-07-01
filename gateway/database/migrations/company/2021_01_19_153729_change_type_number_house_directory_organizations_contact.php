<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeNumberHouseDirectoryOrganizationsContact extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_organizations_details_contact_adresses', function (Blueprint $table) {
            $table->string('number_house')->change();
        });

        Schema::table('directory_organizations_details_contact', function (Blueprint $table) {
            $table->string('legal_number_house')->change();
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
