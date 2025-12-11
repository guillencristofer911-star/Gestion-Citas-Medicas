<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


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
