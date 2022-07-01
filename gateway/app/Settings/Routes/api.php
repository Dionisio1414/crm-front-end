<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'domain'     => '{domain}',
        'middleware' => 'auth.company:api',
        'prefix'     => 'v1'
    ], function () {
    Route::prefix('settings')->group(function () {
        Route::resources([
            '/main'       => 'SettingsController'
        ]);
    });
});
