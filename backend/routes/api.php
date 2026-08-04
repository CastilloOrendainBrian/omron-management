<?php

use App\Http\Controllers\AnthropometricMeasurementController;
use App\Http\Controllers\BodyCompositionMeasurementController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\SkinfoldMeasurementController;
use App\Http\Controllers\SkinfoldProtocolController;
use App\Http\Controllers\SkinfoldSiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/user', fn (Request $request) => $request->user());

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
