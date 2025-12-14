# 🏥 Sistema de Gestión de Citas Médicas

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](LICENSE)
[![Status](https://img.shields.io/badge/Status-In%20Development-yellow?style=flat-square)]()

Sistema web integral para la gestión eficiente de citas médicas, permitiendo que pacientes, doctores y administradores interactúen en una plataforma centralizada con control de acceso basado en roles (RBAC).

**[🔗 Ver Demostración](#demo)** • **[📚 Documentación](#documentación)** • **[🚀 Inicio Rápido](#instalación)** • **[🤝 Contribuir](#contribuciones)**

---

## 📋 Tabla de Contenidos

- [Características](#características)
- [Requerimientos](#requerimientos)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Uso](#uso)
- [Roles y Permisos](#roles-y-permisos)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Base de Datos](#base-de-datos)
- [API Endpoints](#api-endpoints)
- [Estados de Citas](#estados-de-citas)
- [Troubleshooting](#troubleshooting)
- [Roadmap](#roadmap)
- [Contribuciones](#contribuciones)
- [Licencia](#licencia)
- [Contacto](#contacto)

---

## 🌟 Características

### Para Pacientes
- ✅ Registro y autenticación segura
- ✅ Visualizar catálogo de doctores con especialidades
- ✅ Solicitar citas médicas con validación de disponibilidad
- ✅ Ver estado de citas (pendiente, confirmada, atendida, cancelada)
- ✅ Historial completo de citas médicas
- ✅ Cancelar citas no atendidas
- ✅ Dashboard personalizado con estadísticas
- ✅ Ver información del doctor (especialidad, biografía, foto)

### Para Doctores
- ✅ Visualizar todas las citas asignadas
- ✅ Confirmar o rechazar solicitudes de citas
- ✅ Marcar citas como atendidas
- ✅ Visualizar agenda diaria y semanal
- ✅ Agregar notas a las citas
- ✅ Dashboard con estadísticas de desempeño
- ✅ Ver información del paciente en cada cita
- ✅ Gestionar su disponibilidad

### Para Administradores
- ✅ Crear, editar y desactivar perfiles de doctores
- ✅ Gestionar horarios de atención de doctores
- ✅ Definir especialidades médicas
- ✅ Visualizar todas las citas del sistema
- ✅ Gestionar estados de citas
- ✅ Administrar usuarios registrados
- ✅ Monitor de la plataforma
- ✅ Reportes de actividad

### Seguridad
- ✅ Autenticación con sesiones seguras
- ✅ Control de acceso basado en roles (RBAC)
- ✅ Encriptación de contraseñas (Bcrypt)
- ✅ Validación de datos en formularios
- ✅ Prevención de duplicación de citas
- ✅ Protección CSRF con tokens

---

## 📋 Requerimientos

### Sistema Operativo
- Windows, macOS, o Linux

### Requisitos de Software
- **PHP**: 8.2 o superior
- **Composer**: Último versión
- **Node.js**: 16.x o superior (para assets frontend)
- **npm** o **yarn**: Para gestionar dependencias de frontend
- **Base de datos**: MySQL 8.0+ o PostgreSQL 14+
- **Servidor web**: Apache, Nginx o servidor de desarrollo de Laravel

### Librerías PHP (manejadas por Composer)
- Laravel Framework 11.x
- Laravel Breeze (autenticación)
- Illuminate ORM (Eloquent)

### Dependencias Frontend
- Vite
- Tailwind CSS (opcional, según tu configuración)

---

## 🚀 Instalación

### Paso 1: Clonar el Repositorio

```bash
git clone https://github.com/guillencristofer911-star/Gestion-Citas-Medicas.git
cd Gestion-Citas-Medicas/Citas-Medicas
```

### Paso 2: Instalar Dependencias de PHP

```bash
composer install
```

### Paso 3: Instalar Dependencias de Frontend (Opcional)

```bash
npm install
# o si usas yarn
yarn install
```

### Paso 4: Configurar el Archivo `.env`

Copia el archivo de ejemplo y configura las variables:

```bash
cp .env.example .env
```

Edita el archivo `.env` y configura:

```env
APP_NAME="Sistema de Citas Médicas"
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxx
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de Datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=citas_medicas
DB_USERNAME=root
DB_PASSWORD=

# Mail (opcional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
```

### Paso 5: Generar Clave de Aplicación

```bash
php artisan key:generate
```

### Paso 6: Crear la Base de Datos

```bash
# Crear la base de datos en MySQL
mysql -u root -p -e "CREATE DATABASE citas_medicas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Paso 7: Ejecutar Migraciones

```bash
php artisan migrate
```

Este comando crea todas las tablas necesarias:
- `users` - Usuarios del sistema
- `doctors` - Perfiles de doctores
- `appointments` - Citas médicas
- `schedules` - Horarios de doctores

### Paso 8: Ejecutar Seeders (Opcional - para datos de prueba)

```bash
php artisan db:seed
```

### Paso 9: Compilar Assets Frontend

```bash
npm run dev
# Para producción
npm run build
```

### Paso 10: Iniciar el Servidor de Desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en: **http://localhost:8000**

---

## ⚙️ Configuración

### Configuración de Correo

Para habilitar notificaciones por correo, configura en `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_contraseña_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=sistema@citasmedicas.com
```

### Configuración de Base de Datos

Para usar PostgreSQL en lugar de MySQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=citas_medicas
DB_USERNAME=postgres
DB_PASSWORD=
```

### Configuración de Sesiones

En `.env`:

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

---

## 📖 Uso

### Acceso a la Aplicación

Una vez instalada, dirígete a **http://localhost:8000**

### Crear Usuarios de Prueba

#### 1. Registrar un Paciente

```
URL: http://localhost:8000/register
- Nombre: Juan Pérez
- Email: juan@example.com
- Contraseña: password123
- Rol: Paciente (por defecto)
```

#### 2. Crear un Doctor (como Administrador)

```
URL: http://localhost:8000/admin/doctors/create
- Usuario: Doctor (crear usuario primero con rol doctor)
- Especialidad: Cardiología
- Número de Licencia: LIC123456
- Biografía: 15 años de experiencia...
- Foto: Subir imagen
```

#### 3. Acceso de Administrador

Se requiere acceso directo a la base de datos o código para asignar rol admin:

```sql
UPDATE users SET role = 'admin' WHERE id = 1;
```

### Solicitar una Cita (Paciente)

1. Inicia sesión como paciente
2. Ve a "Dashboard"
3. En la sección "Solicitar Cita"
4. Selecciona un doctor
5. Elige fecha y hora disponible
6. Ingresa motivo de consulta
7. Haz clic en "Agendar Cita"

### Confirmar una Cita (Doctor)

1. Inicia sesión como doctor
2. Ve a tu Dashboard
3. En "Citas Pendientes"
4. Haz clic en "Confirmar"
5. La cita cambia a estado "Confirmada"

### Marcar Cita como Atendida (Doctor)

1. En el Dashboard del doctor
2. Localiza la cita en "Próximas Citas"
3. Haz clic en "Marcar como Atendida"
4. Opcionalmente, agrega notas
5. Confirma la acción

---

## 👥 Roles y Permisos

### 1. PACIENTE (Patient)

| Acción | Permiso |
|--------|---------|
| Registrarse | ✅ Sí |
| Ver doctores | ✅ Sí |
| Solicitar cita | ✅ Sí |
| Ver sus citas | ✅ Sí |
| Cancelar cita | ✅ Sí (si no está atendida) |
| Ver citas de otros | ❌ No |
| Cambiar estado de cita | ❌ No |

### 2. DOCTOR (Doctor)

| Acción | Permiso |
|--------|---------|
| Ver citas asignadas | ✅ Sí |
| Confirmar cita | ✅ Sí |
| Marcar como atendida | ✅ Sí |
| Cancelar cita | ✅ Sí |
| Ver citas de otros doctores | ❌ No |
| Crear citas | ❌ No |
| Crear doctores | ❌ No |

### 3. ADMINISTRADOR (Admin)

| Acción | Permiso |
|--------|---------|
| Crear doctores | ✅ Sí |
| Editar doctores | ✅ Sí |
| Desactivar doctores | ✅ Sí |
| Gestionar horarios | ✅ Sí |
| Ver todas las citas | ✅ Sí |
| Gestionar usuarios | ✅ Sí |
| Definir estados | ✅ Sí |
| Acceder a reportes | ✅ Sí |

---

## 📂 Estructura del Proyecto

```
Citas-Medicas/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   └── LogoutController.php
│   │   │   ├── AppointmentController.php      # Gestión de citas
│   │   │   ├── PatientDashboardController.php # Dashboard paciente
│   │   │   ├── DoctorDashboardController.php  # Dashboard doctor
│   │   │   ├── DashboardController.php        # Dashboard general
│   │   │   └── Controller.php                 # Controlador base
│   │   └── Middleware/
│   │       ├── Authenticate.php
│   │       ├── RoleMiddleware.php             # Verificar roles
│   │       └── ...
│   ├── Models/
│   │   ├── User.php                           # Modelo de usuario
│   │   ├── Doctor.php                         # Modelo de doctor
│   │   ├── Appointment.php                    # Modelo de cita
│   │   └── Schedule.php                       # Modelo de horario
│   └── Providers/
│       └── ...
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2025_12_11_141027_create_doctors_table.php
│   │   ├── 2025_12_11_141043_create_schedules_table.php
│   │   ├── 2025_12_11_141051_create_appointments_table.php
│   │   └── 2025_12_13_143647_update_appointments_status_enum.php
│   ├── factories/
│   │   ├── UserFactory.php
│   │   ├── DoctorFactory.php
│   │   └── AppointmentFactory.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   └── navigation.blade.php
│   │   ├── dashboard/
│   │   │   ├── patient/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── appointments.blade.php
│   │   │   ├── doctor/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── appointments.blade.php
│   │   │   └── admin/
│   │   │       ├── index.blade.php
│   │   │       └── doctors.blade.php
│   │   ├── appointments/
│   │   │   ├── create.blade.php
│   │   │   └── index.blade.php
│   │   └── auth/
│   │       ├── login.blade.php
│   │       └── register.blade.php
│   ├── css/
│   │   └── app.css
│   └── js/
│       └── app.js
├── routes/
│   ├── web.php                    # Rutas principales
│   ├── api.php                    # Rutas API (si aplica)
│   └── channels.php
├── config/
│   ├── app.php
│   ├── database.php
│   ├── mail.php
│   └── ...
├── public/
│   ├── index.php                  # Punto de entrada
│   ├── css/
│   └── js/
├── storage/
│   ├── app/
│   ├── logs/
│   └── framework/
├── bootstrap/
│   └── app.php
├── tests/
│   ├── Feature/
│   └── Unit/
├── .env.example                   # Ejemplo de variables de entorno
├── composer.json                  # Dependencias PHP
├── package.json                   # Dependencias Node
├── phpunit.xml                    # Configuración de pruebas
├── vite.config.js                 # Configuración de Vite
└── README.md                      # Este archivo
```

---

## 🗄️ Base de Datos

### Diagrama de Relaciones

```
┌─────────────────────────────────────────────────────────┐
│                         USERS                            │
├─────────────────────────────────────────────────────────┤
│ id (PK)                                                  │
│ name                                                     │
│ email (UNIQUE)                                          │
│ password                                                 │
│ role (enum: patient, doctor, admin)                     │
│ active (boolean)                                        │
│ created_at, updated_at                                  │
└─────────────────┬──────────────────┬────────────────────┘
                  │                  │
                  │ 1:1 (doctor)     │ 1:N (appointments)
                  │                  │
        ┌─────────▼──────────┐  ┌────▼──────────────────┐
        │     DOCTORS        │  │   APPOINTMENTS       │
        ├────────────────────┤  ├────────────────────┤
        │ id (PK)            │  │ id (PK)              │
        │ user_id (FK)       │  │ patient_id (FK)      │
        │ license_number     │  │ doctor_id (FK) ──┐  │
        │ specialty          │  │ appointment_date │  │
        │ biography          │  │ status           │  │
        │ profile_photo      │  │ consultation_    │  │
        │ active             │  │ reason           │  │
        │ created_at, updated│  │ notes            │  │
        └──────┬─────────────┘  │ created_at       │  │
               │                 └────────────────┬─┘  │
               │                    1:N (doctor)    │   │
               └─────────────────────────────────────┘  │
                                                        │
                    ┌──────────────────────────────────┘
                    │
        ┌───────────▼──────────────┐
        │      SCHEDULES           │
        ├──────────────────────────┤
        │ id (PK)                  │
        │ doctor_id (FK)           │
        │ day_of_week              │
        │ start_time               │
        │ end_time                 │
        │ break_time_start         │
        │ break_time_end           │
        │ created_at, updated_at   │
        └──────────────────────────┘
```

### Tablas

#### 1. USERS (Usuarios)

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('patient', 'doctor', 'admin') DEFAULT 'patient',
    active BOOLEAN DEFAULT TRUE,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### 2. DOCTORS (Doctores)

```sql
CREATE TABLE doctors (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    license_number VARCHAR(255) UNIQUE NOT NULL,
    specialty VARCHAR(255) NOT NULL,
    biography TEXT NULL,
    profile_photo VARCHAR(255) NULL,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### 3. APPOINTMENTS (Citas)

```sql
CREATE TABLE appointments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    patient_id BIGINT UNSIGNED NOT NULL,
    doctor_id BIGINT UNSIGNED NOT NULL,
    appointment_date_time DATETIME NOT NULL,
    status ENUM('pending', 'confirmed', 'attended', 'cancelled') 
        DEFAULT 'pending',
    consultation_reason TEXT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    UNIQUE KEY (doctor_id, appointment_date_time),
    INDEX (patient_id),
    INDEX (doctor_id),
    INDEX (status)
);
```

#### 4. SCHEDULES (Horarios)

```sql
CREATE TABLE schedules (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    doctor_id BIGINT UNSIGNED NOT NULL,
    day_of_week INT NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    break_time_start TIME NULL,
    break_time_end TIME NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);
```

---

## 🔄 Estados de Citas

Una cita médica pasa por los siguientes estados:

```
┌─────────────────────────────────────────────────────────┐
│                  CICLO DE VIDA DE CITAS                 │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  PENDING 🟡          CONFIRMED 🟢      ATTENDED 🔵     │
│  └─────────────────────────────────────────┘            │
│                                                         │
│         CANCELLED ⚫                                    │
│      (en cualquier momento)                            │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

### Estados

| Estado | Descripción | Quién Actúa | Siguiente Estado |
|--------|-------------|-------------|-----------------|
| **PENDING** 🟡 | Cita solicitada, pendiente de confirmación del doctor | Paciente | CONFIRMED / CANCELLED |
| **CONFIRMED** 🟢 | Doctor confirmó la cita, está programada | Doctor | ATTENDED / CANCELLED |
| **ATTENDED** 🔵 | Cita completada, paciente fue atendido | Doctor | (Final) |
| **CANCELLED** ⚫ | Cita cancelada por paciente o doctor | Ambos | (Final) |

---

## 📡 API Endpoints

### Autenticación

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/register` | Registrar nuevo usuario |
| POST | `/login` | Iniciar sesión |
| POST | `/logout` | Cerrar sesión |

### Citas (Paciente)

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/appointments` | Listar citas del paciente |
| GET | `/appointments/{id}` | Ver detalles de cita |
| POST | `/appointments` | Crear nueva cita |
| POST | `/appointments/{id}/cancel` | Cancelar cita |

### Citas (Doctor)

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/doctor/appointments` | Listar citas del doctor |
| PUT | `/appointments/{id}/confirm` | Confirmar cita |
| PUT | `/appointments/{id}/attend` | Marcar como atendida |
| PUT | `/appointments/{id}/cancel` | Cancelar cita |
| PUT | `/appointments/{id}/notes` | Agregar notas |

### Dashboard

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/dashboard` | Dashboard según rol |
| GET | `/patient/dashboard` | Dashboard paciente |
| GET | `/doctor/dashboard` | Dashboard doctor |
| GET | `/admin/dashboard` | Dashboard administrador |

---

## 🐛 Troubleshooting

### Error: "No application encryption key has been specified"

**Solución:**
```bash
php artisan key:generate
```

### Error: "Connection refused" (Base de datos)

**Solución:**
1. Verifica que MySQL está corriendo
2. Confirma credenciales en `.env`
3. Intenta conectar manualmente:
```bash
mysql -u root -p -h 127.0.0.1
```

### Error: "SQLSTATE[HY000]: General error: 1030 Got error..."

**Solución:**
```bash
php artisan migrate:fresh
php artisan db:seed
```

### Las citas no se guardan

**Verifica:**
1. La fecha está en el futuro
2. La hora está en formato válido (HH:MM)
3. El doctor existe y está activo
4. El consultorios no tiene otra cita a esa hora

### No puedo ver el dashboard del doctor

**Verifica:**
1. Tu usuario tiene rol `doctor`
2. Existe un registro en la tabla `doctors` para tu usuario
3. El campo `active` en `doctors` es `true`

### Las estilos no cargan

**Solución:**
```bash
npm run dev
# o
npm run build
```

---

## 📈 Roadmap

### Versión 1.0 (Actual)
- [x] Autenticación básica
- [x] Control de roles (RBAC)
- [x] Gestión de citas
- [x] Dashboards personalizados
- [x] Validación de disponibilidad

### Versión 1.1 (Próxima)
- [ ] Panel de administrador completo
- [ ] Notificaciones por correo
- [ ] Gestión completa de horarios
- [ ] Búsqueda y filtros avanzados
- [ ] Reportes PDF

### Versión 1.2
- [ ] API RESTful completa
- [ ] Aplicación móvil
- [ ] Integración con calendarios (Google Calendar, Outlook)
- [ ] Sistema de calificaciones para doctores
- [ ] Recordatorios SMS

### Versión 2.0 (Largo Plazo)
- [ ] Telemedicina (videollamadas)
- [ ] Historial médico electrónico
- [ ] Prescripciones digitales
- [ ] Integración con sistemas de facturación
- [ ] Análisis y reportes avanzados
- [ ] Disponibilidad en múltiples idiomas

---

## 🤝 Contribuciones

¡Las contribuciones son bienvenidas! Para mantener la calidad del proyecto:

### Pasos para Contribuir

1. **Fork el repositorio**
```bash
git clone https://github.com/tu-usuario/Gestion-Citas-Medicas.git
cd Gestion-Citas-Medicas
```

2. **Crear rama para la feature**
```bash
git checkout -b feature/mi-nueva-feature
```

3. **Realizar cambios y commits**
```bash
git add .
git commit -m "Agregada nueva feature: descripción clara"
```

4. **Push a tu fork**
```bash
git push origin feature/mi-nueva-feature
```

5. **Crear Pull Request**
   - Describe claramente los cambios
   - Incluye referencias a issues relacionados
   - Asegúrate de que el código pase todas las pruebas

### Estándares de Código

- Sigue el estilo PSR-12 para PHP
- Usa nombres descriptivos para variables y funciones
- Comenta código complejo
- Escribe tests para nuevas funcionalidades
- Actualiza la documentación

### Reportar Bugs

1. Verifica que el bug no ha sido reportado
2. Abre un nuevo Issue con:
   - Título descriptivo
   - Descripción detallada
   - Pasos para reproducir
   - Resultado esperado vs actual
   - Capturas de pantalla si aplica

---

## 📝 Licencia

Este proyecto está licenciado bajo la **Licencia MIT**. Ver archivo [LICENSE](LICENSE) para más detalles.

```
MIT License

Copyright (c) 2025 Guillén Cristófer

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
```

---

## 📞 Contacto

**Desarrollador:** Guillén Cristófer  
**Email:** [tu-email@example.com](mailto:tu-email@example.com)  
**GitHub:** [@guillencristofer911-star](https://github.com/guillencristofer911-star)  
**LinkedIn:** [tu-perfil](https://linkedin.com)  

### Redes Sociales
- 🐦 [Twitter](https://twitter.com)
- 💼 [LinkedIn](https://linkedin.com)
- 📸 [Instagram](https://instagram.com)

---

## 🙏 Agradecimientos

- [Laravel Documentation](https://laravel.com/docs) - Documentación oficial
- [Laravel Breeze](https://laravel.com/docs/starter-kits#breeze) - Kit de inicio de autenticación
- La comunidad de Laravel por su support

---

## 📚 Recursos Útiles

### Documentación
- [Laravel 11 Docs](https://laravel.com/docs/11.x)
- [Eloquent ORM](https://laravel.com/docs/11.x/eloquent)
- [Blade Templates](https://laravel.com/docs/11.x/blade)

### Tutoriales
- [Laravel for Beginners](https://www.youtube.com/playlist?list=PL_QvH8YLCLHiGKpqHGYMlTpQJoNCpXmHN)
- [Database Relationships](https://laravel.com/docs/11.x/eloquent-relationships)

### Herramientas
- [Postman](https://www.postman.com/) - Pruebas de API
- [DB Browser for SQLite](https://sqlitebrowser.org/) - Visualizar BD
- [VS Code Extensions](https://marketplace.visualstudio.com/) - PHP Intellisense, etc.

---

## 📊 Estadísticas

![GitHub Repo Size](https://img.shields.io/github/repo-size/guillencristofer911-star/Gestion-Citas-Medicas?style=flat-square)
![GitHub Last Commit](https://img.shields.io/github/last-commit/guillencristofer911-star/Gestion-Citas-Medicas?style=flat-square)
![GitHub Issues](https://img.shields.io/github/issues/guillencristofer911-star/Gestion-Citas-Medicas?style=flat-square)
![GitHub Pull Requests](https://img.shields.io/github/issues-pr/guillencristofer911-star/Gestion-Citas-Medicas?style=flat-square)

---

## ⭐ Muestra tu Apoyo

Si este proyecto te fue útil, ¡considera darle una estrella! ⭐

```bash
# Clona el repositorio
git clone https://github.com/guillencristofer911-star/Gestion-Citas-Medicas.git

# Dale una estrella en GitHub 🌟
# Comparte el proyecto con otros 📢
# Reporta bugs y sugiere mejoras 💡
```

---

<div align="center">

**[Subir al inicio ⬆️](#sistema-de-gestión-de-citas-médicas)**

Hecho con ❤️ por [Guillén Cristófer](https://github.com/guillencristofer911-star)

</div>
