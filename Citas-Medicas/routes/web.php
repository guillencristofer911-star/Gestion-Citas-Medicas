<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\AppointmentController;

// PÚBLICAS
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// AUTH GUEST (para no autenticados)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// AUTH REQUIRED (cualquier usuario autenticado)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// ====== PACIENTES ======
Route::middleware(['auth', 'checkRole:patient'])->prefix('paciente')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
});

// ====== MÉDICOS ======
Route::middleware(['auth', 'checkRole:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
    Route::post('/appointments/{appointment}/update-status', 
        [DoctorDashboardController::class, 'updateAppointmentStatus'])->name('appointments.update-status');
});

// ====== ADMIN ======
Route::middleware(['auth', 'checkRole:admin'])->prefix('admin')->name('admin.')->group(function () {

});

// ====== CITAS (sin protección de rol, solo autenticado) ======
Route::middleware('auth')->group(function () {
    Route::post('/citas', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::post('/citas/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
});
