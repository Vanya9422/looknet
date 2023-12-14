<?php

Route::group([
    'middleware' => 'auth:sanctum',
    'namespace' => 'Payments',
    'prefix' => 'payments'
], function () {

    Route::controller('SubscriptionController')->group(function () {
        Route::post('checkout', 'createSession');
    });
});
