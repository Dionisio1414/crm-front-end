<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('title', 'short_title');
            $table->json('dock_title')->after('title')->nullable();
            $table->string('packing')->after('lower_limit')->nullable();
            $table->string('code')->after('lower_limit')->nullable();
            $table->string('barcode')->after('lower_limit')->nullable();
            $table->string('barcode_supplier')->after('lower_limit')->nullable();
            $table->bigInteger('reserve')->after('lower_limit')->nullable();
            $table->bigInteger('general_count')->after('lower_limit')->nullable();
            $table->unsignedBigInteger('country_id')->after('supplier_id')->nullable();
            $table->integer('identifier_successful')->after('supplier_id')->nullable();
            $table->integer('identifier_fullness')->after('supplier_id')->nullable();
            $table->bigInteger('lower_limit')->default(0)->change();
            $table->foreign('country_id')
                ->references('id')
                ->on('directory_countries')
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
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('short_title', 'title');
            $table->dropColumn('dock_title');
            $table->dropColumn('packing');
            $table->dropColumn('code');
            $table->dropColumn('barcode');
            $table->dropColumn('barcode_supplier');
            $table->dropColumn('reserve');
            $table->dropColumn('general_count');
            $table->dropColumn('country_id');
            $table->dropColumn('identifier_successful');
            $table->dropColumn('identifier_fullness');
            $table->dropForeign('country_id');
        });
    }
}
