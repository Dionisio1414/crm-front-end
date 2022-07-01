<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_managers', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('surname');
            $table->string('patronymic');
            $table->integer('phone');
            $table->string('email');
            $table->date('dob');
            $table->tinyInteger('gender');
            $table->string('number_doc');
            $table->unsignedBigInteger('position_id')->nullable();
            $table->unsignedBigInteger('type_doc_individual_id')->nullable();
            $table->unsignedBigInteger('sources_id')->nullable();
            $table->timestamps();

            $table->foreign('position_id')
                ->references('id')
                ->on('directory_positions')
                ->onDelete('set null');

            $table->foreign('type_doc_individual_id')
                ->references('id')
                ->on('directory_type_doc_individuals')
                ->onDelete('set null');

            $table->foreign('sources_id')
                ->references('id')
                ->on('directory_attraction_sources')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_managers');
    }
}
