<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix' => 'v1'
    ], function () {
    Route::prefix('filters')->group(function () {
        $directories = [
            'counterparties_contracts',
        ];

        foreach ($directories as $directory){
            if(strripos($directory, '_') === false){
                $directoryController = ucfirst($directory);
            }else{
                $directoryArray = explode('_', $directory);
                $directoryController = ucfirst($directoryArray[0]) .  '\\' . ucfirst($directoryArray[1]);
            }

            Route::prefix($directory)->group(function () use ($directoryController){
                Route::resource('/', $directoryController . 'Controller');
            });
        }
    });
});
