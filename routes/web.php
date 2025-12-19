<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\PatientDashboardController;

/*
|--------------------------------------------------------------------------
| Rutas Web de la Aplicación
|--------------------------------------------------------------------------
|
| Aquí se registran todas las rutas web de la aplicación. Estas rutas
| son cargadas por el RouteServiceProvider y todas serán asignadas al
| grupo de middleware "web".
|
*/

// ==================== RUTAS PÚBLICAS ====================
// Rutas accesibles sin autenticación

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ==================== AUTENTICACIÓN ====================
// Rutas para registro e inicio de sesión (solo para invitados)

Route::middleware('guest')->group(function () {
    // Registro
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    
    // Inicio de sesión
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// ==================== RUTAS AUTENTICADAS ====================
// Rutas disponibles para todos los usuarios autenticados

Route::middleware('auth')->group(function () {
    // Dashboard principal (redirige según rol)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Cerrar sesión
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ==================== CITAS MÉDICAS ====================
    // Gestión de citas para pacientes
    
    Route::prefix('citas')->name('appointments.')->group(function () {
        Route::post('/', [AppointmentController::class, 'store'])->name('store');
        Route::post('/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('cancel');
    });
});

// ==================== DASHBOARD PACIENTE ====================
// Rutas exclusivas para usuarios con rol 'patient'

Route::middleware(['auth', 'checkRole:patient'])->prefix('paciente')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
  });


// ==================== DASHBOARD MÉDICO ====================
// Rutas exclusivas para usuarios con rol 'doctor'

Route::middleware(['auth', 'checkRole:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    // Dashboard del médico
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
    
    // Actualización de estado de citas
    Route::post('/appointments/{appointment}/update-status', [DoctorDashboardController::class, 'updateAppointmentStatus'])
        ->name('appointments.update-status');
});

// ==================== PANEL ADMINISTRATIVO ====================
// Rutas exclusivas para usuarios con rol 'admin'

Route::middleware(['auth', 'checkRole:admin'])->group(function () {
    // Dashboard principal del administrador
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
    
    // ==================== CRUD ADMINISTRATIVO ====================
    // Endpoints AJAX para operaciones de administración
    
    Route::prefix('admin')->name('admin.')->group(function () {
        // Gestión de Médicos
        Route::post('/doctors/store', [AdminDoctorController::class, 'store'])->name('doctors.store');
        Route::put('/doctors/{doctor}', [AdminDoctorController::class, 'update'])->name('doctors.update');
        Route::delete('/doctors/{doctor}', [AdminDoctorController::class, 'destroy'])->name('doctors.destroy');
        Route::patch('/doctors/{doctor}/toggle', [AdminDoctorController::class, 'toggleStatus'])->name('doctors.toggle');
        // Gestión de Horarios
        Route::post('/schedules/store', [AdminScheduleController::class, 'store'])->name('schedules.store');
        Route::put('/schedules/{schedule}', [AdminScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{schedule}', [AdminScheduleController::class, 'destroy'])->name('schedules.destroy');
        
        // Gestión de Usuarios
        Route::patch('/users/{user}/toggle', [AdminUserController::class, 'toggleStatus'])->name('users.toggle');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
});
