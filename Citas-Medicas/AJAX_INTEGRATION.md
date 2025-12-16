# 🚀 Guía de Integración AJAX - Dashboard Admin

## 🎯 Archivos Creados

### 1. **ajax-handler.js** - Manejador Centralizado
- 📂 Ubicación: `public/js/ajax-handler.js`
- ✅ Funciones exportadas:
  - `AjaxHandler` - Peticiones AJAX
  - `Toast` - Notificaciones
  - `Loader` - Spinners de carga
  - `Confirm` - Diálogos de confirmación

### 2. **admin-dashboard.js** - Lógica del Admin
- 📂 Ubicación: `public/js/admin-dashboard.js`
- ✅ Funciones implementadas:
  - `editDoctor(doctorId)` - Cargar datos completos al editar
  - `searchDoctors()` - Búsqueda en tiempo real
  - `viewAppointment(appointmentId)` - Ver detalles de cita
  - `searchAppointments()` - Filtrar citas
  - `searchUsers()` - Filtrar usuarios

### 3. **Controladores Actualizados**
- ✅ `Admin/DoctorController.php` - Métodos: `show()`, `search()`
- ✅ `Admin/AppointmentController.php` - Métodos: `show()`, `search()`
- ✅ `Admin/UserController.php` - Método: `search()`

### 4. **Rutas Agregadas**
```php
// En routes/web.php
Route::get('/admin/doctors/{doctor}', 'show');
Route::get('/admin/doctors/search', 'search');
Route::get('/admin/appointments/{appointment}', 'show');
Route::get('/admin/appointments/search', 'search');
Route::get('/admin/users/search', 'search');
```

---

## 🔧 Pasos de Integración

### **Paso 1: Incluir Scripts en la Vista**

Editar: `resources/views/dashboard/admin/index.blade.php`

**Agregar ANTES del cierre de `</body>`:**

```html
<!-- AJAX Handlers -->
<script src="{{ asset('js/ajax-handler.js') }}"></script>
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
```

**Actualizar la variable currentUserId en el script actual:**

```javascript
<script>
    // Agregar esta línea al inicio del script existente
    window.currentUserId = {{ auth()->id() }};
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                      '{{ csrf_token() }}';
    // ... resto del código existente
</script>
```

---

### **Paso 2: Agregar Campos de Búsqueda**

#### **A. Sección de Médicos**

Agregar ANTES de la tabla de médicos:

```html
<div class="section-title">👨‍⚕️ Gestión de Médicos</div>
<div class="section">
    <!-- NUEVO: Barra de búsqueda -->
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 15px; margin-bottom: 20px; align-items: end;">
        <div class="form-group" style="margin: 0;">
            <label>Buscar por Nombre</label>
            <input type="text" id="searchDoctorName" placeholder="Nombre del médico..." oninput="searchDoctors()">
        </div>
        <div class="form-group" style="margin: 0;">
            <label>Buscar por Especialidad</label>
            <input type="text" id="searchDoctorSpecialty" placeholder="Cardiología, Pediatría..." oninput="searchDoctors()">
        </div>
        <div class="form-group" style="margin: 0;">
            <label>Estado</label>
            <select id="filterDoctorActive" onchange="searchDoctors()">
                <option value="">Todos</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
            </select>
        </div>
        <div class="action-buttons" style="margin: 0;">
            <button class="btn btn-primary" onclick="openAddDoctorModal()">➡️ Agregar Médico</button>
        </div>
    </div>
    
    <table>
        <!-- ... tabla existente ... -->
    </table>
</div>
```

#### **B. Sección de Citas**

Agregar ANTES de la tabla de citas:

```html
<div class="section-title">📅 Todas las Citas del Sistema</div>
<div class="section">
    <!-- NUEVO: Filtros de búsqueda -->
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 1fr; gap: 15px; margin-bottom: 20px;">
        <div class="form-group" style="margin: 0;">
            <label>Paciente</label>
            <input type="text" id="searchPatientName" placeholder="Nombre..." oninput="searchAppointments()">
        </div>
        <div class="form-group" style="margin: 0;">
            <label>Médico</label>
            <input type="text" id="searchDoctorName" placeholder="Nombre..." oninput="searchAppointments()">
        </div>
        <div class="form-group" style="margin: 0;">
            <label>Desde</label>
            <input type="date" id="filterDateFrom" onchange="searchAppointments()">
        </div>
        <div class="form-group" style="margin: 0;">
            <label>Hasta</label>
            <input type="date" id="filterDateTo" onchange="searchAppointments()">
        </div>
        <div class="form-group" style="margin: 0;">
            <label>Estado</label>
            <select id="filterAppointmentStatus" onchange="searchAppointments()">
                <option value="">Todos</option>
                <option value="pending">Pendiente</option>
                <option value="confirmed">Confirmada</option>
                <option value="attended">Atendida</option>
                <option value="canceled">Cancelada</option>
            </select>
        </div>
    </div>
    
    <table>
        <tbody id="appointmentsTableBody">
            <!-- ... tbody existente ... -->
        </tbody>
    </table>
</div>
```

#### **C. Sección de Usuarios**

Agregar ANTES de la tabla de usuarios:

```html
<div class="section-title">👥 Gestión de Usuarios</div>
<div class="section">
    <!-- NUEVO: Filtros de búsqueda -->
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 15px; margin-bottom: 20px;">
        <div class="form-group" style="margin: 0;">
            <label>Buscar por Nombre</label>
            <input type="text" id="searchUserName" placeholder="Nombre..." oninput="searchUsers()">
        </div>
        <div class="form-group" style="margin: 0;">
            <label>Buscar por Email</label>
            <input type="text" id="searchUserEmail" placeholder="Email..." oninput="searchUsers()">
        </div>
        <div class="form-group" style="margin: 0;">
            <label>Rol</label>
            <select id="filterUserRole" onchange="searchUsers()">
                <option value="">Todos</option>
                <option value="patient">Paciente</option>
                <option value="doctor">Médico</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div class="form-group" style="margin: 0;">
            <label>Estado</label>
            <select id="filterUserActive" onchange="searchUsers()">
                <option value="">Todos</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
            </select>
        </div>
    </div>
    
    <table>
        <tbody id="usersTableBody">
            <!-- ... tbody existente ... -->
        </tbody>
    </table>
</div>
```

---

### **Paso 3: Actualizar Función `editDoctor()` Existente**

**REEMPLAZAR** la función actual en el `<script>` de la vista:

```javascript
// BORRAR ESTA FUNCIÓN (la antigua que lee del DOM):
function editDoctor(doctorId) {
    const row = document.querySelector(`tr[data-doctor-id="${doctorId}"]`);
    const cells = row.querySelectorAll('td');
    // ...
}

// YA NO ES NECESARIA, se usa la nueva de admin-dashboard.js
```

---

### **Paso 4: Actualizar Función `viewAppointment()`**

**REEMPLAZAR** la función actual:

```javascript
// BORRAR ESTA FUNCIÓN (la antigua con alert):
function viewAppointment(appointmentId) {
    alert('Ver detalles de cita: ' + appointmentId);
}

// YA NO ES NECESARIA, se usa la nueva de admin-dashboard.js
```

---

## ✅ Verificación de Integración

### **Test 1: Verificar Scripts Cargados**

Abrir consola del navegador (F12) y ejecutar:

```javascript
console.log(typeof AjaxHandler); // Debe mostrar: "object"
console.log(typeof Toast);       // Debe mostrar: "object"
console.log(typeof editDoctor);  // Debe mostrar: "function"
```

### **Test 2: Probar Búsqueda de Médicos**

1. Ir a "Gestionar Médicos"
2. Escribir en el campo "Buscar por Nombre"
3. Ver tabla actualizándose automáticamente

### **Test 3: Ver Detalles de Cita**

1. Ir a "Todas las Citas"
2. Click en botón "Ver" de cualquier cita
3. Debe abrir modal con detalles completos

### **Test 4: Editar Médico con AJAX**

1. Click en "Editar" de un médico
2. Verificar que el modal carga con todos los datos (incluido license_number)
3. Modificar y guardar
4. Ver notificación Toast de éxito

---

## 🐞 Troubleshooting

### Error: "AjaxHandler is not defined"

**Solución:** Verificar que `ajax-handler.js` esté incluido ANTES de `admin-dashboard.js`

### Error 404 en rutas

**Solución:** Ejecutar:

```bash
php artisan route:clear
php artisan cache:clear
```

### Búsquedas no funcionan

**Solución:** Verificar que los IDs de los inputs coincidan:
- `searchDoctorName`
- `searchDoctorSpecialty`
- `filterDoctorActive`
- etc.

### Modal no se cierra

**Solución:** Verificar que exista la función `closeModal()` en el script

---

## 🎉 Funcionalidades Completadas

- ✅ Ver detalles completos de citas
- ✅ Búsqueda en tiempo real de médicos
- ✅ Filtros de citas por fecha/estado/paciente/doctor
- ✅ Búsqueda de usuarios por nombre/email/rol
- ✅ Cargar datos completos al editar médico
- ✅ Sistema de notificaciones Toast
- ✅ Loading spinners durante operaciones
- ✅ Renderizado dinámico de tablas

---

## 🚀 Próximos Pasos

Continuar con:

1. Dashboard de Doctor - AJAX para filtros y agenda
2. Dashboard de Paciente - Búsqueda de doctores y slots
3. Gestión de horarios (Schedules CRUD)
4. Notificaciones en tiempo real con WebSockets

---

**📌 Nota:** Todos los endpoints ya están creados en el backend. Solo falta integrar la UI.
