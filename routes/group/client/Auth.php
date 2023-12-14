<?php

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {

    Route::controller('AuthController')->group(function () {

        Route::middleware('check_confirm_type')->group(function () {
            Route::post('register', 'register');
            Route::post('login', 'login');
            Route::post('confirm-login', 'checkConfirmCodeAndLogin');
            Route::post('send-again', 'sendCodeAgain');
            Route::post('reset/code', 'sendResetCode');
        });

        Route::get('check-exists', 'checkConfirmTypeExists');

        /* Social Login And Register */
        Route::group(['prefix' => 'social'], function () {
            Route::prefix('{provider}')->group(function () {
                Route::get('redirect', 'socialRedirect');
                Route::get('callback', 'callback')->where('provider', 'google|facebook');
            });
        });

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('logout', 'logout');
            Route::put('password/reset', 'showResetForm')
                ->middleware(['check_confirm_type', 'abilities:reset-password']);
        });
    });
});
