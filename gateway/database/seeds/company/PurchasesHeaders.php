<?php

namespace Database\Seeders\Company;

use Database\Seeders\Traits\SeederCore;
use Illuminate\Database\Seeder;
use App\Core\Helpers\Csv\ImportCsv;
use Illuminate\Support\Facades\DB;

class PurchasesHeaders extends Seeder
{
    use SeederCore;

    protected const PURCHASE_HEADERS_CSV = '/database/seeds/company/data/document_purchases_headers.csv';
    protected const PURCHASE_NOMENCLATURES_HEADERS_CSV = '/database/seeds/company/data/document_purchases_nomenclatures_headers.csv';

    protected const TABLE_HEADER_PURCHACE = 'document_purchases_headers';
    protected const TABLE_HEADER_PURCHACE_NOMENCLATUR = 'document_purchases_nomenclatures_headers';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($is_edit = false)
    {
        $headers = ImportCsv::_getDataJsonTitle(base_path() . self::PURCHASE_HEADERS_CSV);
        $this->headers($headers, self::TABLE_HEADER_PURCHACE, $is_edit);

        $headers = ImportCsv::_getDataJsonTitle(base_path() . self::PURCHASE_NOMENCLATURES_HEADERS_CSV);
        $this->headers($headers, self::TABLE_HEADER_PURCHACE_NOMENCLATUR, $is_edit);
    }
}
