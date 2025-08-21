<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController as V1AuthController;
use App\Http\Controllers\Api\V1\SocialController as V1SocialController;
use App\Http\Controllers\Api\V1\AzureController as V1AzureController;

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
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('jwt.auth')->group(function () {
        Route::get('/me', function () {
            return auth('api')->user();
        });
        Route::post('/logout', [V1AuthController::class, 'logout']);
        Route::post('/refresh', [V1AuthController::class, 'refresh']);
    });

    Route::post('/login', [V1AuthController::class, 'login']);

    Route::get('/auth/linkedin', [V1SocialController::class, 'redirectLinkedin']);
    Route::get('/auth/linkedin/callback', [V1SocialController::class, 'handleLinkedin']);

    Route::get('/auth/google', [V1SocialController::class, 'redirectGoogle']);
    Route::get('/auth/google/callback', [V1SocialController::class, 'handleGoogle']);

    Route::get('/auth/facebook', [V1SocialController::class, 'redirectFacebook']);
    Route::get('/auth/facebook/callback', [V1SocialController::class, 'handleFacebook']);

    Route::get('/auth/azure', [V1AzureController::class, 'redirectAzure']);
    Route::get('/auth/azure/callback', [V1AzureController::class, 'handleAzure']);
});
