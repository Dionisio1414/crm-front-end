<?php

namespace Database\Seeders\Company;

use Illuminate\Database\Seeder;
use App\Core\Helpers\Csv\ImportCsv;
use Illuminate\Support\Facades\DB;

class CharacteristicSizeValue extends Seeder
{
    protected const CHARACTERISTIC_SIZE_CSV = '/database/seeds/company/data/characteristic_size.csv';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($is_edit = false)
    {
        $characteristic_size = ImportCsv::_getData(base_path() . self::CHARACTERISTIC_SIZE_CSV);

        foreach ($characteristic_size as $key => $value)
        {
            DB::table('characteristic_size_value')->updateOrInsert(
                [
                    'id'    =>  $value['id']
                ],
                [
                    'title' => $value['title'],
                    'order' =>  $key,
                    'type'  => $value['type']
                ]
            );
        }
    }
}
