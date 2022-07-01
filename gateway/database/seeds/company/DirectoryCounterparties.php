<?php

namespace Database\Seeders\Company;

use Database\Seeders\Traits\SeederCore;
use Illuminate\Database\Seeder;
use App\Core\Helpers\Csv\ImportCsv;
use Illuminate\Support\Facades\DB;

class DirectoryCounterparties extends Seeder
{
    use SeederCore;

    protected const PATH_CSV_HEADER = '/database/seeds/company/data/directory_counterparties_headers.csv';
    protected const TABLE_HEADER = 'directory_counterparties_headers';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($is_edit = false)
    {
        $headers = ImportCsv::_getDataJsonTitle(base_path() . self::PATH_CSV_HEADER);

        $this->headers($headers, self::TABLE_HEADER, $is_edit);
    }
}
