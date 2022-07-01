<?php

namespace Database\Seeders\Company;

use Database\Seeders\Traits\SeederCore;
use Illuminate\Database\Seeder;
use App\Core\Helpers\Csv\ImportCsv;
use Illuminate\Support\Facades\DB;

class TableRelatedNomenclaturesInNomenclatureHeaders extends Seeder
{
    use SeederCore;

    protected const TABLE_RELATED_NOMENCLATURES_IN_NOMENCLATURE_HEADERS_CSV = '/database/seeds/company/data/table_related_nomenclatures_in_nomenclature_headers.csv';

    protected const TABLE_HEADER = 'table_related_nomenclatures_in_nomenclature_headers';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($is_edit = false)
    {

        $headers = ImportCsv::_getDataJsonTitle(base_path() . self::TABLE_RELATED_NOMENCLATURES_IN_NOMENCLATURE_HEADERS_CSV);

        $this->headers($headers, self::TABLE_HEADER, $is_edit);
    }
}
