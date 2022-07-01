<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileManagerShemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_manager_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->string('title');

            $table->timestamps();
        });

        Schema::create('file_manager', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable(true);
            $table->string('title');
            $table->text('path')->nullable(true);
            $table->text('full_path')->nullable(true);
            $table->boolean('is_image')->default(0);

            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('file_manager_categories')
                ->onDelete('cascade');
        });

        Schema::table('file_nomenclature', function (Blueprint $table) {
            $table->dropForeign(['file_id']);

            $table->foreign('file_id')
                ->references('id')
                ->on('file_manager')
                ->onDelete('cascade');
        });

        Schema::dropIfExists('files');
        Schema::dropIfExists('category_file');
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
