<?php


Route::group([
    'middleware' => 'role:admin|moderator',
    'prefix' => 'reviews'
], function () {

    Route::controller('ReviewController')->group(function () {

        Route::get('/', 'list');
        Route::put('/', 'published');
    });
});
