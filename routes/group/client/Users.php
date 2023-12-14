<?php

use App\Http\Controllers\Api\V1\Users\SupportController;

Route::get('users/{user}/information', [\App\Http\Controllers\Api\V1\Users\UserController::class, 'getUserInformation']);

Route::post('supports/add-ticket', [SupportController::class, 'addTicket']);

Route::group(['namespace' => 'Users', 'middleware' => 'auth:sanctum'], function () {

    Route::prefix('supports')->group(function () {
        Route::controller('SupportController')->group(function () {
            Route::get('themes', 'getThemes');
        });
    });

    Route::prefix('users')->group(function () {

        Route::controller('UserBlockController')->group(function () {
            Route::post('blocked-user', 'blockUser');
            Route::post('unblocked-user', 'unBlockUser');
            Route::get('blocked-user-list', 'blockList');
        });

        Route::controller('UserController')->group(function () {
            Route::prefix('products')->group(function () {
                Route::get('/', 'getAdvertises');
                Route::get('statistics', 'getStatisticUser');
                Route::get('favorites', 'getFavoriteAdvertises');
            });

            Route::prefix('settings')->group(function () {
                Route::put('/', 'update');

                Route::group(['middleware' => 'check_confirm_type', 'prefix' => 'change'], function () {
                    Route::put('password', 'changePassword');
                    Route::put('send-email-phone', 'sendCodForEmailOrPhone');
                    Route::put('update-email-phone', 'changeEmailOrPhone');
                });
            });
        });

        Route::controller('NotificationController')->group(function () {

            Route::prefix('notifications')->group(function () {
                Route::get('/', 'list');
                Route::put('/', 'readNotifications');
                Route::delete('{notification}', 'destroy');

                Route::middleware('check_confirm_type')->group(function () {
                    Route::post('confirmation-send', 'sendConfirmationCode');
                    Route::put('confirmation-check', 'checkConfirmationCode');
                });
            });
        });
    });
});
