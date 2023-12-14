<?php

/**
 * Commercial Users Logic
 */


Route::group([
    'middleware' => 'role_or_permission:admin|access_commercial',
    'namespace' => 'Commercial',
    'prefix' => 'commercial/users'
], function () {

    Route::controller('UsersController')->group(function () {
        Route::get('/', 'list');
        Route::put('/', 'updateOrCreate');

        Route::prefix('{commercial_user}')->group(function () {
            Route::get('/', 'details');
            Route::put('/', 'changeToDraft');
            Route::delete('/', 'destroy');
        });
    });
});
