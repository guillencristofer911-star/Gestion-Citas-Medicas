<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediConnect - Dashboard Admin</title>
    
    {{-- Hojas de Estilo --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}">
</head>

<body>
    <div class="dashboard-container">
        {{-- ==================== SIDEBAR ==================== --}}
        <div class="sidebar">
            {{-- Logo Section --}}
            <div class="logo-section">
                <div class="logo">
                    <div class="logo-icon">🥼</div>
                    <span>MediConnect</span>
                </div>
            </div>

            {{-- Menu Section --}}
            <div class="menu-section">
                <div class="menu-title">Menú Principal</div>
                
                <div class="menu-item active" onclick="showSection('dashboard')">
                    <span class="menu-item-icon">📊</span>
                    <span>Dashboard</span>
                </div>
                
                <div class="menu-item" onclick="showSection('doctors')">
                    <span class="menu-item-icon">👨‍⚕️</span>
                    <span>Gestionar Médicos</span>
                </div>
                
                <div class="menu-item" onclick="showSection('appointments')">
                    <span class="menu-item-icon">📅</span>
                    <span>Todas las Citas</span>
                </div>
                
                <div class="menu-item" onclick="showSection('users')">
                    <span class="menu-item-icon">👥</span>
                    <span>Gestionar Usuarios</span>
                </div>
            </div>

            {{-- User Profile Section --}}
            <div class="user-profile">
                <div class="user-info">
                    <div class="user-avatar">⚙️</div>
                    <div>
                        <h4>{{ auth()->user()->name }}</h4>
                        <p>Administrador</p>
                    </div>
                </div>
                
                <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                    @csrf
                    <button type="submit" class="logout-btn-sidebar">Cerrar Sesión</button>
                </form>
            </div>
        </div>

        {{-- ==================== MAIN CONTENT ==================== --}}
        <div class="main-content">
            {{-- Header --}}
            <div class="header">
                <h1>Dashboard Administrador</h1>
            </div>

            {{-- Contenedor de Alertas --}}
            <div id="alertContainer"></div>

            {{-- ==================== DASHBOARD SECTION ==================== --}}
            <div id="dashboard" class="content-section">
                {{-- Tarjetas de Estadísticas --}}
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">👥</div>
                        <div class="stat-content">
                            <h3>Total Médicos</h3>
                            <p class="stat-number">{{ $totalDoctors }}</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon green">👤</div>
                        <div class="stat-content">
                            <h3>Total Pacientes</h3>
                            <p class="stat-number">{{ $totalPatients }}</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon orange">📅</div>
                        <div class="stat-content">
                            <h3>Citas Este Mes</h3>
                            <p class="stat-number">{{ $totalAppointments }}</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon red">⏳</div>
                        <div class="stat-content">
                            <h3>Pendientes de Confirmar</h3>
                            <p class="stat-number">{{ $pendingAppointments }}</p>
                        </div>
                    </div>
                </div>

                {{-- Tabla: Resumen General --}}
                <div class="section-title">📊 Resumen General del Sistema</div>
                <div class="section">
                    <table>
                        <thead>
                            <tr>
                                <th>Métrica</th>
                                <th>Valor</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Médicos Activos</td>
                                <td>{{ $doctors->where('active', true)->count() }}</td>
                                <td><span class="status-badge status-active">Activo</span></td>
                            </tr>
                            <tr>
                                <td>Pacientes Registrados</td>
                                <td>{{ $totalPatients }}</td>
                                <td><span class="status-badge status-active">Activo</span></td>
                            </tr>
                            <tr>
                                <td>Citas Confirmadas</td>
                                <td>{{ $appointments->where('status', 'confirmed')->count() }}</td>
                                <td><span class="status-badge status-confirmed">Confirmadas</span></td>
                            </tr>
                            <tr>
                                <td>Citas Pendientes</td>
                                <td>{{ $pendingAppointments }}</td>
                                <td><span class="status-badge status-pending">Pendientes</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ==================== GESTIONAR MÉDICOS SECTION ==================== --}}
            <div id="doctors" class="content-section" style="display:none;">
                <div class="section-title">👨‍⚕️ Gestión de Médicos</div>
                <div class="section">
                    <div class="action-buttons">
                        <button class="btn btn-primary" onclick="openAddDoctorModal()">➕ Agregar Médico</button>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Especialidad</th>
                                <th>Teléfono</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="doctorsTableBody">
                            @forelse($doctors as $doctor)
                                <tr data-doctor-id="{{ $doctor->id }}">
                                    <td>{{ $doctor->user->name }}</td>
                                    <td>{{ $doctor->user->email }}</td>
                                    <td>{{ $doctor->specialty }}</td>
                                    <td>{{ $doctor->phone }}</td>
                                    <td>
                                        @if($doctor->active)
                                            <span class="status-badge status-active">Activo</span>
                                        @else
                                            <span class="status-badge status-inactive">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="editDoctor({{ $doctor->id }})">
                                            Editar
                                        </button>
                                        @if($doctor->active)
                                            {{-- Desactivar --}}
                                            <form method="POST" 
                                                action="{{ route('admin.doctors.toggle', $doctor->id) }}" 
                                                style="display: inline;"
                                                onsubmit="return confirm('¿Desactivar este médico?')">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="active" value="0">
                                                <input type="hidden" name="section" value="doctors">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Desactivar
                                                </button>
                                            </form>
                                        @else
                                            {{-- Activar --}}
                                            <form method="POST" 
                                                action="{{ route('admin.doctors.toggle', $doctor->id) }}" 
                                                style="display: inline;"
                                                onsubmit="return confirm('¿Activar este médico?')">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="active" value="1">
                                                <input type="hidden" name="section" value="doctors">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    Activar
                                                </button>
                                            </form>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 20px;">No hay médicos registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ==================== TODAS LAS CITAS SECTION ==================== --}}
            <div id="appointments" class="content-section" style="display:none;">
                <div class="section-title">📅 Todas las Citas del Sistema</div>
                <div class="section">
                    <table>
                        <thead>
                            <tr>
                                <th>Paciente</th>
                                <th>Médico</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->patient->name }}</td>
                                    <td>{{ $doctor->user ? $doctor->user->name : 'Usuario no disponible' }}</td>
                                    <td>{{ $appointment->appointment_date_time->format('d/m/Y') }}</td>
                                    <td>{{ $appointment->appointment_date_time->format('H:i') }}</td>
                                    <td>
                                        @if($appointment->status === 'confirmed')
                                            <span class="status-badge status-confirmed">Confirmada</span>
                                        @elseif($appointment->status === 'pending')
                                            <span class="status-badge status-pending">Pendiente</span>
                                        @elseif($appointment->status === 'attended')
                                            <span class="status-badge status-attended">Atendida</span>
                                        @else
                                            <span class="status-badge" style="background: #f0f0f0; color: #666;">{{ ucfirst($appointment->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-secondary btn-sm" onclick="viewAppointment({{ $appointment->id }})">Ver</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 20px;">No hay citas registradas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ==================== GESTIONAR USUARIOS SECTION ==================== --}}
            <div id="users" class="content-section" style="display:none;">
                <div class="section-title">👥 Gestión de Usuarios</div>
                <div class="section">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr data-user-id="{{ $user->id }}">
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role === 'patient')
                                            <span class="status-badge" style="background: #E8D4F7; color: #5A0A9E;">Paciente</span>
                                        @elseif($user->role === 'doctor')
                                            <span class="status-badge" style="background: #D4E8F7; color: #0A5A9E;">Médico</span>
                                        @elseif($user->role === 'admin')
                                            <span class="status-badge" style="background: #D4F7D4; color: #0A5A0A;">Admin</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->active ?? true)
                                            <span class="status-badge status-active">Activo</span>
                                        @else
                                            <span class="status-badge status-inactive">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->id !== auth()->id())
                                            @if($user->active ?? true)
                                                <button class="btn btn-danger btn-sm" onclick="deactivateUser({{ $user->id }})">Desactivar</button>
                                            @else
                                                <button class="btn btn-success btn-sm" onclick="activateUser({{ $user->id }})">Activar</button>
                                            @endif
                                        @else
                                            <span style="color: #999;">Tu usuario</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 20px;">No hay usuarios registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL AGREGAR/EDITAR MÉDICO ==================== --}}
    <div id="doctorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="doctorModalTitle">Agregar Nuevo Médico</h2>
                <button class="close-modal" onclick="closeModal('doctorModal')">&times;</button>
            </div>

            <form id="doctorForm" onsubmit="submitDoctorForm(event)">
                @csrf
                <input type="hidden" id="doctorId" name="doctor_id">

                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" id="doctorName" name="name" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="doctorEmail" name="email" required>
                </div>

                <div class="form-group">
                    <label>Especialidad</label>
                    <input type="text" id="doctorSpecialty" name="specialty" required>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="tel" id="doctorPhone" name="phone" required>
                </div>

                <div class="form-group">
                    <label>Número de Licencia</label>
                    <input type="text" id="doctorLicense" name="license_number" required>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-success" id="doctorSubmitBtn">Guardar</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('doctorModal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== JAVASCRIPT ==================== --}}
    <script>
        /**
         * Token CSRF para peticiones AJAX (si lo necesitas en el futuro)
         */
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        /**
         * ==================== NAVEGACIÓN DE SECCIONES CON URL ====================
         * Cambiar entre secciones del dashboard con actualización de URL
         */
        function showSection(sectionId) {
            // Ocultar todas las secciones
            document.querySelectorAll('.content-section').forEach(section => {
                section.style.display = 'none';
            });
            
            // Mostrar la sección seleccionada
            const targetSection = document.getElementById(sectionId);
            if (targetSection) {
                targetSection.style.display = 'block';
            }

            // Actualizar menú activo
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('data-section') === sectionId) {
                    item.classList.add('active');
                }
            });

            // Actualizar URL sin recargar la página
            const newUrl = `{{ route('admin.dashboard') }}?section=${sectionId}`;
            window.history.pushState({section: sectionId}, '', newUrl);
        }

        /**
         * ==================== MANEJO DE NAVEGADOR (Atrás/Adelante) ====================
         * Detecta cuando el usuario usa botones del navegador
         */
        window.addEventListener('popstate', function(event) {
            if (event.state && event.state.section) {
                const sectionId = event.state.section;
                
                // Ocultar todas las secciones
                document.querySelectorAll('.content-section').forEach(section => {
                    section.style.display = 'none';
                });

                // Mostrar la sección guardada
                const targetSection = document.getElementById(sectionId);
                if (targetSection) {
                    targetSection.style.display = 'block';
                }

                // Actualizar menú activo
                document.querySelectorAll('.menu-item').forEach(item => {
                    item.classList.remove('active');
                    if (item.getAttribute('data-section') === sectionId) {
                        item.classList.add('active');
                    }
                });
            }
        });

        /**
         * ==================== INICIALIZACIÓN ====================
         * Configuración inicial al cargar la página
         */
        window.addEventListener('load', function() {
            // Leer sección desde URL (?section=doctors)
            const urlParams = new URLSearchParams(window.location.search);
            const sectionParam = urlParams.get('section');
            
            // Determinar qué sección mostrar
            let initialSection = 'dashboard'; // Valor por defecto
            
            // Si hay parámetro en la URL, usarlo
            if (sectionParam && document.getElementById(sectionParam)) {
                initialSection = sectionParam;
            }
            
            // Mostrar la sección determinada
            showSection(initialSection);

            // Guardar estado inicial en el historial
            window.history.replaceState({section: initialSection}, '', window.location.href);
        });

        /**
         * ==================== EVENTOS DE MENÚ ====================
         * Manejo de clicks en items del menú
         */
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const sectionId = this.getAttribute('data-section');
                    if (sectionId) {
                        showSection(sectionId);
                    }
                });
            });
        });

        // ==================== GESTIÓN DE MODALES (Solo UI) ====================

        /**
         * Abrir modal para agregar nuevo médico
         */
        function openAddDoctorModal() {
            document.getElementById('doctorModalTitle').textContent = 'Agregar Nuevo Médico';
            document.getElementById('doctorForm').reset();
            document.getElementById('doctorId').value = '';
            document.getElementById('doctorEmail').disabled = false;
            document.getElementById('doctorSubmitBtn').textContent = 'Guardar';
            document.getElementById('doctorModal').classList.add('active');
        }

        /**
         * Editar médico existente - Solo carga datos en el modal
         */
        function editDoctor(doctorId) {
            const row = document.querySelector(`tr[data-doctor-id="${doctorId}"]`);
            if (!row) {
                console.error('No se encontró la fila del doctor');
                return;
            }

            const cells = row.querySelectorAll('td');

            document.getElementById('doctorModalTitle').textContent = 'Editar Médico';
            document.getElementById('doctorId').value = doctorId;
            document.getElementById('doctorName').value = cells[0].textContent.trim();
            document.getElementById('doctorEmail').value = cells[1].textContent.trim();
            document.getElementById('doctorEmail').disabled = true;
            document.getElementById('doctorSpecialty').value = cells[2].textContent.trim();
            document.getElementById('doctorPhone').value = cells[3].textContent.trim();
            document.getElementById('doctorLicense').value = '';
            document.getElementById('doctorSubmitBtn').textContent = 'Actualizar';
            document.getElementById('doctorModal').classList.add('active');
        }

        /**
         * Cerrar modal
         */
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        /**
         * Cerrar modal al hacer clic fuera
         */
        window.onclick = function(event) {
            const modal = event.target;
            if (modal.classList && modal.classList.contains('modal')) {
                modal.classList.remove('active');
            }
        }

        // ==================== GESTIÓN DE CITAS ====================

        /**
         * Ver detalles de una cita (placeholder para funcionalidad futura)
         */
        function viewAppointment(appointmentId) {
            alert('Ver detalles de cita: ' + appointmentId);
            // TODO: Implementar modal de detalles de cita
        }
    </script>

</body>
</html>
