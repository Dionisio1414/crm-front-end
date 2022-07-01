<?php

namespace Database\Seeders\Company;

use Database\Seeders\Traits\SeederCore;
use Illuminate\Database\Seeder;
use App\Core\Helpers\Csv\ImportCsv;
use Illuminate\Support\Facades\DB;

class ShowLeadHeaders extends Seeder
{
    use SeederCore;

    protected const ITEMS_HEADERS_CSV = '/database/seeds/company/data/show_lead_headers.csv';

    protected const TABLE_HEADER = 'show_lead_headers';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($is_edit = false)
    {
        $headers = ImportCsv::_getDataJsonTitle(base_path() . self::ITEMS_HEADERS_CSV);

        $this->headers($headers, self::TABLE_HEADER, $is_edit);
    }
}
