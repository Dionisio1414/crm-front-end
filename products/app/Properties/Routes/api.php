<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix'     => 'v1'
    ], function () {
    Route::prefix('products')->group(function () {
          Route::resource('properties', 'PropertyController');
          Route::post('properties/sortProperties', 'PropertyController@sortProperties');
          Route::post('properties/toArchive', 'PropertyController@toArchive');
          Route::get('properties/{id}/properties-category', 'PropertyController@getPropertiesCategory');
          Route::post('properties/{id}/add-property-value', 'PropertyController@addPropertyValue');
          Route::put('properties/{id}/edit-property-value', 'PropertyController@editPropertyValue');
//        Route::get('properties', 'PropertyController@index')->name('properties');
//        Route::post('properties/store', 'PropertyController@store')->name('properties.store');
//        Route::patch('properties/{id}', 'PropertyController@update')->name('properties.update');
    });
});
