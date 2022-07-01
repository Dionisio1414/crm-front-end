<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->json('phone')->nullable(true);
            $table->string('street')->nullable();
            $table->string('number_house')->nullable();
            $table->boolean('archive')->default(0);
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('warehouse_type_id')->nullable();
            $table->unsignedBigInteger('warehouse_group_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();

            $table->foreign('country_id')
                ->references('id')
                ->on('directory_countries')
                ->onDelete('cascade');

            $table->foreign('region_id')
                ->references('id')
                ->on('directory_cities')
                ->onDelete('cascade');

            $table->foreign('city_id')
                ->references('id')
                ->on('directory_cities')
                ->onDelete('cascade');

            $table->foreign('warehouse_group_id')
                ->references('id')
                ->on('warehouse_groups')
                ->onDelete('cascade');

//            $table->foreign('warehouse_type_id')
//                ->references('id')
//                ->on('warehouse_groups')
//                ->onDelete('cascade');

            $table->foreign('employee_id')
                ->references('id')
                ->on('directory_employees')
                ->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
}
