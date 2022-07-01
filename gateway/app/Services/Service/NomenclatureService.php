<?php

namespace App\Services\Service;

use App\Core\Traits\CoreService;
use App\Core\Traits\ConsumeExternalService;

class NomenclatureService
{

    use ConsumeExternalService, CoreService;

    public function getProducts($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-products/'. $id, $this->request);
    }

    public function getServices($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-services/'. $id, $this->request);
    }

    public function getKits($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-kits/'. $id, $this->request);
    }

    public function getNotActualNomenclaturesServices($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-not-actual-nomenclatures/'. $id, $this->request);
    }

    public function storeProduct()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/store-product', $this->request);
    }

    public function storeService()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/store-service', $this->request);
    }

    public function storeKit()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/store-kit', $this->request);
    }

    public function storeGroupProduct()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/store-group-product', $this->request);
    }

    public function updateProduct($id)
    {
        return $this->performRequestForm('PUT','api/v1/products/nomenclatures/update-product/'.$id, $this->request);
    }

    public function updateService($id)
    {
        return $this->performRequestForm('PUT','api/v1/products/nomenclatures/update-service/'.$id, $this->request);
    }

    public function updateKit($id)
    {
        return $this->performRequestForm('PUT','api/v1/products/nomenclatures/update-kit/'.$id, $this->request);
    }

    public function indexProductsAll()
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-products-all', $this->request);
    }

    public function indexServicesAll()
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-services-all', $this->request);
    }

    public function indexKitsAll()
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-kits-all', $this->request);
    }

    public function indexNotActualNomenclaturesAll()
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-not-actual-nomenclatures-all', $this->request);
    }

    public function getGroupsNomenclatures($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-groups-nomenclatures/'.$id, $this->request);
    }

    public function showProduct($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-product/'.$id, $this->request);
    }

    public function showService($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-service/'.$id, $this->request);
    }

    public function showKit($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-kit/'.$id, $this->request);
    }

    public function storeGroupsProduct($id)
    {

        return $this->performRequestForm('POST','api/v1/products/nomenclatures/store-groups-product/'.$id, $this->request);
    }

    public function changeMinPriceNomenclature($id)
    {
        return $this->performRequestForm('PUT', 'api/v1/products/nomenclatures/change-min_price-nomenclature/'.$id, $this->request);
    }

    public function changePriceNomenclature()
    {
        return $this->performRequestForm('PUT', 'api/v1/products/nomenclatures/change-price-nomenclature', $this->request);
    }

    public function changePriceNomenclatureHistory()
    {
        return $this->performRequestForm('PUT', 'api/v1/products/nomenclatures/change-price-nomenclature-history', $this->request);
    }

    public function choosePriceNomenclature()
    {
        return $this->performRequestForm('POST', 'api/v1/products/nomenclatures/choose-price-nomenclature', $this->request);
    }

    public function toVisibility()
    {
        return $this->performRequestForm('POST', 'api/v1/products/nomenclatures/toVisibility', $this->request);
    }

    public function outVisibility()
    {
        return $this->performRequestForm('POST', 'api/v1/products/nomenclatures/outVisibility', $this->request);
    }

    public function toArchive()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/toArchive', $this->request);
    }

    public function outArchive()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/outArchive', $this->request);
    }

    public function toActual()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/toActual', $this->request);
    }

    public function outActual()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/outActual', $this->request);
    }

    public function updateProductsHeader()
    {
        return $this->performRequestForm('PUT','api/v1/products/nomenclatures/product/headers', $this->request);
    }

    public function updateServicesHeader()
    {
        return $this->performRequestForm('PUT','api/v1/products/nomenclatures/service/headers', $this->request);
    }

    public function updateKitsHeader()
    {
        return $this->performRequestForm('PUT','api/v1/products/nomenclatures/kit/headers', $this->request);
    }

    public function updateNomenclaturesHeader()
    {
        return $this->performRequestForm('PUT','api/v1/products/nomenclatures/nomenclature/headers', $this->request);
    }

    public function moveProducts($id)
    {
        return $this->performRequestForm('PUT','api/v1/products/nomenclatures/move-products/' . $id, $this->request);
    }

    public function moveServices($id)
    {
        return $this->performRequestForm('PUT','api/v1/products/nomenclatures/move-services/' . $id, $this->request);
    }

    public function moveKits($id)
    {
        return $this->performRequestForm('PUT','api/v1/products/nomenclatures/move-kits/' . $id, $this->request);
    }

    public function selectedKits()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/selected-kits', $this->request);
    }

    public function selectedCharacteristicsKits()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/selected-characteristics-kits', $this->request);
    }

    public function storeAsyncProductsValidations()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/store-async-products-validations', $this->request);
    }

    public function updateAsyncProductsValidations()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/update-async-products-validations', $this->request);
    }

    public function storeAsyncServicesValidations()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/store-async-services-validations', $this->request);
    }

    public function updateAsyncServicesValidations()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/update-async-services-validations', $this->request);
    }

    public function generateProducts()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/generate', $this->request);
    }

    public function selectProduct($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/select-product/'.$id, $this->request);
    }

    public function indexSelectProductsAll()
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-select-products-all', $this->request);
    }

    public function indexSelectServicesAll()
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-select-services-all', $this->request);
    }

    public function indexSelectProducts($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-select-products/'.$id, $this->request);
    }

    public function indexSelectServices($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-select-services/'.$id, $this->request);
    }

    public function searchProductsDocumentTable()
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/search-products-document-table', $this->request);
    }

    public function searchProductsDocument()
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/document/search-products', $this->request);
    }

    public function tableStocksProduct($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/document/table-stocks-product/'.$id, $this->request);
    }

    public function tableWriteOfStocksProduct($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/document/table-write-of-stocks-product/'.$id, $this->request);
    }

    public function selectedNomenclatures()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/selected-nomenclatures', $this->request);
    }

    public function selectedOrdersNomenclatures()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/selected-orders-nomenclatures', $this->request);
    }

    public function selectedServices()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/selected-services', $this->request);
    }

    public function selectedWriteOfNomenclatures()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/selected-write-of-nomenclatures', $this->request);
    }

    //related
    public function getRelatedProducts($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-related-products/'. $id, $this->request);
    }

    public function getProductsInRelatedOrAnalog($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-products-in-related-or-analog/'. $id, $this->request);
    }

    public function getProductsInRelatedOrAnalogAll()
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-products-in-related-or-analog-all', $this->request);
    }

    public function getTableRelatedProducts($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-table-related-products/'. $id, $this->request);
    }

    public function createRelatedProducts($id)
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/create-related-products/'. $id, $this->request);
    }

    public function deleteRelatedProducts($id)
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/delete-related-products/'. $id, $this->request);
    }

    public function createAnalogProducts($id)
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/create-analog-products/'. $id, $this->request);
    }

    public function deleteAnalogProducts($id)
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/delete-analog-products/'. $id, $this->request);
    }

//    public function updateOrCreateAnalogProducts($id)
//    {
//        return $this->performRequestForm('POST','api/v1/products/nomenclatures/update-or-create-analog-products/'. $id, $this->request);
//    }

    public function selectedRelatedOrAnalogNomenclatures()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/selected-related-or-analog-nomenclatures', $this->request);
    }

    public function selectedRelatedNomenclaturesInNomenclature()
    {
        return $this->performRequestForm('POST','api/v1/products/nomenclatures/selected-related_nomenclatures-in-nomenclature', $this->request);
    }

    public function getTableAnalogProducts($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-table-analog-products/'. $id, $this->request);
    }

    public function getTableRelatedProduct($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/related/table-product/'. $id, $this->request);
    }

    public function indexSelectRelatedProducts($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-select-related-products/'. $id, $this->request);
    }

    public function indexSelectRelatedProductsAll()
    {
        return $this->performRequestQuery('GET','api/v1/products/nomenclatures/get-select-related-products-all', $this->request);
    }

    public function updateRelatedProductsHeader()
    {
        return $this->performRequestForm('PUT','api/v1/products/nomenclatures/related-product/headers', $this->request);
    }

    public function updateSelectRelatedProductsHeader()
    {
        return $this->performRequestForm('PUT','api/v1/products/nomenclatures/select-related-product/headers', $this->request);
    }

    //price
    public function getNomenclatureCrmPrices()
    {
        return $this->performRequestQuery('GET','api/v1/products/prices/get-nomenclature-crm-prices', $this->request);
    }

    public function getNomenclaturePrices()
    {
        return $this->performRequestQuery('GET','api/v1/products/prices/get-nomenclature-prices', $this->request);
    }

    public function getNomenclaturePrice($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/prices/get-nomenclature-price/'. $id, $this->request);
    }

    public function updateNomenclaturePriceHeader()
    {
        return $this->performRequestForm('PUT','api/v1/products/prices/nomenclature-prices/headers', $this->request);
    }

    public function deletePrices()
    {
        return $this->performRequestForm('POST','api/v1/products/prices/nomenclatures/deletePrices', $this->request);
    }

    public function getTableSettingPrices()
    {
        return $this->performRequestForm('POST','api/v1/products/prices/table-setting-prices', $this->request);
    }

    public function storeSettingPrices()
    {
        return $this->performRequestForm('POST','api/v1/products/prices/store-setting-prices', $this->request);
    }
}
