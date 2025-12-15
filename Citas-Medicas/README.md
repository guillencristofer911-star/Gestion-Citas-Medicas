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

1. [Descripción](#descripción)
2. [Características](#características)
3. [Requisitos Previos](#requisitos-previos)
4. [Instalación](#instalación)
5. [Seeders y Datos de Prueba](#seeders-y-datos-de-prueba)
6. [Configuración](#configuración)
7. [Estructura del Proyecto](#estructura-del-proyecto)
8. [Uso](#uso)
9. [Documentación Técnica](#documentación-técnica)
10. [API & Endpoints](#api--endpoints)
11. [Base de Datos](#base-de-datos)
12. [Seguridad](#seguridad)
13. [Testing](#testing)
14. [Contribución](#contribución)
15. [Licencia](#licencia)
16. [Contacto & Soporte](#contacto--soporte)

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
- ✅ Activación/desactivación de cuentas
- ✅ Validación de datos con Form Requests
- ✅ Búsqueda y filtrado de usuarios
- ✅ Registro automático como paciente

### 👨‍⚕️ Gestión de Doctores
- ✅ Registro de doctores con datos completos
- ✅ Especialidades médicas
- ✅ Número de licencia única
- ✅ Fotos de perfil
- ✅ Biografía profesional
- ✅ Estados activo/inactivo
- ✅ Relación 1:1 con usuario

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

### Personalizar Seeders

Para crear tus propios datos de prueba, edita los archivos en `database/seeders/`:

```php
// database/seeders/DoctorSeeder.php
public function run(): void
{
    $doctors = [
        [
            'user' => [
                'name' => 'Tu Doctor',
                'email' => 'doctor@example.com',
                'password' => Hash::make('password123'),
                'role' => 'doctor',
            ],
            'doctor' => [
                'license_number' => 'MED-004',
                'specialty' => 'Tu Especialidad',
                'biography' => 'Tu biografía',
                'active' => true,
            ]
        ],
    ];
    // ...
}
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
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   └── ...
│   │   │   ├── Admin/
│   │   │   │   ├── DoctorController.php      # CRUD de doctores
│   │   │   │   ├── UserController.php        # CRUD de usuarios
│   │   │   │   ├── AppointmentController.php # Gestión de citas (admin)
│   │   │   │   └── ScheduleController.php    # Gestión de horarios
│   │   │   ├── DashboardController.php       # Enrutador de dashboards
│   │   │   ├── PatientDashboardController.php # Dashboard paciente
│   │   │   ├── DoctorDashboardController.php  # Dashboard doctor
│   │   │   └── AppointmentController.php      # Gestión de citas
│   │   ├── Middleware/
│   │   │   ├── CheckRole.php                 # Control de acceso por rol
│   │   │   └── Authenticate.php              # Verificación de autenticación
│   │   └── Requests/
│   │       ├── StoreAppointmentRequest.php   # Validación de citas
│   │       └── UpdateAppointmentRequest.php  # Validación de actualización
│   ├── Models/
│   │   ├── User.php                          # Usuario (paciente/doctor/admin)
│   │   ├── Doctor.php                        # Perfil de doctor (1:1 con User)
│   │   ├── Appointment.php                   # Cita médica
│   │   └── Schedule.php                      # Horario de disponibilidad
│   └── ...
├── database/
│   ├── factories/
│   │   └── UserFactory.php                   # Factory para generar usuarios
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2024_12_XX_create_doctors_table.php
│   │   ├── 2024_12_XX_create_appointments_table.php
│   │   └── 2024_12_XX_create_schedules_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php                # Seeder principal
│       ├── DoctorSeeder.php                  # Seeder de doctores (3 doctores)
│       ├── ScheduleSeeder.php                # Seeder de horarios
│       └── AppointmentSeeder.php             # Seeder de citas
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   └── register.blade.php
│   │   ├── dashboard/
│   │   │   ├── patient.blade.php             # Dashboard paciente
│   │   │   ├── doctor.blade.php              # Dashboard doctor
│   │   │   └── admin.blade.php               # Dashboard admin
│   │   ├── admin/
│   │   │   ├── doctors/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   ├── edit.blade.php
│   │   │   │   └── show.blade.php
│   │   │   ├── users/
│   │   │   ├── appointments/
│   │   │   └── schedules/
│   │   ├── appointments/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── show.blade.php
│   │   ├── layouts/
│   │   │   ├── app.blade.php                 # Layout principal
│   │   │   ├── guest.blade.php               # Layout para invitados
│   │   │   └── navigation.blade.php          # Navegación
│   │   └── components/
│   └── css/
│       └── app.css
├── routes/
│   ├── web.php                               # Rutas principales
│   ├── api.php                               # Rutas API (si aplica)
│   ├── auth.php                              # Rutas de autenticación
│   └── console.php
├── config/
│   ├── app.php                               # Configuración de aplicación
│   ├── database.php                          # Configuración de BD
│   ├── auth.php                              # Configuración de autenticación
│   └── ...
├── tests/
│   ├── Feature/
│   │   ├── Auth/
│   │   ├── AppointmentTest.php
│   │   └── DoctorTest.php
│   └── Unit/
│       └── ModelTest.php
├── public/
│   ├── index.php
│   ├── css/
│   ├── js/
│   └── images/
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
├── .env.example
├── .gitignore
├── README.md
├── composer.json
├── package.json
├── phpunit.xml
└── artisan
```

### Descripción de Directorios Principales

| Directorio | Descripción |
|-----------|-------------|
| `app/Http/Controllers/` | Lógica de negocio y manipulación de datos |
| `app/Http/Controllers/Admin/` | Controladores específicos para administradores |
| `app/Http/Controllers/Auth/` | Controladores de autenticación (login, registro) |
| `app/Models/` | Modelos Eloquent (representan tablas de BD) |
| `app/Http/Middleware/` | Filtros y validaciones de peticiones HTTP |
| `app/Http/Requests/` | Validación centralizada de formularios |
| `resources/views/` | Plantillas Blade (interfaz visual) |
| `resources/views/dashboard/` | Vistas de dashboards por rol |
| `resources/views/admin/` | Vistas del panel de administración |
| `routes/` | Definición de URLs y rutas |
| `database/migrations/` | Historial de cambios en BD (esquema) |
| `database/seeders/` | Datos iniciales para desarrollo y testing |
| `database/factories/` | Generadores de datos falsos |
| `config/` | Configuración de la aplicación |
| `tests/` | Pruebas automatizadas (Feature y Unit) |
| `public/` | Archivos públicos accesibles (CSS, JS, imágenes) |
| `storage/` | Archivos generados (logs, cache, uploads) |

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
- Gestión completa de doctores (crear, editar, desactivar)
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

# Dra. María González (Pediatra)
Email: maria.gonzalez@hospital.com
Contraseña: password123

# Dr. Juan Rodríguez (Traumatólogo)
Email: juan.rodriguez@hospital.com
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

# O usar pacientes generados por seeders
# (Verificar en base de datos tabla 'users' donde role='patient')

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

**Validaciones aplicadas:**
- Fecha debe ser futura
- Hora debe estar dentro del horario del doctor
- No puede haber otra cita en el mismo horario
- Doctor debe estar activo

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

#### 3️⃣ Doctor Marca Cita como Atendida

```
1. Ir a Dashboard → /doctor/dashboard
2. Ver sección "Citas Confirmadas"
3. Después de atender al paciente
4. Click "Marcar como Atendida"
5. (Opcional) Agregar notas médicas
→ Estado: "attended" (atendida)
```

#### 4️⃣ Administrador Gestiona Doctores

```
1. Login como admin → /login
2. Ir a Dashboard → /admin/dashboard
3. Click "Gestión de Doctores"
4. Opciones:
   - "Crear Doctor" → Formulario de registro
   - "Editar" → Modificar datos de doctor
   - "Ver" → Ver perfil completo
   - "Desactivar" → Deshabilitar doctor
5. Click "Guardar Cambios"
→ Notificación: "Doctor actualizado exitosamente"
```

#### 5️⃣ Administrador Gestiona Horarios

```
1. Ir a /admin/schedules
2. Seleccionar doctor
3. Configurar horarios por día:
   - Lunes: 8:00 AM - 5:00 PM
   - Martes: 8:00 AM - 5:00 PM
   - ...
4. Marcar días no disponibles
5. Click "Guardar Horarios"
→ Horarios actualizados para reservas
```

### Navegación del Sistema

**Rutas Públicas:**
- `/` - Página de inicio
- `/login` - Inicio de sesión
- `/register` - Registro de nuevo paciente

**Rutas de Paciente (requiere autenticación):**
- `/paciente/dashboard` - Dashboard principal
- `/doctores` - Lista de doctores disponibles
- `/citas` - Mis citas
- `/citas/crear` - Agendar nueva cita
- `/perfil` - Mi perfil

**Rutas de Doctor (requiere autenticación + rol doctor):**
- `/doctor/dashboard` - Dashboard principal
- `/doctor/citas` - Mis citas asignadas
- `/doctor/citas/{id}/confirmar` - Confirmar cita
- `/doctor/citas/{id}/atender` - Marcar como atendida
- `/doctor/horarios` - Mis horarios
- `/doctor/perfil` - Mi perfil profesional

**Rutas de Admin (requiere autenticación + rol admin):**
- `/admin/dashboard` - Dashboard principal
- `/admin/doctores` - Gestión de doctores
- `/admin/doctores/crear` - Crear nuevo doctor
- `/admin/doctores/{id}/editar` - Editar doctor
- `/admin/usuarios` - Gestión de usuarios
- `/admin/citas` - Ver todas las citas
- `/admin/horarios` - Gestión de horarios
- `/admin/estadisticas` - Estadísticas del sistema

---

## 📚 Documentación Técnica

### Autenticación

```php
// Verificar usuario autenticado
if (Auth::check()) {
    $user = Auth::user();
    echo $user->name;
}

// Obtener ID del usuario actual
$userId = Auth::id();

// Obtener rol del usuario
$role = Auth::user()->role; // 'patient', 'doctor', 'admin'

// Loguear usuario manualmente
Auth::login($user);

// Cerrar sesión
Auth::logout();
request()->session()->invalidate();
request()->session()->regenerateToken();
```

### Autorización (RBAC)

```php
// En routes/web.php - Proteger rutas por rol
Route::middleware(['auth', 'checkRole:doctor,admin'])->group(function () {
    Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index']);
});

// En Controller - Verificar rol manualmente
public function index(Request $request)
{
    if ($request->user()->role !== 'admin') {
        abort(403, 'No autorizado');
    }
    // ...
}

// Middleware CheckRole (app/Http/Middleware/CheckRole.php)
public function handle(Request $request, Closure $next, ...$roles)
{
    if (!in_array($request->user()->role, $roles)) {
        abort(403, 'Acceso denegado');
    }
    return $next($request);
}
```

### Modelos y Relaciones Eloquent

```php
// User Model (app/Models/User.php)
class User extends Authenticatable
{
    // Relación 1:1 con Doctor (un usuario puede ser un doctor)
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }
    
    // Relación 1:N con Appointments (un paciente tiene muchas citas)
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
}

// Doctor Model (app/Models/Doctor.php)
class Doctor extends Model
{
    // Relación inversa 1:1 con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relación 1:N con Appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    
    // Relación 1:N con Schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}

// Appointment Model (app/Models/Appointment.php)
class Appointment extends Model
{
    // Relación con paciente (User)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    
    // Relación con doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}

// Schedule Model (app/Models/Schedule.php)
class Schedule extends Model
{
    // Relación con doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
```

### Ejemplos de Uso de Modelos

```php
// Obtener doctor con su usuario
$doctor = Doctor::with('user')->find(1);
echo $doctor->user->name; // "Dr. Carlos Pérez"

// Obtener citas de un doctor
$appointments = $doctor->appointments()
    ->where('status', 'pending')
    ->with('patient')
    ->get();

// Crear nueva cita
$appointment = Appointment::create([
    'patient_id' => Auth::id(),
    'doctor_id' => $request->doctor_id,
    'appointment_date_time' => $request->appointment_date_time,
    'consultation_reason' => $request->consultation_reason,
    'status' => 'pending',
]);

// Obtener usuario con su perfil de doctor (si existe)
$user = User::find(1);
if ($user->role === 'doctor') {
    $doctorProfile = $user->doctor;
    echo $doctorProfile->specialty; // "Cardiología"
}
```

### Validación de Datos

```php
// Form Request - Validación centralizada
// app/Http/Requests/StoreAppointmentRequest.php

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo pacientes pueden crear citas
        return $this->user()->role === 'patient';
    }

    public function rules(): array
    {
        return [
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date_time' => [
                'required',
                'date',
                'after:now',
                // Validación personalizada
                function ($attribute, $value, $fail) {
                    // Verificar disponibilidad del doctor
                    $exists = Appointment::where('doctor_id', $this->doctor_id)
                        ->where('appointment_date_time', $value)
                        ->where('status', '!=', 'cancelled')
                        ->exists();
                    
                    if ($exists) {
                        $fail('Ya existe una cita en ese horario.');
                    }
                },
            ],
            'consultation_reason' => 'required|string|max:500',
        ];
    }
    
    public function messages(): array
    {
        return [
            'doctor_id.required' => 'Debe seleccionar un doctor.',
            'appointment_date_time.after' => 'La fecha debe ser futura.',
            'consultation_reason.required' => 'Debe especificar el motivo de consulta.',
        ];
    }
}

// Usar en Controller
public function store(StoreAppointmentRequest $request)
{
    // Datos ya validados
    $validated = $request->validated();
    
    $appointment = Appointment::create([
        'patient_id' => Auth::id(),
        ...$validated,
        'status' => 'pending',
    ]);
    
    return redirect()->route('appointments.index')
        ->with('success', 'Cita creada exitosamente.');
}
```

### Consultas a Base de Datos

```php
// Obtener doctores activos con eager loading
$doctors = Doctor::where('active', true)
    ->with('user')
    ->orderBy('specialty')
    ->get();

// Obtener citas de un paciente con paginación
$appointments = Appointment::where('patient_id', Auth::id())
    ->with(['doctor.user'])
    ->where('status', '!=', 'cancelled')
    ->orderBy('appointment_date_time', 'desc')
    ->paginate(10);

// Verificar disponibilidad de horario
$isAvailable = !Appointment::where('doctor_id', $doctorId)
    ->where('appointment_date_time', $dateTime)
    ->whereIn('status', ['pending', 'confirmed'])
    ->exists();

// Contar citas por estado
$pendingCount = Appointment::where('doctor_id', $doctorId)
    ->where('status', 'pending')
    ->count();

// Obtener estadísticas para admin
$stats = [
    'total_doctors' => Doctor::where('active', true)->count(),
    'total_patients' => User::where('role', 'patient')->count(),
    'pending_appointments' => Appointment::where('status', 'pending')->count(),
    'confirmed_appointments' => Appointment::where('status', 'confirmed')->count(),
];

// Buscar doctores por especialidad
$cardiologists = Doctor::where('specialty', 'like', '%Cardiología%')
    ->where('active', true)
    ->with('user')
    ->get();

// Obtener horarios de un doctor por día
$mondaySchedule = Schedule::where('doctor_id', $doctorId)
    ->where('day_of_week', 1) // 1 = Lunes
    ->where('is_active', true)
    ->first();
```

### Scopes Personalizados (opcional)

```php
// En Doctor Model
public function scopeActive($query)
{
    return $query->where('active', true);
}

public function scopeBySpecialty($query, $specialty)
{
    return $query->where('specialty', $specialty);
}

// Uso
$doctors = Doctor::active()->bySpecialty('Cardiología')->get();
```

---

## 🔌 API & Endpoints

### Rutas de Autenticación

| Método | Endpoint | Descripción | Middleware |
|--------|----------|-------------|------------|
| GET | `/login` | Mostrar formulario de login | guest |
| POST | `/login` | Procesar login | guest |
| GET | `/register` | Mostrar formulario de registro | guest |
| POST | `/register` | Procesar registro (crea paciente) | guest |
| POST | `/logout` | Cerrar sesión | auth |

### Rutas de Paciente

| Método | Endpoint | Descripción | Middleware |
|--------|----------|-------------|------------|
| GET | `/paciente/dashboard` | Dashboard principal del paciente | auth, checkRole:patient |
| GET | `/doctores` | Listar doctores disponibles | auth, checkRole:patient |
| GET | `/doctores/{id}` | Ver perfil de doctor | auth, checkRole:patient |
| GET | `/citas` | Ver mis citas | auth, checkRole:patient |
| GET | `/citas/crear` | Formulario para crear cita | auth, checkRole:patient |
| POST | `/citas` | Crear nueva cita | auth, checkRole:patient |
| GET | `/citas/{id}` | Ver detalles de cita | auth, checkRole:patient |
| DELETE | `/citas/{id}` | Cancelar cita | auth, checkRole:patient |

### Rutas de Doctor

| Método | Endpoint | Descripción | Middleware |
|--------|----------|-------------|------------|
| GET | `/doctor/dashboard` | Dashboard principal del doctor | auth, checkRole:doctor |
| GET | `/doctor/citas` | Ver citas asignadas | auth, checkRole:doctor |
| GET | `/doctor/citas/{id}` | Ver detalles de cita | auth, checkRole:doctor |
| POST | `/doctor/citas/{id}/confirmar` | Confirmar cita pendiente | auth, checkRole:doctor |
| POST | `/doctor/citas/{id}/rechazar` | Rechazar cita | auth, checkRole:doctor |
| POST | `/doctor/citas/{id}/atender` | Marcar cita como atendida | auth, checkRole:doctor |
| GET | `/doctor/horarios` | Ver mis horarios | auth, checkRole:doctor |
| GET | `/doctor/perfil` | Ver perfil profesional | auth, checkRole:doctor |

### Rutas de Admin

| Método | Endpoint | Descripción | Middleware |
|--------|----------|-------------|------------|
| GET | `/admin/dashboard` | Dashboard principal de admin | auth, checkRole:admin |
| GET | `/admin/doctores` | Listar todos los doctores | auth, checkRole:admin |
| GET | `/admin/doctores/crear` | Formulario crear doctor | auth, checkRole:admin |
| POST | `/admin/doctores` | Crear nuevo doctor | auth, checkRole:admin |
| GET | `/admin/doctores/{id}` | Ver doctor | auth, checkRole:admin |
| GET | `/admin/doctores/{id}/editar` | Formulario editar doctor | auth, checkRole:admin |
| PUT | `/admin/doctores/{id}` | Actualizar doctor | auth, checkRole:admin |
| DELETE | `/admin/doctores/{id}` | Eliminar doctor | auth, checkRole:admin |
| POST | `/admin/doctores/{id}/toggle-active` | Activar/desactivar doctor | auth, checkRole:admin |
| GET | `/admin/usuarios` | Listar todos los usuarios | auth, checkRole:admin |
| GET | `/admin/usuarios/{id}` | Ver usuario | auth, checkRole:admin |
| POST | `/admin/usuarios/{id}/toggle-active` | Activar/desactivar usuario | auth, checkRole:admin |
| GET | `/admin/citas` | Ver todas las citas del sistema | auth, checkRole:admin |
| GET | `/admin/citas/{id}` | Ver detalles de cita | auth, checkRole:admin |
| GET | `/admin/horarios` | Gestionar horarios de doctores | auth, checkRole:admin |
| POST | `/admin/horarios/{doctorId}` | Guardar horarios de doctor | auth, checkRole:admin |
| GET | `/admin/estadisticas` | Ver estadísticas del sistema | auth, checkRole:admin |

### Códigos de Estado HTTP

| Código | Significado | Uso en el Sistema |
|--------|-------------|-------------------|
| 200 | OK | Operación exitosa |
| 201 | Created | Recurso creado (cita, doctor, usuario) |
| 302 | Redirect | Redirección después de acción |
| 401 | Unauthorized | Usuario no autenticado |
| 403 | Forbidden | Usuario sin permisos (rol incorrecto) |
| 404 | Not Found | Recurso no encontrado |
| 422 | Unprocessable Entity | Errores de validación |
| 500 | Server Error | Error interno del servidor |

---

## 🗄️ Base de Datos

### Diagrama ER (Entity-Relationship)

```
┌──────────────────┐
│      users       │
├──────────────────┤
│ id (PK)          │
│ name             │
│ email (UQ)       │
│ password         │
│ role (ENUM)      │          ┌──────────────────┐
│ active (BOOL)    ├──────────┤    doctors       │
│ remember_token   │  1    1  ├──────────────────┤
│ created_at       │          │ id (PK)          │
│ updated_at       │          │ user_id (FK, UQ) │
└──────────────────┘          │ license_number   │
        │                     │ specialty        │
        │ 1                   │ biography (TEXT) │
        │                     │ photo_url        │
        │ N                   │ active (BOOL)    │
        │                     │ created_at       │
┌───────┴────────┐           │ updated_at       │
│                │            └──────────────────┘
│                │                     │
│                │                     │ 1
│                │                     │
│                │        ┌────────────┴──────────────┐
│                │        │ N                         │ N
│        ┌───────────────────┐              ┌─────────────────┐
│        │   appointments    │              │    schedules    │
│        ├───────────────────┤              ├─────────────────┤
│        │ id (PK)           │              │ id (PK)         │
└────────┤ patient_id (FK)   │              │ doctor_id (FK)  │
         │ doctor_id (FK)    │              │ day_of_week (0-6)│
         │ appointment_date  │              │ start_time      │
         │ status (ENUM)     │              │ end_time        │
         │ consultation_reason│             │ is_active       │
         │ notes (TEXT)      │              │ created_at      │
         │ created_at        │              │ updated_at      │
         │ updated_at        │              └─────────────────┘
         └───────────────────┘

Relaciones:
- users (1) ──── (1) doctors [user_id]
- users (1) ──── (N) appointments [patient_id]
- doctors (1) ── (N) appointments [doctor_id]
- doctors (1) ── (N) schedules [doctor_id]
```

### Descripción de Tablas

#### 1. **users**
Almacena todos los usuarios del sistema (pacientes, doctores y administradores).

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | BIGINT UNSIGNED | Clave primaria auto-incremental |
| name | VARCHAR(255) | Nombre completo del usuario |
| email | VARCHAR(255) | Email único del usuario |
| password | VARCHAR(255) | Contraseña hasheada (Bcrypt) |
| role | ENUM('patient','doctor','admin') | Rol del usuario |
| active | BOOLEAN | Estado activo/inactivo (default: true) |
| remember_token | VARCHAR(100) | Token para "Recordarme" |
| created_at | TIMESTAMP | Fecha de creación |
| updated_at | TIMESTAMP | Fecha de última actualización |

**Índices:**
- PRIMARY KEY (id)
- UNIQUE (email)
- INDEX (role)

#### 2. **doctors**
Perfil profesional de los doctores (relación 1:1 con users).

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | BIGINT UNSIGNED | Clave primaria auto-incremental |
| user_id | BIGINT UNSIGNED | FK a users (único) |
| license_number | VARCHAR(50) | Número de licencia médica (único) |
| specialty | VARCHAR(100) | Especialidad médica |
| biography | TEXT | Biografía profesional |
| photo_url | VARCHAR(255) | URL de foto de perfil (nullable) |
| active | BOOLEAN | Estado activo/inactivo (default: true) |
| created_at | TIMESTAMP | Fecha de creación |
| updated_at | TIMESTAMP | Fecha de última actualización |

**Índices:**
- PRIMARY KEY (id)
- UNIQUE (user_id)
- UNIQUE (license_number)
- INDEX (specialty)
- INDEX (active)

**Relaciones:**
- belongsTo(User) - Un doctor pertenece a un usuario
- hasMany(Appointment) - Un doctor tiene muchas citas
- hasMany(Schedule) - Un doctor tiene muchos horarios

#### 3. **appointments**
Citas médicas entre pacientes y doctores.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | BIGINT UNSIGNED | Clave primaria auto-incremental |
| patient_id | BIGINT UNSIGNED | FK a users (rol patient) |
| doctor_id | BIGINT UNSIGNED | FK a doctors |
| appointment_date_time | DATETIME | Fecha y hora de la cita |
| status | ENUM('pending','confirmed','attended','cancelled') | Estado de la cita |
| consultation_reason | TEXT | Motivo de consulta |
| notes | TEXT | Notas adicionales (nullable) |
| created_at | TIMESTAMP | Fecha de creación |
| updated_at | TIMESTAMP | Fecha de última actualización |

**Índices:**
- PRIMARY KEY (id)
- INDEX (patient_id)
- INDEX (doctor_id)
- INDEX (appointment_date_time)
- INDEX (status)
- UNIQUE (doctor_id, appointment_date_time) - Previene duplicados

**Relaciones:**
- belongsTo(User, 'patient_id') - Una cita pertenece a un paciente
- belongsTo(Doctor) - Una cita pertenece a un doctor

**Estados de Cita:**
- `pending`: Cita creada, pendiente de confirmación del doctor
- `confirmed`: Cita confirmada por el doctor
- `attended`: Cita atendida (completada)
- `cancelled`: Cita cancelada (por paciente o doctor)

#### 4. **schedules**
Horarios de disponibilidad de los doctores.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | BIGINT UNSIGNED | Clave primaria auto-incremental |
| doctor_id | BIGINT UNSIGNED | FK a doctors |
| day_of_week | TINYINT | Día de la semana (0=Domingo, 6=Sábado) |
| start_time | TIME | Hora de inicio |
| end_time | TIME | Hora de fin |
| is_active | BOOLEAN | Horario activo (default: true) |
| created_at | TIMESTAMP | Fecha de creación |
| updated_at | TIMESTAMP | Fecha de última actualización |

**Índices:**
- PRIMARY KEY (id)
- INDEX (doctor_id)
- INDEX (day_of_week)
- INDEX (is_active)
- UNIQUE (doctor_id, day_of_week) - Un horario por día por doctor

**Relaciones:**
- belongsTo(Doctor) - Un horario pertenece a un doctor

**Días de la Semana:**
- 0 = Domingo
- 1 = Lunes
- 2 = Martes
- 3 = Miércoles
- 4 = Jueves
- 5 = Viernes
- 6 = Sábado

### Migraciones

Para ver el código completo de las migraciones, consulta:
- `database/migrations/*_create_users_table.php`
- `database/migrations/*_create_doctors_table.php`
- `database/migrations/*_create_appointments_table.php`
- `database/migrations/*_create_schedules_table.php`

### Comandos de Base de Datos

```bash
# Ver estado de migraciones
php artisan migrate:status

# Ejecutar migraciones pendientes
php artisan migrate

# Revertir última migración
php artisan migrate:rollback

# Revertir todas las migraciones
php artisan migrate:reset

# Refrescar base de datos (eliminar todo y recrear)
php artisan migrate:fresh

# Refrescar + seeders
php artisan migrate:fresh --seed

# Ver SQL que se ejecutará (sin ejecutar)
php artisan migrate --pretend
```

---

## 🔒 Seguridad

### Implementaciones de Seguridad

#### 1. **CSRF Protection (Cross-Site Request Forgery)**

Todas las rutas POST, PUT, PATCH y DELETE están protegidas con tokens CSRF.

```php
<!-- En formularios Blade -->
<form method="POST" action="{{ route('appointments.store') }}">
    @csrf
    <!-- campos del formulario -->
</form>

<!-- En peticiones AJAX (Axios) -->
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
```

#### 2. **Password Hashing**

Las contraseñas se hashean con Bcrypt (costo 12) antes de almacenarlas.

```php
// Al registrar usuario
use Illuminate\Support\Facades\Hash;

$user->password = Hash::make($request->password);

// Al validar login
if (Hash::check($request->password, $user->password)) {
    // Contraseña correcta
    Auth::login($user);
}
```

#### 3. **SQL Injection Prevention**

Uso de Eloquent ORM y Query Builder con parámetros vinculados.

```php
// ❌ VULNERABLE (NO usar)
$user = DB::select("SELECT * FROM users WHERE email = '{$email}'");

// ✅ SEGURO (Eloquent)
$user = User::where('email', $email)->first();

// ✅ SEGURO (Query Builder)
$user = DB::table('users')->where('email', $email)->first();
```

#### 4. **XSS Prevention (Cross-Site Scripting)**

Blade escapa automáticamente la salida para prevenir XSS.

```php
{{-- ✅ SEGURO (escapado automático) --}}
<p>{{ $user->bio }}</p>

{{-- ❌ PELIGROSO (sin escapar) - Solo usar con datos confiables --}}
<p>{!! $user->bio !!}</p>

{{-- ✅ SEGURO (sanitizar HTML) --}}
<p>{{ strip_tags($user->bio) }}</p>
```

#### 5. **Authorization (Control de Acceso)**

**Middleware personalizado CheckRole:**

```php
// app/Http/Middleware/CheckRole.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return redirect('login');
        }

        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'Acceso no autorizado.');
        }

        return $next($request);
    }
}
```

**Uso en rutas:**

```php
// routes/web.php
Route::middleware(['auth', 'checkRole:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
});

Route::middleware(['auth', 'checkRole:doctor,admin'])->group(function () {
    Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index']);
});
```

#### 6. **Input Validation**

Validación exhaustiva de todos los datos de entrada.

```php
$request->validate([
    'email' => 'required|email|unique:users,email',
    'password' => 'required|min:8|confirmed',
    'name' => 'required|string|max:255',
    'appointment_date_time' => 'required|date|after:now',
]);
```

#### 7. **Session Security**

Configuración segura de sesiones en `config/session.php`:

```php
'secure' => env('SESSION_SECURE_COOKIE', false), // true en producción (HTTPS)
'http_only' => true, // Previene acceso desde JavaScript
'same_site' => 'lax', // Protección CSRF adicional
```

#### 8. **Rate Limiting (Limitación de Intentos)**

Protección contra ataques de fuerza bruta en login:

```php
// app/Http/Controllers/Auth/LoginController.php
protected $maxAttempts = 5; // Máximo 5 intentos
protected $decayMinutes = 15; // Bloqueo de 15 minutos
```

### Best Practices Implementadas

- ✅ **Validación en servidor**: Nunca confiar solo en validación frontend
- ✅ **Principio de menor privilegio**: Usuarios solo acceden a lo necesario
- ✅ **Logging de acciones críticas**: Registro de actividades importantes
- ✅ **Variables de entorno**: Credenciales sensibles en .env
- ✅ **HTTPS en producción**: Forzar conexiones seguras
- ✅ **Actualización regular**: Mantener Laravel y dependencias actualizadas
- ✅ **Sanitización de inputs**: Limpiar datos antes de procesar
- ✅ **Tokens de sesión rotativos**: Regenerar tokens después de login/logout
- ✅ **Manejo seguro de errores**: No exponer información sensible en errores

### Checklist de Seguridad para Producción

```bash
# 1. Configurar .env correctamente
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

# 2. Generar nueva clave de aplicación
php artisan key:generate

# 3. Configurar permisos de archivos
chmod -R 755 storage bootstrap/cache

# 4. Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 5. Cachear configuraciones (producción)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Habilitar mantenimiento durante despliegue
php artisan down
# ... desplegar cambios ...
php artisan up
```

---

## 🧪 Testing

El proyecto incluye pruebas automatizadas para garantizar la calidad del código.

### Ejecutar Tests

```bash
# Ejecutar todos los tests
php artisan test

# Tests con información detallada
php artisan test --verbose

# Tests específicos
php artisan test --filter AuthTest
php artisan test tests/Feature/AppointmentTest.php

# Con reporte de cobertura (requiere Xdebug)
php artisan test --coverage
php artisan test --coverage-html coverage

# Tests en paralelo (más rápido)
php artisan test --parallel
```

### Estructura de Tests

```
tests/
├── Feature/                    # Tests de funcionalidades completas
│   ├── Auth/
│   │   ├── LoginTest.php       # Tests de inicio de sesión
│   │   └── RegistrationTest.php # Tests de registro
│   ├── AppointmentTest.php     # Tests de gestión de citas
│   ├── DoctorTest.php          # Tests de gestión de doctores
│   └── DashboardTest.php       # Tests de dashboards
└── Unit/                       # Tests unitarios
    ├── UserTest.php            # Tests del modelo User
    └── DoctorTest.php          # Tests del modelo Doctor
```

### Ejemplo de Test

```php
// tests/Feature/AppointmentTest.php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_create_appointment()
    {
        // Arrange: Preparar datos
        $patient = User::factory()->create(['role' => 'patient']);
        $doctor = Doctor::factory()->create();
        
        // Act: Ejecutar acción
        $response = $this->actingAs($patient)->post('/citas', [
            'doctor_id' => $doctor->id,
            'appointment_date_time' => now()->addDays(1),
            'consultation_reason' => 'Consulta general',
        ]);
        
        // Assert: Verificar resultado
        $response->assertRedirect('/citas');
        $this->assertDatabaseHas('appointments', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'status' => 'pending',
        ]);
    }
    
    public function test_doctor_cannot_access_patient_dashboard()
    {
        $doctor = User::factory()->create(['role' => 'doctor']);
        
        $response = $this->actingAs($doctor)->get('/paciente/dashboard');
        
        $response->assertStatus(403);
    }
}
```

---

## 🤝 Contribución

¡Las contribuciones son bienvenidas! Sigue estos pasos:

### 1. Fork el Repositorio

```bash
# Clonar tu fork
git clone https://github.com/TU_USUARIO/Gestion-Citas-Medicas.git
cd Gestion-Citas-Medicas/Citas-Medicas
```

### 2. Crear Rama para tu Feature

```bash
# Crear rama desde main
git checkout -b feature/nombre-descriptivo

# Ejemplos
git checkout -b feature/agregar-notificaciones
git checkout -b bugfix/corregir-validacion-citas
git checkout -b docs/mejorar-readme
```

### 3. Realizar Cambios

```bash
# Hacer cambios en el código
# ...

# Agregar archivos modificados
git add .

# Commit con mensaje descriptivo
git commit -m "feat: agregar sistema de notificaciones por email"
```

### 4. Push a tu Fork

```bash
git push origin feature/nombre-descriptivo
```

### 5. Abrir Pull Request

Ve a GitHub y abre un Pull Request con:
- **Título claro**: Describe brevemente el cambio
- **Descripción detallada**: Explica qué hace el PR y por qué
- **Screenshots**: Si aplica (cambios visuales)
- **Tests**: Asegúrate de que todos los tests pasen
- **Referencias**: Menciona issues relacionados (#123)

### Guía de Commits (Conventional Commits)

```
feat: nueva característica
fix: corrección de bug
docs: cambios en documentación
style: formateo de código (sin cambios funcionales)
refactor: refactorización de código
test: agregar o modificar tests
chore: tareas de mantenimiento (actualizar dependencias)
perf: mejoras de rendimiento

# Ejemplos
feat: agregar filtro de búsqueda de doctores por especialidad
fix: corregir validación de fechas en formulario de citas
docs: actualizar README con información de seeders
refactor: simplificar lógica de verificación de disponibilidad
test: agregar tests para AppointmentController
```

### Estándares de Código

- **PSR-12**: Seguir estándares de codificación PHP
- **Nombres descriptivos**: Variables y funciones claras
- **Comentarios**: Documentar lógica compleja
- **Tests**: Incluir tests para nuevas funcionalidades
- **Sin console.log**: Eliminar logs de depuración

### Code Review

Todo PR será revisado por un mantenedor. Se verificará:
- ✅ Funcionalidad correcta
- ✅ Tests pasando
- ✅ Código limpio y legible
- ✅ Sin conflictos de merge
- ✅ Documentación actualizada (si aplica)

---

## 📄 Licencia

Este proyecto está bajo la licencia **MIT**. Ver archivo [LICENSE](LICENSE) para más detalles.

### Resumen de la Licencia MIT

**Permisos:**
- ✅ Uso comercial
- ✅ Modificación
- ✅ Distribución
- ✅ Uso privado

**Condiciones:**
- ⚠️ Incluir licencia y copyright en distribuciones
- ⚠️ Los cambios deben estar documentados

**Limitaciones:**
- ❌ Sin garantía
- ❌ Sin responsabilidad del autor

```
MIT License

Copyright (c) 2025 Guillén Cristófer

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software...
```

---

## 📞 Contacto & Soporte

### 📧 Email
- **Desarrollador**: [guillencristofer911@gmail.com](mailto:guillencristofer911@gmail.com)
- **Soporte**: [soporte@mediconnect.local](mailto:soporte@mediconnect.local)

### 🔗 Enlaces Importantes
- 🐛 [Reportar Issues](https://github.com/guillencristofer911-star/Gestion-Citas-Medicas/issues)
- 💡 [Sugerencias de Features](https://github.com/guillencristofer911-star/Gestion-Citas-Medicas/discussions)
- 📖 [Wiki del Proyecto](https://github.com/guillencristofer911-star/Gestion-Citas-Medicas/wiki)

### 🎓 Recursos de Aprendizaje
- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [PHP The Right Way](https://phptherightway.com)
- [Eloquent ORM Guide](https://laravel.com/docs/eloquent)
- [Blade Templates](https://laravel.com/docs/blade)

### 👥 Equipo
- **Desarrollador Principal**: Guillén Cristófer
- **Última Actualización**: Diciembre 15, 2025
- **Versión**: 1.1.0

### 🌐 Comunidad
- [Laravel Español](https://laraveles.com)
- [Stack Overflow - Laravel](https://stackoverflow.com/questions/tagged/laravel)
- [Laravel News](https://laravel-news.com)

---

## 📓 Changelog

### [1.1.0] - 2025-12-15

#### ✨ Agregado
- **Seeders completos**: DatabaseSeeder, DoctorSeeder, ScheduleSeeder, AppointmentSeeder
- **Datos de prueba**: 1 admin, 3 doctores, 5 pacientes
- **Documentación de seeders**: Guía completa de uso
- **Estructura del proyecto actualizada**: Directorios y archivos detallados

#### 📝 Documentación
- Sección completa sobre seeders y datos de prueba
- Credenciales de acceso para testing
- Guía detallada de uso del sistema
- Ejemplos de código mejorados
- Tabla de contenidos reorganizada

#### 🔧 Mejoras
- README más organizado y profesional
- Instrucciones de instalación mejoradas
- Ejemplos de uso más detallados
- Mejor estructura de navegación

### [1.0.0] - 2025-12-14

#### ✨ Agregado
- Sistema completo de autenticación con 3 roles
- CRUD de doctores (Create, Read, Update, Delete)
- Sistema de reserva de citas médicas
- Gestión de horarios por doctor
- Dashboards personalizados (Paciente, Doctor, Admin)
- Validaciones avanzadas con Form Requests
- Control de acceso basado en roles (RBAC)
- Middleware personalizado CheckRole

#### 🔐 Seguridad
- Protección CSRF en todos los formularios
- Hashing de contraseñas con Bcrypt
- Prevención de SQL Injection con Eloquent
- Prevención de XSS con Blade escapado
- Autorización basada en roles

#### 📚 Documentación
- README completo y profesional
- Documentación de API y endpoints
- Guía de arquitectura MVC
- Diagramas ER de base de datos

---

## 📊 Estadísticas del Proyecto

```
Total de Líneas de Código: ~4,000+
Controladores: 10+
Modelos: 4
Migraciones: 6
Seeders: 4
Views: 30+
Tests: 15+
Documentación: Completa y detallada
Stack: Laravel 12 + PHP 8.2 + MySQL 8.0 + Blade
```

### Tecnologías Utilizadas

| Categoría | Tecnología | Versión |
|-----------|------------|--------|
| Backend Framework | Laravel | 12.x |
| Lenguaje | PHP | 8.2+ |
| Base de Datos | MySQL | 8.0+ |
| Frontend | Blade, HTML5, CSS3 | - |
| JavaScript | Vanilla JS | ES6+ |
| Autenticación | Laravel Auth | Built-in |
| ORM | Eloquent | Built-in |
| Testing | PHPUnit | 10.x |
| Dependency Management | Composer | 2.x |

---

## 🙏 Agradecimientos

Gracias a todos los que han contribuido a este proyecto:

- **Laravel Framework Team**: Por crear un framework excepcional
- **PHP Community**: Por mantener PHP moderno y robusto
- **Contribuidores**: A todos los que reportan bugs y sugieren mejoras
- **Beta Testers**: Por probar y dar feedback valioso

### Tecnologías Open Source Utilizadas

- [Laravel](https://laravel.com) - Framework PHP
- [MySQL](https://mysql.com) - Sistema de base de datos
- [Composer](https://getcomposer.org) - Gestor de dependencias PHP
- [PHPUnit](https://phpunit.de) - Framework de testing
- [Tailwind CSS](https://tailwindcss.com) - Framework CSS (si aplica)

---

## ⭐ Apoya este Proyecto

Si encuentras útil este proyecto, considera:

- ⭐ **Dar una estrella** en GitHub
- 🍴 **Fork** del proyecto para tus propios desarrollos
- 📢 **Compartir** con otros desarrolladores
- 💬 **Dejar feedback** y sugerencias
- 🐛 **Reportar bugs** para mejorar el sistema
- 📝 **Contribuir** con código o documentación
- ☕ **Invitar un café** al desarrollador (opcional)

### Cómo Dar Estrella

1. Ve al [repositorio en GitHub](https://github.com/guillencristofer911-star/Gestion-Citas-Medicas)
2. Click en el botón "⭐ Star" en la esquina superior derecha
3. ¡Eso es todo! Gracias por tu apoyo 🙏

---

## 🎯 Roadmap Futuro

### Próximas Funcionalidades

- [ ] **Sistema de notificaciones**
  - Email de confirmación de citas
  - Recordatorios automáticos
  - Notificaciones push (opcional)

- [ ] **Integración de pagos**
  - Pasarela de pago (Stripe/PayPal)
  - Facturación automática
  - Historial de pagos

- [ ] **Historial médico**
  - Expediente digital del paciente
  - Subida de archivos (estudios, recetas)
  - Notas médicas por cita

- [ ] **Sistema de valoraciones**
  - Pacientes pueden calificar doctores
  - Comentarios y reseñas
  - Sistema de reputación

- [ ] **Chat en tiempo real**
  - Mensajería entre paciente y doctor
  - WebSocket con Laravel Echo
  - Notificaciones en tiempo real

- [ ] **API RESTful completa**
  - Endpoints documentados con Swagger
  - Autenticación con Laravel Sanctum
  - Mobile app compatible

- [ ] **Dashboard mejorado**
  - Gráficos interactivos
  - Reportes en PDF
  - Exportación de datos

- [ ] **Multi-idioma (i18n)**
  - Español
  - Inglés
  - Portugués

### Mejoras Técnicas

- [ ] Implementar caché con Redis
- [ ] Queue jobs para tareas pesadas
- [ ] Optimización de consultas SQL
- [ ] Logs estructurados con Monolog
- [ ] Docker para desarrollo
- [ ] CI/CD con GitHub Actions

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

[Documentación](https://github.com/guillencristofer911-star/Gestion-Citas-Medicas/wiki) • 
[Issues](https://github.com/guillencristofer911-star/Gestion-Citas-Medicas/issues) • 
[Releases](https://github.com/guillencristofer911-star/Gestion-Citas-Medicas/releases) • 
[Changelog](https://github.com/guillencristofer911-star/Gestion-Citas-Medicas/blob/main/CHANGELOG.md)

---

**MediConnect v1.1.0** | Última actualización: Diciembre 15, 2025

</div>