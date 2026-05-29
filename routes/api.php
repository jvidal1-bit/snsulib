<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\MobileAuthController;
use App\Http\Controllers\Mobile\MobileStudentController;

// Mobile Login (no auth needed)
Route::post('/mobile/login', [MobileAuthController::class, 'login']);

// Mobile Student Routes (need token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/mobile/logout',            [MobileAuthController::class,   'logout']);
    Route::get('/student/home',              [MobileStudentController::class, 'home']);
    Route::get('/student/catalog',           [MobileStudentController::class, 'catalog']);
    Route::get('/student/requests',          [MobileStudentController::class, 'requests']);
    Route::post('/student/requests',         [MobileStudentController::class, 'storeRequest']);
    Route::get('/student/requests/{id}',     [MobileStudentController::class, 'showRequest']);
    Route::get('/student/profile',           [MobileStudentController::class, 'profile']);
    Route::post('/student/profile/update',   [MobileStudentController::class, 'updateProfile']);
    Route::post('/student/password/update',  [MobileStudentController::class, 'updatePassword']);
    Route::post('/student/profile/avatar',   [MobileStudentController::class, 'updateAvatar']);
});