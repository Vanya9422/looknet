<?php


use App\Http\Controllers\Api\V1\Review\ReviewController;

Route::group([
    'namespace' => 'Review',
    'prefix' => 'reviews'
], function () {

    Route::get('/', [ReviewController::class, 'list']);
    Route::options('/', [ReviewController::class, 'options']);

    Route::controller('ReviewController')->group(function () {

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', 'store');
            Route::put('/', 'update');
            Route::delete('{review}/picture', 'destroyPicture');
            Route::post('add-complaint/{review}', 'addComplaint');
        });
    });
});
