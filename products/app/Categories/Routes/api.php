<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix'     => 'v1'
    ], function () {
    Route::prefix('products')->group(function () {
        Route::resource('categories', 'CategoryController');
        Route::post('categories/sortCategories', 'CategoryController@sortCategories');
        Route::post('categories/sortItemCategories', 'CategoryController@sortItemCategories');
        Route::put('categories/{id}/visibility', 'CategoryController@visibility');

        Route::post('categories/toArchive', 'CategoryController@toArchive');
        //Route::post('categories/check-nomenclatures-in-categories', 'NomenclatureController@checkNomenclaturesInCategories');

        Route::post('categories/store-async-validations', 'CategoryController@storeAsyncValidations');
        Route::post('categories/update-async-validations', 'CategoryController@updateAsyncValidations');


//        Route::get('categories', 'CategoryController@index')->name('categories');
//        Route::get('categories/{id}', 'CategoryController@show')->name('categories.show');
    });
});
