<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

//Route::get('v1/chat/test', function (Request $request) {
//
//    $user = \App\Users\Model\User::find(env('WEBSOCKET_TEST_USER_ID')); /* This user id test */
//    broadcast(new \App\Events\PublicMessage('testing send public messages'));
//    broadcast(new \App\Events\PrivateMessage($user, 'Hello private user', $request->all()));
//    return "Public and Private messages sends";
//});
//
//Route::get('v1/chat/test', function (Request $request) {
//
//    $user = \App\Users\Model\User::find(env('WEBSOCKET_TEST_USER_ID')); /* This user id test */
//    broadcast(new \App\Events\PublicMessage('testing send public messages'));
//    broadcast(new \App\Events\PrivateMessage($user, 'Hello private user', $request->all()));
//    return "Public and Private messages sends";
//});


Route::prefix('v1/chat-data-in')->group(function () {
//    Route::get('store-private-chat', 'Chats\ChatDataInController@storePrivateChat');
//    Route::get('store-group-chat', 'Chats\ChatDataInController@storeGroupChat');
    Route::get('store-chat', 'Chats\ChatDataInController@storeChat');
    Route::get('send-message', 'Chats\ChatDataInController@sendMessage');
    Route::get('read-messages', 'Chats\ChatDataInController@readMessages');
    Route::get('like-message', 'Chats\ChatDataInController@likeMessage');
    Route::get('update-message', 'Chats\ChatDataInController@updateMessage');
});

Route::group(
    [
        'domain'     => '{domain}',
        'middleware' => 'auth.company:api',
        'prefix'     => 'v1'
    ], function () {
    //socket data-in
//    Route::prefix('v1/chat/chat-data-in')->group(function () {
//        Route::get('store-chat', 'Chats\ChatDataInController@storeChat');
//    });
    Route::prefix('products')->group(function () {
        Route::get('/', 'Products\ProductsController@index')->name('products');

        //categories
        Route::resource('categories', 'Products\CategoryController');
        Route::post('categories/sortCategories', 'Products\CategoryController@sortCategories');
        Route::post('categories/sortItemCategories', 'Products\CategoryController@sortItemCategories');
        Route::put('categories/{id}/visibility', 'Products\CategoryController@visibility');
        Route::post('categories/toArchive', 'Products\CategoryController@toArchive');
        Route::post('categories/store-async-validations', 'Products\CategoryController@storeAsyncValidations');
        Route::post('categories/update-async-validations', 'Products\CategoryController@updateAsyncValidations');

        //properties
        Route::resource('properties', 'Products\PropertyController');
        Route::post('properties/sortProperties', 'Products\PropertyController@sortProperties');
        Route::post('properties/toArchive', 'Products\PropertyController@toArchive');
        Route::get('properties/{id}/properties-category', 'Products\PropertyController@getPropertiesCategory');
        Route::post('properties/{id}/add-property-value', 'Products\PropertyController@addPropertyValue');
        Route::put('properties/{id}/edit-property-value', 'Products\PropertyController@editPropertyValue');

        //characteristics
        Route::put('characteristics/updateColorCharacteristics', 'Products\CharacteristicController@updateColor');
        Route::put('characteristics/updateSizeCharacteristics', 'Products\CharacteristicController@updateSize');
        Route::post('characteristics/sortCharacteristics', 'Products\CharacteristicController@sortCharacteristics');
        Route::get('characteristics/editColorCharacteristics', 'Products\CharacteristicController@editColor');
        Route::get('characteristics/editSizeCharacteristics', 'Products\CharacteristicController@editSize');
        Route::get('characteristics/{id}/characteristics-category', 'Products\CharacteristicController@getCharacteristicsCategory');
        Route::post('characteristics/{id}/add-characteristic-value', 'Products\CharacteristicController@addCharacteristicValue');
        Route::post('characteristics/add-characteristic-color-value', 'Products\CharacteristicController@addCharacteristicColorValue');
        Route::resource('characteristics', 'Products\CharacteristicController');
        Route::post('characteristics/toArchive', 'Products\CharacteristicController@toArchive');


        //nomenclatures
        Route::get('nomenclatures/get-products/{id}', 'Products\NomenclatureController@indexProducts');
        Route::get('nomenclatures/get-products-all', 'Products\NomenclatureController@indexProductsAll');
        Route::post('nomenclatures/store-product', 'Products\NomenclatureController@storeProduct');
        Route::post('nomenclatures/store-group-product', 'Products\NomenclatureController@storeGroupProduct');
        Route::post('nomenclatures/store-groups-product/{id}', 'Products\NomenclatureController@storeGroupsProduct');
        Route::put('nomenclatures/update-product/{id}', 'Products\NomenclatureController@updateProduct');
        Route::get('nomenclatures/get-product/{id}', 'Products\NomenclatureController@showProduct');

        Route::get('nomenclatures/get-groups-nomenclatures/{id}', 'Products\NomenclatureController@getGroupsNomenclatures');

        Route::put('nomenclatures/product/headers', 'Products\NomenclatureController@updateProductsHeader');
        Route::put('nomenclatures/change-min_price-nomenclature/{id}', 'Products\NomenclatureController@changeMinPriceNomenclature');
        Route::put('nomenclatures/change-price-nomenclature', 'Products\NomenclatureController@changePriceNomenclature');
        Route::put('nomenclatures/change-price-nomenclature-history', 'Products\NomenclatureController@changePriceNomenclatureHistory');
        Route::post('nomenclatures/choose-price-nomenclature', 'Products\NomenclatureController@choosePriceNomenclature');
        //validation
        Route::post('nomenclatures/store-async-products-validations', 'Products\NomenclatureController@storeAsyncProductsValidations');
        Route::post('nomenclatures/update-async-products-validations', 'Products\NomenclatureController@updateAsyncProductsValidations');
        Route::post('nomenclatures/store-async-services-validations', 'Products\NomenclatureController@storeAsyncServicesValidations');
        Route::post('nomenclatures/update-async-services-validations', 'Products\NomenclatureController@updateAsyncServicesValidations');

        //nomenclatures_nomenclatures
        Route::get('nomenclatures/get-not-actual-nomenclatures/{id}', 'Products\NomenclatureController@indexNotActualNomenclatures');
        Route::get('nomenclatures/get-not-actual-nomenclatures-all', 'Products\NomenclatureController@indexNotActualNomenclaturesAll');
        Route::put('nomenclatures/nomenclature/headers', 'Products\NomenclatureController@updateNomenclaturesHeader');

        //active nomenclatures
        Route::post('nomenclatures/toVisibility', 'Products\NomenclatureController@toVisibility');
        Route::post('nomenclatures/outVisibility', 'Products\NomenclatureController@outVisibility');
        Route::post('nomenclatures/toArchive', 'Products\NomenclatureController@toArchive');
        Route::post('nomenclatures/outArchive', 'Products\NomenclatureController@outArchive');
        Route::post('nomenclatures/toActual', 'Products\NomenclatureController@toActual');
        Route::post('nomenclatures/outActual', 'Products\NomenclatureController@outActual');
        Route::put('nomenclatures/move-products/{id}', 'Products\NomenclatureController@moveProducts');
        Route::post('nomenclatures/generate', 'Products\NomenclatureController@generateProducts');
        //service
        Route::get('nomenclatures/get-services/{id}', 'Products\NomenclatureController@indexServices');
        Route::get('nomenclatures/get-services-all', 'Products\NomenclatureController@indexServicesAll');
        Route::post('nomenclatures/store-service', 'Products\NomenclatureController@storeService');
        Route::get('nomenclatures/get-service/{id}', 'Products\NomenclatureController@showService');
        Route::put('nomenclatures/update-service/{id}', 'Products\NomenclatureController@updateService');
        Route::put('nomenclatures/service/headers', 'Products\NomenclatureController@updateServicesHeader');
        Route::put('nomenclatures/move-services/{id}', 'Products\NomenclatureController@moveServices');
        //kit
        Route::get('nomenclatures/get-kits/{id}', 'Products\NomenclatureController@indexKits');
        Route::get('nomenclatures/get-kits-all', 'Products\NomenclatureController@indexKitsAll');
        Route::post('nomenclatures/store-kit', 'Products\NomenclatureController@storeKit');
        Route::get('nomenclatures/get-kit/{id}', 'Products\NomenclatureController@showKit');
        Route::put('nomenclatures/update-kit/{id}', 'Products\NomenclatureController@updateKit');
        Route::put('nomenclatures/kit/headers', 'Products\NomenclatureController@updateKitsHeader');
        Route::put('nomenclatures/move-kits/{id}', 'Products\NomenclatureController@moveKits');
        Route::post('nomenclatures/selected-kits', 'Products\NomenclatureController@selectedKits');
        Route::post('nomenclatures/selected-characteristics-kits', 'Products\NomenclatureController@selectedCharacteristicsKits');
        //analog
        Route::get('nomenclatures/get-table-analog-products/{id}', 'Products\NomenclatureController@getTableAnalogProducts');
        Route::post('nomenclatures/create-analog-products/{id}', 'Products\NomenclatureController@createAnalogProducts');
        Route::post('nomenclatures/delete-analog-products/{id}', 'Products\NomenclatureController@deleteAnalogProducts');
        //related
        //actual
        Route::get('nomenclatures/get-products-in-related-or-analog-all', 'Products\NomenclatureController@getProductsInRelatedOrAnalogAll');
        Route::get('nomenclatures/get-products-in-related-or-analog/{id}', 'Products\NomenclatureController@getProductsInRelatedOrAnalog');
        Route::get('nomenclatures/get-related-products/{id}', 'Products\NomenclatureController@getRelatedProducts');
        Route::get('nomenclatures/get-table-related-products/{id}', 'Products\NomenclatureController@getTableRelatedProducts');
        Route::post('nomenclatures/create-related-products/{id}', 'Products\NomenclatureController@createRelatedProducts');
        Route::post('nomenclatures/delete-related-products/{id}', 'Products\NomenclatureController@deleteRelatedProducts');

        //Route::post('nomenclatures/update-or-create-related-products/{id}', 'Products\NomenclatureController@updateOrCreateRelatedProducts');
        Route::post('nomenclatures/selected-related-or-analog-nomenclatures', 'Products\NomenclatureController@selectedRelatedOrAnalogNomenclatures');
        Route::post('nomenclatures/selected-related_nomenclatures-in-nomenclature', 'Products\NomenclatureController@selectedRelatedNomenclaturesInNomenclature');
        Route::get('nomenclatures/related/table-product/{id}', 'Products\NomenclatureController@getTableRelatedProduct');
        Route::get('nomenclatures/get-select-related-products/{id}', 'Products\NomenclatureController@indexSelectRelatedProducts');
        Route::get('nomenclatures/get-select-related-products-all', 'Products\NomenclatureController@indexSelectRelatedProductsAll');
        Route::put('nomenclatures/related-product/headers', 'Products\NomenclatureController@updateRelatedProductsHeader');
        Route::put('nomenclatures/select-related-product/headers', 'Products\NomenclatureController@updateSelectRelatedProductsHeader');

        //documents-nomenclatures
        Route::get('nomenclatures/select-product/{id}', 'Products\NomenclatureController@selectProduct');
        Route::get('nomenclatures/get-select-products-all', 'Products\NomenclatureController@indexSelectProductsAll');
        Route::get('nomenclatures/get-select-services-all', 'Products\NomenclatureController@indexSelectServicesAll');
        Route::post('nomenclatures/selected-nomenclatures', 'Products\NomenclatureController@selectedNomenclatures');
        Route::post('nomenclatures/selected-write-of-nomenclatures', 'Products\NomenclatureController@selectedWriteOfNomenclatures');

        Route::post('nomenclatures/selected-services', 'Products\NomenclatureController@selectedServices');
        Route::post('nomenclatures/selected-orders-nomenclatures', 'Products\NomenclatureController@selectedOrdersNomenclatures');

        Route::get('nomenclatures/get-select-products/{id}', 'Products\NomenclatureController@indexSelectProducts');
        Route::get('nomenclatures/get-select-services/{id}', 'Products\NomenclatureController@indexSelectServices');
        Route::get('nomenclatures/document/search-products', 'Products\NomenclatureController@searchProductsDocument');
        Route::get('nomenclatures/search-products-document-table', 'Products\NomenclatureController@searchProductsDocumentTable');
        Route::get('nomenclatures/document/table-stocks-product/{id}', 'Products\NomenclatureController@tableStocksProduct');
        Route::get('nomenclatures/document/table-write-of-stocks-product/{id}', 'Products\NomenclatureController@tableWriteOfStocksProduct');

        //warehouses
        Route::get('warehouses/products/{id}', 'Products\WarehouseController@showProducts');
        Route::get('warehouses/products-all', 'Products\WarehouseController@showProductsAll');
        Route::get('warehouses/kits/{id}', 'Products\WarehouseController@showKits');
        Route::get('warehouses/kits-all', 'Products\WarehouseController@showKitsAll');
        Route::get('warehouses/get-warehouses', 'Products\WarehouseController@getWarehouses');
        Route::get('warehouses/get-warehouses-groups', 'Products\WarehouseController@getWarehousesGroups');

        Route::post('warehouses/create-warehouse-group', 'Products\WarehouseController@storeWarehouseGroup');
        Route::put('warehouses/update-warehouse-group/{id}', 'Products\WarehouseController@updateWarehouseGroup');
        Route::get('warehouses/get-warehouse-group/{id}', 'Products\WarehouseController@showWarehouseGroup');

        Route::post('warehouses/toArchiveWarehouse', 'Products\WarehouseController@toArchiveWarehouse');
        Route::post('warehouses/toArchiveWarehouseGroup', 'Products\WarehouseController@toArchiveWarehouseGroup');
        Route::put('warehouses/move-warehouses/{id}', 'Products\WarehouseController@moveWarehouses');
        Route::put('warehouses/choose-default-warehouse/{id}', 'Products\WarehouseController@chooseDefaultWarehouse');
        Route::get('warehouses/fill-products-stocks/{id}', 'Products\WarehouseController@fillProductsStocks');
        Route::get('warehouses/get-default-warehouse', 'Products\WarehouseController@getDefaultWarehouse');

        Route::resource('warehouses', 'Products\WarehouseController');

        //price
        Route::get('prices/get-nomenclature-prices', 'Products\NomenclatureController@getNomenclaturePrices');
        Route::put('prices/nomenclature-prices/headers', 'Products\NomenclatureController@updateNomenclaturePriceHeader');
        Route::get('prices/get-nomenclature-price/{id}', 'Products\NomenclatureController@getNomenclaturePrice');
        Route::post('prices/nomenclatures/deletePrices', 'Products\NomenclatureController@deletePrices');

        //price-crm
        Route::get('prices/get-nomenclature-crm-prices', 'Products\NomenclatureController@getNomenclatureCrmPrices');
        Route::post('prices/table-setting-prices', 'Products\NomenclatureController@getTableSettingPrices');
        Route::post('prices/store-setting-prices', 'Products\NomenclatureController@storeSettingPrices');

        //not swagger

        Route::get('nomenclatures/get-products-warehouse/{id}', 'Products\NomenclatureController@getProductsWithWarehouse');
    });

    /* Suppliers */
    Route::prefix('suppliers')->group(function () {
        Route::get('table', 'Suppliers\SuppliersController@getTable');
        Route::post('groups/toArchive', 'Suppliers\GroupsController@toArchive');
        Route::post('toArchive', 'Suppliers\SuppliersController@toArchive');
        Route::put('headers', 'Suppliers\SuppliersController@updateHeader');

        /* Validations */
        Route::post('store-async-validations', 'Suppliers\SuppliersController@storeAsyncValidations');
        Route::post('update-async-validations', 'Suppliers\SuppliersController@updateAsyncValidations');

        /* Changes Groups */
        Route::post('changeGroups', 'Suppliers\SuppliersController@changeGroups');

        Route::resource('groups', 'Suppliers\GroupsController');
        Route::resource('list', 'Suppliers\SuppliersController');
    });

    /* Directory */
    Route::prefix('directories')->group(function () {
        /* Validations */
        Route::post('companies_departments/store-async-validations', 'Directories\CompaniesDepartmentsController@storeAsyncValidations');
        Route::post('companies_departments/update-async-validations', 'Directories\CompaniesDepartmentsController@updateAsyncValidations');
        Route::post('organizations/store-async-validations', 'Directories\OrganizationsController@storeAsyncValidations');
        Route::post('organizations/update-async-validations', 'Directories\OrganizationsController@updateAsyncValidations');

        /* Organizations */
        Route::resource('organizations', 'Directories\OrganizationsController');
        Route::resource('companies_departments', 'Directories\CompaniesDepartmentsController');

        Route::get('{directory}/table', 'Directories\DirectoriesController@getTable');
        Route::post('{directory}/toArchive', 'Directories\DirectoriesController@toArchive');
        Route::put('{directory}/headers', 'Directories\DirectoriesController@updateHeader');
        Route::post('{directory}/headers/toDefault', 'Directories\DirectoriesController@defaultHeader');
        Route::resource('{directory}/list', 'Directories\DirectoriesController');

        Route::post('{directory}/store-async-validations', 'Directories\DirectoriesController@storeAsyncValidations');
        Route::post('{directory}/update-async-validations', 'Directories\DirectoriesController@updateAsyncValidations');
    });

    /* Filters */
    Route::prefix('filters')->group(function () {
        Route::resource('{filter}/', 'Filters\FiltersController');
    });

    /* Reports */
    Route::prefix('reports')->group(function () {
        Route::resource('worktime', 'Reports\WorkTimeController');
    });

    /* Documents */
    Route::prefix('documents')->group(function () {
        Route::get('get-warehouse-documents/{id}', 'Documents\DocumentsController@getWarehouseDocuments');
        Route::get('get-warehouse-documents-all', 'Documents\DocumentsController@getWarehouseDocumentsAll');
        Route::get('get-documents-all', 'Documents\DocumentsController@getDocumentsAll');

        Route::put('document-nomenclatures-headers', 'Documents\DocumentsController@updateDocumentNomenclaturesHeader');
        Route::put('document-warehouses-headers', 'Documents\DocumentsController@updateDocumentWarehousesHeader');
        Route::put('document-all-headers', 'Documents\DocumentsController@updateDocumentAllHeader');

        Route::post('toArchive', 'Documents\DocumentsController@toArchive');

        //purchases
        Route::get('purchases/get-activity-purchases', 'Documents\DocumentsController@getActivityPurchases');
        //orders
        Route::get('orders/get-shipment-orders', 'Documents\DocumentsController@getShipmentOrders');

        Route::post('{document}/store-document', 'Documents\DocumentsController@storeDocument');
        Route::post('{document}/store-capitalized-document', 'Documents\DocumentsController@storeCapitalizedDocument');
        Route::put('{document}/update-document/{id}', 'Documents\DocumentsController@updateDocument');
        Route::put('{document}/capitalized-document/{id}', 'Documents\DocumentsController@capitalizedDocument');
        Route::put('{document}/canceled-capitalized-document/{id}', 'Documents\DocumentsController@canceledCapitalizedDocument');
        Route::get('{document}/table', 'Documents\DocumentsController@getTable');
//        Route::post('{document}/toArchive', 'Directories\DirectoriesController@toArchive');
        Route::put('{document}/headers', 'Documents\DocumentsController@updateHeader');
        Route::resource('{document}/list', 'Documents\DocumentsController');
        Route::get('{document}/get-document/{id}', 'Documents\DocumentsController@getDocumentTable');
        Route::get('{document}/get-select-document/{id}', 'Documents\DocumentsController@getSelectDocumentTable');
        Route::get('{document}/table', 'Documents\DocumentsController@getTable');

        //nomenclatures add checkout (orders)
        Route::post('update-nomenclatures-in-basket','Documents\DocumentsController@updateNomenclaturesInBasket');
        Route::delete('clear-basket',  'Documents\DocumentsController@clearBasket');

        Route::get('open-basket',  'Documents\DocumentsController@openBasket');
        Route::get('general-count-basket', 'Documents\DocumentsController@generalCountBasket');

        //not swagger
    });

    /* File manager */
    Route::prefix('file_manager')->group(function () {
        Route::resource('file', 'FileManager\FileManagerController');
        Route::post('delete', 'FileManager\FileManagerController@delete');
        Route::get('list', 'FileManager\FileManagerController@list');
    });

    /* Suppliers */
    Route::prefix('leads')->group(function () {
        Route::get('table',   'Leads\LeadsController@getTable');
        Route::get('failure-table',   'Leads\LeadsController@getFailureTable');
        Route::get('store-order-and-customer',   'Leads\LeadsController@storeOrderAndCustomer');

        Route::put('headers', 'Leads\LeadsController@updateHeader');
        Route::post('toArchive', 'Leads\LeadsController@toArchive');
        Route::post('toFailure', 'Leads\LeadsController@toFailure');
        Route::post('outFailure', 'Leads\LeadsController@outFailure');

        Route::resource('list', 'Leads\LeadsController');
    });

    /* Chats */
    Route::prefix('chats')->group(function () {

        Route::post('store-private-chat', 'Chats\ChatController@storePrivateChat');
        Route::post('store-group-chat', 'Chats\ChatController@storeGroupChat');
        Route::post('send-message', 'Chats\ChatController@sendMessage');
        Route::put('update-message/{id}', 'Chats\ChatController@updateMessage');
        Route::get('get-chats',   'Chats\ChatController@getChats');
        Route::get('get-chat/{id}', 'Chats\ChatController@getChat');
        Route::post('read-messages', 'Chats\ChatController@readMessages');
        Route::put('like-message/{id}', 'Chats\ChatController@likeMessage');
        Route::put('update-chat-options/{id}', 'Chats\ChatController@updateChatOptions');
        Route::put('update-chat/{id}', 'Chats\ChatController@updateChat');
        Route::put('add-users-to-chat/{id}', 'Chats\ChatController@addUsersToChat');

        Route::get('get-content-chat/{id}', 'Chats\ChatController@getContentChat');
        Route::get('get-content-user/{id}', 'Chats\ChatController@getContentUser');

//        Route::get('search-chats', 'Chats\ChatController@searchChats');
    });
});
