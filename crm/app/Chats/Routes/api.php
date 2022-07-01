<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix'     => 'v1'
    ], function () {
    Route::prefix('chats')->group(function () {
          //Route::resource('chats', 'ChatController');
            Route::post('store-private-chat', 'ChatController@storePrivateChat');
            Route::post('store-group-chat', 'ChatController@storeGroupChat');

            Route::post('send-message', 'ChatController@sendMessage');
            Route::put('update-message/{id}', 'ChatController@updateMessage');
            Route::get('get-chats', 'ChatController@getChats');
            Route::get('get-chat/{id}', 'ChatController@getChat');
            Route::post('read-messages', 'ChatController@readMessages');
            Route::put('like-message/{id}', 'ChatController@likeMessage');
            Route::put('update-chat-options/{id}', 'ChatController@updateChatOptions');
            Route::put('update-chat/{id}', 'ChatController@updateChat');
            Route::put('add-users-to-chat/{id}', 'ChatController@addUsersToChat');
            Route::get('get-content-chat/{id}', 'ChatController@getContentChat');
            Route::get('get-content-user/{id}', 'ChatController@getContentUser');

 //           Route::get('search-chats', 'ChatController@searchChats');
    });
});
