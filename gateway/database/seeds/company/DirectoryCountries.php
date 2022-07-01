<?php

namespace Database\Seeders\Company;

use Database\Seeders\Traits\SeederCore;
use Illuminate\Database\Seeder;
use App\Core\Helpers\Csv\ImportCsv;
use Illuminate\Support\Facades\DB;
use App\Core\Helpers\Earth\ImportCountries;

class DirectoryCountries extends Seeder
{
    use SeederCore;

    protected const PATH_CSV_HEADER = '/database/seeds/company/data/directory_countries/directory_countries_headers.csv';

    protected const TABLE_HEADER = 'directory_countries_headers';
    protected const TABLE_ITEMS = 'directory_countries';
    protected const TABLE_DETAIL = 'directory_countries_details';

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
            $items = ImportCountries::_getDataMainDetail($languages);
            $this->items($items['main'], self::TABLE_ITEMS);
            $this->details($items['detail'], self::TABLE_DETAIL, $is_edit);
        }
    }
}
