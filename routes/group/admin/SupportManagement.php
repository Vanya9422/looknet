<?php

Route::group([
    'middleware' => 'role:admin|moderator',
    'namespace' => 'Support',
    'prefix' => 'supports'
], function () {

    Route::controller('SupportController')->group(function () {
        Route::get('themes', 'listThemes');
        Route::get('tickets', 'listTickets');
        Route::get('tickets-contacts', 'ticketsContacts');
        Route::prefix('{ticket}')->group(function () {
            Route::put('accept', 'acceptTicket');
            Route::put('{action}', 'actionTicket')->where('action', 'close|expect|viewed');
        });
    });
});

