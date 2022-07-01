<?php

namespace Database\Seeders\Company;

use App\Core\Helpers\Earth\ImportCountries;
use Database\Seeders\Traits\SeederCore;
use Illuminate\Database\Seeder;
use App\Core\Helpers\Csv\ImportCsv;

class DirectoryCities extends Seeder
{
    use SeederCore;

    protected const PATH_CSV_HEADER = '/database/seeds/company/data/directory_cities/directory_cities_headers.csv';

    protected const TABLE_HEADER = 'directory_cities_headers';
    protected const TABLE_ITEMS = 'directory_cities';
    protected const TABLE_DETAIL = 'directory_cities_details';

    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run($is_edit = false, $languages = null)
    {
        $headers = ImportCsv::_getDataJsonTitle(base_path() . self::PATH_CSV_HEADER);

        $this->headers($headers, self::TABLE_HEADER, $is_edit);

        if(!$is_edit){
            /* Get from api Countries */
            $items = ImportCountries::_getCitiesDataMainDetail($languages);
            $this->items($items['main'], self::TABLE_ITEMS);
            $this->details($items['detail'], self::TABLE_DETAIL, $is_edit);
        }
    }
}
