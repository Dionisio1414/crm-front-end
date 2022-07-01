<?php

namespace Database\Seeders\Company;

use Database\Seeders\Traits\SeederCore;
use Illuminate\Database\Seeder;
use App\Core\Helpers\Csv\ImportCsv;
use Illuminate\Support\Facades\DB;

class DocumentNomenclatureHeaders extends Seeder
{
    use SeederCore;

    protected const DOCUMENT_NOMENCLATURE_HEADERS_CSV = '/database/seeds/company/data/document_nomenclature_headers.csv';

    protected const TABLE_HEADER = 'document_nomenclature_headers';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $headers = ImportCsv::_getDataJsonTitle(base_path() . self::DOCUMENT_NOMENCLATURE_HEADERS_CSV);

        $this->headers($headers, self::TABLE_HEADER, $is_edit = false);
    }
}
