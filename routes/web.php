<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes would go here in a real app
// For now, we'll use basic auth for demo purposes

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AttendanceController::class, 'index'])->name('dashboard');
    
    // Attendance routes
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/events/{event}/attendance', [AttendanceController::class, 'showEvent'])->name('attendance.show');
    Route::post('/events/{event}/attendance/manual', [AttendanceController::class, 'registerManual'])->name('attendance.register.manual');
    Route::post('/events/{event}/attendance/qr', [AttendanceController::class, 'registerQR'])->name('attendance.register.qr');
    Route::post('/events/{event}/attendance/barcode', [AttendanceController::class, 'registerBarcode'])->name('attendance.register.barcode');
    Route::post('/events/{event}/attendance/face', [AttendanceController::class, 'registerFace'])->name('attendance.register.face');
    Route::post('/face-image', [AttendanceController::class, 'uploadFaceImage'])->name('face.image.upload');
    
    // QR and Barcode generation
    Route::get('/events/{event}/qrcode', [AttendanceController::class, 'generateQRCode'])->name('qrcode.generate');
    Route::get('/events/{event}/barcode', [AttendanceController::class, 'generateBarcode'])->name('barcode.generate');
    
    // User management (for admins and coordinators)
    Route::resource('users', UserController::class)->middleware('role:admin,coordinator');
    
    // Event management (for admins)
    Route::resource('events', EventController::class)->middleware('role:admin');
    Route::post('/events/{event}/assign-users', [EventController::class, 'assignUsers'])->name('events.assign.users');
    Route::delete('/events/{event}/users/{user}', [EventController::class, 'removeUser'])->name('events.remove.user');
});

// API routes for face recognition
Route::prefix('api')->group(function () {
    Route::post('/face-verify/{event}', [AttendanceController::class, 'registerFace']);
    Route::post('/face-extract', [AttendanceController::class, 'uploadFaceImage']);
});