<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix' => 'v1'
    ], function () {
    Route::prefix('leads')->group(function () {
        Route::get('table',   'LeadsController@getTable');
        Route::get('failure-table',   'LeadsController@getFailureTable');
        Route::get('store-order-and-customer/{id}',   'LeadsController@storeOrderAndCustomer');

        Route::put('headers', 'LeadsController@updateHeader');
        Route::post('toArchive', 'LeadsController@toArchive');
        Route::post('toFailure', 'LeadsController@toFailure');
        Route::post('outFailure', 'LeadsController@outFailure');

        Route::resource('list', 'LeadsController');

        /* Validations */
        Route::get('store-async-validations', 'LeadsController@storeAsyncValidations');
        Route::get('update-async-validations', 'LeadsController@updateAsyncValidations');
    });
});
