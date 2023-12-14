<?php

Route::group([
    'middleware' => 'role:admin|moderator',
    'namespace' => 'Pages',
    'prefix' => 'pages'
], function () {

    Route::controller('PagesController')->group(function () {
        Route::delete('{media}', 'deletePageMedia');
        Route::put('/', 'update');
    });
});

