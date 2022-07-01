<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix' => 'v1'
    ], function () {
    Route::prefix('documents')->group(function () {

        Route::get('get-warehouse-documents/{id}', 'DocumentController@getWarehouseDocuments');
        Route::get('get-warehouse-documents-all', 'DocumentController@getWarehouseDocumentsAll');

        Route::get('get-documents-all', 'DocumentController@getDocumentsAll');
        Route::put('document-nomenclatures-headers', 'DocumentController@updateDocumentNomenclaturesHeader');
        Route::put('document-warehouses-headers', 'DocumentController@updateDocumentWarehousesHeader');
        Route::put('document-all-headers', 'DocumentController@updateDocumentAllHeader');

        Route::post('toArchive', 'DocumentController@toArchive');

        //purchases
        Route::get('purchases/get-activity-purchases', 'PurchasesController@getActivityPurchases');
        //orders
        Route::get('orders/get-shipment-orders', 'OrdersController@getShipmentOrders');

        $directories = [
            'receipt_stocks',
            'write_off_stocks',
            'transfer_stocks',
            'purchases',
            'orders',
        ];

        foreach ($directories as $directory) {
            if (strripos($directory, '_') === false) {
                $directoryController = ucfirst($directory);
            } else {
                $directoryArray = explode('_', $directory);
                $directoryController = '';
                foreach ($directoryArray as $direct){
                    $directoryController = $directoryController . ucfirst($direct);
                }
                //$directoryController = ucfirst($directoryArray[0]) . ucfirst($directoryArray[1]);
            }

            Route::prefix($directory)->group(function () use ($directoryController) {
                Route::get('get-document/{id}', $directoryController . 'Controller@getDocumentTable');
                //Route::get('get-select-document/{id}', $directoryController . 'Controller@getSelectDocumentTable'); //front not used

                Route::post('store-document', $directoryController . 'Controller@storeDocument');
                Route::post('store-capitalized-document', $directoryController . 'Controller@storeCapitalizedDocument');

                Route::put('update-document/{id}', $directoryController . 'Controller@updateDocument');
                Route::put('capitalized-document/{id}', $directoryController . 'Controller@capitalizedDocument');
                Route::put('canceled-capitalized-document/{id}', $directoryController . 'Controller@canceledCapitalizedDocument');

                //nomenclatures add checkout (orders)
                Route::post('update-nomenclatures-in-basket', $directoryController . 'Controller@updateNomenclaturesInBasket');
                Route::delete('clear-basket', $directoryController . 'Controller@clearBasket');
                Route::get('open-basket', $directoryController . 'Controller@openBasket');
                Route::get('general-count-basket', $directoryController . 'Controller@generalCountBasket');

                //directories
                Route::get('table', $directoryController . 'Controller@getTable');
                Route::put('headers', $directoryController . 'Controller@updateHeader');
                Route::post('toArchive', $directoryController . 'Controller@toArchive');
                Route::resource('list', $directoryController . 'Controller');
            });
        }
    });
});
