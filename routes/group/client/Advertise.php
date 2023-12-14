<?php

/**
 * Advertises List and details Advertise
 */

use App\Http\Controllers\Api\V1\Advertise\AdvertiseList;

Route::controller('AdvertiseController')->group(function () {

    Route::prefix('statistics')->group(function () {
        Route::prefix('{advertise}')->group(function () {
            Route::post('phone-view', 'addStatisticsPhoneView')/*->middleware('throttle:phone_view')*/;
            Route::post('view-details', 'addStatisticsDetailsView')/*->middleware('throttle:show_details')*/;
            Route::post('favorite', 'addStatisticsFavorite')/*->middleware('throttle:favorite')*/;
        });
    });

    Route::get('search_texts', 'getSearchTexts');
    Route::get('search-products', 'getCategoriesAndAdvertisesCountBySearch');

    Route::prefix('products')->group(function () {

        Route::get('/', AdvertiseList::class);
        Route::get('{advertise}/review-information', 'reviewInfo');
        Route::get('{id_or_slug}', 'show');
        Route::get('{id}/images', 'gallery');

        Route::middleware('auth:sanctum')->group(function () {

            Route::prefix('favorite')->group(function () {
                Route::post('/', 'addFavorite');
                Route::delete('remove', 'detachFavorite');
            });

            Route::post('/', 'store');
            Route::put('action/{action}', 'changeProductStatusOrDeleteProduct')
                ->where('action', 'delete|deactivate|draft|activate|moderation');

            Route::prefix('{advertise}')->group(function () {
                Route::put('/', 'update');
                Route::delete('{media}', 'deletePicture');
            });
        });
    });
});
