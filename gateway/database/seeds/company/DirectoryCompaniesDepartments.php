<?php

namespace Database\Seeders\Company;

use Database\Seeders\Traits\SeederCore;
use Illuminate\Database\Seeder;
use App\Core\Helpers\Csv\ImportCsv;
use Illuminate\Support\Facades\DB;

class DirectoryCompaniesDepartments extends Seeder
{
    use SeederCore;

    protected const PATH_CSV = '/database/seeds/company/data/directory_companies_departments.csv';

    protected const TABLE_ITEMS = 'directory_companies_departments';

    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run($is_edit = false)
    {
        $items = ImportCsv::_getDataJsonTitle(base_path() . self::PATH_CSV);

        $this->headers($items, self::TABLE_ITEMS, $is_edit);
    }
}
