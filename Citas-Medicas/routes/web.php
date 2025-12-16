<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AdminDashboardController;

// ====== PÚBlICAS ======
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ====== AUTENTICACIÓN (GUEST) ======
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// ====== RUTAS AUTENTICADAS (TODAS) ======
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ====== CITAS ======
    Route::prefix('citas')->name('appointments.')->group(function () {
        Route::post('/', [AppointmentController::class, 'store'])->name('store');
        Route::post('/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('cancel');
    });
});

// ====== PACIENTES ======
Route::middleware(['auth', 'checkRole:patient'])->prefix('paciente')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
});

// ====== MÉDICOS ======
Route::middleware(['auth', 'checkRole:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
    Route::post('/appointments/{appointment}/update-status', [DoctorDashboardController::class, 'updateAppointmentStatus'])
        ->name('appointments.update-status');
});

// ====== ADMIN ======
Route::middleware(['auth', 'checkRole:admin'])->group(function () {
    // Dashboard principal
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
    
    // CRUD endpoints AJAX
    Route::prefix('admin')->name('admin.')->group(function () {
        // Doctors
        Route::get('/doctors/{doctor}', [AdminDoctorController::class, 'show'])->name('doctors.show');
        Route::get('/doctors/search', [AdminDoctorController::class, 'search'])->name('doctors.search');
        Route::post('/doctors/store', [AdminDoctorController::class, 'store'])->name('doctors.store');
        Route::put('/doctors/{doctor}', [AdminDoctorController::class, 'update'])->name('doctors.update');
        Route::delete('/doctors/{doctor}', [AdminDoctorController::class, 'destroy'])->name('doctors.destroy');
        
        // Appointments
        Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
        Route::get('/appointments/search', [AdminAppointmentController::class, 'search'])->name('appointments.search');
        
        // Schedules
        Route::post('/schedules/store', [AdminScheduleController::class, 'store'])->name('schedules.store');
        Route::put('/schedules/{schedule}', [AdminScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{schedule}', [AdminScheduleController::class, 'destroy'])->name('schedules.destroy');
        
        // Users
        Route::get('/users/search', [AdminUserController::class, 'search'])->name('users.search');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
});
