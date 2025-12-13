<?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Auth\LoginController;
    use App\Http\Controllers\Auth\RegisterController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\PatientDashboardController;
    use App\Http\Controllers\AppointmentController;
    use App\Http\Controllers\DoctorDashboardController;


    // Rutas públicas

    // Página de bienvenida (accesible para cualquier visitante)
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');


    // Autenticación — accesible solo por usuarios invitados
    Route::middleware('guest')->group(function () {
        // Registro
        Route::get('/register', [RegisterController::class, 'show'])->name('register');
        Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

        // Inicio de sesión
        Route::get('/login', [LoginController::class, 'show'])->name('login');
        Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    });


    // Rutas protegidas — requieren autenticación
    Route::middleware('auth')->group(function () {
        // Dashboard principal (redirige según rol)
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Dashboard para pacientes
        Route::get('/paciente/dashboard', [PatientDashboardController::class, 'index'])
            ->name('patient.dashboard');
        // Dashboard para doctores
        Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])
            ->name('doctor.dashboard');


        // Cerrar sesión
        Route::post('/logout', [LoginController::class, 'logout'])
            ->name('logout');
    });

    // Rutas relacionadas con citas médicas
    Route::post('/citas', [AppointmentController::class, 'store'])
        ->name('appointments.store');

    Route::post('/citas/{appointment}/cancel', [AppointmentController::class, 'cancel'])
        ->name('appointments.cancel');