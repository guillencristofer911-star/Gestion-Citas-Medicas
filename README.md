# 🏥 MediConnect

[![Laravel](https://img.shields.io/badge/Laravel-^12.0-red?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-^8.2-blue?style=flat-square&logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-green?style=flat-square&logo=mysql)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-yellow?style=flat-square)](LICENSE)
[![Last Updated](https://img.shields.io/badge/Last%20Updated-December%202025-blueviolet?style=flat-square)]()

> 🎯 **Sistema integral de gestión de citas médicas desarrollado con Laravel 12 y arquitectura MVC profesional.**

**MediConnect** es una plataforma moderna, segura y escalable que facilita la comunicación entre pacientes y doctores mediante un sistema de reserva de citas en línea con control de acceso basado en roles (RBAC).

---

## 📑 Tabla de Contenidos

1. [Descripción](#-descripción)
2. [Características](#-características)
3. [Requisitos Previos](#-requisitos-previos)
4. [Instalación](#-instalación)
5. [Seeders y Datos de Prueba](#-seeders-y-datos-de-prueba)
6. [Configuración](#️-configuración)
7. [Estructura del Proyecto](#-estructura-del-proyecto)
8. [Uso](#-uso)
9. [Documentación Técnica](#-documentación-técnica)
10. [API & Endpoints](#-api--endpoints)
11. [Base de Datos](#️-base-de-datos)
12. [Seguridad](#-seguridad)
13. [Testing](#-testing)
14. [Contribución](#-contribución)
15. [Licencia](#-licencia)
16. [Contacto & Soporte](#-contacto--soporte)

---

## 📋 Descripción

**MediConnect** es una plataforma integral de gestión de citas médicas que conecta pacientes con profesionales de salud. Implementa un control de acceso basado en roles (RBAC) con tres niveles de usuario: **Pacientes**, **Doctores** y **Administradores**.

**Casos de Uso:**
- 👥 **Pacientes**: Buscan doctores disponibles, reservan citas y gestionan su historial médico
- 👨‍⚕️ **Doctores**: Visualizan citas, confirman/rechazan solicitudes, gestionan su disponibilidad horaria
- 🔧 **Administradores**: Supervisan doctores, usuarios, citas y configuración del sistema

**Stack Tecnológico:**
```
Backend:    Laravel 12 (PHP 8.2+)
Frontend:   Blade (HTML5, CSS3, JavaScript)
Database:   MySQL 8.0+
Auth:       Laravel Session-based + RBAC
Validation: Form Requests + Validaciones Personalizadas
Security:   CSRF Protection, Password Hashing (Bcrypt), SQL Injection Prevention
```

---

## ✨ Características

### 🔐 Autenticación y Seguridad
- ✅ Registro e inicio de sesión con validación completa
- ✅ Hashing de contraseñas con Bcrypt
- ✅ Protección CSRF en todos los formularios
- ✅ Control de acceso basado en roles (RBAC)
- ✅ Middleware personalizado de autorización (`CheckRole`)
- ✅ Protección contra SQL Injection (Eloquent ORM)
- ✅ Prevención de XSS (Blade escapado automático)

### 👤 Gestión de Usuarios
- ✅ Tres roles: **patient**, **doctor**, **admin**
- ✅ Perfiles de usuario personalizados
- ✅ **Activación/desactivación de cuentas** (soft delete)
- ✅ Validación de datos con Form Requests
- ✅ Búsqueda y filtrado de usuarios
- ✅ Registro automático como paciente
- ✅ **Observers automáticos**: Cambio de rol cuando doctor es desactivado

### 👨‍⚕️ Gestión de Doctores
- ✅ Registro de doctores con datos completos
- ✅ Especialidades médicas
- ✅ Número de licencia única
- ✅ Fotos de perfil
- ✅ Biografía profesional
- ✅ **Estados activo/inactivo con soft delete**
- ✅ **Sincronización automática usuario-doctor**
- ✅ Relación 1:1 con usuario
- ✅ **DoctorObserver**: Al desactivar doctor → usuario cambia a paciente inactivo
- ✅ **Recuperación**: Al restaurar doctor → usuario vuelve a rol doctor activo

### 📅 Sistema de Citas
- ✅ Reserva de citas con validaciones avanzadas
- ✅ Verificación de disponibilidad en tiempo real
- ✅ Estados de cita: **pending**, **confirmed**, **attended**, **cancelled**
- ✅ Notas y detalles de consulta
- ✅ Historial completo de citas
- ✅ Cancelación de citas
- ✅ Prevención de duplicados

### ⏰ Horarios de Disponibilidad
- ✅ Gestión de horarios por día de la semana (0-6: Lunes-Domingo)
- ✅ Horas de inicio y fin configurables
- ✅ Activación/desactivación de horarios
- ✅ Validación automática de slots disponibles
- ✅ Impide citas fuera de horario

### 📊 Dashboards Personalizados
- ✅ Dashboard de Paciente: citas pendientes, confirmadas y canceladas
- ✅ Dashboard de Doctor: citas asignadas, pendientes de confirmación
- ✅ Dashboard de Admin: estadísticas generales, gestión completa
- ✅ **Vista de doctores inactivos** (incluye soft deleted)
- ✅ Vista rápida de información relevante
- ✅ Acceso basado en rol

### 🎯 Validaciones Avanzadas
- ✅ Validación de fechas futuras
- ✅ Verificación de horarios del doctor
- ✅ Prevención de citas duplicadas
- ✅ Mensajes de error personalizados
- ✅ Feedback en tiempo real
- ✅ Validación de email único
- ✅ Validación de doctor activo

### 🔄 Observers y Eventos (Nuevo)
- ✅ **DoctorObserver**: Automatiza cambios en usuario al gestionar doctores
  - `created`: Asigna rol 'doctor' al usuario
  - `deleting`: Cambia usuario a 'patient' inactivo antes de soft delete
  - `restoring`: Restaura usuario a 'doctor' activo
- ✅ Logging detallado de cambios
- ✅ Sincronización automática entre tablas relacionadas

---

## 🔧 Requisitos Previos

Antes de instalar, asegúrate de tener:

```bash
# Sistema Operativo
Linux / macOS / Windows (WSL2)

# Software Requerido
- PHP 8.2 o superior
- Composer 2.0+
- MySQL 8.0+
- Git 2.0+
- Node.js 18+ (opcional, para frontend)

# Extensiones PHP
- php-mysql
- php-xml
- php-json
- php-curl
- php-mbstring
- php-tokenizer
- php-gd (opcional, para imágenes)
```

### Verificar Versiones

```bash
# PHP
php --version
# Expected: PHP 8.2.0 or higher

# Composer
composer --version
# Expected: Composer 2.0.0 or higher

# MySQL
mysql --version
# Expected: MySQL 8.0.0 or higher

# Node.js (opcional)
node --version
# Expected: v18.0.0 or higher
```

---

## 📥 Instalación

### Paso 1: Clonar Repositorio

```bash
git clone https://github.com/guillencristofer911-star/Gestion-Citas-Medicas.git
cd Gestion-Citas-Medicas/Citas-Medicas
```

### Paso 2: Instalar Dependencias

```bash
composer install
```

**Tiempo estimado:** 2-3 minutos (depende de velocidad de internet)

### Paso 3: Configurar Archivo .env

```bash
# Copiar archivo de ejemplo
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### Paso 4: Configurar Base de Datos

Edita el archivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mediconnect
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Paso 5: Crear Base de Datos

```bash
# Opción 1: Crear manualmente en MySQL
mysql -u root -p
CREATE DATABASE mediconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### Paso 6: Ejecutar Migraciones y Seeders

```bash
# Opción 1: Solo migraciones (base de datos vacía)
php artisan migrate

# Opción 2: Migraciones + Seeders (RECOMENDADO para desarrollo)
php artisan migrate:fresh --seed
```

**⚠️ Nota:** `migrate:fresh --seed` eliminará todos los datos existentes y recreará las tablas con datos de prueba.

### Paso 7: Instalar Dependencias Frontend (opcional)

```bash
npm install
npm run dev  # Desarrollo
# o
npm run build  # Producción
```

### Paso 8: Iniciar Servidor

```bash
php artisan serve

# Salida esperada:
# Laravel development server started: http://127.0.0.1:8000
```

**Accede a:** `http://localhost:8000`

---

## 🌱 Seeders y Datos de Prueba

El proyecto incluye seeders para poblar la base de datos con datos de prueba durante el desarrollo.

### Seeders Disponibles

#### 1. **DatabaseSeeder** (Principal)
Seeder maestro que ejecuta todos los demás seeders en orden.

**Ejecutar:**
```bash
php artisan db:seed
# o específicamente
php artisan db:seed --class=DatabaseSeeder
```

**Crea:**
- 1 usuario administrador
- 5 usuarios pacientes (generados con Factory)
- Llama a DoctorSeeder, ScheduleSeeder y AppointmentSeeder

#### 2. **DoctorSeeder**
Crea 3 doctores con sus perfiles completos y usuarios asociados.

**Ejecutar individualmente:**
```bash
php artisan db:seed --class=DoctorSeeder
```

**Datos creados:**

| Doctor | Email | Licencia | Especialidad | Contraseña |
|--------|-------|----------|--------------|------------|
| Dr. Carlos Pérez | carlos.perez@hospital.com | MED-001 | Cardiología | password123 |
| Dra. María González | maria.gonzalez@hospital.com | MED-002 | Pediatría | password123 |
| Dr. Juan Rodríguez | juan.rodriguez@hospital.com | MED-003 | Traumatología | password123 |

#### 3. **ScheduleSeeder**
Crea horarios de disponibilidad para los doctores.

**Ejecutar individualmente:**
```bash
php artisan db:seed --class=ScheduleSeeder
```

**Horarios típicos creados:**
- Lunes a Viernes: 8:00 AM - 5:00 PM
- Días y horarios configurables por doctor

#### 4. **AppointmentSeeder**
Crea citas médicas de ejemplo con diferentes estados.

**Ejecutar individualmente:**
```bash
php artisan db:seed --class=AppointmentSeeder
```

**Crea citas con estados:**
- `pending`: Citas pendientes de confirmación
- `confirmed`: Citas confirmadas por el doctor
- `attended`: Citas ya atendidas
- `cancelled`: Citas canceladas

### Comandos Útiles de Seeders

```bash
# Ejecutar todos los seeders
php artisan db:seed

# Refrescar base de datos y ejecutar seeders
php artisan migrate:fresh --seed

# Ejecutar un seeder específico
php artisan db:seed --class=DoctorSeeder

# Ver lista de seeders disponibles
php artisan db:seed --help
```

### Datos de Acceso para Testing

#### 👨‍💼 Administrador
```
Email: admin@example.com
Contraseña: password123
Rol: admin
```

#### 👨‍⚕️ Doctores
```
# Dr. Carlos Pérez (Cardiólogo)
Email: carlos.perez@hospital.com
Contraseña: password123
Rol: doctor
Especialidad: Cardiología
Licencia: MED-001

# Dra. María González (Pediatra)
Email: maria.gonzalez@hospital.com
Contraseña: password123
Rol: doctor
Especialidad: Pediatría
Licencia: MED-002

# Dr. Juan Rodríguez (Traumatólogo)
Email: juan.rodriguez@hospital.com
Contraseña: password123
Rol: doctor
Especialidad: Traumatología
Licencia: MED-003
```

#### 👥 Pacientes
```
# 5 pacientes generados automáticamente con Factory
# Verificar en la base de datos para obtener emails y contraseñas
# Por defecto, contraseña: password123
```

---

## ⚙️ Configuración

### Variables de Entorno (.env)

```env
# Aplicación
APP_NAME="MediConnect"
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxx (generado automáticamente)
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de Datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mediconnect
DB_USERNAME=root
DB_PASSWORD=

# Cache
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=cookie
SESSION_LIFETIME=120

# Mail (opcional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@mediconnect.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 📁 Estructura del Proyecto

```
Citas-Medicas/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── Admin/
│   │   │   │   ├── AdminDashboardController.php
│   │   │   │   ├── DoctorController.php        # Gestión de doctores
│   │   │   │   ├── UserController.php          # Gestión de usuarios
│   │   │   │   ├── AppointmentController.php
│   │   │   │   └── ScheduleController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── PatientDashboardController.php
│   │   │   ├── DoctorDashboardController.php
│   │   │   └── AppointmentController.php
│   │   ├── Middleware/
│   │   │   ├── CheckRole.php                   # Control de acceso RBAC
│   │   │   └── Authenticate.php
│   │   └── Requests/
│   │       ├── StoreAppointmentRequest.php
│   │       └── UpdateAppointmentRequest.php
│   ├── Models/
│   │   ├── User.php                            # Soft deletes activado
│   │   ├── Doctor.php                          # Soft deletes activado
│   │   ├── Appointment.php
│   │   └── Schedule.php
│   ├── Observers/                              # ✨ NUEVO
│   │   ├── DoctorObserver.php                  # Sincroniza usuario-doctor
│   │   └── UserObserver.php
│   └── Providers/
│       └── AppServiceProvider.php              # Registra Observers
├── database/
│   ├── factories/
│   │   └── UserFactory.php
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2024_12_XX_create_doctors_table.php
│   │   ├── 2024_12_XX_create_appointments_table.php
│   │   ├── 2024_12_XX_create_schedules_table.php
│   │   └── 2024_12_XX_add_soft_deletes.php     # ✨ Soft deletes
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── DoctorSeeder.php
│       ├── ScheduleSeeder.php
│       └── AppointmentSeeder.php
├── resources/
│   └── views/
│       ├── auth/
│       ├── dashboard/
│       │   ├── patient.blade.php
│       │   ├── doctor.blade.php
│       │   └── admin/
│       │       └── index.blade.php             # Muestra doctores inactivos
│       ├── admin/
│       └── layouts/
├── routes/
│   ├── web.php                                 # Rutas con middleware CheckRole
│   ├── api.php
│   ├── auth.php
│   └── console.php
├── README.md                                    # Este archivo
├── TECHNICAL_DOCUMENTATION.md                   # ✨ Documentación técnica avanzada
└── ...
```

---

## 🚀 Uso

### Inicio de Sesión

Una vez ejecutados los seeders, puedes acceder con las siguientes credenciales:

#### 👨‍💼 Como Administrador
```
URL: http://localhost:8000/login
Email: admin@example.com
Contraseña: password123
Dashboard: /admin/dashboard
```

**Funcionalidades:**
- Gestión completa de doctores (crear, editar, **activar/desactivar**)
- **Ver doctores inactivos** (soft deleted)
- Gestión de usuarios (activar/desactivar cuentas)
- Gestión de horarios de doctores
- Vista de todas las citas del sistema
- Estadísticas generales

#### 👨‍⚕️ Como Doctor
```
URL: http://localhost:8000/login

# Dr. Carlos Pérez (Cardiólogo)
Email: carlos.perez@hospital.com
Contraseña: password123

Dashboard: /doctor/dashboard
```

**Funcionalidades:**
- Ver citas asignadas
- Confirmar/rechazar citas pendientes
- Marcar citas como atendidas
- Ver historial de pacientes
- Gestionar disponibilidad horaria
- Ver perfil profesional

#### 👥 Como Paciente
```
URL: http://localhost:8000/login

# Crear cuenta nueva (registro)
URL: http://localhost:8000/register

Dashboard: /paciente/dashboard
```

**Funcionalidades:**
- Buscar doctores por especialidad
- Ver perfil de doctores
- Reservar citas médicas
- Ver mis citas (pendientes, confirmadas, canceladas)
- Cancelar citas
- Ver historial médico

### Flujos de Trabajo Principales

#### 1️⃣ Paciente Reserva una Cita

```
1. Login como paciente → /login
2. Ir a Dashboard → /paciente/dashboard
3. Click "Buscar Doctores" o "Agendar Cita"
4. Seleccionar doctor por especialidad
5. Elegir fecha y hora disponible
6. Ingresar motivo de consulta
7. Click "Reservar Cita"
→ Estado: "pending" (pendiente de confirmación por el doctor)
→ Notificación: "Cita creada exitosamente"
```

#### 2️⃣ Doctor Confirma Cita

```
1. Login como doctor → /login
2. Ir a Dashboard → /doctor/dashboard
3. Ver sección "Citas Pendientes"
4. Click en cita para ver detalles
5. Revisar información del paciente y motivo
6. Click "Confirmar Cita" o "Rechazar"
→ Estado: "confirmed" (confirmada) o "cancelled" (rechazada)
→ Notificación: Enviada al paciente
```

#### 3️⃣ Administrador Gestiona Doctores

```
1. Login como admin → /login
2. Ir a Dashboard → /admin/dashboard
3. Click "Gestión de Doctores"
4. Opciones:
   - "Crear Doctor" → Formulario de registro
   - "Editar" → Modificar datos de doctor
   - "Ver" → Ver perfil completo
   - "Desactivar" → Soft delete del doctor
     ✨ Observer automático:
        • doctor.deleted_at = now()
        • user.role = 'patient'
        • user.active = false
   - "Activar" → Restaurar doctor
     ✨ Observer automático:
        • doctor.deleted_at = NULL
        • user.role = 'doctor'
        • user.active = true
5. Ver lista completa (incluye doctores inactivos)
→ Notificación: "Doctor actualizado exitosamente"
```

#### 4️⃣ Administrador Gestiona Usuarios

```
1. Ir a /admin/dashboard
2. Sección "Gestionar Usuarios"
3. Ver todos los usuarios del sistema
4. Activar/Desactivar usuarios:
   - Click botón "Desactivar" → user.active = false
   - Click botón "Activar" → user.active = true
5. Los cambios se reflejan automáticamente
→ Usuario inactivo no puede iniciar sesión
```

---

## 📚 Documentación Técnica

### Documentación Avanzada

Para información técnica detallada sobre arquitectura, Observers, Soft Deletes, Middleware, Validaciones y más, consulta:

📖 **[TECHNICAL_DOCUMENTATION.md](./TECHNICAL_DOCUMENTATION.md)**

Esta documentación incluye:
- 🏗️ Arquitectura MVC completa
- 🔄 Modelos y relaciones Eloquent
- 👁️ **Observers y eventos** (DoctorObserver explicado)
- 🗑️ **Soft Deletes** (implementación y consultas)
- 🔐 Middleware y autorización
- ✅ Validaciones personalizadas
- 📊 Flujos de negocio
- ⚡ Optimizaciones y performance
- 🔒 Seguridad avanzada

### Autenticación

```php
// Verificar usuario autenticado
if (Auth::check()) {
    $user = Auth::user();
    echo $user->name;
}

// Obtener rol del usuario
$role = Auth::user()->role; // 'patient', 'doctor', 'admin'

// Loguear usuario
Auth::login($user);

// Cerrar sesión
Auth::logout();
```

### Observers - Ejemplo Práctico

```php
// DoctorObserver registrado en AppServiceProvider
Doctor::observe(DoctorObserver::class);

// Al desactivar doctor:
$doctor->delete(); // Soft delete

// Observer ejecuta automáticamente:
public function deleting(Doctor $doctor): void
{
    $doctor->user->update([
        'role' => 'patient',  // Cambia rol
        'active' => false,    // Desactiva usuario
    ]);
}

// Resultado:
// - doctor.deleted_at = now()
// - user.role = 'patient'
// - user.active = false
```

### Soft Deletes - Consultas

```php
// Solo doctores activos (default)
$activeDoctors = Doctor::all();

// Incluir doctores eliminados (soft deleted)
$allDoctors = Doctor::withTrashed()->get();

// Solo doctores eliminados
$deletedDoctors = Doctor::onlyTrashed()->get();

// Verificar si está eliminado
if ($doctor->trashed()) {
    echo "Doctor inactivo";
}

// Restaurar doctor
$doctor->restore(); // Ejecuta Observer::restoring()

// Eliminar permanentemente
$doctor->forceDelete();
```

---

## 🔌 API & Endpoints

### Rutas de Admin - Gestión de Doctores

| Método | Endpoint | Descripción | Middleware |
|--------|----------|-------------|------------|
| GET | `/admin/dashboard` | Dashboard de admin | auth, checkRole:admin |
| GET | `/admin/doctors` | Listar todos los doctores (incluye inactivos) | auth, checkRole:admin |
| PATCH | `/admin/doctors/{id}/toggle` | Activar/desactivar doctor (soft delete) | auth, checkRole:admin |
| PUT | `/admin/doctors/{id}` | Actualizar doctor | auth, checkRole:admin |
| DELETE | `/admin/doctors/{id}` | Eliminar doctor (soft delete) | auth, checkRole:admin |

### Rutas de Admin - Gestión de Usuarios

| Método | Endpoint | Descripción | Middleware |
|--------|----------|-------------|------------|
| GET | `/admin/users` | Listar todos los usuarios | auth, checkRole:admin |
| PATCH | `/admin/users/{id}/toggle` | Activar/desactivar usuario | auth, checkRole:admin |

### Comportamiento de Soft Delete

```
PATCH /admin/doctors/{id}/toggle

Si doctor está activo (deleted_at = NULL):
  → Ejecuta: $doctor->delete()
  → Observer deleting:
      • user.role = 'patient'
      • user.active = false
  → Resultado: doctor.deleted_at = now()

Si doctor está inactivo (deleted_at != NULL):
  → Ejecuta: $doctor->restore()
  → Observer restoring:
      • user.role = 'doctor'
      • user.active = true
  → Resultado: doctor.deleted_at = NULL
```

---

## 🗄️ Base de Datos

### Diagrama ER Actualizado

```
┌────────────────────────┐
│        users           │
├────────────────────────┤
│ id (PK)                │
│ name                   │
│ email (UNIQUE)         │
│ password               │
│ role (ENUM)            │
│ active (BOOLEAN)       │  ✨ Control de acceso
│ deleted_at (TIMESTAMP) │  ✨ Soft delete
│ created_at             │
│ updated_at             │
└───┬────────────────┬───┘
    │ 1:1            │ 1:N
    ▼                ▼
┌──────────────┐   ┌─────────────┐
│   doctors    │   │ appointments│
├──────────────┤   ├─────────────┤
│ id (PK)      │   │ id (PK)     │
│ user_id (FK) │◄──┤ patient_id  │
│ license      │   │ doctor_id   │
│ specialty    │   │ date_time   │
│ active       │   │ status      │
│ deleted_at   │   │ notes       │
└──────────────┘   └─────────────┘
   ✨ Soft delete     ✨ Estados
```

### Campos Importantes

#### Tabla `users`
- **`active`**: `BOOLEAN` - Controla si el usuario puede iniciar sesión
- **`deleted_at`**: `TIMESTAMP` - Soft delete activado
- **`role`**: `ENUM('patient', 'doctor', 'admin')` - Control de acceso

#### Tabla `doctors`
- **`active`**: `BOOLEAN` - Estado del doctor (legacy, se usa deleted_at)
- **`deleted_at`**: `TIMESTAMP` - Soft delete (NULL = activo, NOT NULL = inactivo)
- **Observer sincroniza**: `deleted_at` ↔ `user.role` + `user.active`

#### Tabla `appointments`
- **`status`**: `ENUM('pending', 'confirmed', 'attended', 'cancelled')`

---

## 🔒 Seguridad

### Implementaciones de Seguridad

#### 1. **CSRF Protection**
Todas las rutas POST, PUT, PATCH y DELETE están protegidas.

```blade
<form method="POST" action="{{ route('appointments.store') }}">
    @csrf
    <!-- campos del formulario -->
</form>
```

#### 2. **Password Hashing**
Contraseñas hasheadas con Bcrypt (costo 12).

```php
use Illuminate\Support\Facades\Hash;

$user->password = Hash::make($request->password);
```

#### 3. **SQL Injection Prevention**
Uso de Eloquent ORM con parámetros vinculados.

```php
// ✅ SEGURO
$user = User::where('email', $email)->first();

// ❌ VULNERABLE (nunca usar)
$user = DB::select("SELECT * FROM users WHERE email = '$email'");
```

#### 4. **XSS Prevention**
Blade escapa automáticamente.

```blade
{{-- ✅ SEGURO (escapado automático) --}}
<p>{{ $user->bio }}</p>

{{-- ❌ PELIGROSO (sin escapar) --}}
<p>{!! $user->bio !!}</p>
```

#### 5. **Authorization (RBAC)**
Middleware `CheckRole` controla acceso por rol.

```php
// Solo admins pueden acceder
Route::middleware(['auth', 'checkRole:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});
```

#### 6. **Logging de Acciones Críticas**

```php
use Illuminate\Support\Facades\Log;

// Al desactivar usuario
Log::info('Usuario desactivado', [
    'admin_id' => Auth::id(),
    'user_id' => $user->id,
    'action' => 'deactivated',
    'timestamp' => now(),
]);
```

---

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Tests con información detallada
php artisan test --verbose

# Tests específicos
php artisan test --filter AuthTest
php artisan test tests/Feature/AppointmentTest.php

# Con reporte de cobertura
php artisan test --coverage
```

---

## 🤝 Contribución

¡Las contribuciones son bienvenidas! Sigue estos pasos:

### 1. Fork el Repositorio

```bash
git clone https://github.com/TU_USUARIO/Gestion-Citas-Medicas.git
cd Gestion-Citas-Medicas/Citas-Medicas
```

### 2. Crear Rama

```bash
git checkout -b feature/nombre-descriptivo
```

### 3. Hacer Cambios

```bash
git add .
git commit -m "feat: descripción del cambio"
```

### 4. Push y Pull Request

```bash
git push origin feature/nombre-descriptivo
```

Abre un Pull Request en GitHub.

---

## 📄 Licencia

Este proyecto está bajo la licencia **MIT**. Ver archivo [LICENSE](LICENSE) para más detalles.

---

## 📞 Contacto & Soporte

### 📧 Email
- **Desarrollador**: [guillencristofer911@gmail.com](mailto:guillencristofer911@gmail.com)

### 🔗 Enlaces
- 🐛 [Reportar Issues](https://github.com/guillencristofer911-star/Gestion-Citas-Medicas/issues)
- 💡 [Sugerencias](https://github.com/guillencristofer911-star/Gestion-Citas-Medicas/discussions)
- 📖 [Documentación Técnica](./TECHNICAL_DOCUMENTATION.md)

### 🎓 Recursos
- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Eloquent ORM Guide](https://laravel.com/docs/eloquent)
- [Blade Templates](https://laravel.com/docs/blade)

---

## 📓 Changelog

### [1.2.0] - 2025-12-18

#### ✨ Agregado
- **Observers**: `DoctorObserver` para sincronizar usuarios y doctores
- **Soft Deletes**: Implementado en modelos `User` y `Doctor`
- **Activación/Desactivación**: Sistema completo para usuarios y doctores
- **Sincronización automática**: Al desactivar doctor → usuario cambia a paciente inactivo
- **Vista de inactivos**: Admin puede ver doctores desactivados
- **Logging**: Sistema de logs para cambios críticos
- **Documentación técnica**: `TECHNICAL_DOCUMENTATION.md` completo

#### 🔧 Mejorado
- `AdminDashboardController`: Usa `withTrashed()` para incluir doctores inactivos
- `DoctorController::toggleStatus()`: Implementado soft delete con observers
- `UserController::toggleStatus()`: Control de usuarios activos/inactivos
- Middleware `CheckRole`: Verificación mejorada de acceso
- Validaciones: Form Requests con reglas personalizadas

#### 🐛 Corregido
- Observer usando evento incorrecto (`deleted` → `deleting`)
- Observer no registrado en `AppServiceProvider`
- Doctores desactivados desaparecían de la vista
- Usuarios no se sincronizaban al desactivar doctores

### [1.1.0] - 2025-12-15

#### ✨ Agregado
- **Seeders completos**: DatabaseSeeder, DoctorSeeder, ScheduleSeeder, AppointmentSeeder
- **Datos de prueba**: 1 admin, 3 doctores, 5 pacientes
- **Documentación de seeders**: Guía completa de uso

### [1.0.0] - 2025-12-14

#### ✨ Agregado
- Sistema completo de autenticación con 3 roles
- CRUD de doctores
- Sistema de reserva de citas médicas
- Gestión de horarios
- Dashboards personalizados
- Control de acceso RBAC

---

## 📊 Estadísticas del Proyecto

```
Total de Líneas de Código: ~5,000+
Controladores: 12+
Modelos: 4
Observers: 2  ✨ NUEVO
Migraciones: 7
Seeders: 4
Views: 30+
Tests: 15+
Documentación: Completa y actualizada
Stack: Laravel 12 + PHP 8.2 + MySQL 8.0 + Blade
```

---

## 🙏 Agradecimientos

Gracias a:
- **Laravel Framework Team**: Por crear un framework excepcional
- **PHP Community**: Por mantener PHP moderno y robusto
- **Contribuidores**: A todos los que reportan bugs y sugieren mejoras

---

## ⭐ Apoya este Proyecto

Si encuentras útil este proyecto:

- ⭐ **Dale una estrella** en GitHub
- 🍴 **Fork** del proyecto
- 📢 **Comparte** con otros desarrolladores
- 💬 **Deja feedback**
- 🐛 **Reporta bugs**
- 📝 **Contribuye** con código

---

## 🎯 Roadmap Futuro

### Próximas Funcionalidades

- [ ] **Sistema de notificaciones**
  - Email de confirmación de citas
  - Recordatorios automáticos
  - Notificaciones en tiempo real

- [ ] **Historial médico**
  - Expediente digital del paciente
  - Subida de archivos (estudios, recetas)
  - Notas médicas por cita

- [ ] **Sistema de valoraciones**
  - Pacientes califican doctores
  - Comentarios y reseñas

- [ ] **Chat en tiempo real**
  - Mensajería entre paciente y doctor
  - WebSocket con Laravel Echo

- [ ] **API RESTful completa**
  - Endpoints documentados con Swagger
  - Autenticación con Laravel Sanctum

- [ ] **Dashboard mejorado**
  - Gráficos interactivos
  - Reportes en PDF
  - Exportación de datos

### Mejoras Técnicas

- [ ] Tests automatizados completos (Feature + Unit)
- [ ] CI/CD con GitHub Actions
- [ ] Docker para desarrollo
- [ ] Queue jobs para tareas pesadas
- [ ] Caché con Redis
- [ ] Multi-idioma (i18n)

---

**¡Gracias por tu interés en MediConnect!**

---

<div align="center">

**Hecho con ❤️ por [Guillén Cristófer](https://github.com/guillencristofer911-star)**

[![GitHub](https://img.shields.io/badge/GitHub-guillencristofer911--star-black?style=for-the-badge&logo=github)](https://github.com/guillencristofer911-star)
[![Email](https://img.shields.io/badge/Email-guillencristofer911%40gmail.com-red?style=for-the-badge&logo=gmail)](mailto:guillencristofer911@gmail.com)

**Repositorio:** [Gestión de Citas Médicas](https://github.com/guillencristofer911-star/Gestion-Citas-Medicas)

---

### ⚡ Quick Links

[Documentación Técnica](./TECHNICAL_DOCUMENTATION.md) • 
[Issues](https://github.com/guillencristofer911-star/Gestion-Citas-Medicas/issues) • 
[Releases](https://github.com/guillencristofer911-star/Gestion-Citas-Medicas/releases) • 
[Changelog](#-changelog)

---

**MediConnect v1.2.0** | Última actualización: Diciembre 18, 2025

</div>
