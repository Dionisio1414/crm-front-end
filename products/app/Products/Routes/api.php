<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix'     => 'v1'
    ], function () {
    Route::prefix('products')->group(function () {
        Route::get('/', 'ProductsController@index')->name('products');
    });
});
