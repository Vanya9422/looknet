<?php

Route::group([
    'middleware' => 'role:admin|moderator',
    'namespace' => 'Complaints',
    'prefix' => 'complaints'
], function () {

    Route::get('/', \App\Http\Controllers\Api\V1\Admin\Complaints\ComplaintsList::class);
    Route::delete('/', \App\Http\Controllers\Api\V1\Admin\Complaints\ComplaintsDestroy::class);
});

