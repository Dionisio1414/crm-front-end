<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1/user')->group(function() {
    /* Register or Login Email, Phone */
    Route::post('register', 'Auth\RegisterController@register')->name('user.register');

    /* Login by email and phone */
    Route::post('login', 'Auth\AuthController@login')->name('user.login');

    /* Login by email or phone, redirect by verification messages */
    Route::post('login/verify', 'Auth\AuthController@loginVerify')->name('user.login.verify');

    /* Register and Login Social */
    Route::post('register/social', 'Auth\SocialController@handleProviderCallback')->name('user.register.social');
    Route::post('login/social', 'Auth\SocialController@login')->name('user.login.social');

    /* Resend verification codes */
    Route::post('resend/verification_token', 'Auth\RegisterController@resendVerificationToken')->name('user.resend.verification_token');
    Route::post('send/invite', 'Auth\RegisterController@sendInvite')->name('user.send.invite');

    /* Validate phone */
    Route::post('phone/validate', 'Auth\RegisterController@validPhone')->name('user.phone.validate');
});

Route::group(
    [
        'domain'     => '{domain}',
        'middleware' => 'auth.company:api',
        'prefix'     => 'v1'
    ], function () {
    Route::prefix('user')->group(function () {
        Route::resources([
            'auth'       => 'User\UserController',
        ]);

        Route::prefix('password')->group(function () {
            Route::post('change', 'User\UserController@changePassword');
            Route::post('check_old_password', 'User\UserController@checkOldPassword');
        });

        Route::prefix('authorization')->group(function () {
            Route::post('send_token', 'User\UserController@sendToken');
            Route::post('change', 'User\UserController@changeAutorization');
            Route::post('change/social', 'User\UserController@changeAutorizationSocial');
            Route::post('delete/social', 'User\UserController@deleteAutorizationSocial');
        });

        Route::prefix('company')->group(function () {
            Route::get('check_generate', 'User\CompanyController@checkGenerate');
        });

        Route::get('logout', 'Auth\AuthController@logout')->name('user.logout');

        Route::post('store-async-validations', 'User\UserController@storeAsyncValidations');
        Route::post('update-async-validations', 'User\UserController@updateAsyncValidations');

        Route::put('{user}/online', 'User\UserOnlineController');
        Route::put('{user}/offline', 'User\UserOnlineController');
        Route::put('{user}/not-disturb', 'User\UserNotDisturbController');
        Route::put('{user}/not-here', 'User\UserNotHereController');
    });
});

/* Admin Route */
Route::group(
    [
        'middleware' => 'auth.admin:api',
        'prefix'     => 'v1/admin'
    ], function () {
    Route::prefix('user')->group(function () {
        Route::resources([
            '/'       => 'User\UserController',
            'company' => 'User\CompanyController'
        ]);

        Route::post('createRegular', 'User\UserController@createRegular');
        Route::put('updateRegular/{id}', 'User\UserController@updateRegular');
    });
});

