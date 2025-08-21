<?php

use App\Http\Controllers\Api\V1\ImpersonationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Rotas da API v1
Route::prefix('v1')->group(function () {

    Route::middleware('jwt.auth')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::get('/me', function () {
            return auth('api')->user();
        });
        Route::post('/logout', [App\Http\Controllers\Api\V1\AuthController::class, 'logout']);
        Route::post('/refresh', [App\Http\Controllers\Api\V1\AuthController::class, 'refresh']);

        Route::post('/chat', [App\Http\Controllers\Api\V1\ChatController::class, 'send']);

        Route::post('/impersonate/{id}', [ImpersonationController::class, 'impersonate']);
    });

    Route::post('/login', [App\Http\Controllers\Api\V1\AuthController::class, 'login']);

    Route::get('/auth/linkedin', [App\Http\Controllers\Api\V1\SocialController::class, 'redirectLinkedin']);
    Route::get('/auth/linkedin/callback', [App\Http\Controllers\Api\V1\SocialController::class, 'handleLinkedin']);

    Route::get('/auth/google', [App\Http\Controllers\Api\V1\SocialController::class, 'redirectGoogle']);
    Route::get('/auth/google/callback', [App\Http\Controllers\Api\V1\SocialController::class, 'handleGoogle']);

    Route::get('/auth/facebook', [App\Http\Controllers\Api\V1\SocialController::class, 'redirectFacebook']);
    Route::get('/auth/facebook/callback', [App\Http\Controllers\Api\V1\SocialController::class, 'handleFacebook']);

    Route::get('/auth/azure', [App\Http\Controllers\Api\V1\AzureController::class, 'redirectAzure']);
    Route::get('/auth/azure/callback', [App\Http\Controllers\Api\V1\AzureController::class, 'handleAzure']);

    Route::post('/chat/test', [App\Http\Controllers\Api\V1\ChatController::class, 'test']);
});
