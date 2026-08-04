<?php

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
});
