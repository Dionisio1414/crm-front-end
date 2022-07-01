<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix'     => 'v1'
    ], function () {
    Route::prefix('products')->group(function () {
        Route::put('characteristics/updateColorCharacteristics', 'CharacteristicController@updateColor');
        Route::put('characteristics/updateSizeCharacteristics', 'CharacteristicController@updateSize');
        Route::post('characteristics/sortCharacteristics', 'CharacteristicController@sortCharacteristics');
        Route::get('characteristics/editColorCharacteristics', 'CharacteristicController@editColor');
        Route::get('characteristics/editSizeCharacteristics', 'CharacteristicController@editSize');
        Route::get('characteristics/{id}/characteristics-category', 'CharacteristicController@getCharacteristicsCategory');

        Route::post('characteristics/{id}/add-characteristic-value', 'CharacteristicController@addCharacteristicValue');
        Route::post('characteristics/add-characteristic-color-value', 'CharacteristicController@addCharacteristicColorValue');
        Route::resource('characteristics', 'CharacteristicController');
        Route::post('characteristics/toArchive', 'CharacteristicController@toArchive');

    });
});
