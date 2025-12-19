# 📘 Documentación Técnica - MediConnect

## Índice

1. [Arquitectura del Sistema](#arquitectura-del-sistema)
2. [Modelos y Relaciones](#modelos-y-relaciones)
3. [Observers y Eventos](#observers-y-eventos)
4. [Soft Deletes](#soft-deletes)
5. [Middleware y Autorización](#middleware-y-autorización)
6. [Validaciones Personalizadas](#validaciones-personalizadas)
7. [Flujos de Negocio](#flujos-de-negocio)
8. [Gestión de Estados](#gestión-de-estados)
9. [Optimizaciones y Performance](#optimizaciones-y-performance)
10. [Seguridad Avanzada](#seguridad-avanzada)

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
     * Un usuario puede ser un doctor (pero no todos los usuarios son doctores)
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Relación 1:N con Appointments (como paciente)
     * Un usuario (paciente) puede tener muchas citas
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
     * Un doctor pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación 1:N con Appointment
     * Un doctor puede tener muchas citas
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Relación 1:N con Schedule
     * Un doctor puede tener muchos horarios
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

    /**
     * Scope: Doctores por especialidad
     */
    public function scopeBySpecialty($query, $specialty)
    {
        return $query->where('specialty', $specialty);
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

    /**
     * Verificar si la cita está pendiente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verificar si la cita está confirmada
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
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

### Relaciones con Soft Deletes

```php
// Obtener citas de un doctor incluyendo si está eliminado
$doctor = Doctor::withTrashed()->find(1);
$appointments = $doctor->appointments;

// Obtener solo citas de doctores activos (no eliminados)
$appointments = Appointment::whereHas('doctor', function($query) {
    $query->whereNull('deleted_at');
})->get();

// AdminDashboardController - Incluir doctores eliminados
public function index()
{
    // Incluir doctores con soft delete
    $doctors = Doctor::withTrashed()->with('user')->get();
    
    // Solo horarios de doctores activos
    $schedules = Schedule::whereHas('doctor', function($query) {
        $query->whereNull('deleted_at');
    })->with('doctor.user')->get();
    
    return view('dashboard.admin.index', compact('doctors', 'schedules'));
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
    
    Route::post('/doctor/citas/{id}/confirmar', [DoctorDashboardController::class, 'confirm'])
        ->name('doctor.appointments.confirm');
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

## Validaciones Personalizadas

### Form Request - StoreAppointmentRequest

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Appointment;
use App\Models\Doctor;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para hacer esta petición
     */
    public function authorize(): bool
    {
        // Solo pacientes pueden crear citas
        return $this->user() && $this->user()->role === 'patient';
    }

    /**
     * Reglas de validación
     */
    public function rules(): array
    {
        return [
            'doctor_id' => [
                'required',
                'exists:doctors,id',
                // Validación personalizada: Doctor debe estar activo
                function ($attribute, $value, $fail) {
                    $doctor = Doctor::find($value);
                    if (!$doctor || $doctor->trashed()) {
                        $fail('El doctor seleccionado no está disponible.');
                    }
                },
            ],
            'appointment_date_time' => [
                'required',
                'date',
                'after:now', // Fecha futura
                // Validación personalizada: Verificar disponibilidad
                function ($attribute, $value, $fail) {
                    // Verificar que no haya otra cita en ese horario
                    $exists = Appointment::where('doctor_id', $this->doctor_id)
                        ->where('appointment_date_time', $value)
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->exists();
                    
                    if ($exists) {
                        $fail('Ya existe una cita en ese horario. Por favor, elija otro.');
                    }
                    
                    // TODO: Verificar que la hora esté dentro del horario del doctor
                    // $this->validateDoctorSchedule($value, $fail);
                },
            ],
            'consultation_reason' => [
                'required',
                'string',
                'max:500',
                'min:10',
            ],
        ];
    }

    /**
     * Mensajes de error personalizados
     */
    public function messages(): array
    {
        return [
            'doctor_id.required' => 'Debe seleccionar un doctor.',
            'doctor_id.exists' => 'El doctor seleccionado no existe.',
            'appointment_date_time.required' => 'Debe especificar fecha y hora.',
            'appointment_date_time.after' => 'La fecha debe ser futura.',
            'consultation_reason.required' => 'Debe indicar el motivo de la consulta.',
            'consultation_reason.min' => 'El motivo debe tener al menos 10 caracteres.',
            'consultation_reason.max' => 'El motivo no puede exceder 500 caracteres.',
        ];
    }

    /**
     * Atributos personalizados para mensajes de error
     */
    public function attributes(): array
    {
        return [
            'doctor_id' => 'doctor',
            'appointment_date_time' => 'fecha de la cita',
            'consultation_reason' => 'motivo de consulta',
        ];
    }

    /**
     * Validación personalizada: Verificar horario del doctor
     */
    protected function validateDoctorSchedule($dateTime, $fail)
    {
        $doctor = Doctor::find($this->doctor_id);
        if (!$doctor) return;

        $date = \Carbon\Carbon::parse($dateTime);
        $dayOfWeek = $date->dayOfWeek; // 0 = Domingo, 6 = Sábado
        $time = $date->format('H:i:s');

        // Verificar si el doctor trabaja ese día
        $schedule = $doctor->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            $fail('El doctor no atiende ese día.');
            return;
        }

        // Verificar si la hora está dentro del rango
        if ($time < $schedule->start_time || $time > $schedule->end_time) {
            $fail("El doctor solo atiende de {$schedule->start_time} a {$schedule->end_time}.");
        }
    }
}
```

### Uso en Controller

```php
public function store(StoreAppointmentRequest $request)
{
    // Los datos ya están validados por el FormRequest
    $validated = $request->validated();
    
    $appointment = Appointment::create([
        'patient_id' => Auth::id(),
        'doctor_id' => $validated['doctor_id'],
        'appointment_date_time' => $validated['appointment_date_time'],
        'consultation_reason' => $validated['consultation_reason'],
        'status' => 'pending',
    ]);
    
    return redirect()->route('appointments.index')
        ->with('success', 'Cita creada exitosamente. Esperando confirmación del doctor.');
}
```

---

## Flujos de Negocio

### Flujo Completo: Crear y Gestionar una Cita

```
┌─────────────────────────────────────────────────────────┐
│  PASO 1: Paciente Reserva Cita                          │
└─────────────────────────────────────────────────────────┘

Paciente:
1. Login → /login
2. Dashboard → /paciente/dashboard
3. Click "Agendar Cita"
4. Selecciona doctor por especialidad
5. Elige fecha y hora
6. Ingresa motivo de consulta
7. Submit formulario

Sistema:
1. Valida datos (StoreAppointmentRequest)
   - Doctor existe y está activo
   - Fecha es futura
   - No hay otra cita en ese horario
   - Motivo es válido
2. Crea Appointment con status='pending'
3. Redirige a /citas con mensaje de éxito

Estado:
- Appointment.status = 'pending'
- Visible para doctor en su dashboard

┌─────────────────────────────────────────────────────────┐
│  PASO 2: Doctor Revisa y Confirma Cita                  │
└─────────────────────────────────────────────────────────┘

Doctor:
1. Login → /login
2. Dashboard → /doctor/dashboard
3. Ve sección "Citas Pendientes"
4. Click en cita para ver detalles:
   - Nombre del paciente
   - Fecha y hora
   - Motivo de consulta
5. Decide: Confirmar o Rechazar
6. Click "Confirmar Cita"

Sistema:
1. DoctorDashboardController::confirm($id)
2. Valida que el doctor sea el asignado
3. Actualiza Appointment.status = 'confirmed'
4. (Opcional) Envía notificación al paciente
5. Redirige con mensaje de éxito

Estado:
- Appointment.status = 'confirmed'
- Visible en "Citas Confirmadas" del doctor
- Visible en "Mis Citas" del paciente con badge verde

┌─────────────────────────────────────────────────────────┐
│  PASO 3: Día de la Cita - Doctor Atiende Paciente      │
└─────────────────────────────────────────────────────────┘

Doctor:
1. Dashboard → /doctor/dashboard
2. Ve "Citas Confirmadas" para hoy
3. Atiende al paciente (fuera del sistema)
4. Después de la consulta:
   - Click "Marcar como Atendida"
   - (Opcional) Agrega notas médicas
   - Submit

Sistema:
1. DoctorDashboardController::markAttended($id)
2. Actualiza:
   - Appointment.status = 'attended'
   - Appointment.notes = notas del doctor
3. Redirige con mensaje de éxito

Estado:
- Appointment.status = 'attended'
- Cita archivada en historial
- Paciente puede ver notas médicas

┌─────────────────────────────────────────────────────────┐
│  ALTERNATIVA: Cancelación de Cita                       │
└─────────────────────────────────────────────────────────┘

Paciente o Doctor:
1. Click "Cancelar Cita"
2. Confirma acción

Sistema:
1. Actualiza Appointment.status = 'cancelled'
2. (Opcional) Notifica a la otra parte
3. Libera horario para nuevas citas

Estado:
- Appointment.status = 'cancelled'
- Ya no aparece en secciones activas
- Visible en historial
```

### Estados de Cita - Máquina de Estados

```
┌─────────────────────────────────────────────────────────┐
│                ESTADOS DE APPOINTMENT                    │
└─────────────────────────────────────────────────────────┘

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

### Implementación en Controller

```php
<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorDashboardController extends Controller
{
    /**
     * Confirmar cita pendiente
     */
    public function confirmAppointment(Request $request, $appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        
        // Verificar que el doctor sea el asignado
        if ($appointment->doctor->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }
        
        // Verificar que esté pendiente
        if ($appointment->status !== 'pending') {
            return back()->with('error', 'La cita ya no está pendiente.');
        }
        
        // Cambiar estado
        $appointment->update(['status' => 'confirmed']);
        
        // TODO: Enviar notificación al paciente
        
        return back()->with('success', 'Cita confirmada exitosamente.');
    }
    
    /**
     * Marcar cita como atendida
     */
    public function markAttended(Request $request, $appointmentId)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $appointment = Appointment::findOrFail($appointmentId);
        
        // Verificar que el doctor sea el asignado
        if ($appointment->doctor->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }
        
        // Verificar que esté confirmada
        if ($appointment->status !== 'confirmed') {
            return back()->with('error', 'Solo se pueden marcar como atendidas las citas confirmadas.');
        }
        
        // Cambiar estado y agregar notas
        $appointment->update([
            'status' => 'attended',
            'notes' => $request->notes,
        ]);
        
        return back()->with('success', 'Cita marcada como atendida.');
    }
    
    /**
     * Cancelar cita
     */
    public function cancelAppointment($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        
        // Verificar autorización (doctor asignado o admin)
        if ($appointment->doctor->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'No autorizado');
        }
        
        // Verificar que NO esté atendida
        if ($appointment->status === 'attended') {
            return back()->with('error', 'No se puede cancelar una cita ya atendida.');
        }
        
        // Cambiar estado
        $appointment->update(['status' => 'cancelled']);
        
        return back()->with('success', 'Cita cancelada.');
    }
}
```

---

## Optimizaciones y Performance

### Eager Loading (Carga Anticipada)

```php
// ❌ PROBLEMA: N+1 Query
$doctors = Doctor::all();
foreach ($doctors as $doctor) {
    echo $doctor->user->name; // 1 query por cada doctor
}
// Total: 1 query inicial + N queries (uno por doctor) = N+1

// ✅ SOLUCIÓN: Eager Loading
$doctors = Doctor::with('user')->get();
foreach ($doctors as $doctor) {
    echo $doctor->user->name; // Sin queries adicionales
}
// Total: 2 queries (1 para doctors, 1 para users)

// ✅ Eager Loading múltiple
$appointments = Appointment::with(['patient', 'doctor.user'])->get();
// Total: 3 queries (appointments, users, doctors)

// ✅ Eager Loading condicional
$doctors = Doctor::with(['schedules' => function($query) {
    $query->where('is_active', true);
}])->get();
```

### Paginación

```php
// ❌ Cargar todos los registros (lento con muchos datos)
$appointments = Appointment::all();

// ✅ Paginación
$appointments = Appointment::paginate(20); // 20 por página

// En la vista Blade
{{ $appointments->links() }} // Links de paginación

// Paginación simple (solo siguiente/anterior)
$appointments = Appointment::simplePaginate(20);
```

### Caché

```php
use Illuminate\Support\Facades\Cache;

// Cachear doctores activos por 1 hora
$doctors = Cache::remember('doctors.active', 3600, function () {
    return Doctor::active()->with('user')->get();
});

// Invalidar caché cuando se actualiza
public function store(Request $request)
{
    $doctor = Doctor::create($request->validated());
    
    // Limpiar caché
    Cache::forget('doctors.active');
    
    return redirect()->back();
}
```

### Índices en Base de Datos

```php
// Migración con índices
Schema::create('appointments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('patient_id')->constrained()->onDelete('cascade');
    $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
    $table->dateTime('appointment_date_time');
    $table->enum('status', ['pending', 'confirmed', 'attended', 'cancelled']);
    $table->timestamps();
    
    // Índices para mejorar performance
    $table->index('patient_id');
    $table->index('doctor_id');
    $table->index('appointment_date_time');
    $table->index('status');
    $table->unique(['doctor_id', 'appointment_date_time']); // Prevenir duplicados
});
```

---

## Seguridad Avanzada

### Rate Limiting

```php
// routes/web.php
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

// Aplicar en rutas
Route::middleware('throttle:60,1')->group(function () {
    // Máximo 60 peticiones por minuto
});
```

### Logging de Acciones Críticas

```php
use Illuminate\Support\Facades\Log;

public function toggleStatus($userId)
{
    $user = User::findOrFail($userId);
    $previousState = $user->active;
    
    $user->active = !$user->active;
    $user->save();
    
    // Log de auditoría
    Log::channel('audit')->info('Usuario modificado', [
        'admin_id' => Auth::id(),
        'admin_email' => Auth::user()->email,
        'user_id' => $user->id,
        'user_email' => $user->email,
        'action' => $user->active ? 'activated' : 'deactivated',
        'previous_state' => $previousState,
        'new_state' => $user->active,
        'timestamp' => now(),
        'ip' => request()->ip(),
    ]);
    
    return back()->with('success', 'Usuario actualizado');
}
```

### Sanitización de Inputs

```php
use Illuminate\Support\Str;

$request->validate([
    'bio' => 'required|string',
]);

// Sanitizar HTML peligroso
$safeBio = strip_tags($request->bio, '<p><br><b><i><u>');

// O usar librerías especializadas
$safeBio = clean($request->bio); // Laravel HTML Purifier

$doctor->update([
    'biography' => $safeBio,
]);
```

---

## Conclusión

Esta documentación técnica cubre los aspectos avanzados de **MediConnect**:

- ✅ **Arquitectura MVC**: Separación clara de capas
- ✅ **Eloquent Avanzado**: Relaciones, scopes, eager loading
- ✅ **Observers**: Automatización de lógica de negocio
- ✅ **Soft Deletes**: Recuperación de datos eliminados
- ✅ **Autorización**: Control de acceso basado en roles
- ✅ **Validaciones**: Form Requests con lógica personalizada
- ✅ **Performance**: Optimizaciones y buenas prácticas
- ✅ **Seguridad**: Rate limiting, logging, sanitización

### Próximos Pasos

1. Implementar sistema de notificaciones (email/SMS)
2. Agregar tests automatizados (Feature y Unit)
3. Crear API RESTful con Laravel Sanctum
4. Implementar chat en tiempo real con Laravel Echo
5. Dashboard con gráficos interactivos
6. Exportación de reportes en PDF

---

**Autor**: Guillén Cristófer  
**Última actualización**: Diciembre 18, 2025  
**Versión**: 1.2.0
