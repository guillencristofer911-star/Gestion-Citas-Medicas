<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MediConnect - Dashboard Médico</title>
    
    {{-- ==================== HOJAS DE ESTILO ==================== --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/schedule.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}">
</head>

<body>
    <div class="dashboard-container">
        {{-- ==================== SIDEBAR ==================== --}}
        <div class="sidebar">
            {{-- Logo Section --}}
            <div class="logo-section">
                <div class="logo">
                    <div class="logo-icon">🏥</div>
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
                
                <div class="menu-item" onclick="showSection('appointments')">
                    <span class="menu-item-icon">📅</span>
                    <span>Mis Citas</span>
                </div>
                
                <div class="menu-item" onclick="showSection('schedule')">
                    <span class="menu-item-icon">⏰</span>
                    <span>Mi Agenda</span>
                </div>
            </div>

            {{-- Perfil de Usuario --}}
            <div class="user-profile">
                <div class="user-info">
                    <div class="user-avatar">👨‍⚕️</div>
                    <div>
                        <h4>{{ $user->name }}</h4>
                        <p>{{ $userRole }}</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn-sidebar">Cerrar Sesión</button>
                </form>
            </div>
        </div>

        {{-- ==================== CONTENIDO PRINCIPAL ==================== --}}
        <div class="main-content">
            {{-- Encabezado --}}
            <div class="header">
                <h1>Dashboard Médico</h1>
            </div>

            {{-- ==================== SECCIÓN: DASHBOARD ==================== --}}
            <div id="dashboard" class="content-section">
                {{-- Tarjetas de Estadísticas --}}
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

                {{-- Tabla: Próximas Citas --}}
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
                                    <td>{{ $appointment->patient->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date_time)->format('d/m/Y H:i A') }}</td>
                                    <td>
                                        @if($appointment->status === 'confirmed')
                                            <span class="status-badge status-confirmed">Confirmada</span>
                                        @elseif($appointment->status === 'pending')
                                            <span class="status-badge status-pending">Pendiente</span>
                                        @elseif($appointment->status === 'attended')
                                            <span class="status-badge status-attended">Atendida</span>
                                        @else
                                            <span class="status-badge status-canceled">Cancelada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="openStatusModal('{{ $appointment->patient->name }}', '{{ \Carbon\Carbon::parse($appointment->appointment_date_time)->format('H:i A') }}', {{ $appointment->id }})">
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

                    <div style="margin-top: 1rem;">
                        {{ $upcomingList->links() }}
                    </div>
                </div>
            </div>

            {{-- ==================== SECCIÓN: MIS CITAS ==================== --}}
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
                                    <td>{{ $appointment->patient->name }}</td>
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
                                            <span class="status-badge status-canceled">Cancelada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($appointment->status !== 'attended' && $appointment->status !== 'canceled')
                                            <button class="btn btn-primary btn-sm" onclick="openStatusModal('{{ $appointment->patient->name }}', '{{ \Carbon\Carbon::parse($appointment->appointment_date_time)->format('H:i A') }}', {{ $appointment->id }})">
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

                    <div style="margin-top: 1rem;">
                        {{ $allAppointments->links() }}
                    </div>
                </div>
            </div>

            {{-- ==================== SECCIÓN: MI AGENDA ==================== --}}
            <div id="schedule" class="content-section" style="display:none;">
                <div class="section-title">⏰ Mi Agenda de Atención</div>
                
                {{-- Selector de Vista --}}
                <div class="schedule-header">
                    <div class="view-toggle">
                        <button class="toggle-btn active" data-view="daily" onclick="showScheduleView('daily')">
                            <span class="icon">📅</span> Vista Diaria
                        </button>
                        <button class="toggle-btn" data-view="weekly" onclick="showScheduleView('weekly')">
                            <span class="icon">📊</span> Vista Semanal
                        </button>
                    </div>
                    <div class="schedule-controls">
                        <input type="date" id="scheduleDate" class="date-picker" value="{{ date('Y-m-d') }}" onchange="updateScheduleView()">
                    </div>
                </div>

                <div class="section">
                    {{-- VISTA DIARIA --}}
                    <div id="dailyView" class="schedule-view active">
                        <div class="daily-header">
                            <h3>Agenda del <span id="dailyDate">{{ date('d/m/Y') }}</span></h3>
                            <span class="current-time" id="currentTime"></span>
                        </div>

                        <div class="timeline-container">
                            <div class="current-time-indicator" id="currentTimeIndicator"></div>

                            @forelse($dailySchedule as $timeSlot)
                                <div class="timeline-slot {{ $timeSlot['status'] === 'booked' ? 'booked' : 'available' }}" data-time="{{ $timeSlot['start_time'] }}">
                                    <div class="slot-time">
                                        <span class="time-label">{{ $timeSlot['start_time'] }} - {{ $timeSlot['end_time'] }}</span>
                                    </div>
                                    <div class="slot-content">
                                        @if($timeSlot['status'] === 'booked' && isset($timeSlot['appointment']) && $timeSlot['appointment'])
                                            <div class="slot-appointment">
                                                <strong>{{ $timeSlot['appointment']->patient->name }}</strong>
                                                <p>{{ $timeSlot['appointment']->reason ?? 'Sin motivo especificado' }}</p>
                                                <span class="status-badge status-{{ $timeSlot['appointment']->status }}">
                                                    {{ ucfirst($timeSlot['appointment']->status) }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="slot-available">
                                                <span>✓ Disponible</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="timeline-empty">
                                    <p>No hay información de agenda para este día</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="daily-summary">
                            <div class="summary-card">
                                <span class="summary-label">Citas Hoy:</span>
                                <span class="summary-value">{{ $todayAppointments ?? 0 }}</span>
                            </div>
                            <div class="summary-card">
                                <span class="summary-label">Disponibilidad:</span>
                                <span class="summary-value">{{ $todayAvailability ?? '0 hrs' }}</span>
                            </div>
                            <div class="summary-card">
                                <span class="summary-label">Estado:</span>
                                <span class="summary-value">
                                    <span class="status-badge status-confirmed">Activo</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- VISTA SEMANAL --}}
                    <div id="weeklyView" class="schedule-view" style="display:none;">
                        <div class="weekly-header">
                            <h3>Agenda de la Semana</h3>
                        </div>

                        <div class="weekly-grid">
                            @foreach($weeklySchedule as $day)
                                <div class="day-card {{ $day['status'] === 'inactive' ? 'inactive' : '' }}" data-day="{{ $day['date'] }}">
                                    <div class="day-header">
                                        <div class="day-name">{{ $day['day_name'] }}</div>
                                        <div class="day-date">{{ $day['date_short'] }}</div>
                                    </div>
                                    
                                    <div class="day-content">
                                        @if($day['status'] === 'active')
                                            <div class="day-hours">
                                                <span class="hours-label">{{ $day['start_time'] }} - {{ $day['end_time'] }}</span>
                                            </div>
                                            
                                            <div class="day-stats">
                                                <div class="stat-item">
                                                    <span class="stat-icon">📅</span>
                                                    <span class="stat-text">{{ $day['appointments_count'] }} citas</span>
                                                </div>
                                                <div class="stat-item">
                                                    <span class="stat-icon">⏱️</span>
                                                    <span class="stat-text">{{ $day['available_hours'] }} hrs libres</span>
                                                </div>
                                            </div>

                                            <div class="appointments-preview">
                                                @forelse($day['appointments'] as $apt)
                                                    <div class="apt-preview">
                                                        <span class="apt-time">{{ $apt['time'] }}</span>
                                                        <span class="apt-patient">{{ substr($apt['patient'], 0, 15) }}...</span>
                                                    </div>
                                                @empty
                                                    <p class="no-apts">Sin citas</p>
                                                @endforelse
                                            </div>

                                            <span class="day-status status-badge status-confirmed">Activo</span>
                                        @else
                                            <div class="day-closed">
                                                <span class="closed-icon">🚫</span>
                                                <span class="closed-text">Inactivo</span>
                                            </div>
                                            <span class="day-status status-badge status-canceled">No labora</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="weekly-stats">
                            <div class="stat-box">
                                <div class="stat-box-icon">📅</div>
                                <div class="stat-box-content">
                                    <h4>Citas de la Semana</h4>
                                    <p class="stat-box-number">{{ $weeklyAppointments ?? 0 }}</p>
                                </div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-box-icon">⏰</div>
                                <div class="stat-box-content">
                                    <h4>Horas de Atención</h4>
                                    <p class="stat-box-number">{{ $weeklyHours ?? '0' }} hrs</p>
                                </div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-box-icon">✓</div>
                                <div class="stat-box-content">
                                    <h4>Confirmadas</h4>
                                    <p class="stat-box-number">{{ $weeklyConfirmed ?? 0 }}</p>
                                </div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-box-icon">⏳</div>
                                <div class="stat-box-content">
                                    <h4>Pendientes</h4>
                                    <p class="stat-box-number">{{ $weeklyPending ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: CAMBIAR ESTADO ==================== --}}
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
                        <option value="canceled">Cancelada</option>
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

    {{-- ==================== JAVASCRIPT ==================== --}}
    <script>
        /**
         * ==================== VARIABLES GLOBALES ====================
         */
        let currentView = 'daily';

        /**
         * ==================== NAVEGACIÓN DE SECCIONES ====================
         * Cambia entre las diferentes secciones del dashboard
         * 
         * @param {string} sectionId - ID de la sección a mostrar
         */
        function showSection(sectionId) {
            document.querySelectorAll('.content-section').forEach(section => {
                section.style.display = 'none';
            });

            document.getElementById(sectionId).style.display = 'block';

            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            event.target.closest('.menu-item').classList.add('active');
        }

        /**
         * ==================== GESTIÓN DE MODAL ====================
         * Abre el modal para cambiar estado de cita
         * 
         * @param {string} patientName - Nombre del paciente
         * @param {string} time - Hora de la cita
         * @param {number} appointmentId - ID de la cita
         */
        function openStatusModal(patientName, time, appointmentId) {
            document.getElementById('patientName').textContent = patientName;
            document.getElementById('appointmentTime').textContent = time;
            document.getElementById('appointmentId').value = appointmentId;
            document.getElementById('statusModal').classList.add('active');
        }

        /**
         * Cierra el modal y limpia los campos
         */
        function closeModal() {
            document.getElementById('statusModal').classList.remove('active');
            document.getElementById('statusSelect').value = '';
            document.getElementById('notesField').value = '';
        }

        /**
         * ==================== ACTUALIZACIÓN DE ESTADO ====================
         * Envía petición AJAX para actualizar estado de cita
         * 
         * @param {Event} event - Evento del formulario
         */
        function updateStatus(event) {
            event.preventDefault();
            
            const status = document.getElementById('statusSelect').value;
            const notes = document.getElementById('notesField').value;
            const appointmentId = document.getElementById('appointmentId').value;
            
            if (!status) {
                alert('Por favor selecciona un estado');
                return;
            }
            
            const url = `/doctor/appointments/${appointmentId}/update-status`;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status, notes })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw errorData;
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('✓ ' + data.message);
                    closeModal();
                    location.reload();
                } else {
                    alert('❌ Error: ' + data.message);
                }
            })
            .catch(error => {
                if (error.errors) {
                    let errorMsg = 'Errores de validación:\n';
                    for (let field in error.errors) {
                        errorMsg += `- ${field}: ${error.errors[field].join(', ')}\n`;
                    }
                    alert(errorMsg);
                } else if (error.message) {
                    alert('❌ Error: ' + error.message);
                } else {
                    alert('❌ Error al actualizar el estado');
                }
            });
        }

        /**
         * ==================== VISTAS DE AGENDA ====================
         * Cambia entre vista diaria y semanal
         * 
         * @param {string} view - Tipo de vista ('daily' o 'weekly')
         */
        function showScheduleView(view) {
            currentView = view;
            
            document.querySelectorAll('.toggle-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.closest('.toggle-btn').classList.add('active');
            
            document.getElementById('dailyView').style.display = view === 'daily' ? 'block' : 'none';
            document.getElementById('weeklyView').style.display = view === 'weekly' ? 'block' : 'none';
            
            if (view === 'daily') {
                updateCurrentTime();
                initDailyTimeline();
            }
        }

        /**
         * Actualiza la vista de agenda según fecha seleccionada
         */
        function updateScheduleView() {
            const selectedDate = document.getElementById('scheduleDate').value;
            console.log('Actualizar agenda para:', selectedDate);
            location.reload();
        }

        /**
         * ==================== GESTIÓN DE TIEMPO ====================
         * Actualiza la hora actual mostrada
         */
        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('currentTime').textContent = 'Hora actual: ' + timeString;
            updateCurrentTimeIndicator();
        }

        /**
         * Actualiza el indicador visual de hora actual
         */
        function updateCurrentTimeIndicator() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes();
            const totalMinutes = hours * 60 + minutes;
            const percentFromStart = ((totalMinutes - (8 * 60)) / 540) * 100;
            
            const indicator = document.getElementById('currentTimeIndicator');
            if (indicator && percentFromStart >= 0 && percentFromStart <= 100) {
                indicator.style.top = percentFromStart + '%';
                indicator.style.display = 'block';
            }
        }

        /**
         * Inicializa la línea de tiempo diaria con scroll automático
         */
        function initDailyTimeline() {
            const timeSlots = document.querySelectorAll('.timeline-slot');
            timeSlots.forEach(slot => {
                const slotTime = slot.getAttribute('data-time');
                const now = new Date();
                const currentHour = String(now.getHours()).padStart(2, '0');
                
                if (slotTime.startsWith(currentHour)) {
                    slot.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }

        /**
         * ==================== EVENTOS ====================
         * Cierra modal al hacer clic fuera del contenido
         */
        window.onclick = function(event) {
            const modal = document.getElementById('statusModal');
            if (event.target === modal) {
                closeModal();
            }
        };

        /**
         * ==================== INICIALIZACIÓN ====================
         * Configuración inicial al cargar la página
         */
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(updateCurrentTime, 60000);
            updateCurrentTime();
        });

        setInterval(updateCurrentTimeIndicator, 300000);
    </script>
</body>
</html>