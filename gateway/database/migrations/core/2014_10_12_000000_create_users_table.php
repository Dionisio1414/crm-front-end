<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{

    protected $database = 'core';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_role', function (Blueprint $table) {
            $table->id('id');
            $table->string('title');
            $table->text('resources');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->unsignedBigInteger('role_id')->nullable(true);
            $table->string('email')->unique()->nullable(true);
            $table->string('thumbnail')->nullable(true);
            $table->string('first_name')->nullable(true);
            $table->string('last_name')->nullable(true);
            $table->string('phone')->unique()->nullable(true);
            $table->string('password')->nullable(true);
            $table->string('api_token')->unique()->nullable(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role_id')
                ->references('id')
                ->on('user_role')
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_role');
    }
}
