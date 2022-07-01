<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company_name');
            $table->unsignedBigInteger('company_id')->after('id')->nullable(true);

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->string('company_name');
            $table->dropColumn('company_id');
        });

        Schema::dropIfExists('companies');
    }
}
