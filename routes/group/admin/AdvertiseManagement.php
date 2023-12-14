<?php


Route::group([
    'middleware' => 'role:admin|moderator',
    'prefix' => 'products'
], function () {

    Route::controller('AdvertiseController')->group(function () {
        Route::get('/', 'list');
        Route::get('{user}', 'otherAdvertises');
        Route::put('status', 'changeStatus');

        Route::prefix('{advertise}')->group(function () {
            Route::get('details', 'show');
            Route::put('change-category', 'changeCategory');
        });
    });
});
