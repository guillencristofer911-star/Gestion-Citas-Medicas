<!DOCTYPE html>
<!-- Laravel Blade Template -->
<html lang="es">
<head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediConnect - Dashboard</title>
    <!-- CSS Compartido -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo-section">
                <div class="logo">
                    <div class="logo-icon">🏥</div>
                    <span>MediConnect</span>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-title">Menú Principal</div>
                <div class="menu-item active" onclick="showSection('dashboard')">
                    <span class="menu-item-icon">📊</span>
                    <span>Dashboard</span>
                </div>
                <div class="menu-item" onclick="showSection('appointments')">
                    <span class="menu-item-icon">📅</span>
                    <span>Mis Citas</span>
                </div>
                <div class="menu-item" onclick="showSection('schedule')">
                    <span class="menu-item-icon">⏰</span>
                    <span>Mi Agenda</span>
                </div>
            </div>

<div class="user-profile">
    <div class="user-info">
        <div class="user-avatar">👨‍⚕️</div>
        <div>
            <h4>Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h4>
            <p>{{ $userRole }}</p>
        </div>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn-sidebar">Cerrar Sesión</button>
    </form>
</div>

        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Médico</h1>
            </div>

            <!-- Dashboard Section -->
            <div id="dashboard" class="content-section">
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">📅</div>
        <div class="stat-content">
            <h3>Citas Próximas</h3>
            <p class="stat-number">{{ $upcomingAppointments }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">✓</div>
        <div class="stat-content">
            <h3>Citas Confirmadas</h3>
            <p class="stat-number">{{ $confirmedAppointments }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">⏳</div>
        <div class="stat-content">
            <h3>Pendientes</h3>
            <p class="stat-number">{{ $pendingAppointments }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">📋</div>
        <div class="stat-content">
            <h3>Total Citas</h3>
            <p class="stat-number">{{ $totalAppointments }}</p>
        </div>
    </div>
</div>


<div class="section-title">📋 Próximas Citas</div>
<div class="section">
    <table>
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Fecha y Hora</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($upcomingList as $appointment)
            <tr>
                <td>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date_time)->format('d/m/Y H:i A') }}</td>
                <td>
                    @if($appointment->status === 'confirmed')
                        <span class="status-badge status-confirmed">Confirmada</span>
                    @elseif($appointment->status === 'pending')
                        <span class="status-badge status-pending">Pendiente</span>
                    @elseif($appointment->status === 'attended')
                        <span class="status-badge status-attended">Atendida</span>
                    @else
                        <span class="status-badge status-cancelled">Cancelada</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-primary btn-sm" 
                            onclick="openStatusModal('{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}', '{{ \Carbon\Carbon::parse($appointment->appointment_date_time)->format('H:i A') }}', {{ $appointment->id }})">
                        Cambiar Estado
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 2rem;">
                    No hay citas próximas programadas
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Paginación -->
    <div style="margin-top: 1rem;">
        {{ $upcomingList->links() }}
    </div>
</div>

            </div>

<!-- Mis Citas Section -->
<div id="appointments" class="content-section" style="display:none;">
    <div class="section-title">📅 Todas Mis Citas</div>
    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allAppointments as $appointment)
                <tr>
                    <td>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date_time)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date_time)->format('H:i A') }}</td>
                    <td>{{ $appointment->reason ?? 'No especificado' }}</td>
                    <td>
                        @if($appointment->status === 'confirmed')
                            <span class="status-badge status-confirmed">Confirmada</span>
                        @elseif($appointment->status === 'pending')
                            <span class="status-badge status-pending">Pendiente</span>
                        @elseif($appointment->status === 'attended')
                            <span class="status-badge status-attended">Atendida</span>
                        @else
                            <span class="status-badge status-cancelled">Cancelada</span>
                        @endif
                    </td>
                    <td>
                        @if($appointment->status !== 'attended' && $appointment->status !== 'cancelled')
                            <button class="btn btn-primary btn-sm" 
                                    onclick="openStatusModal('{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}', '{{ \Carbon\Carbon::parse($appointment->appointment_date_time)->format('H:i A') }}', {{ $appointment->id }})">
                                Actualizar
                            </button>
                        @else
                            <button class="btn btn-secondary btn-sm" disabled>Completada</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem;">
                        No tienes citas registradas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Paginación -->
        <div style="margin-top: 1rem;">
            {{ $allAppointments->links() }}
        </div>
    </div>
</div>


            <!-- Mi Agenda Section -->
            <div id="schedule" class="content-section" style="display:none;">
                <div class="section-title">⏰ Mi Agenda de Atención</div>
                <div class="section">
                    <h3 style="margin-bottom: 1.5rem; color: #333;">Horario de Atención Regular</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Día</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Lunes</td>
                                <td>08:00 AM</td>
                                <td>05:00 PM</td>
                                <td><span class="status-badge status-confirmed">Activo</span></td>
                            </tr>
                            <tr>
                                <td>Martes</td>
                                <td>08:00 AM</td>
                                <td>05:00 PM</td>
                                <td><span class="status-badge status-confirmed">Activo</span></td>
                            </tr>
                            <tr>
                                <td>Miércoles</td>
                                <td>08:00 AM</td>
                                <td>05:00 PM</td>
                                <td><span class="status-badge status-confirmed">Activo</span></td>
                            </tr>
                            <tr>
                                <td>Jueves</td>
                                <td>08:00 AM</td>
                                <td>05:00 PM</td>
                                <td><span class="status-badge status-confirmed">Activo</span></td>
                            </tr>
                            <tr>
                                <td>Viernes</td>
                                <td>08:00 AM</td>
                                <td>04:00 PM</td>
                                <td><span class="status-badge status-confirmed">Activo</span></td>
                            </tr>
                            <tr>
                                <td>Sábado</td>
                                <td>-</td>
                                <td>-</td>
                                <td><span class="status-badge status-cancelled">Inactivo</span></td>
                            </tr>
                            <tr>
                                <td>Domingo</td>
                                <td>-</td>
                                <td>-</td>
                                <td><span class="status-badge status-cancelled">Inactivo</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cambiar estado de cita -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Cambiar Estado de Cita</h2>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
<div style="margin-bottom: 1.5rem; padding: 1rem; background: #f5f5f5; border-radius: 8px;">
    <p><strong>Paciente:</strong> <span id="patientName"></span></p>
    <p><strong>Hora:</strong> <span id="appointmentTime"></span></p>
</div>
<form onsubmit="updateStatus(event)">
    <input type="hidden" id="appointmentId" value="">
    <div class="form-group">

                    <label>Seleccionar Nuevo Estado</label>
                    <select id="statusSelect" required>
                        <option value="">-- Selecciona un estado --</option>
                        <option value="confirmed">Confirmada</option>
                        <option value="attended">Atendida</option>
                        <option value="cancelled">Cancelada</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Notas (Opcional)</label>
                    <textarea id="notesField" placeholder="Agrega notas sobre la cita..."></textarea>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Cambiar secciones
        function showSection(sectionId) {
            // Ocultar todas las secciones
            document.querySelectorAll('.content-section').forEach(section => {
                section.style.display = 'none';
            });

            // Mostrar la sección seleccionada
            document.getElementById(sectionId).style.display = 'block';

            // Actualizar menu activo
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            event.target.closest('.menu-item').classList.add('active');
        }

        // Abrir modal de cambio de estado
        function openStatusModal(patientName, time) {
            document.getElementById('patientName').textContent = patientName;
            document.getElementById('appointmentTime').textContent = time;
            document.getElementById('statusModal').classList.add('active');
        }

        // Cerrar modal
        function closeModal() {
            document.getElementById('statusModal').classList.remove('active');
            document.getElementById('statusSelect').value = '';
            document.getElementById('notesField').value = '';
        }

        // Actualizar estado de cita
        function updateStatus(event) {
            event.preventDefault();
            const status = document.getElementById('statusSelect').value;
            const notes = document.getElementById('notesField').value;
            alert(`✓ Estado actualizado a: ${status}\n${notes ? 'Notas: ' + notes : ''}`);
            closeModal();
        }

        // Cerrar sesión
        function logout() {
            if(confirm('¿Deseas cerrar sesión?')) {
                alert('Sesión cerrada. Hasta pronto.');
                // Redirigir a login
            }
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('statusModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
