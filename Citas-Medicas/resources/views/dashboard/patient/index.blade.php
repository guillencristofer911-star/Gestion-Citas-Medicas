<!DOCTYPE html>
<!-- Laravel Blade Template -->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MediConnect - Dashboard</title>
    <!-- CSS Compartido -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="dashboard-container">
        <!-- ==================== SIDEBAR ==================== -->
        <div class="sidebar">
            <div class="logo-section">
                <div class="logo">
                    <div class="logo-icon">🏥</div>
                    <span>MediConnect</span>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-title">Menú Principal</div>
                
                <!-- Dashboard Menu Item -->
                <div class="menu-item active" data-section="dashboard">
                    <span class="menu-item-icon">📊</span>
                    <span>Dashboard</span>
                </div>
                
                <!-- Médicos Menu Item -->
                <div class="menu-item" data-section="doctors">
                    <span class="menu-item-icon">👨‍⚕️</span>
                    <span>Ver Médicos</span>
                </div>
                
                <!-- Mis Citas Menu Item -->
                <div class="menu-item" data-section="appointments">
                    <span class="menu-item-icon">📅</span>
                    <span>Mis Citas</span>
                </div>
                
                <!-- Solicitar Cita Menu Item -->
                <div class="menu-item" data-section="request-appointment">
                    <span class="menu-item-icon">➕</span>
                    <span>Solicitar Cita</span>
                </div>
            </div>

            <div class="user-profile">
                <div class="user-info">
                    <div class="user-avatar">👤</div>
                    <div>
                        <h4>{{ $user->name }}</h4>
                        <p>{{ $userRole }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn-sidebar">Cerrar Sesión</button>
                </form>
            </div>
        </div>

        <!-- ==================== MAIN CONTENT ==================== -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Paciente</h1>
            </div>

            <!-- ==================== DASHBOARD SECTION ==================== -->
            <div id="dashboard" class="content-section">
                {{-- Tarjetas de Estadísticas --}}
                <div class="stats-grid">
                    {{-- Citas Próximas --}}
                    <div class="stat-card">
                        <div class="stat-icon blue">📅</div>
                        <div class="stat-content">
                            <h3>Citas Próximas</h3>
                            <p class="stat-number">{{ $upcomingAppointments }}</p>
                        </div>
                    </div>

                    {{-- Citas Confirmadas --}}
                    <div class="stat-card">
                        <div class="stat-icon green">✓</div>
                        <div class="stat-content">
                            <h3>Citas Confirmadas</h3>
                            <p class="stat-number">{{ $confirmedAppointments }}</p>
                        </div>
                    </div>

                    {{-- Citas Pendientes --}}
                    <div class="stat-card">
                        <div class="stat-icon orange">⏳</div>
                        <div class="stat-content">
                            <h3>Pendientes</h3>
                            <p class="stat-number">{{ $pendingAppointments }}</p>
                        </div>
                    </div>
                </div>

                {{-- Tabla: Próximas Citas --}}
                <div class="section-title">📋 Próximas Citas</div>
                <div class="section">
                    @if($upcomingList->count() > 0)
                        <table>
                            <thead>
                                <tr>
                                    <th>Médico</th>
                                    <th>Especialidad</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingList as $appointment)
                                    <tr>
                                        <td>{{ $appointment->doctor->user->name }}</td>
                                        <td>{{ $appointment->doctor->specialty }}</td>
                                        <td>{{ $appointment->appointment_date_time->format('d/m/Y') }}</td>
                                        <td>{{ $appointment->appointment_date_time->format('H:i') }}</td>
                                        <td>
                                            @if($appointment->status === 'confirmed')
                                                <span class="status-badge status-confirmed">Confirmada</span>
                                            @elseif($appointment->status === 'pending')
                                                <span class="status-badge status-pending">Pendiente</span>
                                            @elseif($appointment->status === 'attended')
                                                <span class="status-badge status-attended">Atendida</span>
                                            @elseif($appointment->status === 'canceled')
                                                <span class="status-badge status-canceled">Cancelada</span>
                                            @else
                                                <span class="status-badge">{{ ucfirst($appointment->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($upcomingList->hasPages())
                            <div class="pagination-wrapper">
                                {{ $upcomingList->links() }}
                            </div>
                        @endif
                    @else
                        <p style="text-align: center; color: #999; padding: 20px;">
                            📭 No hay citas próximas
                        </p>
                    @endif
                </div>
            </div>

            <!-- ==================== VER MÉDICOS SECTION ==================== -->
            <div id="doctors" class="content-section" style="display:none;">
                <div class="section-title">👨‍⚕️ Médicos Disponibles</div>
                <div class="section">
                    @if($doctors->count() > 0)
                        <table>
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Especialidad</th>
                                    <th>Licencia</th>
                                    <th>Biografía</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($doctors as $doctor)
                                    <tr>
                                        <td>
                                            <strong>{{ $doctor->user->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="specialty-badge">{{ $doctor->specialty }}</span>
                                        </td>
                                        <td>
                                            <code>{{ $doctor->license_number }}</code>
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($doctor->biography ?? 'Sin información', 50) }}</small>
                                        </td>
                                        <td>
                                            @if($doctor->active)
                                                <span class="status-badge status-confirmed">Disponible</span>
                                            @else
                                                <span class="status-badge status-pending">No disponible</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-primary" onclick="scrollToRequestForm()">
                                                Solicitar Cita
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 30px; color: #666;">
                                            <p>📭 No hay médicos disponibles en este momento.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        <div style="text-align: center; padding: 40px; color: #999;">
                            <p style="font-size: 16px;">📭 No hay médicos disponibles</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ==================== MIS CITAS SECTION ==================== -->
            <div id="appointments" class="content-section" style="display:none;">
                <div class="section-title">📅 Historial de Citas</div>
                
                {{-- Mensaje de éxito --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Mensaje de error --}}
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="section">
                    @if($allAppointments->count() > 0)
                        <table>
                            <thead>
                                <tr>
                                    <th>Médico</th>
                                    <th>Especialidad</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allAppointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->doctor->user->name }}</td>
                                        <td>{{ $appointment->doctor->specialty }}</td>
                                        <td>{{ $appointment->appointment_date_time->format('d/m/Y') }}</td>
                                        <td>{{ $appointment->appointment_date_time->format('H:i') }}</td>
                                        <td>
                                            @if($appointment->status === 'confirmed')
                                                <span class="status-badge status-confirmed">Confirmada</span>
                                            @elseif($appointment->status === 'pending')
                                                <span class="status-badge status-pending">Pendiente</span>
                                            @elseif($appointment->status === 'attended')
                                                <span class="status-badge status-attended">Atendida</span>
                                            @elseif($appointment->status === 'canceled')
                                                <span class="status-badge status-canceled">Cancelada</span>
                                            @else
                                                <span class="status-badge">{{ ucfirst($appointment->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(in_array($appointment->status, ['pending', 'confirmed']))
                                                <button class="btn btn-danger btn-sm" onclick="cancelAppointment({{ $appointment->id }})">
                                                    Cancelar
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>No se puede cancelar</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Paginación --}}
                        @if($allAppointments->hasPages())
                            <div class="pagination-wrapper">
                                {{ $allAppointments->links() }}
                            </div>
                        @endif
                    @else
                        <p style="text-align: center; color: #666; padding: 20px;">
                            No tienes citas registradas. <a href="javascript:void(0)" onclick="showSection('request-appointment')">Solicita una ahora</a>
                        </p>
                    @endif
                </div>
            </div>

            <!-- ==================== SOLICITAR CITA SECTION ==================== -->
            <div id="request-appointment" class="content-section" style="display:none;">
                <div class="section-title">➕ Solicitar Nueva Cita</div>
                <div class="section">
                    {{-- Contenedor de alertas --}}
                    <div id="alert-container"></div>

                    {{-- Mostrar errores de validación --}}
                    @if($errors->any())
                        <div class="alert alert-danger" id="validation-errors">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Formulario conectado a Laravel --}}
                    <form method="POST" action="{{ route('appointments.store') }}" id="appointment-form">
                        @csrf {{-- Token de seguridad obligatorio --}}
                        
                        <div class="form-group">
                            <label>Seleccionar Médico</label>
                            <select name="doctor_id" required>
                                <option value="">-- Selecciona un médico --</option>
                                @forelse($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->user->name }} - {{ $doctor->specialty }}
                                    </option>
                                @empty
                                    <option disabled>No hay médicos disponibles</option>
                                @endforelse
                            </select>
                            @error('doctor_id')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Fecha de la Cita</label>
                            <input type="date" name="appointment_date" value="{{ old('appointment_date') }}" required>
                            @error('appointment_date')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Hora Disponible</label>
                            <select name="appointment_time" required>
                                <option value="">-- Selecciona una hora --</option>
                                <option value="08:00" {{ old('appointment_time') == '08:00' ? 'selected' : '' }}>08:00 AM</option>
                                <option value="09:00" {{ old('appointment_time') == '09:00' ? 'selected' : '' }}>09:00 AM</option>
                                <option value="10:00" {{ old('appointment_time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                <option value="11:00" {{ old('appointment_time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                <option value="14:00" {{ old('appointment_time') == '14:00' ? 'selected' : '' }}>02:00 PM</option>
                                <option value="15:00" {{ old('appointment_time') == '15:00' ? 'selected' : '' }}>03:00 PM</option>
                                <option value="16:00" {{ old('appointment_time') == '16:00' ? 'selected' : '' }}>04:00 PM</option>
                            </select>
                            @error('appointment_time')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Motivo de la Cita</label>
                            <textarea name="consultation_reason" placeholder="Describe brevemente el motivo de tu consulta" required>{{ old('consultation_reason') }}</textarea>
                            @error('consultation_reason')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary">Solicitar Cita</button>
                            <button type="reset" class="btn btn-secondary">Limpiar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ========== AUTO-DESVANECIMIENTO DE ALERTAS ==========
        function setupAutoFadeAlerts(duration = 5000) {
            const alerts = document.querySelectorAll('.alert');
            
            alerts.forEach(alert => {
                const alertDuration = alert.classList.contains('alert-danger') ? duration * 1.5 : duration;
                
                setTimeout(() => {
                    alert.classList.add('fade-out');
                    
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, alertDuration);
            });
        }

        // ========== MANEJO DE SECCIONES ==========
        function showSection(sectionId) {
            console.log('Mostrando sección:', sectionId);
            
            // Ocultar todas las secciones
            const allSections = document.querySelectorAll('.content-section');
            allSections.forEach(section => {
                section.style.display = 'none';
            });

            // Mostrar la sección seleccionada
            const targetSection = document.getElementById(sectionId);
            if (targetSection) {
                targetSection.style.display = 'block';
            } else {
                console.error('Sección no encontrada:', sectionId);
                return;
            }

            // Actualizar menú activo
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.classList.remove('active');
            });

            // Encontrar y marcar como activo el menú correspondiente
            menuItems.forEach(item => {
                if (item.getAttribute('data-section') === sectionId) {
                    item.classList.add('active');
                }
            });
        }

        // ========== INICIALIZACIÓN ==========
        window.addEventListener('load', function() {
            setupAutoFadeAlerts(5000);

            // Verificar si hay fragmento en la URL
            const fragment = window.location.hash.substring(1);
            
            if (fragment && document.getElementById(fragment)) {
                showSection(fragment);
            } else {
                showSection('dashboard');
            }
        });

        // ========== EVENTOS DE MENÚ ==========
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.menu-item');
            
            menuItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const sectionId = this.getAttribute('data-section');
                    showSection(sectionId);
                });
            });
        });

        // ========== CANCELAR CITA ==========
        function cancelAppointment(appointmentId) {
            if(confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/citas/${appointmentId}/cancel`;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                
                if (csrfToken) {
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = csrfToken;
                    form.appendChild(tokenInput);
                }
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        // ========== SCROLL A FORMULARIO ==========
        function scrollToRequestForm() {
            showSection('request-appointment');
            setTimeout(() => {
                const section = document.querySelector('#request-appointment');
                if (section) {
                    section.scrollIntoView({ behavior: 'smooth' });
                }
            }, 100);
        }
    </script>
</body>
</html>