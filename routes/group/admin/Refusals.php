<?php

Route::group([
    'middleware' => 'role:admin|moderator',
    'prefix' => 'refusals'
], function () {

    /**
     * Commercial client Logic
     */
    Route::controller('RefusalController')->group(function () {
        Route::put('/', 'updateOrCreate');
        Route::delete('{refusal}', 'destroy');
    });
});
