# 📘 Documentación Técnica - MediConnect

## Índice

1. [Arquitectura del Sistema](#arquitectura-del-sistema)
2. [Modelos y Relaciones](#modelos-y-relaciones)
3. [Observers y Eventos](#observers-y-eventos)
4. [Soft Deletes](#soft-deletes)
5. [Middleware y Autorización](#middleware-y-autorización)
6. [Diagramas de Flujo](#diagramas-de-flujo)
7. [Gestion de Estados](#gestión-de-estados)
8. [Implementación Real del Proyecto](#implementación-real-del-proyecto)

---

## Arquitectura del Sistema

### Patrón MVC (Model-View-Controller)

MediConnect implementa el patrón MVC de Laravel con separación clara de responsabilidades:

```
┌─────────────────────────────────────────────────────────┐
│                    CLIENTE (Browser)                     │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                      ROUTING (web.php)                   │
│  • Definición de URLs                                   │
│  • Aplicación de Middleware                             │
│  • Mapeo a Controladores                                │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                   MIDDLEWARE LAYER                       │
│  • Authenticate: Verificar login                        │
│  • CheckRole: Validar permisos por rol                  │
│  • CSRF: Protección contra ataques CSRF                 │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                     CONTROLLERS                          │
│  • PatientDashboardController                           │
│  • DoctorDashboardController                            │
│  • AdminDashboardController                             │
│  • Admin/DoctorController                               │
│  • Admin/UserController                                 │
│  • AppointmentController                                │
└────┬────────────────────────┬───────────────────────────┘
     │                        │
     ▼                        ▼
┌─────────────┐        ┌──────────────┐
│   MODELS    │◄──────►│  OBSERVERS   │
│  • User     │        │  • Doctor    │
│  • Doctor   │        │  • User      │
│  • Appoint  │        │              │
│  • Schedule │        └──────────────┘
└─────┬───────┘
      │
      ▼
┌─────────────────────────────────────────────────────────┐
│                    DATABASE (MySQL)                      │
│  • users                                                │
│  • doctors                                              │
│  • appointments                                         │
│  • schedules                                            │
└─────────────────────────────────────────────────────────┘
```

### Capas de la Aplicación

#### 1. **Presentation Layer** (Vistas)
- **Ubicación**: `resources/views/`
- **Tecnología**: Blade Templates
- **Responsabilidad**: Renderizar HTML y mostrar datos al usuario

```blade
{{-- Ejemplo: resources/views/dashboard/admin/index.blade.php --}}
<div class="stats">
    <h3>Total Doctores: {{ $totalDoctors }}</h3>
    <h3>Citas Pendientes: {{ $pendingAppointments }}</h3>
</div>
```

#### 2. **Business Logic Layer** (Controladores)
- **Ubicación**: `app/Http/Controllers/`
- **Responsabilidad**: Procesar peticiones, coordinar modelos, devolver vistas

```php
// app/Http/Controllers/Admin/AdminDashboardController.php
public function index()
{
    $doctors = Doctor::withTrashed()->with('user')->get();
    $users = User::all();
    
    return view('dashboard.admin.index', compact('doctors', 'users'));
}
```

#### 3. **Data Access Layer** (Modelos)
- **Ubicación**: `app/Models/`
- **Responsabilidad**: Interacción con base de datos, relaciones, lógica de datos

```php
// app/Models/Doctor.php
class Doctor extends Model
{
    use SoftDeletes;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## Modelos y Relaciones

### Diagrama de Relaciones Detallado

```
┌──────────────────────────────────────────────────────────┐
│                        User                              │
│──────────────────────────────────────────────────────────│
│ PK: id                                                   │
│ name, email, password                                    │
│ role: ENUM('patient', 'doctor', 'admin')                │
│ active: BOOLEAN                                          │
│ deleted_at: TIMESTAMP (Soft Delete)                      │
└───┬──────────────────────────────────────────────────┬───┘
    │                                                   │
    │ 1:1 (hasOne)                                      │ 1:N (hasMany)
    │                                                   │
    ▼                                                   ▼
┌───────────────────────────┐              ┌────────────────────┐
│         Doctor            │              │   Appointment      │
│───────────────────────────│              │────────────────────│
│ PK: id                    │              │ PK: id             │
│ FK: user_id (UNIQUE)      │              │ FK: patient_id     │
│ license_number (UNIQUE)   │◄────┐        │ FK: doctor_id      │
│ specialty                 │     │        │ appointment_date   │
│ biography (TEXT)          │     │        │ status: ENUM       │
│ active: BOOLEAN           │     │        │ notes (TEXT)       │
│ deleted_at (Soft Delete)  │     │        └────────────────────┘
└───┬───────────────────────┘     │
    │                             │
    │ 1:N (hasMany)               │ N:1 (belongsTo)
    │                             │
    ▼                             │
┌───────────────────────────┐     │
│        Schedule           │     │
│───────────────────────────│     │
│ PK: id                    │─────┘
│ FK: doctor_id             │
│ day_of_week: 0-6          │
│ start_time: TIME          │
│ end_time: TIME            │
│ is_active: BOOLEAN        │
└───────────────────────────┘
```

### Implementación de Relaciones

#### User Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'active' => 'boolean',
    ];

    /**
     * Relación 1:1 con Doctor
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Relación 1:N con Appointments (como paciente)
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    /**
     * Verificar si el usuario es doctor
     */
    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    /**
     * Verificar si el usuario es admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Verificar si el usuario es paciente
     */
    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }
}
```

#### Doctor Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'license_number',
        'specialty',
        'biography',
        'photo_url',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relación inversa 1:1 con User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación 1:N con Appointment
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Relación 1:N con Schedule
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Scope: Solo doctores activos
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}
```

#### Appointment Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date_time',
        'status',
        'consultation_reason',
        'notes',
    ];

    protected $casts = [
        'appointment_date_time' => 'datetime',
    ];

    /**
     * Relación con paciente (User)
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Relación con doctor
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Scope: Citas pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Citas confirmadas
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
}
```

---

## Observers y Eventos

### Concepto de Observers

Los **Observers** en Laravel permiten escuchar eventos del ciclo de vida de los modelos Eloquent y ejecutar lógica automáticamente.

#### ¿Por qué usar Observers?

- ✅ **Separación de responsabilidades**: Lógica de negocio fuera de controllers
- ✅ **Reutilización**: Un observer se ejecuta sin importar dónde se modifique el modelo
- ✅ **Mantenibilidad**: Código más limpio y organizado
- ✅ **Consistencia**: Garantiza que ciertas acciones siempre se ejecuten

### Eventos Disponibles

| Evento | Momento de Ejecución | Uso Común |
|--------|---------------------|----------|
| `creating` | **ANTES** de crear el registro | Validaciones, asignación de valores default |
| `created` | **DESPUÉS** de crear el registro | Notificaciones, logs |
| `updating` | **ANTES** de actualizar el registro | Validaciones, auditoría |
| `updated` | **DESPUÉS** de actualizar el registro | Notificaciones de cambios |
| `saving` | **ANTES** de guardar (crear o actualizar) | Validaciones generales |
| `saved` | **DESPUÉS** de guardar (crear o actualizar) | Logs, notificaciones |
| `deleting` | **ANTES** de eliminar (incluye soft delete) | Limpiar datos relacionados |
| `deleted` | **DESPUÉS** de eliminar | Logs, notificaciones |
| `restoring` | **ANTES** de restaurar (soft delete) | Validaciones, preparar datos |
| `restored` | **DESPUÉS** de restaurar | Notificaciones, logs |
| `forceDeleted` | Al eliminar permanentemente (sin soft delete) | Limpieza final |

### DoctorObserver - Implementación Completa

```php
<?php

namespace App\Observers;

use App\Models\Doctor;
use Illuminate\Support\Facades\Log;

class DoctorObserver
{
    /**
     * Se ejecuta DESPUÉS de crear un doctor
     * Asegura que el usuario asociado tenga rol 'doctor' y esté activo
     */
    public function created(Doctor $doctor): void
    {
        if ($doctor->user && $doctor->user->role !== 'doctor') {
            $doctor->user->update([
                'role' => 'doctor',
                'active' => true,
            ]);
            
            Log::info("Usuario {$doctor->user->id} actualizado a rol doctor");
        }
    }

    /**
     * Se ejecuta ANTES de hacer soft delete del doctor
     * CRÍTICO: Cambia el usuario asociado a paciente inactivo
     * 
     * ¿Por qué 'deleting' y no 'deleted'?
     * - 'deleting' se ejecuta ANTES del soft delete
     * - Permite modificar datos relacionados en la misma transacción
     * - 'deleted' sería DESPUÉS, cuando puede haber problemas de timing
     */
    public function deleting(Doctor $doctor): void
    {
        Log::info("=== OBSERVER DELETING ===");
        Log::info("Doctor ID: {$doctor->id}, User ID: {$doctor->user_id}");
        
        if ($doctor->user) {
            Log::info("Usuario ANTES - Role: {$doctor->user->role}, Active: " . ($doctor->user->active ? '1' : '0'));
            
            // Cambiar usuario a paciente inactivo
            $doctor->user->update([
                'role' => 'patient',
                'active' => false,
            ]);
            
            // Recargar para confirmar cambios
            $doctor->user->refresh();
            
            Log::info("Usuario DESPUÉS - Role: {$doctor->user->role}, Active: " . ($doctor->user->active ? '1' : '0'));
        } else {
            Log::warning("Doctor {$doctor->id} no tiene usuario asociado");
        }
    }

    /**
     * Se ejecuta ANTES de restaurar un doctor (revertir soft delete)
     * Devuelve el rol 'doctor' al usuario y lo activa
     */
    public function restoring(Doctor $doctor): void
    {
        Log::info("=== OBSERVER RESTORING ===");
        Log::info("Doctor ID: {$doctor->id}");
        
        if ($doctor->user) {
            Log::info("Restaurando usuario {$doctor->user->id}");
            
            $doctor->user->update([
                'role' => 'doctor',
                'active' => true,
            ]);
            
            $doctor->user->refresh();
            
            Log::info("Usuario restaurado - Role: {$doctor->user->role}, Active: " . ($doctor->user->active ? '1' : '0'));
        }
    }

    /**
     * Se ejecuta al eliminar permanentemente (force delete)
     * Opcional: Agregar lógica de limpieza final
     */
    public function forceDeleted(Doctor $doctor): void
    {
        Log::warning("Doctor {$doctor->id} eliminado permanentemente");
        // Aquí podrías eliminar archivos relacionados, etc.
    }
}
```

### Registrar Observer en AppServiceProvider

```php
<?php

namespace App\Providers;

use App\Models\Doctor;
use App\Models\User;
use App\Observers\DoctorObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Aquí registramos los Observers
     */
    public function boot(): void
    {
        // Registrar DoctorObserver
        Doctor::observe(DoctorObserver::class);
        
        // Registrar UserObserver (si existe)
        // User::observe(UserObserver::class);
    }
}
```

### Flujo de Ejecución con Observer

```
Ejemplo: Desactivar un doctor

1. Admin hace clic en "Desactivar" en la interfaz
   ↓
2. Petición POST a /admin/doctors/{id}/toggle
   ↓
3. DoctorController::toggleStatus() ejecuta:
   $doctor->delete(); // Soft delete
   ↓
4. Laravel detecta el delete() y dispara eventos:
   ┌─────────────────────────────────┐
   │ Evento 'deleting' (ANTES)       │
   ├─────────────────────────────────┤
   │ DoctorObserver::deleting()      │
   │  → user->role = 'patient'       │
   │  → user->active = false         │
   │  → save()                       │
   └─────────────────────────────────┘
   ↓
5. Laravel completa el soft delete:
   doctor->deleted_at = now()
   ↓
6. Evento 'deleted' (DESPUÉS)
   ↓
7. Controlador devuelve respuesta:
   return redirect()->with('success', 'Doctor desactivado');
   ↓
8. Vista se actualiza mostrando:
   - Doctor con badge "Inactivo"
   - Usuario con rol "Paciente" e "Inactivo"
```

---

## Soft Deletes

### Concepto

**Soft Delete** es una técnica que no elimina físicamente el registro de la base de datos, sino que marca un campo `deleted_at` con la fecha de "eliminación".

#### Ventajas

- ✅ **Recuperación de datos**: Puedes restaurar registros eliminados
- ✅ **Auditoría**: Mantiene historial de lo que se eliminó
- ✅ **Integridad referencial**: No rompe relaciones con otros registros
- ✅ **Análisis**: Puedes analizar qué se eliminó y cuándo

### Implementación en Modelos

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes; // ← Trait que habilita soft deletes

    protected $dates = ['deleted_at']; // Laravel 11+
    // o
    protected $casts = [
        'deleted_at' => 'datetime', // Laravel 12+
    ];
}
```

### Migración con Soft Deletes

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->string('specialty');
            $table->text('biography')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes(); // ← Agrega columna deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
```

### Consultas con Soft Deletes

```php
// ==================== CONSULTAS BÁSICAS ====================

// Obtener solo registros NO eliminados (comportamiento por defecto)
$doctors = Doctor::all();
$doctors = Doctor::where('specialty', 'Cardiología')->get();

// Incluir registros eliminados
$allDoctors = Doctor::withTrashed()->get();

// Obtener SOLO registros eliminados
$deletedDoctors = Doctor::onlyTrashed()->get();

// ==================== OPERACIONES ====================

// Soft delete (marca deleted_at)
$doctor = Doctor::find(1);
$doctor->delete();
// Resultado: deleted_at = '2025-12-18 22:30:00'

// Verificar si está eliminado
if ($doctor->trashed()) {
    echo "Doctor está inactivo (soft deleted)";
}

// Restaurar (quita deleted_at)
$doctor->restore();
// Resultado: deleted_at = NULL

// Eliminar permanentemente (force delete)
$doctor->forceDelete();
// Resultado: Registro borrado físicamente de la BD

// ==================== CONSULTAS AVANZADAS ====================

// Contar doctores activos
$activeCount = Doctor::count();

// Contar doctores eliminados
$deletedCount = Doctor::onlyTrashed()->count();

// Contar todos (activos + eliminados)
$totalCount = Doctor::withTrashed()->count();

// Buscar por ID incluyendo eliminados
$doctor = Doctor::withTrashed()->find(1);

// Restaurar múltiples registros
Doctor::onlyTrashed()
    ->where('specialty', 'Cardiología')
    ->restore();

// Eliminar permanentemente registros antiguos
Doctor::onlyTrashed()
    ->where('deleted_at', '<', now()->subMonths(6))
    ->forceDelete();
```

### Relaciones con Soft Deletes (Implementado en el Proyecto)

```php
// AdminDashboardController - Incluir doctores eliminados
public function index()
{
    // Incluir doctores con soft delete
    $doctors = Doctor::withTrashed()->with('user')->get();
    
    // Obtener schedules y appointments normalmente
    $schedules = Schedule::with('doctor.user')->get();
    $appointments = Appointment::with('patient', 'doctor')
        ->orderBy('appointment_date_time', 'desc')
        ->get();
    
    // Estadísticas
    $totalDoctors = Doctor::count();
    $totalPatients = User::where('role', 'patient')->count();
    $totalAppointments = Appointment::count();
    $pendingAppointments = Appointment::where('status', 'pending')->count();
    
    return view('dashboard.admin.index', compact(
        'doctors',
        'schedules',
        'appointments',
        'users',
        'totalDoctors',
        'totalPatients',
        'totalAppointments',
        'pendingAppointments'
    ));
}
```

---

## Middleware y Autorización

### CheckRole Middleware

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Verifica que el usuario tenga uno de los roles permitidos
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Roles permitidos (patient, doctor, admin)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificar si hay usuario autenticado
        if (!$request->user()) {
            return redirect('login')->with('error', 'Debe iniciar sesión');
        }

        // Verificar si el rol del usuario está en la lista de permitidos
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'No tiene permisos para acceder a esta sección');
        }

        return $next($request);
    }
}
```

### Registrar Middleware

```php
// bootstrap/app.php (Laravel 12)
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registrar middleware con alias
        $middleware->alias([
            'checkRole' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->create();
```

### Uso en Rutas

```php
// routes/web.php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\PatientDashboardController;

// ==================== RUTAS DE PACIENTE ====================
Route::middleware(['auth', 'checkRole:patient'])->group(function () {
    Route::get('/paciente/dashboard', [PatientDashboardController::class, 'index'])
        ->name('patient.dashboard');
    
    Route::get('/citas', [AppointmentController::class, 'index'])
        ->name('appointments.index');
    
    Route::post('/citas', [AppointmentController::class, 'store'])
        ->name('appointments.store');
});

// ==================== RUTAS DE DOCTOR ====================
Route::middleware(['auth', 'checkRole:doctor'])->group(function () {
    Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])
        ->name('doctor.dashboard');
    
    Route::post('/doctor/citas/{appointment}/status', [DoctorDashboardController::class, 'updateAppointmentStatus'])
        ->name('doctor.appointments.update-status');
});

// ==================== RUTAS DE ADMIN ====================
Route::middleware(['auth', 'checkRole:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
    
    Route::prefix('admin')->name('admin.')->group(function () {
        // Doctores
        Route::patch('/doctors/{doctor}/toggle', [AdminDoctorController::class, 'toggleStatus'])
            ->name('doctors.toggle');
        
        // Usuarios
        Route::patch('/users/{user}/toggle', [AdminUserController::class, 'toggleStatus'])
            ->name('users.toggle');
    });
});

// ==================== RUTAS MULTI-ROL ====================
// Accesible por doctores Y administradores
Route::middleware(['auth', 'checkRole:doctor,admin'])->group(function () {
    Route::get('/estadisticas', [StatsController::class, 'index']);
});
```

---

## Diagramas de Flujo

### 🔄 Flujo 1: Paciente Reserva Cita Médica

```
┌──────────────────────────────────────────────────────────────────┐
│                     INICIO: Paciente Logueado                    │
└────────────────────────────────┬─────────────────────────────────┘
                                 │
                                 ▼
                    ┌────────────────────────┐
                    │  Ir a /patient/dashboard│
                    └────────┬───────────────┘
                             │
                             ▼
                    ┌────────────────────────┐
                    │ Ver Listado de Doctores│
                    │  - Especialidades      │
                    │  - Biografías          │
                    └────────┬───────────────┘
                             │
                             ▼
                    ┌────────────────────────┐
                    │ Seleccionar Doctor     │
                    └────────┬───────────────┘
                             │
                             ▼
                    ┌────────────────────────┐
                    │ Formulario de Reserva: │
                    │  - Fecha y hora        │
                    │  - Motivo de consulta  │
                    └────────┬───────────────┘
                             │
                             ▼
                    ┌────────────────────────┐
                    │ Submit POST /citas     │
                    └────────┬───────────────┘
                             │
                             ▼
              ┌──────────────────────────────────┐
              │ Validaciones en Servidor         │
              │  ✓ Doctor existe y está activo   │
              │  ✓ Fecha es futura               │
              │  ✓ No hay cita duplicada         │
              │  ✓ Horario disponible            │
              └─────┬────────────────────┬───────┘
                    │                    │
            ✓ Válido│                    │✗ Inválido
                    ▼                    ▼
        ┌──────────────────┐    ┌──────────────────┐
        │ Crear Appointment│    │ Mostrar Errores  │
        │  status: pending │    │ Volver al Form   │
        └────────┬─────────┘    └──────────────────┘
                 │
                 ▼
        ┌──────────────────┐
        │ Redirect con     │
        │ success message  │
        └────────┬─────────┘
                 │
                 ▼
        ┌──────────────────┐
        │ Ver en Dashboard │
        │ "Cita Pendiente" │
        └──────────────────┘
                 │
                 ▼
        ┌──────────────────┐
        │ FIN              │
        └──────────────────┘
```

### 🩺 Flujo 2: Doctor Gestiona Citas

```
┌──────────────────────────────────────────────────────────────────┐
│                     INICIO: Doctor Logueado                      │
└────────────────────────────────┬─────────────────────────────────┘
                                 │
                                 ▼
                    ┌────────────────────────┐
                    │ Ir a /doctor/dashboard │
                    └────────┬───────────────┘
                             │
                             ▼
                    ┌────────────────────────┐
                    │ Ver Dashboard:         │
                    │  • Citas Pendientes    │
                    │  • Citas Confirmadas   │
                    │  • Agenda Semanal      │
                    │  • Agenda Diaria       │
                    │  • Estadísticas        │
                    └────────┬───────────────┘
                             │
              ┌──────────────┴──────────────┐
              │                             │
              ▼                             ▼
   ┌──────────────────┐        ┌──────────────────┐
   │ Ver Cita         │        │ Ver Agenda       │
   │ Pendiente        │        │ (Diaria/Semanal) │
   └────────┬─────────┘        └──────────────────┘
            │
            ▼
   ┌────────────────────────┐
   │ Decidir Acción:        │
   │  1. Confirmar          │
   │  2. Rechazar           │
   │  3. Ver Detalles       │
   └──┬──────────┬──────────┘
      │          │
   [1]│       [2]│
      ▼          ▼
┌─────────┐  ┌─────────┐
│Confirmar│  │Rechazar │
│  Cita   │  │  Cita   │
└────┬────┘  └────┬────┘
     │            │
     ▼            ▼
┌────────────────────────┐
│ POST /doctor/citas/    │
│   {id}/status          │
│                        │
│ Body:                  │
│  - status: confirmed   │
│    o canceled          │
│  - notes (opcional)    │
└────────┬───────────────┘
         │
         ▼
┌────────────────────────┐
│ Validar Autorización:  │
│  ✓ Doctor es dueño     │
│  ✓ Status válido       │
└─────┬──────────────────┘
      │
      ▼
┌────────────────────────┐
│ Actualizar Appointment │
│  - status              │
│  - notes               │
└────────┬───────────────┘
         │
         ▼
┌────────────────────────┐
│ Respuesta JSON Success │
└────────┬───────────────┘
         │
         ▼
┌────────────────────────┐
│ Actualizar Vista       │
│ (sin recargar página)  │
└────────┬───────────────┘
         │
         ▼
┌────────────────────────┐
│ FIN                    │
└────────────────────────┘
```

### 🔧 Flujo 3: Admin Gestiona Doctores (Con Soft Delete)

```
┌──────────────────────────────────────────────────────────────────┐
│                     INICIO: Admin Logueado                       │
└────────────────────────────────┬─────────────────────────────────┘
                                 │
                                 ▼
                    ┌────────────────────────┐
                    │ Ir a /admin/dashboard  │
                    └────────┬───────────────┘
                             │
                             ▼
                    ┌────────────────────────┐
                    │ Ver Lista de Doctores: │
                    │  • Activos (activo)    │
                    │  • Inactivos (deleted) │
                    └────────┬───────────────┘
                             │
              ┌──────────────┴──────────────────────┐
              │                                     │
              ▼                                     ▼
   ┌──────────────────┐              ┌──────────────────┐
   │ Doctor ACTIVO    │              │ Doctor INACTIVO  │
   │ (deleted_at=NULL)│              │ (deleted_at!=NULL│
   └────────┬─────────┘              └────────┬─────────┘
            │                                 │
            │ Click "Desactivar"              │ Click "Activar"
            │                                 │
            ▼                                 ▼
┌──────────────────────┐         ┌──────────────────────┐
│ PATCH /admin/doctors/│         │ PATCH /admin/doctors/│
│    {id}/toggle       │         │    {id}/toggle       │
└──────────┬───────────┘         └──────────┬───────────┘
           │                                │
           ▼                                ▼
┌──────────────────────┐         ┌──────────────────────┐
│ Controller:          │         │ Controller:          │
│ $doctor->delete()    │         │ $doctor->restore()   │
│ (Soft Delete)        │         │                      │
└──────────┬───────────┘         └──────────┬───────────┘
           │                                │
           ▼                                ▼
┌──────────────────────────────────────────────────────┐
│              Laravel dispara Observer                │
└──────────┬───────────────────────────────┬───────────┘
           │                               │
           ▼                               ▼
┌──────────────────────┐       ┌──────────────────────┐
│ DoctorObserver::     │       │ DoctorObserver::     │
│   deleting()         │       │   restoring()        │
│                      │       │                      │
│ • user.role=patient  │       │ • user.role=doctor   │
│ • user.active=false  │       │ • user.active=true   │
└──────────┬───────────┘       └──────────┬───────────┘
           │                               │
           ▼                               ▼
┌──────────────────────┐       ┌──────────────────────┐
│ doctor.deleted_at    │       │ doctor.deleted_at    │
│   = now()            │       │   = NULL             │
└──────────┬───────────┘       └──────────┬───────────┘
           │                               │
           └───────────────┬───────────────┘
                           │
                           ▼
                ┌──────────────────────┐
                │ Redirect con Success │
                └──────────┬───────────┘
                           │
                           ▼
                ┌──────────────────────┐
                │ Vista Actualizada:   │
                │  • Badge correcto    │
                │  • User role/active  │
                │    sincronizado      │
                └──────────┬───────────┘
                           │
                           ▼
                ┌──────────────────────┐
                │ FIN                  │
                └──────────────────────┘
```

### 📊 Flujo 4: Estados de Cita (Máquina de Estados)

```
┌──────────────────────────────────────────────────────────────────┐
│                    ESTADOS DE APPOINTMENT                        │
└──────────────────────────────────────────────────────────────────┘

          ┌──────────────┐
          │   pending    │ ← Cita creada por paciente
          └──────┬───────┘
                 │
      ┌──────────┴──────────┐
      │                     │
      ▼                     ▼
┌─────────────┐       ┌─────────────┐
│ confirmed   │       │ cancelled   │ ← Rechazada por doctor
│             │       │             │    o paciente
└──────┬──────┘       └─────────────┘
       │
       │ Doctor marca como atendida
       ▼
┌─────────────┐
│  attended   │ ← Cita completada
└─────────────┘

Transiciones Permitidas:

pending → confirmed   (Doctor confirma)
pending → cancelled   (Doctor rechaza o paciente cancela)
confirmed → attended  (Doctor marca como atendida)
confirmed → cancelled (Paciente cancela)

Transiciones NO Permitidas:

attended → cualquier otro estado (ya fue atendida)
cancelled → cualquier otro estado (ya fue cancelada)
```

---

## Gestión de Estados

### Implementación Real en DoctorDashboardController

```php
<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorDashboardController extends Controller
{
    /**
     * Actualiza el estado de una cita médica
     * Solo permite al doctor dueño de la cita modificarla
     */
    public function updateAppointmentStatus(Request $request, Appointment $appointment)
    {
        // Validar que el doctor autenticado sea el dueño de esta cita
        $doctor = Auth::user()->doctor;
        
        if ($appointment->doctor_id !== $doctor->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para modificar esta cita.'
            ], 403);
        }

        // Validar los datos recibidos
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,attended,canceled',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Actualizar el estado de la cita
        $appointment->status = $validated['status'];
        
        // Si hay notas, agregarlas
        if (!empty($validated['notes'])) {
            $appointment->notes = $validated['notes'];
        }
        
        $appointment->save();

        // Retornar respuesta JSON exitosa
        return response()->json([
            'success' => true,
            'message' => 'Estado de la cita actualizado correctamente.',
            'appointment' => $appointment
        ]);
    }
}
```

---

## Implementación Real del Proyecto

### Características Implementadas

#### ✅ Autenticación y Autorización
- Sistema de login/registro con Laravel Breeze
- Middleware `CheckRole` para control de acceso
- Tres roles: patient, doctor, admin
- Protección CSRF en todos los formularios

#### ✅ Dashboard de Doctor
El `DoctorDashboardController` implementa:

1. **Estadísticas Generales**
   ```php
   $totalAppointments = $doctor->appointments()->count();
   $pendingAppointments = $doctor->appointments()->where('status', 'pending')->count();
   $confirmedAppointments = $doctor->appointments()->where('status', 'confirmed')->count();
   $attendedAppointments = $doctor->appointments()->where('status', 'attended')->count();
   ```

2. **Agenda Diaria** (RF-13)
   - Slots de 1 hora desde 8am a 5pm
   - Estado: `booked` o `available`
   - Información del paciente si hay cita
   ```php
   private function generateDailySchedule($doctorId, $date)
   {
       // Genera slots horarios con información de citas
   }
   ```

3. **Agenda Semanal** (RF-13)
   - Vista de 7 días (Lunes a Domingo)
   - Días laborales vs no laborales
   - Contador de citas por día
   - Horas disponibles calculadas
   ```php
   private function generateWeeklySchedule($doctorId, $startOfWeek, $endOfWeek)
   {
       // Genera agenda de la semana con estadísticas
   }
   ```

4. **Gestión de Citas**
   - Actualizar estado de citas (AJAX)
   - Agregar notas médicas
   - Validación de autorización (solo doctor dueño)

#### ✅ Dashboard de Admin
El `AdminDashboardController` implementa:

```php
public function index()
{
    // Doctores incluyendo eliminados (soft deleted)
    $doctors = Doctor::withTrashed()->with('user')->get();
    
    // Schedules y appointments
    $schedules = Schedule::with('doctor.user')->get();
    $appointments = Appointment::with('patient', 'doctor')
        ->orderBy('appointment_date_time', 'desc')
        ->get();
    
    // Todos los usuarios
    $users = User::all();
    
    // Estadísticas
    $totalDoctors = Doctor::count();
    $totalPatients = User::where('role', 'patient')->count();
    $totalAppointments = Appointment::count();
    $pendingAppointments = Appointment::where('status', 'pending')->count();
    
    return view('dashboard.admin.index', compact(
        'doctors', 'schedules', 'appointments', 'users',
        'totalDoctors', 'totalPatients', 'totalAppointments', 'pendingAppointments'
    ));
}
```

#### ✅ Observers
- `DoctorObserver` registrado en `AppServiceProvider`
- Sincronización automática usuario-doctor
- Logging de cambios críticos

#### ✅ Soft Deletes
- Implementado en modelos `User` y `Doctor`
- Queries con `withTrashed()` en AdminDashboard
- Toggle activar/desactivar sin pérdida de datos

### Características NO Implementadas (Roadmap Futuro)

#### ❌ Sistema de Notificaciones
- Emails de confirmación
- Recordatorios automáticos
- Notificaciones push

#### ❌ Caché
- Redis para optimización
- Caché de consultas frecuentes

#### ❌ Rate Limiting
- Límite de peticiones por IP
- Protección contra ataques

#### ❌ API RESTful
- Endpoints públicos
- Autenticación con Sanctum
- Documentación con Swagger

#### ❌ Tests Automatizados
- Feature tests
- Unit tests
- Coverage reports

---

## Conclusión

Esta documentación técnica cubre los aspectos **reales e implementados** de **MediConnect**:

- ✅ **Arquitectura MVC**: Separación clara de capas
- ✅ **Eloquent**: Relaciones 1:1 y 1:N implementadas
- ✅ **Observers**: DoctorObserver con sincronización automática
- ✅ **Soft Deletes**: Implementado en User y Doctor
- ✅ **Middleware CheckRole**: Control de acceso RBAC
- ✅ **Diagramas de Flujo**: Actualizados y detallados
- ✅ **Código Real**: Basado en la implementación actual

### Próximos Pasos Recomendados

1. Implementar sistema de notificaciones por email
2. Agregar tests automatizados (Feature y Unit)
3. Optimizar consultas con eager loading consistente
4. Crear API RESTful con Laravel Sanctum
5. Implementar sistema de caché con Redis
6. Agregar rate limiting para seguridad

---

**Autor**: Guillén Cristófer  
**Última actualización**: Diciembre 18, 2025  
**Versión**: 1.2.1 (Documentación corregida)
