<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\LandingController;

// Landing page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Language switcher
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['es', 'en'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Authentication routes - Login handled by modal
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard (requires authentication)
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Redirect login route - if authenticated go to dashboard, otherwise to landing
Route::get('login', function() {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('landing');
})->name('login');

// Events routes
Route::resource('events', EventController::class);
Route::post('events/{eventId}/assign-users', [EventController::class, 'assignUsers'])->name('events.assign-users');
Route::delete('events/{eventId}/users/{userId}', [EventController::class, 'removeUser'])->name('events.remove-user');

// Attendance routes
Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('attendance/event/{id}', [AttendanceController::class, 'showEvent'])->name('attendance.show');
Route::post('attendance/{eventId}/manual', [AttendanceController::class, 'registerManual'])->name('attendance.manual');
Route::post('attendance/{eventId}/qr', [AttendanceController::class, 'registerQR'])->name('attendance.qr');
Route::post('attendance/{eventId}/barcode', [AttendanceController::class, 'registerBarcode'])->name('attendance.barcode');
Route::post('attendance/{eventId}/scan', [AttendanceController::class, 'scanCode'])->name('attendance.scan');
Route::post('attendance/{eventId}/face', [AttendanceController::class, 'registerFace'])->name('attendance.face');
Route::post('attendance/{eventId}/quick-register', [AttendanceController::class, 'quickRegister'])->name('attendance.quick-register');
Route::delete('attendance/{attendanceId}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
Route::get('attendance/{eventId}/qrcode', [AttendanceController::class, 'generateQRCode'])->name('attendance.qrcode');
Route::get('attendance/{eventId}/barcode-image', [AttendanceController::class, 'generateBarcode'])->name('attendance.barcode-image');
Route::post('attendance/upload-face', [AttendanceController::class, 'uploadFaceImage'])->name('attendance.upload-face');

// Users routes
Route::resource('users', UserController::class);

// Organizations routes (only for admins)
Route::resource('organizations', OrganizationController::class);
