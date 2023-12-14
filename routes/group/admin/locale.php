<?php

Route::group([
    'middleware' => 'role:admin|moderator',
    'prefix' => 'locales'
], function () {

    Route::prefix('')->group(function () {
        Route::controller('LanguageController')->group(function () {
            Route::post('/', 'addLocale');
            Route::put('/', 'updateLocale');
            Route::delete('{language}', 'destroy');
        });
    });
});
