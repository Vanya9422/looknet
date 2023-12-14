<?php

/**
 * State List And Search Cities
 */
Route::controller('StateController')->group(function () {
    Route::get('states', 'getStates');
    Route::get('search-cities', 'getCitiesBySearch');
    Route::get('check-city', 'city');
    Route::get('get-city/{slug}', 'getCity');
});
