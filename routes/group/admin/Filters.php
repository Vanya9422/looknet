<?php

Route::group([
    'middleware' => 'role:admin|moderator',
    'namespace' => 'Category',
    'prefix' => 'filters'
], function () {

    Route::controller('FilterController')->group(function () {

        Route::get('/', 'list');
        Route::get('answers', 'answersList');
        Route::post('/', 'store');
        Route::put('/', 'update');

        Route::prefix('answers')->group(function () {
            Route::get('/', 'answersList');
            Route::delete('{filterAnswer}', 'answerDelete');
        });

        Route::prefix('{filter}')->group(function () {
            Route::get('/', 'show');
            Route::delete('/', 'destroy');
        });
    });
});

