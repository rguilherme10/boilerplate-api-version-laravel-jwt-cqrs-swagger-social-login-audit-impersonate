<?php

use App\Http\Controllers\Api\V1\HealthController;
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

    Route::middleware('auth:api')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::get('/me', function () {
            return auth('api')->user();
        });
        Route::post('/logout', [App\Http\Controllers\Api\V1\AuthController::class, 'logout']);
        Route::post('/refresh', [App\Http\Controllers\Api\V1\AuthController::class, 'refresh']);

        Route::post('/impersonate/{id}', [ImpersonationController::class, 'impersonate']);
    });

    Route::post('/login', [App\Http\Controllers\Api\V1\AuthController::class, 'login']);
    Route::post('/register', [App\Http\Controllers\Api\V1\UserController::class, 'register']);

    Route::get('health', HealthController::class)
    ->middleware('api')
    ->name('health');
});
