<?php

namespace Database\Seeders\Company;

use Database\Seeders\Traits\SeederCore;
use Illuminate\Database\Seeder;
use App\Core\Helpers\Csv\ImportCsv;
use Illuminate\Support\Facades\DB;

class ServiceHeaders extends Seeder
{
    use SeederCore;

    protected const SERVICES_HEADERS_CSV = '/database/seeds/company/data/services_headers.csv';

    protected const TABLE_HEADER = 'services_headers';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($is_edit = false)
    {

        $headers = ImportCsv::_getDataJsonTitle(base_path() . self::SERVICES_HEADERS_CSV);

        $this->headers($headers, self::TABLE_HEADER, $is_edit);
    }
}
