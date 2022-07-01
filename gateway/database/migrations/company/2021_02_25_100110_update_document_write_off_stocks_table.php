<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDocumentWriteOffStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_write_off_stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('responsible_id')->nullable(true)->after('currency_id');
            $table->foreign('responsible_id')
                ->references('id')
                ->on('directory_users')
                ->onDelete('cascade');
            $table->unsignedBigInteger('organization_id')->nullable(true)->after('comments');
            $table->foreign('organization_id')
                ->references('id')
                ->on('directory_organizations')
                ->onDelete('cascade');
            $table->string('organization_convert_id')->default(0)->after('comments');

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
