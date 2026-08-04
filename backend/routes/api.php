<?php

use App\Http\Controllers\AnthropometricMeasurementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BodyCompositionMeasurementController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\SkinfoldMeasurementController;
use App\Http\Controllers\SkinfoldProtocolController;
use App\Http\Controllers\SkinfoldSiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserRegistrationController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/logout-all', [AuthController::class, 'logoutAll']);

    Route::post('users/register', [UserRegistrationController::class, 'register']);
    Route::put('users/{user}/with-profile', [UserRegistrationController::class, 'updateWithProfile']);

    Route::apiResource('users', UserController::class);

    Route::apiResource('user-profiles', UserProfileController::class)
        ->parameters(['user-profiles' => 'profile']);

    Route::apiResource('skinfold-sites', SkinfoldSiteController::class);

    Route::apiResource('skinfold-protocols', SkinfoldProtocolController::class);

    Route::apiResource('skinfold-measurements', SkinfoldMeasurementController::class);

    Route::apiResource('anthropometric-measurements', AnthropometricMeasurementController::class);

    Route::apiResource('body-composition-measurements', BodyCompositionMeasurementController::class);

    Route::apiResource('devices', DeviceController::class);

    Route::apiResource('goals', GoalController::class);
});
