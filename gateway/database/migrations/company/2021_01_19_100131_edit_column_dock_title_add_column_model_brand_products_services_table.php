<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditColumnDockTitleAddColumnModelBrandProductsServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('dock_title')->change();
            $table->string('model')->after('identifier_successful')->nullable();
            $table->string('brand')->after('identifier_successful')->nullable();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('dock_title')->change();
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
