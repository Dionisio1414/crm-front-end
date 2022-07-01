<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSeedsPropertiesDefaultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $array = [
            ['id' => 1, 'title' => 'Торговая марка', 'order' => 1],
            ['id' => 2, 'title' => 'Модель', 'order' => 2],
        ];

        foreach ($array as $val)
        {
            DB::table('properties')->updateOrInsert(['id'=>$val['id']],$val);
        }
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
