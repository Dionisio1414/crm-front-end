<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryCompaniesDepartmentsShemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_companies_departments', function (Blueprint $table) {
            $table->id();

            $table->json('title')->nullable(true);
            $table->tinyInteger('is_default')->default(0);

            $table->bigInteger('code')->nullable(true);
            $table->boolean('archive')->default(0);

            $table->timestamps();
        });

        Schema::create('directory_companies_departments_employees', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('company_department_id');
            $table->unsignedBigInteger('employee_id');

            $table->boolean('is_leader')->default(0);

            $table->timestamps();

            $table->foreign('company_department_id', 'cd_id')
                ->references('id')
                ->on('directory_companies_departments')
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
        Schema::dropIfExists('directory_companies_departments_employees');
        Schema::dropIfExists('directory_companies_departments');
    }
}
