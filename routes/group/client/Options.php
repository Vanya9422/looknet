<?php


use App\Http\Controllers\Api\V1\Options\AdvertiseStatusesOption;
use App\Http\Controllers\Api\V1\Options\RefusalTypesOption;
use App\Http\Controllers\Api\V1\Options\SendCodeTypesOption;

Route::group([
    'namespace' => 'Options',
    'prefix' => 'options'
], function () {

    Route::options('send-code-types', SendCodeTypesOption::class);
    Route::options('refusal-types', RefusalTypesOption::class);
    Route::options('product-statuses', AdvertiseStatusesOption::class);
});
