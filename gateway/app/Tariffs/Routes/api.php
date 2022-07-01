<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'domain'     => '{domain}',
        'middleware' => 'auth.company:api',
        'prefix'     => 'v1'
    ], function () {
    Route::prefix('tariffs')->group(function () {
        Route::resource('/', 'TariffController');
        Route::post('calculationTariffs', 'TariffController@calculationTariffs');

        Route::post('subscription/pay', 'SubscriptionController@pay');
        Route::get('subscription/active', 'SubscriptionController@active');

        Route::resource('steps', 'StepController');
    });
});

/* Admin Route */
Route::group(
    [
        'middleware' => 'auth.admin:api',
        'prefix'     => 'v1/admin'
    ], function () {
    Route::prefix('tariffs')->group(function () {
        Route::get('detail', 'TariffController@detail');
        Route::resource('/', 'TariffController');
    });
});
