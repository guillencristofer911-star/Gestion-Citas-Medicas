# Documentación Técnica – Sistema de Gestión de Citas Médicas

## 1. Descripción General

Sistema web desarrollado con **Laravel 12** para la gestión integral de citas médicas, que permite la interacción entre tres tipos de usuarios: **pacientes**, **doctores** y **administradores**. El sistema facilita el registro de usuarios, la gestión de médicos, la configuración de horarios de atención y la programación de citas médicas.

### Características Principales
- Sistema multi-rol (Admin, Doctor, Paciente)
- Gestión completa de citas médicas
- Configuración flexible de horarios de atención
- Panel de control específico para cada rol
- Interfaz AJAX para operaciones CRUD
- Autenticación y autorización robusta

### Tecnologías Utilizadas
- **Framework:** Laravel 12.x
- **PHP:** ^8.2
- **Base de datos:** MySQL / MariaDB
- **Frontend:** Blade Templates, JavaScript, CSS
- **Autenticación:** Laravel Auth
- **Patrón arquitectónico:** MVC (Model-View-Controller)

---

## 2. Arquitectura del Sistema

### 2.1. Estructura de Directorios

```
Citas-Medicas/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AdminDashboardController.php
│   │   │   │   ├── AppointmentController.php
│   │   │   │   ├── DoctorController.php
│   │   │   │   ├── ScheduleController.php
│   │   │   │   └── UserController.php
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   └── RegisterController.php
│   │   │   ├── AppointmentController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── DoctorDashboardController.php
│   │   │   └── PatientDashboardController.php
│   │   └── Middleware/
│   │       └── CheckRole.php (middleware personalizado)
│   └── Models/
│       ├── User.php
│       ├── Doctor.php
│       ├── Appointment.php
│       └── Schedule.php
├── config/
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2025_12_11_141027_create_doctors_table.php
│   │   ├── 2025_12_11_141051_create_appointments_table.php
│   │   ├── 2025_12_13_143647_update_appointments_status_enum.php
│   │   └── 2025_12_14_163415_create_schedules_table.php
│   └── seeders/
├── public/
│   └── index.php (punto de entrada)
├── resources/
│   └── views/
│       ├── admin/
│       ├── doctor/
│       ├── patient/
│       └── auth/
├── routes/
│   ├── web.php
│   └── api.php
└── storage/
```

### 2.2. Patrón MVC

El sistema sigue estrictamente el patrón **MVC** de Laravel:

- **Modelos (M):** Representan la lógica de negocio y acceso a datos
- **Vistas (V):** Templates Blade para la presentación
- **Controladores (C):** Gestionan las peticiones HTTP y coordinan modelos y vistas

---

## 3. Modelos de Datos

### 3.1. User (Usuario)

**Responsabilidad:** Gestiona la autenticación y datos básicos de todos los usuarios del sistema.

**Campos principales:**
```php
- id: bigint (PK)
- name: string
- email: string (único)
- password: string (encriptado)
- role: enum('admin', 'doctor', 'patient')
- active: boolean
- email_verified_at: timestamp (nullable)
- remember_token: string (nullable)
- created_at: timestamp
- updated_at: timestamp
```

**Relaciones:**
```php
// Relación uno a uno con Doctor (si role = 'doctor')
public function doctor(): HasOne
    return $this->hasOne(Doctor::class, 'user_id');

// Relación uno a muchos con Appointments (como paciente)
public function appointments(): HasMany
    return $this->hasMany(Appointment::class, 'patient_id');
```

**Atributos protegidos:**
- `$fillable`: ['name', 'email', 'password', 'role', 'active']
- `$hidden`: ['password', 'remember_token']
- `$casts`: ['email_verified_at' => 'datetime', 'active' => 'boolean']

---

### 3.2. Doctor

**Responsabilidad:** Almacena información específica de los doctores registrados en el sistema.

**Campos principales:**
```php
- id: bigint (PK)
- user_id: bigint (FK → users.id)
- license_number: string (número de licencia médica)
- specialty: string
- biography: text (nullable)
- photo_url: string (nullable)
- active: boolean
- created_at: timestamp
- updated_at: timestamp
```

**Relaciones:**
```php
// Relación con User (inversa de uno a uno)
public function user(): BelongsTo
    return $this->belongsTo(User::class, 'user_id');

// Relación uno a muchos con Appointments
public function appointments(): HasMany
    return $this->hasMany(Appointment::class, 'doctor_id');

// Relación uno a muchos con Schedules
public function schedules(): HasMany
    return $this->hasMany(Schedule::class);
```

**Atributos protegidos:**
- `$fillable`: ['user_id', 'license_number', 'specialty', 'biography', 'photo_url', 'active']
- `$casts`: ['active' => 'boolean']

---

### 3.3. Schedule (Horario)

**Responsabilidad:** Define los bloques de horarios disponibles de cada doctor para agendar citas.

**Campos principales:**
```php
- id: bigint (PK)
- doctor_id: bigint (FK → doctors.id)
- day_of_week: enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')
- start_time: time
- end_time: time
- interval_minutes: integer (duración de cada cita en minutos)
- is_active: boolean
- created_at: timestamp
- updated_at: timestamp
```

**Relaciones:**
```php
// Relación con Doctor
public function doctor(): BelongsTo
    return $this->belongsTo(Doctor::class);
```

**Atributos protegidos:**
- `$fillable`: ['doctor_id', 'day_of_week', 'start_time', 'end_time', 'interval_minutes', 'is_active']
- `$casts`: ['is_active' => 'boolean']

**Lógica de negocio:**
- Permite configurar disponibilidad por día de la semana
- Define intervalos de tiempo entre citas
- Puede activarse/desactivarse sin eliminarse

---

### 3.4. Appointment (Cita)

**Responsabilidad:** Registra las citas médicas programadas entre pacientes y doctores.

**Campos principales:**
```php
- id: bigint (PK)
- patient_id: bigint (FK → users.id)
- doctor_id: bigint (FK → doctors.id)
- appointment_date_time: datetime
- status: enum('pending', 'confirmed', 'cancelled', 'completed')
- consultation_reason: text
- notes: text (nullable)
- created_at: timestamp
- updated_at: timestamp
```

**Relaciones:**
```php
// Relación con User (paciente)
public function patient(): BelongsTo
    return $this->belongsTo(User::class, 'patient_id');

// Relación con Doctor
public function doctor(): BelongsTo
    return $this->belongsTo(Doctor::class, 'doctor_id');
```

**Atributos protegidos:**
- `$fillable`: ['patient_id', 'doctor_id', 'appointment_date_time', 'status', 'consultation_reason', 'notes']
- `$casts`: ['appointment_date_time' => 'datetime']

**Estados de cita:**
- `pending`: Cita creada, pendiente de confirmación
- `confirmed`: Cita confirmada por el doctor
- `cancelled`: Cita cancelada (por paciente o doctor)
- `completed`: Cita finalizada

---

## 4. Diagrama de Relaciones (ERD)

```
┌─────────────┐           ┌─────────────┐
│    Users    │           │   Doctors   │
├─────────────┤           ├─────────────┤
│ • id (PK)   │───────────│ • id (PK)   │
│ • name      │   1:1     │ • user_id   │
│ • email     │           │ • license   │
│ • password  │           │ • specialty │
│ • role      │           │ • active    │
│ • active    │           └──────┬──────┘
└──────┬──────┘                  │
       │                         │
       │ 1:N                     │ 1:N
       │                         │
       │                  ┌──────┴───────┐
       │                  │  Schedules   │
       │                  ├──────────────┤
       │                  │ • id (PK)    │
       │                  │ • doctor_id  │
       │                  │ • day_of_week│
       │                  │ • start_time │
       │                  │ • end_time   │
       │                  └──────────────┘
       │
       │
┌──────┴──────────────┐
│  Appointments   │
├─────────────────┤
│ • id (PK)       │
│ • patient_id    │────┐
│ • doctor_id     │────┘
│ • date_time     │
│ • status        │
│ • reason        │
└─────────────────┘
```

---

## 5. Diagramas de Flujo de Procesos

### 5.1. Flujo de Registro de Usuario

```mermaid
┌─────────────────────────────────────────────────────────────────┐
│                    REGISTRO DE USUARIO                          │
└─────────────────────────────────────────────────────────────────┘

        INICIO
          │
          ▼
    ┌─────────────┐
    │ Usuario     │
    │ accede a    │
    │ /register   │
    └──────┬──────┘
           │
           ▼
    ┌─────────────┐
    │ Formulario  │
    │ de registro │
    │ (Blade)     │
    └──────┬──────┘
           │
           ▼
    ┌─────────────────┐
    │ Ingresa datos:  │
    │ - Nombre        │
    │ - Email         │
    │ - Contraseña    │
    │ - Rol           │
    └──────┬──────────┘
           │
           ▼
    ┌──────────────────┐
    │ POST /register   │
    │ RegisterController│
    └──────┬───────────┘
           │
           ▼
    ┌──────────────────┐
    │ Validar datos    │
    │ - Email único    │
    │ - Password ≥8    │
    │ - Campos req.    │
    └──────┬───────────┘
           │
           ├─────── NO ─────┐
           │                │
          SÍ                ▼
           │         ┌─────────────┐
           │         │ Mostrar     │
           │         │ errores     │
           │         └──────┬──────┘
           │                │
           │                │
           ▼                │
    ┌─────────────┐         │
    │ Encriptar   │         │
    │ password    │         │
    │ (bcrypt)    │         │
    └──────┬──────┘         │
           │                │
           ▼                │
    ┌─────────────┐         │
    │ Crear User  │         │
    │ en BD       │         │
    └──────┬──────┘         │
           │                │
           ▼                │
    ┌─────────────┐         │
    │ ¿Rol es     │         │
    │ 'doctor'?   │         │
    └──────┬──────┘         │
           │                │
      ┌────┴────┐           │
     SÍ        NO           │
      │         │           │
      ▼         ▼           │
   ┌────┐   ┌──────────┐   │
   │ Flag│   │Redirigir │   │
   │ para│   │a /login  │   │
   │admin│   └────┬─────┘   │
   └──┬─┘        │         │
      │          │         │
      ▼          │         │
   ┌────────┐    │         │
   │Mensaje │    │         │
   │"Admin  │    │         │
   │debe    │    │         │
   │crear   │    │         │
   │doctor" │    │         │
   └───┬────┘    │         │
       │         │         │
       └─────────┴─────────┘
                 │
                 ▼
              FIN
```

---

### 5.2. Flujo de Inicio de Sesión

```
┌─────────────────────────────────────────────────────────────────┐
│                      INICIO DE SESIÓN                           │
└─────────────────────────────────────────────────────────────────┘

        INICIO
          │
          ▼
    ┌─────────────┐
    │ Usuario     │
    │ accede a    │
    │ /login      │
    └──────┬──────┘
           │
           ▼
    ┌─────────────┐
    │ Formulario  │
    │ de login    │
    └──────┬──────┘
           │
           ▼
    ┌─────────────────┐
    │ Ingresa:        │
    │ - Email         │
    │ - Password      │
    └──────┬──────────┘
           │
           ▼
    ┌──────────────────┐
    │ POST /login      │
    │ LoginController  │
    └──────┬───────────┘
           │
           ▼
    ┌──────────────────┐
    │ Validar          │
    │ credenciales     │
    │ Auth::attempt()  │
    └──────┬───────────┘
           │
      ┌────┴────┐
   VÁLIDO    INVÁLIDO
      │         │
      ▼         ▼
   ┌─────┐  ┌──────────┐
   │Crear│  │ Mensaje  │
   │sesión│  │ "Cred.   │
   └──┬──┘  │ inválidas│
      │     └────┬─────┘
      ▼          │
   ┌─────────┐   │
   │¿Usuario │   │
   │ activo? │   │
   └────┬────┘   │
        │        │
   ┌────┴────┐   │
  SÍ        NO   │
   │         │   │
   ▼         ▼   │
┌──────┐  ┌────┐ │
│Verificar Usuario│
│rol      inactivo│
└──┬─────┘ └──┬─┘ │
   │          │   │
   ▼          │   │
 ┌────────┐   │   │
 │¿Qué rol?│  │   │
 └───┬─────┘  │   │
     │        │   │
  ┌──┴──┬──┬──┘   │
  │     │  │      │
ADMIN DOCTOR      │
  │     │  PATIENT│
  ▼     ▼  ▼      │
┌───┐ ┌──┐ ┌────┐ │
│/admin│doctor│patient│
│dash│ │dash│ │dash│ │
└───┘ └──┘ └────┘ │
  │     │    │    │
  └─────┴────┴────┘
        │
        ▼
      FIN
```

---

### 5.3. Flujo de Creación de Doctor (Administrador)

```
┌─────────────────────────────────────────────────────────────────┐
│              CREACIÓN DE DOCTOR (ADMIN)                         │
└─────────────────────────────────────────────────────────────────┘

        INICIO
          │
          ▼
    ┌─────────────────┐
    │ Admin autenticado│
    │ en /admin/dashboard
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────┐
    │ Clic en botón   │
    │ "Nuevo Doctor"  │
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────┐
    │ Modal AJAX      │
    │ aparece         │
    └──────┬──────────┘
           │
           ▼
    ┌──────────────────────┐
    │ Formulario con:      │
    │ - Select User        │
    │   (solo role=doctor) │
    │ - License Number     │
    │ - Specialty          │
    │ - Biography (opt)    │
    │ - Photo (opt)        │
    └──────┬───────────────┘
           │
           ▼
    ┌──────────────────┐
    │ Admin completa   │
    │ formulario       │
    └──────┬───────────┘
           │
           ▼
    ┌──────────────────┐
    │ POST /admin/     │
    │ doctors/store    │
    │ (AJAX)           │
    └──────┬───────────┘
           │
           ▼
    ┌──────────────────┐
    │ DoctorController │
    │ ::store()        │
    └──────┬───────────┘
           │
           ▼
    ┌────────────────────┐
    │ Validar datos:     │
    │ - user_id exists   │
    │ - license unique   │
    │ - specialty req.   │
    └──────┬─────────────┘
           │
      ┌────┴────┐
   VÁLIDO    INVÁLIDO
      │         │
      ▼         ▼
   ┌─────────┐ ┌──────────┐
   │Verificar│ │Retornar  │
   │que user │ │errores   │
   │no sea   │ │JSON 422  │
   │doctor ya│ └────┬─────┘
   └────┬────┘      │
        │           │
   ┌────┴────┐      │
  SÍ        NO      │
   │         │      │
   ▼         ▼      │
┌──────┐ ┌────────┐ │
│Crear │ │Error:  │ │
│Doctor│ │"Ya es  │ │
│en BD │ │doctor" │ │
└──┬───┘ └───┬────┘ │
   │         │      │
   ▼         │      │
┌──────────┐ │      │
│Subir foto│ │      │
│si existe │ │      │
└────┬─────┘ │      │
     │       │      │
     ▼       │      │
┌──────────┐ │      │
│Retornar  │ │      │
│success   │ │      │
│JSON 200  │ │      │
└────┬─────┘ │      │
     │       │      │
     └───────┴──────┘
           │
           ▼
    ┌──────────────┐
    │ JavaScript   │
    │ actualiza    │
    │ tabla sin    │
    │ recargar     │
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐
    │ Cerrar modal │
    │ Mostrar      │
    │ notificación │
    └──────┬───────┘
           │
           ▼
         FIN
```

---

### 5.4. Flujo de Configuración de Horarios

```
┌─────────────────────────────────────────────────────────────────┐
│           CONFIGURACIÓN DE HORARIOS (ADMIN)                     │
└─────────────────────────────────────────────────────────────────┘

        INICIO
          │
          ▼
    ┌─────────────────┐
    │ Admin selecciona│
    │ doctor desde    │
    │ tabla           │
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────┐
    │ Clic "Gestionar │
    │ Horarios"       │
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────┐
    │ Modal con lista │
    │ de horarios     │
    │ existentes      │
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────┐
    │ Clic "Agregar   │
    │ Horario"        │
    └──────┬──────────┘
           │
           ▼
    ┌──────────────────────┐
    │ Formulario:          │
    │ - Day of Week        │
    │   (select)           │
    │ - Start Time         │
    │   (time picker)      │
    │ - End Time           │
    │   (time picker)      │
    │ - Interval Minutes   │
    │   (number: 15,30,60) │
    │ - Is Active (check)  │
    └──────┬───────────────┘
           │
           ▼
    ┌──────────────────┐
    │ Admin completa   │
    │ datos            │
    └──────┬───────────┘
           │
           ▼
    ┌──────────────────┐
    │ POST /admin/     │
    │ schedules/store  │
    └──────┬───────────┘
           │
           ▼
    ┌──────────────────────┐
    │ ScheduleController   │
    │ ::store()            │
    └──────┬───────────────┘
           │
           ▼
    ┌────────────────────┐
    │ Validar:           │
    │ - doctor_id exists │
    │ - day_of_week enum │
    │ - start < end      │
    │ - interval > 0     │
    └──────┬─────────────┘
           │
      ┌────┴────┐
   VÁLIDO    INVÁLIDO
      │         │
      ▼         ▼
   ┌─────────┐ ┌──────────┐
   │Verificar│ │Retornar  │
   │solapa-  │ │errores   │
   │mientos  │ │JSON 422  │
   └────┬────┘ └────┬─────┘
        │           │
   ┌────┴────┐      │
  NO       SÍ       │
 SOLAPA   SOLAPA    │
   │        │       │
   ▼        ▼       │
┌──────┐ ┌────────┐ │
│Crear │ │Error:  │ │
│Schedule│Horarios│ │
│en BD │ │se      │ │
│      │ │solapan │ │
└──┬───┘ └───┬────┘ │
   │         │      │
   ▼         │      │
┌──────────┐ │      │
│Retornar  │ │      │
│success   │ │      │
│con datos │ │      │
│creados   │ │      │
└────┬─────┘ │      │
     │       │      │
     └───────┴──────┘
           │
           ▼
    ┌──────────────┐
    │ Actualizar   │
    │ lista de     │
    │ horarios     │
    │ (AJAX)       │
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐
    │ Notificación │
    │ de éxito     │
    └──────┬───────┘
           │
           ▼
         FIN
```

---

### 5.5. Flujo de Agendamiento de Cita (Paciente)

```
┌─────────────────────────────────────────────────────────────────┐
│              AGENDAMIENTO DE CITA (PACIENTE)                    │
└─────────────────────────────────────────────────────────────────┘

        INICIO
          │
          ▼
    ┌─────────────────┐
    │ Paciente        │
    │ autenticado en  │
    │ /patient/dashboard
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────┐
    │ Ve lista de     │
    │ doctores        │
    │ disponibles     │
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────┐
    │ Selecciona      │
    │ doctor          │
    └──────┬──────────┘
           │
           ▼
    ┌──────────────────────┐
    │ Sistema carga:       │
    │ - Horarios del doctor│
    │ - Citas existentes   │
    │ - Genera slots       │
    │   disponibles        │
    └──────┬───────────────┘
           │
           ▼
    ┌────────────────────┐
    │ Algoritmo de       │
    │ disponibilidad:    │
    │                    │
    │ FOR cada schedule: │
    │   IF day matches:  │
    │     Genera slots   │
    │     cada interval  │
    │     EXCLUYE citas  │
    │     existentes     │
    └──────┬─────────────┘
           │
           ▼
    ┌─────────────────┐
    │ Muestra         │
    │ calendario con  │
    │ slots verdes    │
    │ (disponibles)   │
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────┐
    │ Paciente        │
    │ selecciona      │
    │ fecha y hora    │
    └──────┬──────────┘
           │
           ▼
    ┌──────────────────────┐
    │ Formulario de cita:  │
    │ - Fecha/Hora (fija)  │
    │ - Motivo consulta    │
    │   (textarea)         │
    └──────┬───────────────┘
           │
           ▼
    ┌──────────────────┐
    │ POST /citas      │
    │ AppointmentController
    └──────┬───────────┘
           │
           ▼
    ┌────────────────────┐
    │ Validar:           │
    │ - doctor_id valid  │
    │ - date > now       │
    │ - reason required  │
    └──────┬─────────────┘
           │
      ┌────┴────┐
   VÁLIDO    INVÁLIDO
      │         │
      ▼         ▼
   ┌─────────┐ ┌──────────┐
   │Verificar│ │Retornar  │
   │disponi- │ │errores   │
   │bilidad  │ └────┬─────┘
   └────┬────┘      │
        │           │
   ┌────┴────┐      │
  DISPONIBLE        │
  OCUPADO           │
   │    │           │
   ▼    ▼           │
┌──────┐ ┌────────┐ │
│Crear │ │Error:  │ │
│Appointment│Horario│
│status │ │ocupado │ │
│pending│ └───┬────┘ │
└──┬───┘     │      │
   │         │      │
   ▼         │      │
┌──────────┐ │      │
│Generar   │ │      │
│ID único  │ │      │
│para cita │ │      │
└────┬─────┘ │      │
     │       │      │
     ▼       │      │
┌──────────┐ │      │
│Guardar en│ │      │
│BD        │ │      │
└────┬─────┘ │      │
     │       │      │
     ▼       │      │
┌──────────┐ │      │
│(Futuro:  │ │      │
│Enviar    │ │      │
│email a   │ │      │
│doctor)   │ │      │
└────┬─────┘ │      │
     │       │      │
     └───────┴──────┘
           │
           ▼
    ┌──────────────┐
    │ Redirigir a  │
    │ dashboard con│
    │ mensaje éxito│
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐
    │ Actualizar   │
    │ lista "Mis   │
    │ Citas"       │
    └──────┬───────┘
           │
           ▼
         FIN
```

---

### 5.6. Flujo de Gestión de Cita (Doctor)

```
┌─────────────────────────────────────────────────────────────────┐
│              GESTIÓN DE CITA (DOCTOR)                           │
└─────────────────────────────────────────────────────────────────┘

        INICIO
          │
          ▼
    ┌─────────────────┐
    │ Doctor          │
    │ autenticado en  │
    │ /doctor/dashboard│
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────────┐
    │ Sistema carga:      │
    │ - Citas asignadas   │
    │   a este doctor     │
    │ - Agrupadas por     │
    │   estado y fecha    │
    └──────┬──────────────┘
           │
           ▼
    ┌─────────────────┐
    │ Vista de citas: │
    │ ┌─────────────┐ │
    │ │ PENDIENTES  │ │
    │ │ (badge rojo)│ │
    │ ├─────────────┤ │
    │ │ CONFIRMADAS │ │
    │ │ (badge azul)│ │
    │ ├─────────────┤ │
    │ │ COMPLETADAS │ │
    │ │(badge verde)│ │
    │ └─────────────┘ │
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────┐
    │ Doctor          │
    │ selecciona      │
    │ una cita        │
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────┐
    │ Modal detalle:  │
    │ - Paciente      │
    │ - Fecha/Hora    │
    │ - Motivo        │
    │ - Estado actual │
    │ - Botones acción│
    └──────┬──────────┘
           │
           ▼
    ┌──────────────────┐
    │ Doctor elige     │
    │ acción:          │
    └──────┬───────────┘
           │
      ┌────┼────┬────────┐
      │    │    │        │
   CONFIRMAR COMPLETAR CANCELAR
      │    │    │        │
      ▼    ▼    ▼        ▼
   ┌────┐┌────┐┌────┐┌────┐
   │POST││POST││POST││POST│
   │update││update││update││update│
   │status││status││status││status│
   └──┬─┘└──┬─┘└──┬─┘└──┬─┘
      │     │     │     │
      └─────┴─────┴─────┘
            │
            ▼
    ┌──────────────────────┐
    │ DoctorDashboard      │
    │ Controller::         │
    │ updateAppointmentStatus
    └──────┬───────────────┘
           │
           ▼
    ┌────────────────────┐
    │ Validar:           │
    │ - Appointment      │
    │   pertenece al     │
    │   doctor autenticado
    └──────┬─────────────┘
           │
      ┌────┴────┐
   VÁLIDO    INVÁLIDO
      │         │
      ▼         ▼
   ┌─────────┐ ┌──────────┐
   │Verificar│ │Error 403 │
   │transición│No autorizado
   │válida   │ └────┬─────┘
   └────┬────┘      │
        │           │
   ┌────┴────┐      │
  VÁLIDA  INVÁLIDA  │
    │       │       │
    ▼       ▼       │
 ┌─────┐ ┌────────┐ │
 │Actualizar│Error:│ │
 │status│"Transición│
 │en BD │ │inválida"│
 └──┬──┘ └───┬────┘ │
    │        │      │
    ▼        │      │
 ┌─────────┐ │      │
 │(Opcional)│ │      │
 │Agregar  │ │      │
 │notas del│ │      │
 │doctor   │ │      │
 └────┬────┘ │      │
     │       │      │
     ▼       │      │
 ┌─────────┐ │      │
 │(Futuro: │ │      │
 │Notificar│ │      │
 │paciente)│ │      │
 └────┬────┘ │      │
      │      │      │
      └──────┴──────┘
            │
            ▼
    ┌──────────────┐
    │ Retornar     │
    │ respuesta    │
    │ JSON         │
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐
    │ JavaScript   │
    │ actualiza    │
    │ tarjeta sin  │
    │ recargar     │
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐
    │ Cambio de    │
    │ badge color  │
    │ según estado │
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐
    │ Notificación │
    │ toast        │
    └──────┬───────┘
           │
           ▼
         FIN
```

---

### 5.7. Flujo de Cancelación de Cita (Paciente)

```
┌─────────────────────────────────────────────────────────────────┐
│            CANCELACIÓN DE CITA (PACIENTE)                       │
└─────────────────────────────────────────────────────────────────┘

        INICIO
          │
          ▼
    ┌─────────────────┐
    │ Paciente en     │
    │ /patient/dashboard
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────┐
    │ Ve "Mis Citas"  │
    │ - Pendientes    │
    │ - Confirmadas   │
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────┐
    │ Selecciona cita │
    │ que desea       │
    │ cancelar        │
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────┐
    │ Clic en botón   │
    │ "Cancelar Cita" │
    └──────┬──────────┘
           │
           ▼
    ┌─────────────────┐
    │ Modal de        │
    │ confirmación:   │
    │ "¿Está seguro?" │
    └──────┬──────────┘
           │
      ┌────┴────┐
     SÍ        NO
      │         │
      ▼         ▼
   ┌──────┐  ┌──────┐
   │Continuar Cerrar│
   │proceso  modal │
   └──┬───┘  └──┬───┘
      │         │
      ▼         ▼
   ┌──────────┐ FIN
   │POST      │
   │/citas/   │
   │{id}/cancel
   └────┬─────┘
        │
        ▼
   ┌──────────────────┐
   │AppointmentController
   │::cancel()        │
   └────┬─────────────┘
        │
        ▼
   ┌────────────────────┐
   │ Validar:           │
   │ - Cita existe      │
   │ - patient_id ==    │
   │   auth()->id()     │
   └──────┬─────────────┘
          │
     ┌────┴────┐
  VÁLIDO    INVÁLIDO
     │         │
     ▼         ▼
  ┌─────────┐ ┌──────────┐
  │Verificar│ │Error 403 │
  │que no   │ │"No puede │
  │esté     │ │cancelar  │
  │completed│ │esta cita"│
  └────┬────┘ └────┬─────┘
       │           │
  ┌────┴────┐      │
 CANCELABLE        │
 NO CANCELABLE     │
  │    │           │
  ▼    ▼           │
┌──────┐ ┌───────┐ │
│Actualizar│Error:│ │
│status│ │"Cita │ │
│='cancelled'│ya   │ │
│     │ │completada│
└──┬──┘ └───┬───┘ │
   │        │     │
   ▼        │     │
┌─────────┐ │     │
│Registrar│ │     │
│fecha de │ │     │
│cancelación│    │
└────┬────┘ │     │
     │      │     │
     ▼      │     │
┌─────────┐ │     │
│(Futuro: │ │     │
│Notificar│ │     │
│doctor   │ │     │
│por email)│     │
└────┬────┘ │     │
     │      │     │
     ▼      │     │
┌─────────┐ │     │
│(Futuro: │ │     │
│Liberar  │ │     │
│slot para│ │     │
│otros    │ │     │
│pacientes)│     │
└────┬────┘ │     │
     │      │     │
     └──────┴─────┘
           │
           ▼
    ┌──────────────┐
    │ Redirigir con│
    │ mensaje:     │
    │ "Cita        │
    │ cancelada"   │
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐
    │ Actualizar   │
    │ lista de     │
    │ citas        │
    └──────┬───────┘
           │
           ▼
    ┌──────────────┐
    │ Cita ahora   │
    │ muestra badge│
    │ gris         │
    │ "Cancelada"  │
    └──────┬───────┘
           │
           ▼
         FIN
```

---

### 5.8. Flujo de Autorización por Middleware

```
┌─────────────────────────────────────────────────────────────────┐
│              MIDDLEWARE DE AUTORIZACIÓN                         │
└─────────────────────────────────────────────────────────────────┘

        REQUEST HTTP
             │
             ▼
      ┌──────────────┐
      │ Ruta solicitada
      │ tiene middleware?
      └──────┬───────┘
             │
        ┌────┴────┐
       SÍ        NO
        │         │
        ▼         ▼
   ┌─────────┐ ┌──────┐
   │Ejecutar │ │Pasar │
   │middleware│ controlador
   └────┬────┘ └──────┘
        │
        ▼
   ┌─────────────┐
   │ Middleware  │
   │ 'auth'      │
   └──────┬──────┘
          │
     ┌────┴────┐
  AUTENTICADO
  NO AUTENTICADO
     │    │
     ▼    ▼
  ┌────┐ ┌─────────┐
  │Continuar│Redirigir│
  │a next│ │a /login │
  └──┬─┘ └─────────┘
     │
     ▼
   ┌─────────────┐
   │ Middleware  │
   │ 'checkRole' │
   └──────┬──────┘
          │
          ▼
   ┌──────────────┐
   │ Obtener rol  │
   │ requerido del│
   │ parámetro    │
   └──────┬───────┘
          │
          ▼
   ┌──────────────┐
   │ auth()->user()│
   │ ->role       │
   └──────┬───────┘
          │
          ▼
   ┌──────────────────┐
   │ ¿Rol del usuario │
   │ coincide con     │
   │ rol requerido?   │
   └──────┬───────────┘
          │
     ┌────┴────┐
    SÍ        NO
     │         │
     ▼         ▼
  ┌──────┐ ┌─────────┐
  │Permitir│Error 403│
  │acceso │ │"Acceso  │
  │a ruta │ │Denegado"│
  └──┬───┘ └─────────┘
     │
     ▼
  ┌──────────────┐
  │ Controlador  │
  │ ejecuta      │
  │ acción       │
  └──────┬───────┘
         │
         ▼
      RESPONSE
```

---

## 6. Capa de Controladores

### 6.1. Controladores de Autenticación

#### LoginController
**Ubicación:** `app/Http/Controllers/Auth/LoginController.php`

**Responsabilidad:** Gestión del inicio de sesión de usuarios.

**Métodos principales:**
- `show()`: Muestra el formulario de login
- `store()`: Procesa las credenciales y autentica al usuario
- `logout()`: Cierra la sesión del usuario

#### RegisterController
**Ubicación:** `app/Http/Controllers/Auth/RegisterController.php`

**Responsabilidad:** Gestión del registro de nuevos usuarios.

**Métodos principales:**
- `show()`: Muestra el formulario de registro
- `store()`: Valida y crea un nuevo usuario en el sistema

---

### 6.2. Controladores de Dashboard

#### DashboardController
**Ubicación:** `app/Http/Controllers/DashboardController.php`

**Responsabilidad:** Redirección al dashboard correspondiente según el rol del usuario.

**Métodos:**
- `index()`: Redirige a `/admin/dashboard`, `/doctor/dashboard` o `/patient/dashboard` según el rol

#### PatientDashboardController
**Ubicación:** `app/Http/Controllers/PatientDashboardController.php`

**Responsabilidad:** Panel de control para pacientes.

**Funcionalidades:**
- Mostrar citas programadas del paciente
- Listar doctores disponibles
- Permitir agendar nuevas citas

#### DoctorDashboardController
**Ubicación:** `app/Http/Controllers/DoctorDashboardController.php`

**Responsabilidad:** Panel de control para doctores.

**Funcionalidades:**
- Mostrar citas asignadas al doctor
- Actualizar estado de citas (confirmar, completar, cancelar)
- Gestionar horarios de atención

**Métodos clave:**
```php
index(): Vista del dashboard del doctor
updateAppointmentStatus(): Actualiza el estado de una cita
```

#### AdminDashboardController
**Ubicación:** `app/Http/Controllers/Admin/AdminDashboardController.php`

**Responsabilidad:** Panel de control administrativo.

**Funcionalidades:**
- Vista general del sistema (estadísticas)
- Acceso a gestión de usuarios, doctores, horarios y citas

---

### 6.3. Controladores de Administración

#### Admin\DoctorController
**Ubicación:** `app/Http/Controllers/Admin/DoctorController.php`

**Responsabilidad:** CRUD de doctores (solo para administradores).

**Métodos:**
```php
store(): Crear un nuevo doctor
update(): Actualizar información de un doctor
destroy(): Eliminar un doctor del sistema
```

**Validaciones:**
- Número de licencia único
- Relación válida con usuario existente
- Especialidad requerida

#### Admin\ScheduleController
**Ubicación:** `app/Http/Controllers/Admin/ScheduleController.php`

**Responsabilidad:** CRUD de horarios de doctores.

**Métodos:**
```php
store(): Crear nuevo bloque de horario
update(): Modificar horario existente
destroy(): Eliminar bloque de horario
```

**Validaciones:**
- Validar que `start_time < end_time`
- Verificar que no existan solapamientos de horarios
- `interval_minutes` debe ser positivo

#### Admin\UserController
**Ubicación:** `app/Http/Controllers/Admin/UserController.php`

**Responsabilidad:** Gestión de usuarios del sistema.

**Métodos:**
```php
update(): Actualizar datos de usuario (nombre, email, rol, estado)
destroy(): Eliminar usuario
```

#### Admin\AppointmentController
**Ubicación:** `app/Http/Controllers/Admin/AppointmentController.php`

**Responsabilidad:** Vista general de todas las citas del sistema.

---

### 6.4. AppointmentController

**Ubicación:** `app/Http/Controllers/AppointmentController.php`

**Responsabilidad:** Gestión de citas por parte de pacientes.

**Métodos:**
```php
store(): Crear nueva cita médica
cancel(): Cancelar una cita existente
```

**Validaciones al crear cita:**
1. Verificar disponibilidad del doctor en la fecha/hora solicitada
2. Validar que la fecha sea futura
3. Verificar que el horario esté dentro de los bloques configurados
4. Evitar citas duplicadas

---

## 7. Sistema de Rutas

### 7.1. Rutas Públicas

```php
GET  /                    → Vista de bienvenida (welcome)
```

### 7.2. Rutas de Autenticación (Guest)

```php
GET  /register           → Formulario de registro
POST /register           → Procesar registro
GET  /login              → Formulario de login
POST /login              → Procesar login
```

### 7.3. Rutas Autenticadas (Todas las Roles)

```php
GET  /dashboard          → Dashboard general (redirige según rol)
POST /logout             → Cerrar sesión

// Gestión de citas
POST /citas              → Crear nueva cita
POST /citas/{appointment}/cancel → Cancelar cita
```

### 7.4. Rutas de Paciente

**Middleware:** `auth`, `checkRole:patient`

```php
GET  /paciente/dashboard → Dashboard del paciente
```

### 7.5. Rutas de Doctor

**Middleware:** `auth`, `checkRole:doctor`

```php
GET  /doctor/dashboard   → Dashboard del doctor
POST /doctor/appointments/{appointment}/update-status → Actualizar estado de cita
```

### 7.6. Rutas de Administrador

**Middleware:** `auth`, `checkRole:admin`

```php
GET  /admin/dashboard    → Dashboard administrativo

// Gestión de Doctores (AJAX)
POST   /admin/doctors/store        → Crear doctor
PUT    /admin/doctors/{doctor}     → Actualizar doctor
DELETE /admin/doctors/{doctor}     → Eliminar doctor

// Gestión de Horarios (AJAX)
POST   /admin/schedules/store      → Crear horario
PUT    /admin/schedules/{schedule} → Actualizar horario
DELETE /admin/schedules/{schedule} → Eliminar horario

// Gestión de Usuarios (AJAX)
PUT    /admin/users/{user}         → Actualizar usuario
DELETE /admin/users/{user}         → Eliminar usuario
```

---

## 8. Middleware y Seguridad

### 8.1. Middleware CheckRole

**Ubicación:** `app/Http/Middleware/CheckRole.php`

**Responsabilidad:** Verificar que el usuario autenticado tenga el rol adecuado para acceder a rutas protegidas.

**Uso:**
```php
Route::middleware(['auth', 'checkRole:admin'])->group(function () {
    // Rutas solo para administradores
});
```

**Roles disponibles:**
- `admin`: Acceso total al sistema
- `doctor`: Acceso a gestión de citas propias
- `patient`: Acceso a agendamiento de citas

### 8.2. Autenticación

- **Sistema:** Laravel Authentication (session-based)
- **Encriptación:** Bcrypt para contraseñas
- **Protección CSRF:** Tokens en todos los formularios POST/PUT/DELETE

### 8.3. Validaciones de Seguridad

**En controladores:**
```php
// Validación de entrada
$request->validate([
    'email' => 'required|email|unique:users',
    'password' => 'required|min:8',
]);

// Autorización de acciones
$this->authorize('update', $appointment);
```

**En rutas:**
- Uso de `middleware('auth')` para rutas protegidas
- Verificación de roles con `checkRole`
- Validación de propietario en operaciones sensibles

---

## 9. Migraciones de Base de Datos

### 9.1. create_users_table.php

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->enum('role', ['admin', 'doctor', 'patient'])->default('patient');
    $table->boolean('active')->default(true);
    $table->timestamp('email_verified_at')->nullable();
    $table->rememberToken();
    $table->timestamps();
});
```

### 9.2. create_doctors_table.php

```php
Schema::create('doctors', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('license_number')->unique();
    $table->string('specialty');
    $table->text('biography')->nullable();
    $table->string('photo_url')->nullable();
    $table->boolean('active')->default(true);
    $table->timestamps();
});
```

### 9.3. create_schedules_table.php

```php
Schema::create('schedules', function (Blueprint $table) {
    $table->id();
    $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
    $table->enum('day_of_week', ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']);
    $table->time('start_time');
    $table->time('end_time');
    $table->integer('interval_minutes')->default(30);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 9.4. create_appointments_table.php

```php
Schema::create('appointments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
    $table->dateTime('appointment_date_time');
    $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
    $table->text('consultation_reason');
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

### 9.5. Índices Recomendados

```php
// En appointments
$table->index('appointment_date_time');
$table->index(['doctor_id', 'status']);
$table->index(['patient_id', 'status']);

// En schedules
$table->index(['doctor_id', 'day_of_week']);
```

---

## 10. Consideraciones de Seguridad

### 10.1. Autenticación y Autorización

- **Session-based authentication:** Laravel gestiona sesiones seguras
- **Password hashing:** Bcrypt automático en User model
- **Middleware de roles:** Verificación de permisos en cada ruta protegida
- **CSRF Protection:** Tokens en todos los formularios

### 10.2. Validación de Datos

**Nivel Controlador:**
```php
$request->validate([
    'email' => 'required|email|unique:users',
    'appointment_date_time' => 'required|date|after:now',
    'doctor_id' => 'required|exists:doctors,id',
]);
```

**Nivel Base de Datos:**
- Restricciones UNIQUE en campos críticos
- Foreign Keys con ON DELETE CASCADE
- Validación de ENUM para estados

### 10.3. Prevención de Vulnerabilidades

- **SQL Injection:** Uso de Eloquent ORM y Query Builder
- **XSS:** Blade templates escapan automáticamente `{{ $variable }}`
- **CSRF:** Middleware `VerifyCsrfToken` activo
- **Mass Assignment:** Definición explícita de `$fillable` en modelos

### 10.4. Control de Acceso

```php
// Verificar que el paciente solo cancele sus propias citas
if ($appointment->patient_id !== auth()->id()) {
    abort(403, 'No autorizado');
}

// Verificar que el doctor solo gestione sus citas
if ($appointment->doctor_id !== auth()->user()->doctor->id) {
    abort(403, 'No autorizado');
}
```

---

## 11. Instalación y Configuración

### 11.1. Requisitos del Sistema

- **PHP:** >= 8.2
- **Composer:** >= 2.0
- **Base de datos:** MySQL 8.0+ / MariaDB 10.3+
- **Node.js:** >= 18.x (para assets)
- **Extensiones PHP:** OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON

### 11.2. Pasos de Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/guillencristofer911-star/Gestion-Citas-Medicas.git
cd Gestion-Citas-Medicas/Citas-Medicas

# 2. Instalar dependencias PHP
composer install

# 3. Copiar archivo de configuración
cp .env.example .env

# 4. Generar clave de aplicación
php artisan key:generate

# 5. Configurar base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=citas_medicas
DB_USERNAME=root
DB_PASSWORD=

# 6. Ejecutar migraciones
php artisan migrate

# 7. (Opcional) Ejecutar seeders
php artisan db:seed

# 8. Instalar dependencias frontend
npm install
npm run build

# 9. Levantar servidor de desarrollo
php artisan serve
```

### 11.3. Configuración de Roles Iniciales

**Crear usuario administrador manualmente:**

```bash
php artisan tinker
```

```php
$user = \App\Models\User::create([
    'name' => 'Administrador',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin',
    'active' => true,
]);
```

### 11.4. Permisos de Storage

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## 12. Testing

### 12.1. Pruebas Unitarias Sugeridas

**Modelos:**
```php
// tests/Unit/AppointmentTest.php
public function test_appointment_belongs_to_patient()
public function test_appointment_belongs_to_doctor()
public function test_appointment_status_enum_validation()
```

**Controladores:**
```php
// tests/Feature/AppointmentControllerTest.php
public function test_patient_can_create_appointment()
public function test_patient_cannot_create_past_appointment()
public function test_patient_can_cancel_own_appointment()
```

### 12.2. Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Con cobertura
php artisan test --coverage

# Tests específicos
php artisan test --filter AppointmentTest
```

---

## 13. Mejoras Futuras Sugeridas

### 13.1. Funcionalidades

- [ ] **Sistema de notificaciones:**
  - Email al crear/confirmar/cancelar citas
  - Recordatorios automáticos 24h antes
  
- [ ] **Calendario interactivo:**
  - Vista mensual/semanal de citas
  - Drag & drop para reagendar
  
- [ ] **Historial médico:**
  - Registro de consultas previas
  - Archivos adjuntos (recetas, exámenes)
  
- [ ] **Sistema de pagos:**
  - Integración con pasarelas de pago
  - Gestión de facturación
  
- [ ] **Chat en tiempo real:**
  - Comunicación doctor-paciente
  - Uso de Laravel Echo + Pusher

- [ ] **Reportes y estadísticas:**
  - Dashboard con gráficos
  - Exportación a PDF/Excel

### 13.2. Técnicas

- [ ] **API REST:**
  - Endpoints para aplicaciones móviles
  - Documentación con Swagger/OpenAPI
  
- [ ] **Testing automatizado:**
  - Cobertura mínima del 80%
  - CI/CD con GitHub Actions
  
- [ ] **Optimización:**
  - Caché de consultas frecuentes
  - Lazy loading de relaciones
  - Paginación de listados
  
- [ ] **Seguridad:**
  - Autenticación de dos factores (2FA)
  - Logs de auditoría
  - Rate limiting en API

- [ ] **DevOps:**
  - Dockerización del proyecto
  - Despliegue automatizado
  - Monitoreo con Laravel Telescope

---

## 14. Solución de Problemas Comunes

### 14.1. Error de permisos en storage

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 14.2. Error de clave de aplicación

```bash
php artisan key:generate
php artisan config:cache
```

### 14.3. Migraciones no se ejecutan

```bash
# Resetear y volver a migrar
php artisan migrate:fresh

# Con seeders
php artisan migrate:fresh --seed
```

### 14.4. Error 403 en rutas de administrador

Verificar que el usuario tenga `role = 'admin'` en la base de datos:

```sql
UPDATE users SET role = 'admin' WHERE email = 'tu_email@example.com';
```

### 14.5. CSRF Token Mismatch

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 15. Contacto y Contribuciones

### Autor
**Guillen Cristofer**
- GitHub: [@guillencristofer911-star](https://github.com/guillencristofer911-star)
- Repositorio: [Gestion-Citas-Medicas](https://github.com/guillencristofer911-star/Gestion-Citas-Medicas)

### Contribuir

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

### Licencia
Este proyecto está bajo la licencia MIT. Consulta el archivo `LICENSE` para más detalles.

---

## 16. Glosario

- **MVC:** Model-View-Controller (patrón de diseño)
- **ORM:** Object-Relational Mapping (Eloquent en Laravel)
- **CRUD:** Create, Read, Update, Delete
- **FK:** Foreign Key (clave foránea)
- **PK:** Primary Key (clave primaria)
- **CSRF:** Cross-Site Request Forgery
- **XSS:** Cross-Site Scripting
- **Middleware:** Software intermedio que procesa peticiones HTTP
- **Blade:** Motor de plantillas de Laravel
- **Eloquent:** ORM incluido en Laravel

---

**Última actualización:** Diciembre 2025  
**Versión de Laravel:** 12.x  
**Estado:** En desarrollo activo
