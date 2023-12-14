<?php

Route::group([
    'middleware' => 'role_or_permission:admin|access_commercial',
    'namespace' => 'Commercial',
    'prefix' => 'commercial/clients'
], function () {

    /**
     * Commercial client Logic
     */
    Route::controller('ClientController')->group(function () {
        Route::get('/', 'list');
        Route::put('/', 'updateOrCreate');

        Route::prefix('{client}')->group(function () {
            Route::get('/', 'client');
            Route::delete('/', 'destroy');
        });
    });
});

