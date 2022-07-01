<?php

namespace Database\Seeders\Company;

use Database\Seeders\Traits\SeederCore;
use Illuminate\Database\Seeder;
use App\Core\Helpers\Csv\ImportCsv;
use Illuminate\Support\Facades\DB;

class ProductInRelatedHeaders extends Seeder
{
    use SeederCore;

    protected const PRODUCTS_IN_RELATED_HEADERS_CSV = '/database/seeds/company/data/products_in_related_headers.csv';

    protected const TABLE_HEADER = 'products_in_related_headers';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($is_edit = false)
    {
        $headers = ImportCsv::_getDataJsonTitle(base_path() . self::PRODUCTS_IN_RELATED_HEADERS_CSV);

        $this->headers($headers, self::TABLE_HEADER, $is_edit);
    }
}
