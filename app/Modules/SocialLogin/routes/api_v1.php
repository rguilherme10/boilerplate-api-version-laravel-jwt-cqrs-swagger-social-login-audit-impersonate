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

// Rotas da API v1

Route::get('/auth/linkedin', [Modules\SocialLogin\App\Http\Controllers\Api\V1\LinkedInController::class, 'redirectLinkedin']);
Route::get('/auth/linkedin/callback', [Modules\SocialLogin\App\Http\Controllers\Api\V1\LinkedInController::class, 'handleLinkedin']);
Route::get('/auth/google', [Modules\SocialLogin\App\Http\Controllers\Api\V1\GoogleController::class, 'redirectGoogle']);
Route::get('/auth/google/callback', [Modules\SocialLogin\App\Http\Controllers\Api\V1\GoogleController::class, 'handleGoogle']);
Route::get('/auth/facebook', [Modules\SocialLogin\App\Http\Controllers\Api\V1\FacebookController::class, 'redirectFacebook']);
Route::get('/auth/facebook/callback', [Modules\SocialLogin\App\Http\Controllers\Api\V1\FacebookController::class, 'handleFacebook']);
Route::get('/auth/azure', [Modules\SocialLogin\App\Http\Controllers\Api\V1\AzureController::class, 'redirectAzure']);
Route::get('/auth/azure/callback', [Modules\SocialLogin\App\Http\Controllers\Api\V1\AzureController::class, 'handleAzure']);