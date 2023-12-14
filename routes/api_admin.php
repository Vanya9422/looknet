<?php

findFiles(__DIR__ . '/group/admin');

Route::get('permissions', 'PermissionController@getPermissions');

Route::group(['middleware' => 'role:admin|moderator'], function () {

    Route::post('give-permission', 'ModeratorController@givePermission');
    Route::post('give-role', 'PermissionController@giveRole');
});
