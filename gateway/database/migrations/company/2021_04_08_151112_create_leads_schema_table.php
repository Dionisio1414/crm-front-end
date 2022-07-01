<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLeadsSchemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads_headers', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);
            $table->tinyInteger('is_sortable')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->bigInteger('order')->default(0);

            $table->string('value')->nullable(true);
        });

        Schema::create('leads_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(true);
            $table->string('title_full')->nullable(true);
            $table->string('background_color')->nullable(true);
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->dateTime('data')->nullable(true);
            $table->string('title')->nullable(true);
            $table->integer('loyalty')->default(0);
            $table->integer('discount')->default(0);

            $table->unsignedBigInteger('lead_status_id')->nullable(true);
            $table->unsignedBigInteger('manager_id')->nullable(true);
            $table->unsignedBigInteger('organization_id')->nullable(true);
            $table->unsignedBigInteger('source_attraction_id')->nullable(true);

            $table->text('comments')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->string('email')->nullable(true);

            $table->boolean('archive')->default(0);

            $table->timestamps();

            $table->foreign('manager_id')
                ->references('id')
                ->on('directory_employees')
                ->onDelete('cascade');

            $table->foreign('organization_id')
                ->references('id')
                ->on('directory_organizations')
                ->onDelete('cascade');

            $table->foreign('lead_status_id')
                ->references('id')
                ->on('leads_statuses')
                ->onDelete('cascade');
        });

        Schema::create('leads_details', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(true);

            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('lead_id');

            $table->foreign('header_id')
                ->references('id')
                ->on('leads_headers')
                ->onDelete('cascade');

            $table->foreign('lead_id')
                ->references('id')
                ->on('leads')
                ->onDelete('cascade');
        });

        Schema::create('category_lead', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('lead_id');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');

            $table->foreign('lead_id')
                ->references('id')
                ->on('leads')
                ->onDelete('cascade');
        });

        Schema::create('nomenclature_lead', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomenclature_id');
            $table->unsignedBigInteger('lead_id');
            $table->integer('count')->nullable(true);
            $table->integer('amount')->nullable(true);
            $table->string('offered_discount')->nullable(true);

            $table->foreign('nomenclature_id')
                ->references('id')
                ->on('nomenclatures')
                ->onDelete('cascade');

            $table->foreign('lead_id')
                ->references('id')
                ->on('leads')
                ->onDelete('cascade');
        });

        DB::table('leads_statuses')->insert([
            ['id' => 1, 'title' => 'Необработанные', 'title_full' => 'Работа с необработанными', 'background_color' => "#9dccff"],
            ['id' => 2, 'title' => 'Интерес', 'title_full' => 'Формирование интереса', 'background_color' => "#f79f30"],
            ['id' => 3, 'title' => 'Убеждение', 'title_full' => 'Убеждение', 'background_color' => "#4eca80"],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads_headers');
        Schema::dropIfExists('leads_statuses');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('leads_details');
        Schema::dropIfExists('category_lead');
        Schema::dropIfExists('nomenclature_lead');
    }
}
