<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientDashboardController;


// ========== RUTAS PÚBLICAS ==========



Route::get('/', function () {
    return view('welcome');
})->name ('welcome');


//Rutas de autenticacion

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login',[LoginController::class, 'show'])->name('login');
    Route::post('/login',[LoginController::class, 'store'])->name('login.store');
});

// Rutas protegidas 
Route::middleware('auth')->group(function () {
    // Dashboard inicial (redirecciona según rol)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/paciente/dashboard', [PatientDashboardController::class, 'index'])
        ->name('patient.dashboard');

    // cerrar sesión
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});
