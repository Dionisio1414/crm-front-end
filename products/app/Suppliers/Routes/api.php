<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix' => 'v1'
    ], function () {
    Route::prefix('suppliers')->group(function () {
        Route::get('table',   'SuppliersController@getTable');
        Route::post('groups/toArchive', 'SuppliersGroupsController@toArchive');
        Route::put('headers', 'SuppliersController@updateHeader');
        Route::post('toArchive', 'SuppliersController@toArchive');

        /* Validations */
        Route::get('store-async-validations', 'SuppliersController@storeAsyncValidations');
        Route::get('update-async-validations', 'SuppliersController@updateAsyncValidations');

        /* Changes Groups */
        Route::post('changeGroups', 'SuppliersController@changeGroups');

        Route::resource('groups', 'SuppliersGroupsController');
        Route::resource('list', 'SuppliersController');
    });
});
