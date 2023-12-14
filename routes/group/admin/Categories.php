<?php

Route::group([
    'middleware' => 'role:admin|moderator',
    'namespace' => 'Category',
    'prefix' => 'categories'
], function () {

    Route::controller('CategoryController')->group(function () {

        Route::get('/', 'list');

        Route::middleware([
            'role:admin|moderator',
            'permission:admin_permission|category_management'
        ])->group(function () {
            Route::post('/', 'store');
            Route::put('order', 'orderUpdate');
            Route::delete('/', 'destroy');
        });

        Route::prefix('{category}')->group(function () {

            Route::get('/', 'show');

            Route::middleware([
                'role:admin|moderator',
                'permission:admin_permission|category_management',
            ])->group(function () {
                Route::post('/', 'duplicateCategory');
                Route::put('/', 'update');
            });
        });
    });
});
