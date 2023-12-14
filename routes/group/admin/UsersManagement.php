<?php

Route::group([
    'middleware' => 'role:admin|moderator',
    'prefix' => 'users'
], function () {

    Route::controller('UserController')->group(function () {
        Route::get('/', 'list');
        Route::put('banned', 'banUser');

        Route::prefix('{user}')->group(function () {
            Route::get('/', 'getAdvertises');
            Route::delete('/', 'deleteAccountUser');
        });
    });
});
