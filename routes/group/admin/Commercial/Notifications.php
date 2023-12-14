<?php



Route::group([
    'middleware' => 'role_or_permission:admin|access_commercial',
    'namespace' => 'Commercial',
    'prefix' => 'commercial/notifications'
], function () {

    /**
     * Commercial Notifications Logic
     */
    Route::controller('NotificationsController')->group(function () {
        Route::get('/', 'list');
        Route::put('/', 'updateOrCreate');

        Route::prefix('{commercial_notification}')->group(function () {
            Route::get('/', 'details');
            Route::put('/', 'changeToDraft');
            Route::delete('/', 'destroy');
        });
    });
});
