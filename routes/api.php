<?php

use App\Http\Controllers\Api\V1\Users\AboutMeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::stripeWebhooks('stripe-webhooks/{configKey}');

Route::get('user', AboutMeController::class)->middleware('auth:sanctum');

findFiles(__DIR__ . '/group/client');
