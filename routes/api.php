<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrganizationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Dashboard statistics
    Route::get('/dashboard/statistics', [AttendanceController::class, 'getStatistics']);
    
    // Events
    Route::apiResource('events', EventController::class);
    
    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::get('/attendance/event/{id}', [AttendanceController::class, 'showEvent']);
    Route::post('/attendance/{eventId}/manual', [AttendanceController::class, 'registerManual']);
    
    // Users
    Route::apiResource('users', UserController::class);
    
    // Organizations
    Route::apiResource('organizations', OrganizationController::class);
});
