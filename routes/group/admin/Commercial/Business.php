<?php

Route::group([
    'middleware' => 'role_or_permission:admin|access_commercial',
    'namespace' => 'Commercial',
    'prefix' => 'commercial/businesses'
], function () {

    /**
     * Commercial Business Logic
     */
    Route::controller('BusinessController')->group(function () {
        Route::get('/', 'list');
        Route::put('/', 'updateOrCreate');

        Route::prefix('{commercial_business}')->group(function () {
            Route::get('/', 'details');
            Route::put('/', 'changeToDraft');
            Route::delete('/', 'destroy');
        });
    });
});
