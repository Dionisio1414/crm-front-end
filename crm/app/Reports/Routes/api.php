<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix'     => 'v1'
    ], function () {
    Route::prefix('reports')->group(function () {

        $crms = [
            'worktime',
        ];

        foreach ($crms as $crm){
            if(strripos($crm, '_') === false){
                $crmController = ucfirst($crm);
            }else{
                $crmArray = explode('_', $crm);
                $crmController = ucfirst($crmArray[0]) .  '\\' . ucfirst($crmArray[1]) ;
            }

            Route::prefix($crm)->group(function () use ($crmController){
                Route::resource('/', $crmController . 'Controller');
            });
        }

    });
});
