<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'domain'     => '{domain}',
        'middleware' => 'auth.company:api',
        'prefix'     => 'v1'
    ], function () {
    Route::prefix('directories')->group(function () {
        Route::resources([
            'languages'       => 'LanguageController'
        ]);

        Route::prefix('core')->group(function () {
            Route::resource('{directory}/list', 'DirectoriesController');
        });
    });
});

/* Admin Route */
Route::group(
    [
        'middleware' => 'auth.admin:api',
        'prefix'     => 'v1/admin'
    ], function () {
    Route::prefix('directories')->group(function () {
        Route::resources([
            'languages'       => 'LanguageController'
        ]);

        Route::prefix('core')->group(function () {
            Route::resource('{directory}/list', 'AdminDirectoriesController');
        });
    });
});
