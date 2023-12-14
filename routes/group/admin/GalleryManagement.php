<?php

Route::group([
    'middleware' => 'role:admin|moderator',
    'prefix' => 'gallery'
], function () {
    Route::controller('GalleryController')->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'list');
        Route::delete('/', 'deleteMultiple');
        Route::put('/', 'duplicate');

        Route::group([
            'middleware' => 'role_or_permission:admin|access_website_content_and_picture_editing',
            'prefix' => '{media}'
        ], function () {
            Route::put('/', 'changeFileProperties');
            Route::delete('/', 'destroy');
            Route::get('/', 'show');
        });
    });
});
