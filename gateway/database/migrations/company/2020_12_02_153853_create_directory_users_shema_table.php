<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryUsersShemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_users_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::create('directory_users', function (Blueprint $table) {
            $table->id();

            $table->string('thumbnail')->nullable(true);
            $table->string('first_name')->nullable(true);
            $table->string('last_name')->nullable(true);
            $table->string('middle_name')->nullable(true);
            $table->dateTime('date_of_birth')->nullable(true);
            $table->unsignedBigInteger('sex_id')->nullable(true);
            $table->unsignedBigInteger('company_department_id')->nullable(true);
            $table->unsignedBigInteger('position_id')->nullable(true);
            $table->boolean('is_employee')->default(0);

            $table->boolean('archive')->default(0);

            $table->timestamps();

            $table->foreign('company_department_id')
                ->references('id')
                ->on('directory_companies_departments')
                ->onDelete('cascade');

            $table->foreign('position_id')
                ->references('id')
                ->on('directory_positions')
                ->onDelete('cascade');
        });

        Schema::create('directory_users_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('directory_user_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('directory_users_headers')
                ->onDelete('cascade');

            $table->foreign('directory_user_id', 'du_id')
                ->references('id')
                ->on('directory_users')
                ->onDelete('cascade');
        });

        Schema::create('directory_users_details_contacts', function (Blueprint $table) {
            $table->id();

            $table->string('mobile_phone')->nullable(true);
            $table->string('work_phone')->nullable(true);
            $table->string('internal_phone')->nullable(true);

            $table->string('email')->nullable(true);
            $table->string('skype')->nullable(true);
            $table->string('zoom')->nullable(true);

            $table->unsignedBigInteger('residence_country_id')->nullable(true);
            $table->unsignedBigInteger('residence_region_id')->nullable(true);
            $table->unsignedBigInteger('residence_city_id')->nullable(true);

            $table->string('residence_street')->nullable(true);
            $table->bigInteger('residence_number_house')->nullable(true);

            $table->boolean('is_equal_residence_registration')->default(0);

            $table->unsignedBigInteger('registration_country_id')->nullable(true);
            $table->unsignedBigInteger('registration_region_id')->nullable(true);
            $table->unsignedBigInteger('registration_city_id')->nullable(true);

            $table->string('registration_street')->nullable(true);
            $table->bigInteger('registration_number_house')->nullable(true);

            $table->unsignedBigInteger('directory_user_id');
            $table->foreign('directory_user_id', 'du1_id')
                ->references('id')
                ->on('directory_users')
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
        Schema::dropIfExists('directory_users_details');
        Schema::dropIfExists('directory_users_details_contacts');
        Schema::dropIfExists('directory_users');
        Schema::dropIfExists('directory_users_headers');
    }
}
