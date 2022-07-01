<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix' => 'v1'
    ], function () {
    Route::prefix('products')->group(function () {
        //Route::get('warehouses/products', 'WarehouseController@indexProducts');
        //products
        Route::get('warehouses/products/{id}', 'WarehouseController@showProducts');
        Route::get('warehouses/products-all', 'WarehouseController@showProductsAll');

        //kits
        Route::get('warehouses/kits/{id}', 'WarehouseController@showKits');
        Route::get('warehouses/kits-all', 'WarehouseController@showKitsAll');
        //
        Route::get('warehouses/fill-products-stocks/{id}', 'WarehouseController@fillProductsStocks');

        Route::get('warehouses/get-warehouses', 'WarehouseController@getWarehouses');
        Route::get('warehouses/get-warehouses-groups', 'WarehouseController@getWarehousesGroups');
        Route::get('warehouses/get-warehouse-group/{id}', 'WarehouseController@showWarehouseGroup');

        Route::put('warehouses/update-warehouse-group/{id}', 'WarehouseController@updateWarehouseGroup');
        Route::post('warehouses/create-warehouse-group', 'WarehouseController@storeWarehouseGroup');

        Route::post('warehouses/toArchiveWarehouse', 'WarehouseController@toArchiveWarehouse');
        Route::post('warehouses/toArchiveWarehouseGroup', 'WarehouseController@toArchiveWarehouseGroup');
        Route::put('warehouses/move-warehouses/{id}', 'WarehouseController@moveWarehouses');
        Route::put('warehouses/choose-default-warehouse/{id}', 'WarehouseController@chooseDefaultWarehouse');

        Route::get('warehouses/get-default-warehouse', 'WarehouseController@getDefaultWarehouse');

        Route::get('warehouses/get-document/{id}', 'WarehouseController@getDocumentReceiptStockWarehouse');
        Route::resource('warehouses', 'WarehouseController');

//          Route::post('properties/sortProperties', 'PropertyController@sortProperties');
//          Route::post('properties/toArchive', 'PropertyController@toArchive');
//          Route::get('properties/{id}/properties-category', 'PropertyController@getPropertiesCategory');
//          Route::post('properties/{id}/add-property-value', 'PropertyController@addPropertyValue');
//          Route::put('properties/{id}/edit-property-value', 'PropertyController@editPropertyValue');
//        Route::get('properties', 'PropertyController@index')->name('properties');
//        Route::post('properties/store', 'PropertyController@store')->name('properties.store');
//        Route::patch('properties/{id}', 'PropertyController@update')->name('properties.update');
    });
});
