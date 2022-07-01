<?php

namespace Database\Seeders\Company;

use Database\Seeders\Traits\SeederCore;
use Illuminate\Database\Seeder;
use App\Core\Helpers\Csv\ImportCsv;
use Illuminate\Support\Facades\DB;

class TableGroupsNomenclaturesHeaders extends Seeder
{
    use SeederCore;
    
    protected const TABLE_GROUPS_NOMENCLATURES_HEADERS_CSV = '/database/seeds/company/data/table_groups_nomenclatures_headers.csv';

    protected const TABLE_HEADER = 'table_groups_nomenclatures_headers';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($is_edit = false)
    {
        $headers = ImportCsv::_getDataJsonTitle(base_path() . self::TABLE_GROUPS_NOMENCLATURES_HEADERS_CSV);

        $this->headers($headers, self::TABLE_HEADER, $is_edit);
    }
}
