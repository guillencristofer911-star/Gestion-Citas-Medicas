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
5. [Configuración](#configuración)
6. [Estructura del Proyecto](#estructura-del-proyecto)
7. [Uso](#uso)
8. [Documentación Técnica](#documentación-técnica)
9. [API & Endpoints](#api--endpoints)
10. [Base de Datos](#base-de-datos)
11. [Seguridad](#seguridad)
12. [Testing](#testing)
13. [Contribución](#contribución)
14. [Licencia](#licencia)
15. [Contacto & Soporte](#contacto--soporte)

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
```

---

## 📥 Instalación

### Paso 1: Clonar Repositorio

```bash
git clone https://github.com/guillencristofer911-star/MediConnect.git
cd MediConnect
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
CREATE DATABASE mediconnect;
EXIT;

# Opción 2: Usar script (si disponible)
php artisan db:create
```

### Paso 6: Ejecutar Migraciones

```bash
# Crear todas las tablas
php artisan migrate

# (Opcional) Con datos de prueba
php artisan migrate:fresh --seed
```

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

# Mail (opcional)
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password

# Session
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
```

---

## 📁 Estructura del Proyecto

```
MediConnect/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php           # Autenticación (login, register, logout)
│   │   │   ├── PatientDashboardController.php    # Dashboard paciente
│   │   │   ├── DoctorDashboardController.php     # Dashboard doctor
│   │   │   ├── AdminDashboardController.php      # Dashboard admin
│   │   │   ├── AppointmentController.php         # Gestión de citas
│   │   │   └── Admin/
│   │   │       ├── DoctorController.php      # CRUD de doctores
│   │   │       ├── UserController.php        # CRUD de usuarios
│   │   │       └── ScheduleController.php    # Gestión de horarios
│   │   ├── Middleware/
│   │   │   └── CheckRole.php                # Control de acceso por rol
│   │   └── Requests/
│   │       └── StoreAppointmentRequest.php  # Validación centralizada de citas
│   ├── Models/
│   │   ├── User.php                         # Usuario (paciente/doctor/admin)
│   │   ├── Doctor.php                       # Perfil de doctor
│   │   ├── Appointment.php                  # Cita médica
│   │   └── Schedule.php                     # Horario de disponibilidad
│   └── ...
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_doctors_table.php
│   │   ├── create_appointments_table.php
│   │   └── create_schedules_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       ├── DoctorSeeder.php
│       └── ScheduleSeeder.php
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   └── register.blade.php
│   │   ├── dashboard/
│   │   │   ├── patient/
│   │   │   ├── doctor/
│   │   │   └── admin/
│   │   ├── appointments/
│   │   ├── layouts/
│   │   └── ...
│   └── css/
│       └── app.css
├── routes/
│   ├── web.php                              # Rutas principales
│   └── api.php                              # Rutas API (si aplica)
├── config/
│   ├── app.php
│   ├── database.php
│   └── ...
├── tests/
│   ├── Feature/
│   └── Unit/
├── .env.example
├── .gitignore
├── README.md
├── composer.json
└── artisan
```

### Descripción de Directorios Principales

| Directorio | Descripción |
|-----------|-------------|
| `app/Http/Controllers/` | Lógica de negocio y manipulación de datos |
| `app/Models/` | Modelos Eloquent (representan tablas de BD) |
| `app/Http/Middleware/` | Filtros y validaciones de peticiones HTTP |
| `app/Http/Requests/` | Validación centralizada de formularios |
| `resources/views/` | Plantillas Blade (interfaz visual) |
| `routes/` | Definición de URLs y rutas |
| `database/migrations/` | Historial de cambios en BD |
| `database/seeders/` | Datos iniciales para desarrollo |
| `config/` | Configuración de la aplicación |

---

## 🚀 Uso

### Inicio de Sesión

#### Como Paciente
```
Email: patient@example.com
Contraseña: password123
URL: http://localhost:8000/login
```

#### Como Doctor
```
Email: doctor@example.com
Contraseña: password123
URL: http://localhost:8000/login
```

#### Como Administrador
```
Email: admin@example.com
Contraseña: password123
URL: http://localhost:8000/login
```

### Flujos Principales

#### 1️⃣ Paciente Reserva una Cita

```
1. Acceder a /paciente/dashboard
2. Click "Buscar Doctores"
3. Seleccionar doctor
4. Seleccionar fecha y hora disponibles
5. Agregar motivo de consulta
6. Confirmar reserva
→ Estado: "pending" (pendiente de confirmación)
```

#### 2️⃣ Doctor Confirma Cita

```
1. Acceder a /doctor/dashboard
2. Ver "Citas Pendientes"
3. Review detalles de paciente
4. Click "Confirmar Cita"
→ Estado: "confirmed" (confirmada)
```

#### 3️⃣ Administrador Gestiona Sistema

```
1. Acceder a /admin/dashboard
2. Gestión de doctores (agregar, editar, desactivar)
3. Gestión de usuarios (activar/desactivar)
4. Gestión de horarios por doctor
5. Vista de todas las citas
```

---

## 📚 Documentación Técnica

### Autenticación

```php
// Verificar usuario autenticado
if (Auth::check()) {
    $user = Auth::user();
}

// Obtener ID del usuario
$userId = Auth::id();

// Loguear usuario
Auth::login($user);

// Cerrar sesión
Auth::logout();
```

### Autorización (RBAC)

```php
// En routes/web.php
Route::middleware(['checkRole:doctor,admin'])->group(function () {
    Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index']);
});

// En Controller
if ($request->user()->role !== 'admin') {
    abort(403, 'Unauthorized access');
}
```

### Modelos y Relaciones

```php
// User Model
$user = User::find(1);
$user->doctor;              // Relación 1:1
$user->appointments();      // Relación 1:N

// Doctor Model
$doctor = Doctor::find(1);
$doctor->user;              // Relación N:1
$doctor->appointments();    // Citas asignadas
$doctor->schedules();       // Horarios disponibles

// Appointment Model
$appointment = Appointment::find(1);
$appointment->patient;      // Usuario paciente
$appointment->doctor;       // Doctor asignado
```

### Validación de Datos

```php
// Form Request - Validación centralizada
use App\Http\Requests\StoreAppointmentRequest;

public function store(StoreAppointmentRequest $request)
{
    // Datos ya validados y autorizados
    $validated = $request->validated();
    
    Appointment::create($validated);
}

// Reglas de validación en StoreAppointmentRequest
public function rules(): array
{
    return [
        'doctor_id' => 'required|exists:doctors,id',
        'appointment_date_time' => 'required|date|after:now',
        'consultation_reason' => 'required|string|max:500',
    ];
}
```

### Consultas a Base de Datos

```php
// Obtener doctores activos
$doctors = Doctor::where('active', true)
    ->with('user')
    ->get();

// Obtener citas de un paciente
$appointments = $patient->appointments()
    ->where('status', '!=', 'cancelled')
    ->orderBy('appointment_date_time')
    ->paginate(15);

// Verificar disponibilidad
$exists = Appointment::where('doctor_id', $doctorId)
    ->where('appointment_date_time', $dateTime)
    ->where('status', '!=', 'cancelled')
    ->exists();
```

---

## 🔌 API & Endpoints

### Rutas de Autenticación

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/login` | Formulario de login |
| POST | `/login` | Procesar login |
| GET | `/register` | Formulario de registro |
| POST | `/register` | Procesar registro |
| POST | `/logout` | Cerrar sesión |

### Rutas de Paciente

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/paciente/dashboard` | Dashboard principal |
| GET | `/paciente/doctores` | Listar doctores disponibles |
| GET | `/citas` | Ver mis citas |
| POST | `/citas` | Crear nueva cita |
| DELETE | `/citas/{id}` | Cancelar cita |

### Rutas de Doctor

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/doctor/dashboard` | Dashboard principal |
| GET | `/doctor/citas` | Ver mis citas |
| POST | `/doctor/citas/{id}/confirmar` | Confirmar cita |
| GET | `/doctor/horarios` | Ver mis horarios |

### Rutas de Admin

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/admin/dashboard` | Dashboard principal |
| GET | `/admin/doctores` | Listar doctores |
| POST | `/admin/doctores` | Crear doctor |
| PUT | `/admin/doctores/{id}` | Editar doctor |
| DELETE | `/admin/doctores/{id}` | Eliminar doctor |
| GET | `/admin/usuarios` | Listar usuarios |
| POST | `/admin/usuarios/{id}/toggle-active` | Activar/desactivar usuario |
| GET | `/admin/citas` | Ver todas las citas |

---

## 🗄️ Base de Datos

### Diagrama ER (Entity-Relationship)

```
┌──────────────┐
│    users     │
├──────────────┤
│ id (PK)      │
│ name         │
│ email (UQ)   │
│ password     │
│ role         │          ┌──────────────┐
│ active       ├──────────┤   doctors    │
│ created_at   │ 1     1  ├──────────────┤
│ updated_at   │          │ id (PK)      │
└──────────────┘          │ user_id (FK) │
                          │ license_#    │
                          │ specialty    │
                          │ biography    │
                          │ photo_url    │
                          │ active       │
                          └──────────────┘
                                  │
                                  │ 1
                                  │
                    ┌─────────────┴──────────────┐
                    │ N                          │ N
            ┌───────────────┐         ┌─────────────────┐
            │ appointments  │         │  schedules      │
            ├───────────────┤         ├─────────────────┤
            │ id (PK)       │         │ id (PK)         │
            │ patient_id    │         │ doctor_id       │
            │ doctor_id     │         │ day_of_week     │
            │ appt_date_time│         │ start_time      │
            │ status        │         │ end_time        │
            │ reason        │         │ is_active       │
            │ notes         │         └─────────────────┘
            └───────────────┘

Relaciones:
- users (1) ──── (N) appointments (como paciente)
- doctors (1) ── (N) appointments
- doctors (1) ── (N) schedules
```

### Tablas SQL

**users**: Almacena todos los usuarios (pacientes, doctores, admins)
**doctors**: Perfil profesional de doctores (relación 1:1 con users)
**appointments**: Citas médicas (relación N:1 con doctors y users)
**schedules**: Horarios disponibles de doctores (relación N:1 con doctors)

---

## 🔒 Seguridad

### Implementaciones de Seguridad

#### 1. **CSRF Protection**
```php
@csrf  <!-- En todos los formularios -->
X-CSRF-TOKEN  <!-- En headers AJAX -->
```

#### 2. **Password Hashing**
```php
// Al registrar
$user->password = Hash::make($request->password);

// Al validar
if (Hash::check($request->password, $user->password)) {
    // Contraseña correcta
}
```

#### 3. **SQL Injection Prevention**
```php
// ❌ Vulnerable
DB::select("SELECT * FROM users WHERE id = '$id'")

// ✅ Seguro (Eloquent)
User::find($id);
User::where('email', $email)->first();
```

#### 4. **XSS Prevention**
```php
{{-- ✅ Escapado automático --}}
{{ $user->bio }}

{{-- ❌ Sin escapar (evitar) --}}
{!! $user->bio !!}
```

#### 5. **Authorization (RBAC)**
```php
// Middleware
Route::middleware(['checkRole:admin'])->group(function () { ... });

// Controller
if ($request->user()->role !== 'admin') {
    abort(403);
}
```

#### 6. **Input Validation**
```php
$request->validate([
    'email' => 'required|email|unique:users',
    'password' => 'required|min:8|confirmed',
]);
```

### Best Practices Implementadas

- ✅ Validación en servidor (no confiar en frontend)
- ✅ Rate limiting (opcional con middleware)
- ✅ Logging de acciones críticas
- ✅ Autenticación con sesiones seguras
- ✅ Autorización basada en roles
- ✅ Variables de entorno para credenciales

---

## 🧪 Testing

### Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test tests/Feature/AuthTest.php

# Con reporte de cobertura
php artisan test --coverage
```

---

## 🤝 Contribución

¡Las contribuciones son bienvenidas! Sigue estos pasos:

### 1. Fork el Repositorio

```bash
git clone https://github.com/guillencristofer911-star/MediConnect.git
cd MediConnect
```

### 2. Crea una Rama

```bash
git checkout -b feature/tu-feature-name
# o
git checkout -b bugfix/tu-bug-name
```

### 3. Realiza los Cambios

```bash
# Modifica archivos
git add .
git commit -m "feat: descripción clara del cambio"
```

### 4. Push a tu Rama

```bash
git push origin feature/tu-feature-name
```

### 5. Abre un Pull Request

Ve a GitHub y abre un Pull Request con:
- Descripción clara de los cambios
- Referencias a issues relacionados
- Screenshots si aplica

### Guía de Commits

```
feat: agregar nueva feature
fix: corregir bug
docs: cambios de documentación
style: formateo de código
refactor: refactorización sin cambios funcionales
test: agregar tests
chore: actualizar dependencias
```

---

## 📄 Licencia

Este proyecto está bajo la licencia **MIT**. Ver archivo [LICENSE](LICENSE) para más detalles.

**Resumen MIT:**
- ✅ Uso comercial permitido
- ✅ Modificación permitida
- ✅ Distribución permitida
- ⚠️ Proporcionar licencia y copyright

---

## 📞 Contacto & Soporte

### 📧 Email
- Desarrollador: [guillencristofer911@gmail.com](mailto:guillencristofer911@gmail.com)
- Soporte: [soporte@mediconnect.local](mailto:soporte@mediconnect.local)

### 🔗 Enlaces Importantes
- 🐛 [Reportar Issues](https://github.com/guillencristofer911-star/MediConnect/issues)
- 💡 [Sugerencias](https://github.com/guillencristofer911-star/MediConnect/discussions)

### 🎓 Recursos de Aprendizaje
- [Laravel Official Docs](https://laravel.com/docs)
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [PHP Best Practices](https://phptherightway.com)

### 👥 Equipo
- **Desarrollador Principal:** Guillén Cristófer
- **Última Actualización:** Diciembre 2025
- **Versión:** 1.0.0

---

## 📓 Changelog

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
- Documentación de importaciones
- Documentación del backend
- Guía de arquitectura MVC

---

## 📊 Estadísticas del Proyecto

```
Total de Líneas de Código: ~3,500+
Controllers: 8
Models: 4
Migrations: 4
Views: 25+
Documentación: Completa
Stack: Laravel 12 + PHP 8.2 + MySQL 8.0
```

---

## 🙏 Agradecimientos

- Laravel Framework
- PHP Community
- Comunidad de desarrolladores

---

## ⭐ Apoya este Proyecto

Si te resulta útil, considera:
- ⭐ Dar una estrella en GitHub
- 🍴 Fork del proyecto
- 📢 Compartir con otros desarrolladores
- 💬 Dejar feedback

**¡Gracias por tu interés en MediConnect!**

---

<div align="center">

**Hecho con ❤️ por [Guillén Cristófer]**

[![GitHub](https://img.shields.io/badge/GitHub-guillencristofer911--star-black?style=flat-square&logo=github)](https://github.com/guillencristofer911-star)
[![Portfolio](https://img.shields.io/badge/Portfolio-mediconnect.local-blue?style=flat-square)](http://mediconnect.local)

**Repositorio:** [MediConnect](https://github.com/guillencristofer911-star/MediConnect)

</div>