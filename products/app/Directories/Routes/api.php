<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix'     => 'v1'
    ], function () {
    Route::prefix('directories')->group(function () {

        $directories = [
            'units',
            'cities',
            'countries',
            'departments',
            'counterparties_cancellations',
            'counterparties_returns',
            'positions',
            'employee_documents',
            'prices_types',
            'currencies',
            'organizations',
            'companies_departments',
            'counterparties',
            'counterparties_contracts',
            'individuals_documents',
            'employees',
            'users',
            'individuals',
            'regions'
        ];

        foreach ($directories as $directory){
            if(strripos($directory, '_') === false){
                $directoryController = ucfirst($directory);
            }else{
                $directoryArray = explode('_', $directory);
                $directoryController = ucfirst($directoryArray[0]) .  '\\' . ucfirst($directoryArray[1]) ;
            }

            Route::prefix($directory)->group(function () use ($directoryController){
                Route::get('table',   $directoryController . 'Controller@getTable');
                Route::put('headers', $directoryController . 'Controller@updateHeader');
                Route::post('toArchive', $directoryController . 'Controller@toArchive');
                Route::resource('list', $directoryController . 'Controller');

                Route::get('store-async-validations', $directoryController . 'Controller@storeAsyncValidations');
                Route::get('update-async-validations', $directoryController . 'Controller@updateAsyncValidations');
            });
        }

    });
});
