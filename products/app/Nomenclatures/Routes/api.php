<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix'     => 'v1'
    ], function () {
    Route::prefix('products')->group(function () {

        Route::get('nomenclatures/get-products/{id}', 'NomenclatureController@indexProducts');
        Route::get('nomenclatures/get-products-all', 'NomenclatureController@indexProductsAll');
        Route::post('nomenclatures/store-product', 'NomenclatureController@storeProduct');
        Route::post('nomenclatures/store-group-product', 'NomenclatureController@storeGroupProduct');

        Route::post('nomenclatures/store-groups-product/{id}', 'NomenclatureController@storeGroupsProduct');
        Route::get('nomenclatures/get-product/{id}', 'NomenclatureController@showProduct');
        Route::put('nomenclatures/update-product/{id}', 'NomenclatureController@updateProduct');
        Route::put('nomenclatures/product/headers', 'NomenclatureController@updateProductsHeader');
        Route::get('nomenclatures/get-groups-nomenclatures/{id}', 'NomenclatureController@getGroupsNomenclatures');
        //nomenclatures
        Route::get('nomenclatures/get-not-actual-nomenclatures/{id}', 'NomenclatureController@indexNotActualNomenclatures');
        Route::get('nomenclatures/get-not-actual-nomenclatures-all', 'NomenclatureController@indexNotActualNomenclaturesAll');
        Route::put('nomenclatures/nomenclature/headers', 'NomenclatureController@updateNomenclaturesHeader');
        //kit
        Route::get('nomenclatures/get-kits/{id}', 'NomenclatureController@indexKits');
        Route::get('nomenclatures/get-kits-all', 'NomenclatureController@indexKitsAll');
        Route::post('nomenclatures/store-kit', 'NomenclatureController@storeKit');
        Route::get('nomenclatures/get-kit/{id}', 'NomenclatureController@showKit');
        Route::put('nomenclatures/update-kit/{id}', 'NomenclatureController@updateKit');
        Route::put('nomenclatures/kit/headers', 'NomenclatureController@updateKitsHeader');
        Route::put('nomenclatures/move-kits/{id}', 'NomenclatureController@moveKits');

        Route::post('nomenclatures/selected-kits', 'NomenclatureController@selectedKits');
        Route::post('nomenclatures/selected-characteristics-kits', 'NomenclatureController@selectedCharacteristicsKits');
        //service
        Route::get('nomenclatures/get-services/{id}', 'NomenclatureController@indexServices');
        Route::get('nomenclatures/get-services-all', 'NomenclatureController@indexServicesAll');
        Route::post('nomenclatures/store-service', 'NomenclatureController@storeService');
        Route::get('nomenclatures/get-service/{id}', 'NomenclatureController@showService');
        Route::put('nomenclatures/update-service/{id}', 'NomenclatureController@updateService');
        Route::put('nomenclatures/service/headers', 'NomenclatureController@updateServicesHeader');
        //Route::put('nomenclatures/service/headers', 'NomenclatureController@updateServicesHeader');
        Route::put('nomenclatures/move-services/{id}', 'NomenclatureController@moveServices');
        //active
        Route::post('nomenclatures/toVisibility', 'NomenclatureController@toVisibility');
        Route::post('nomenclatures/outVisibility', 'NomenclatureController@outVisibility');
        Route::post('nomenclatures/toArchive', 'NomenclatureController@toArchive');
        Route::post('nomenclatures/outArchive', 'NomenclatureController@outArchive');
        Route::post('nomenclatures/toActual', 'NomenclatureController@toActual');
        Route::post('nomenclatures/outActual', 'NomenclatureController@outActual');
        Route::put('nomenclatures/move-products/{id}', 'NomenclatureController@moveProducts');
        Route::put('nomenclatures/move-services/{id}', 'NomenclatureController@moveServices');
        Route::put('nomenclatures/change-min_price-nomenclature/{id}', 'NomenclatureController@changeMinPriceNomenclature');
        //????//
        Route::put('nomenclatures/change-price-nomenclature', 'NomenclatureController@changePriceNomenclature');
        Route::put('nomenclatures/change-price-nomenclature-history', 'NomenclatureController@changePriceNomenclatureHistory');
        Route::post('nomenclatures/choose-price-nomenclature', 'NomenclatureController@choosePriceNomenclature');

        //generate products
        Route::post('nomenclatures/generate', 'GenerateController@generate');
        //warehouse
        Route::get('nomenclatures/get-products-warehouse/{id}', 'NomenclatureController@getProductsWithWarehouse');
        //related

        //analog-or-related
        Route::get('nomenclatures/get-products-in-related-or-analog-all', 'NomenclatureController@getProductsInRelatedOrAnalogAll');
        Route::get('nomenclatures/get-products-in-related-or-analog/{id}', 'NomenclatureController@getProductsInRelatedOrAnalog');
        //
        Route::get('nomenclatures/get-related-products/{id}', 'NomenclatureController@getRelatedProducts');
        Route::get('nomenclatures/get-table-related-products/{id}', 'NomenclatureController@getTableRelatedProducts');
        //Route::post('nomenclatures/update-or-create-related-products/{id}', 'NomenclatureController@updateOrCreateRelatedProducts');
        Route::post('nomenclatures/create-related-products/{id}', 'NomenclatureController@createRelatedProducts');
        Route::post('nomenclatures/delete-related-products/{id}', 'NomenclatureController@deleteRelatedProducts');
        //universal related product
        Route::get('nomenclatures/related/table-product/{id}', 'NomenclatureController@getTableRelatedProduct');

        //related products in nomenclatures
        Route::get('nomenclatures/get-select-related-products/{id}', 'NomenclatureController@indexSelectRelatedProducts');
        Route::get('nomenclatures/get-select-related-products-all', 'NomenclatureController@indexSelectRelatedProductsAll');
        Route::put('nomenclatures/related-product/headers', 'NomenclatureController@updateRelatedProductsHeader');
        Route::put('nomenclatures/select-related-product/headers', 'NomenclatureController@updateSelectRelatedProductsHeader');

        //analog-or-related
        Route::post('nomenclatures/selected-related-or-analog-nomenclatures', 'NomenclatureController@selectedRelatedOrAnalogNomenclatures');
        Route::post('nomenclatures/selected-related_nomenclatures-in-nomenclature', 'NomenclatureController@selectedRelatedNomenclaturesInNomenclature');
        //Route::get('nomenclatures/get-related-products-all', 'NomenclatureController@getRelatedProductsAll');

        //analog
        Route::get('nomenclatures/get-table-analog-products/{id}', 'NomenclatureController@getTableAnalogProducts');
        Route::post('nomenclatures/create-analog-products/{id}', 'NomenclatureController@createAnalogProducts');
        Route::post('nomenclatures/delete-analog-products/{id}', 'NomenclatureController@deleteAnalogProducts');
        //Route::post('nomenclatures/update-or-create-analog-products/{id}', 'NomenclatureController@updateOrCreateAnalogProducts');

        //documents
        Route::get('nomenclatures/document/search-products', 'NomenclatureController@searchProductsDocument');
        Route::get('nomenclatures/document/table-stocks-product/{id}', 'NomenclatureController@tableStocksProduct');
        Route::get('nomenclatures/document/table-write-of-stocks-product/{id}', 'NomenclatureController@tableWriteOfStocksProduct');

        Route::get('nomenclatures/select-product/{id}', 'NomenclatureController@selectProduct');
        Route::get('nomenclatures/search-products-document-table', 'NomenclatureController@searchProductsDocumentTable');

        Route::get('nomenclatures/get-select-products/{id}', 'NomenclatureController@indexSelectProducts');
        Route::get('nomenclatures/get-select-products-all', 'NomenclatureController@indexSelectProductsAll');

        Route::get('nomenclatures/get-select-services/{id}', 'NomenclatureController@indexSelectServices');
        Route::get('nomenclatures/get-select-services-all', 'NomenclatureController@indexSelectServicesAll');

        Route::post('nomenclatures/selected-nomenclatures', 'NomenclatureController@selectedNomenclatures');
        Route::post('nomenclatures/selected-services', 'NomenclatureController@selectedServices');
        Route::post('nomenclatures/selected-orders-nomenclatures', 'NomenclatureController@selectedOrdersNomenclatures');
        Route::post('nomenclatures/selected-write-of-nomenclatures', 'NomenclatureController@selectedWriteOfNomenclatures');

        //validations
        Route::post('nomenclatures/store-async-products-validations', 'NomenclatureController@storeAsyncProductsValidations');
        Route::post('nomenclatures/update-async-products-validations', 'NomenclatureController@updateAsyncProductsValidations');
        Route::post('nomenclatures/store-async-services-validations', 'NomenclatureController@storeAsyncServicesValidations');
        Route::post('nomenclatures/update-async-services-validations', 'NomenclatureController@updateAsyncServicesValidations');
        Route::resource('nomenclatures', 'NomenclatureController');

        //price
        Route::get('prices/get-nomenclature-prices', 'PriceController@getNomenclaturePrices');
        Route::get('prices/get-nomenclature-price/{id}', 'PriceController@getNomenclaturePrice');
        //Route::get('prices/get-nomenclature-prices-table', 'PriceController@getNomenclaturePricesTable');
        Route::put('prices/nomenclature-prices/headers', 'PriceController@updateNomenclaturePriceHeader');
        Route::post('prices/nomenclatures/deletePrices', 'PriceController@deletePrices');

        //price-crm
        Route::get('prices/get-nomenclature-crm-prices', 'PriceController@getNomenclatureCrmPrices');
        Route::post('prices/table-setting-prices', 'PriceController@getTableSettingPrices');
        Route::post('prices/store-setting-prices', 'PriceController@storeSettingPrices');
    });
});
