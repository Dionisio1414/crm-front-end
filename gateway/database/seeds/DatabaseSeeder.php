<?php

use Database\Seeders\Company\DocumentPurchasesActivityHeaders;
use Database\Seeders\Company\DocumentAllHeaders;
use Database\Seeders\Company\DocumentNomenclatureHeaders;
use Database\Seeders\Company\DocumentNomenclatureOrderStockHeaders;
use Database\Seeders\Company\DocumentNomenclatureStockHeaders;
use Database\Seeders\Company\DocumentOrders;
use Database\Seeders\Company\DocumentReceiptStockHeaders;
use Database\Seeders\Company\DocumentOrdersShipmentHeaders;
use Database\Seeders\Company\DocumentTransferStockHeaders;
use Database\Seeders\Company\DocumentWarehouseHeaders;
use Database\Seeders\Company\DocumentWriteOffStockHeaders;
use Database\Seeders\Company\EditLeadHeaders;
use Database\Seeders\Company\KitHeaders;
use Database\Seeders\Company\KitTableHeaders;
use Database\Seeders\Company\LeadFailureHeaders;
use Database\Seeders\Company\LeadHeaders;
use Database\Seeders\Company\NomenclatureHeaders;
use Database\Seeders\Company\NomenclaturePriceHeaders;
use Database\Seeders\Company\NomenclaturePriceTableHeaders;
use Database\Seeders\Company\ProductInRelatedHeaders;
use Database\Seeders\Company\RelatedProductHeaders;
use Database\Seeders\Company\SelectProductHeaders;
use Database\Seeders\Company\SelectRelatedProductHeaders;
use Database\Seeders\Company\SelectServiceHeaders;
use Database\Seeders\Company\ShowLeadHeaders;
use Database\Seeders\Company\TableGroupsNomenclaturesHeaders;
use Database\Seeders\Company\TableRelatedNomenclaturesInNomenclatureHeaders;
use Database\Seeders\Company\TableRelatedProductHeaders;
use Database\Seeders\Company\WarehouseKitHeaders;
use Database\Seeders\Company\WarehouseProductHeaders;
use Illuminate\Database\Seeder;
use Database\Seeders\Company\DirectoryPositions;
use Database\Seeders\Company\CharacteristicSizeValue;
use Database\Seeders\Company\DirectoryUnits;
use Database\Seeders\Company\DirectoryCities;
use Database\Seeders\Company\DirectoryCountries;
use Database\Seeders\Company\DirectoryRegions;
use Database\Seeders\Company\DirectoryDepartmnets;
use Database\Seeders\Company\DirectoryCounterpartiesReturns;
use Database\Seeders\Company\DirectoryCounterpartiesCancellations;
use Database\Seeders\Company\DirectoryTypesPrices;
use Database\Seeders\Company\DirectoryCurrencies;
use Database\Seeders\Company\ProductHeaders;
use Database\Seeders\Company\ServiceHeaders;
use Database\Seeders\Company\DirectoryCompaniesDepartments;
use Database\Seeders\Company\DirectoryCounterparties;
use Database\Seeders\Company\DirectoryCounterpartiesContracts;
use Database\Seeders\Company\DirectoryIndividualsDocumentsHeaders;
use Database\Seeders\Company\DirectoryEmployeeDocuments;
use Database\Seeders\Company\DirectoryEmployees;
use Database\Seeders\Company\DirectoryUsers;
use Database\Seeders\Company\SuppliersHeaders;
use Database\Seeders\Company\PurchasesHeaders;

use App\Languages\Repositories\LanguageRepositories;
use App\Core\Helpers\DatabaseConnection;

class DatabaseSeeder extends Seeder
{
    protected $classes = [
        ProductHeaders::class,
        KitHeaders::class,
        KitTableHeaders::class,
        RelatedProductHeaders::class,
        TableRelatedProductHeaders::class,
        TableRelatedNomenclaturesInNomenclatureHeaders::class,
        TableGroupsNomenclaturesHeaders::class,
        SelectRelatedProductHeaders::class,
        ProductInRelatedHeaders::class,
        WarehouseProductHeaders::class,
        NomenclaturePriceHeaders::class,
        NomenclaturePriceTableHeaders::class,
        SelectProductHeaders::class,
        SelectServiceHeaders::class,
        DocumentNomenclatureHeaders::class,
        DocumentNomenclatureStockHeaders::class,
        DocumentNomenclatureOrderStockHeaders::class,
        DocumentReceiptStockHeaders::class,
        DocumentWriteOffStockHeaders::class,
        DocumentTransferStockHeaders::class,
        DocumentWarehouseHeaders::class,
        DocumentAllHeaders::class,
        DocumentOrders::class,
        ServiceHeaders::class,
        NomenclatureHeaders::class,
        DirectoryPositions::class,
        CharacteristicSizeValue::class,
        DirectoryUnits::class,
        DirectoryCities::class,
        DirectoryCountries::class,
        DirectoryRegions::class,
        DirectoryDepartmnets::class,
        DirectoryCounterpartiesReturns::class,
        DirectoryCounterpartiesCancellations::class,
        DirectoryPositions::class,
        DirectoryCurrencies::class,
        DirectoryTypesPrices::class,
        DirectoryCompaniesDepartments::class,
        DirectoryCounterparties::class,
        DirectoryCounterpartiesContracts::class,
        DirectoryIndividualsDocumentsHeaders::class,
        DirectoryEmployeeDocuments::class,
        DirectoryEmployees::class,
        DirectoryUsers::class,
        SuppliersHeaders::class,
        PurchasesHeaders::class,
        WarehouseKitHeaders::class,
        DocumentPurchasesActivityHeaders::class,
        DocumentOrdersShipmentHeaders::class,
        LeadHeaders::class,
        ShowLeadHeaders::class,
        EditLeadHeaders::class,
        LeadFailureHeaders::class
    ];

    public $class, $is_edit, $languages;

    public function __construct(LanguageRepositories $languageRepositories)
    {
        $this->class   = config('app.class_seeds');
        $this->is_edit = config('app.edit_seeds');

        /*  Get languages from core db */
        $databaseOld = DatabaseConnection::getConnection();

        DatabaseConnection::setConnection([
            'db_database' => env('DB_DATABASE')
        ]);

        $this->languages = $languageRepositories->getLanguages();

        DatabaseConnection::setConnection([
            'db_database' => $databaseOld
        ]);
        /* end */
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $is_edit = $this->is_edit;
        $languages = $this->languages;
        if($this->class){
            $this->call('Database\Seeders\Company\\' . $this->class, false, compact(['is_edit','languages']));
        }else{
            foreach ($this->classes as $class){
                $this->call(
                    $class, false, compact(['is_edit','languages'])
                );
            }
        }
    }
}
