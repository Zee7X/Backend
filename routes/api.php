<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\BusinessController;
use App\Http\Controllers\Api\User\AuthController as UserAuthController;
use App\Http\Controllers\Api\User\BookmarkController;
use App\Http\Controllers\Api\User\BusinessController as UserBusinessController;

// Admin Authentication
Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);
});

// User Authentication
Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);
Route::post('forgot-password', [UserAuthController::class, 'forgotPassword']);
Route::post('reset-password', [UserAuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [UserAuthController::class, 'logout']);

    Route::prefix('user')->group(function () {
        Route::get('bookmarks', [BookmarkController::class, 'index']);
    });

    Route::prefix('businesses/{business:slug}')->group(function () {
        Route::post('bookmark', [BookmarkController::class, 'store']);
        Route::delete('bookmark', [BookmarkController::class, 'destroy']);
    });
});

//Admin
Route::prefix('admin')->middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::get('businesses', [BusinessController::class, 'index']);
    Route::post('businesses', [BusinessController::class, 'store']);
    Route::put('businesses/{business}', [BusinessController::class, 'update']);
    Route::delete('businesses/{business}', [BusinessController::class, 'destroy']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
});

//User
Route::get('businesses', [UserBusinessController::class, 'index']);
Route::get('businesses/{business:slug}', [UserBusinessController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);
