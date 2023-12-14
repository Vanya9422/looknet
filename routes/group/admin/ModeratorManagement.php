<?php

Route::group([
    'middleware' => 'role_or_permission:admin|view_and_manage_managers',
    'prefix' => 'moderators'
], function () {

    Route::controller('ModeratorController')->group(function () {

        Route::get('/', 'list');
        Route::post('/', 'store');
        Route::put('/', 'update');
        Route::put('banned', 'banModerator');

        Route::prefix('{user}')->group(function () {
            Route::get('/', 'getModerator');
            Route::get('statistics', 'getStatistics');
            Route::delete('/', 'destroy');
        });
    });
});
