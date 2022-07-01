<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryCompaniesShemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_organizations', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable(true);
            $table->tinyInteger('is_default')->default(0);
            $table->boolean('archive')->default(0);

            $table->timestamps();
        });

        Schema::create('directory_organizations_details_main', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('organization_type_id');

            $table->string('title')->nullable(true);
            $table->string('full_title')->nullable(true);
            $table->string('first_name')->nullable(true);
            $table->string('last_name')->nullable(true);
            $table->string('middle_name')->nullable(true);
            $table->bigInteger('inn')->nullable(true);

            $table->string('passport_serial')->nullable(true);
            $table->bigInteger('passport_serial_number')->nullable(true);
            $table->string('passport_issued')->nullable(true);
            $table->dateTime('passport_issued_date')->nullable(true);

            $table->string('citizenship')->nullable(true);
            $table->unsignedBigInteger('sex_id')->nullable(true);

            $table->dateTime('date_of_birth')->nullable(true);

            $table->unsignedBigInteger('cashbox_id')->nullable(true);
            $table->unsignedBigInteger('checking_account_id')->nullable(true);
            $table->unsignedBigInteger('price_id')->nullable(true);

            $table->string('edrpou')->nullable(true);

            $table->timestamps();

            $table->foreign('price_id')
                ->references('id')
                ->on('directory_types_prices')
                ->onDelete('cascade');

            $table->foreign('organization_id')
                ->references('id')
                ->on('directory_organizations')
                ->onDelete('cascade');
        });

        Schema::create('directory_organizations_details_contact', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('organization_id');

            $table->unsignedBigInteger('legal_country_id')->nullable(true);
            $table->unsignedBigInteger('legal_region_id')->nullable(true);
            $table->unsignedBigInteger('legal_city_id')->nullable(true);
            
            $table->string('legal_city')->nullable(true);
            $table->bigInteger('legal_number_house')->nullable(true);

            $table->boolean('is_legal_equal_actual')->nullable(true);

            $table->timestamps();

            $table->foreign('organization_id')
                ->references('id')
                ->on('directory_organizations')
                ->onDelete('cascade');
        });

        Schema::create('directory_organizations_details_contact_adresses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('organization_detail_id');

            $table->string('phone')->nullable(true);
            $table->string('email')->nullable(true);

            $table->unsignedBigInteger('country_id')->nullable(true);
            $table->unsignedBigInteger('region_id')->nullable(true);
            $table->unsignedBigInteger('city_id')->nullable(true);
            
            $table->string('city')->nullable(true);
            $table->bigInteger('number_house')->nullable(true);

            $table->timestamps();

            $table->foreign('organization_detail_id', 'o_id_foreign')
                ->references('id')
                ->on('directory_organizations_details_contact')
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
        Schema::dropIfExists('directory_organizations');
        Schema::dropIfExists('directory_organizations_details_main');
        Schema::dropIfExists('directory_organizations_details_contact');
        Schema::dropIfExists('directory_organizations_details_contact_adresses');
    }
}
