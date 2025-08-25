<?php

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

Route::middleware(['auth:api', 'verified'])->group(function () {
    Route::post('/', [Modules\ChatAI\App\Http\Controllers\Api\V1\ChatController::class, 'send']);
});

if(env('APP_ENV')==='local'){
    Route::post('/test', [Modules\ChatAI\App\Http\Controllers\Api\V1\ChatController::class, 'test']);
}