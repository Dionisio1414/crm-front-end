<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryEmployeesShemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_employees_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::create('directory_employees', function (Blueprint $table) {
            $table->id();

            $table->string('thumbnail')->nullable(true);
            $table->string('first_name')->nullable(true);
            $table->string('last_name')->nullable(true);
            $table->string('middle_name')->nullable(true);
            $table->dateTime('date_of_birth')->nullable(true);
            $table->unsignedBigInteger('sex_id')->nullable(true);
            $table->unsignedBigInteger('company_department_id')->nullable(true);
            $table->unsignedBigInteger('position_id')->nullable(true);
            $table->boolean('is_user')->default(0);

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

        Schema::create('directory_employees_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('directory_employee_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('directory_employees_headers')
                ->onDelete('cascade');

            $table->foreign('directory_employee_id', 'de1_id')
                ->references('id')
                ->on('directory_employees')
                ->onDelete('cascade');
        });

        Schema::create('directory_employees_details_main', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('salary')->nullable(true);
            $table->unsignedBigInteger('type_price_id')->nullable(true);
            $table->bigInteger('inn')->nullable(true);

            $table->boolean('is_not_active')->default(0);
            $table->dateTime('date_receipt')->nullable(true);
            $table->dateTime('date_dismissal')->nullable(true);

            $table->unsignedBigInteger('directory_employee_id');

            $table->foreign('directory_employee_id', 'de2_id')
                ->references('id')
                ->on('directory_employees')
                ->onDelete('cascade');

            $table->foreign('type_price_id')
                ->references('id')
                ->on('directory_types_prices')
                ->onDelete('cascade');
        });

        Schema::create('directory_employees_details_contacts', function (Blueprint $table) {
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

            $table->unsignedBigInteger('directory_employee_id');
            $table->foreign('directory_employee_id', 'de3_id')
                ->references('id')
                ->on('directory_employees')
                ->onDelete('cascade');
        });

        Schema::create('directory_employees_details_documents', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('directory_document_id');

            $table->string('serial')->nullable(true);
            $table->bigInteger('serial_number')->nullable(true);

            $table->string('passport_issued')->nullable(true);
            $table->dateTime('passport_issued_date')->nullable(true);
            $table->dateTime('validity_date')->nullable(true);

            $table->unsignedBigInteger('directory_employee_id');
            $table->foreign('directory_employee_id', 'de4_id')
                ->references('id')
                ->on('directory_employees')
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
        Schema::dropIfExists('directory_employees_details');
        Schema::dropIfExists('directory_employees_details_main');
        Schema::dropIfExists('directory_employees_details_contacts');
        Schema::dropIfExists('directory_employees_details_documents');
        Schema::dropIfExists('directory_employees');
        Schema::dropIfExists('directory_employees_headers');
    }
}
