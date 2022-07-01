<?php

namespace Database\Seeders\Company;

use Database\Seeders\Traits\SeederCore;
use Illuminate\Database\Seeder;
use App\Core\Helpers\Csv\ImportCsv;
use Illuminate\Support\Facades\DB;

class DirectoryDepartmnets extends Seeder
{
    use SeederCore;

    protected const PATH_CSV_HEADER = '/database/seeds/company/data/directory_departments/directory_departments_headers.csv';
    protected const PATH_CSV = '/database/seeds/company/data/directory_departments/directory_departments.csv';
    protected const PATH_CSV_DETAIL = '/database/seeds/company/data/directory_departments/directory_departments_detail.csv';

    protected const TABLE_HEADER = 'directory_departments_headers';
    protected const TABLE_ITEMS = 'directory_departments';
    protected const TABLE_DETAIL = 'directory_departments_details';

    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run($is_edit = false)
    {
        $headers = ImportCsv::_getDataJsonTitle(base_path() . self::PATH_CSV_HEADER);
        $items = ImportCsv::_getDataJsonTitle(base_path() . self::PATH_CSV);
        $items_detail = ImportCsv::_getDataJsonTitle(base_path() . self::PATH_CSV_DETAIL);

        $this->headers($headers, self::TABLE_HEADER, $is_edit);
        $this->items($items, self::TABLE_ITEMS);
        $this->details($items_detail, self::TABLE_DETAIL, $is_edit);
    }
}
