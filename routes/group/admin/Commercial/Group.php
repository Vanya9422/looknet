<?php

Route::group([
    'middleware' => 'role_or_permission:admin|access_commercial',
    'namespace' => 'Commercial',
    'prefix' => 'commercial/groups'
], function () {

    /**
     * Commercial Group Logic
     */
    Route::controller('GroupController')->group(function () {

        Route::get('/', 'list');
        Route::put('/', 'updateOrCreate');
        Route::prefix('{group}')->group(function () {
            Route::get('/', 'details');
            Route::delete('/', 'destroy');
        });
    });
});
