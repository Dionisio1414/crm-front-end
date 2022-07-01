<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix'     => 'v1'
    ], function () {
    Route::prefix('file_manager')->group(function () {
        Route::resource('file', 'FileManagerController');
        Route::get('list', 'FileManagerController@list');
        Route::post('delete', 'FileManagerController@delete');
    });
});
