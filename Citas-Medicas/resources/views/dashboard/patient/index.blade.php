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
                <div class="menu-item" onclick="showSection('doctors')">
                    <span class="menu-item-icon">👨‍⚕️</span>
                    <span>Ver Médicos</span>
                </div>
                <div class="menu-item" onclick="showSection('appointments')">
                    <span class="menu-item-icon">📅</span>
                    <span>Mis Citas</span>
                </div>
                <div class="menu-item" onclick="showSection('request-appointment')">
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

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Paciente</h1>
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
                </div>

                <div class="section-title">📋 Próximas Citas</div>
                <div class="section">
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
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                </div>
            </div>

            <!-- Ver Médicos Section -->
            <div id="doctors" class="content-section" style="display:none;">
                <div class="section-title">👨‍⚕️ Médicos Disponibles</div>
                <div class="section">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Especialidad</th>
                                <th>Horario</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Dr. Carlos Martínez</td>
                                <td>Cardiología</td>
                                <td>Lun-Vie: 8:00 AM - 5:00 PM</td>
                                <td><button class="btn btn-primary" onclick="openRequestModal()">Solicitar Cita</button></td>
                            </tr>
                            <tr>
                                <td>Dra. María López</td>
                                <td>Pediatría</td>
                                <td>Lun-Vie: 9:00 AM - 4:00 PM</td>
                                <td><button class="btn btn-primary" onclick="openRequestModal()">Solicitar Cita</button></td>
                            </tr>
                            <tr>
                                <td>Dr. Jorge Mendoza</td>
                                <td>Dermatología</td>
                                <td>Mar-Jue: 10:00 AM - 6:00 PM</td>
                                <td><button class="btn btn-primary" onclick="openRequestModal()">Solicitar Cita</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mis Citas Section -->
            <div id="appointments" class="content-section" style="display:none;">
                <div class="section-title">📅 Historial de Citas</div>
                <div class="section">
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
                            <tr>
                                <td>Dr. Carlos Martínez</td>
                                <td>Cardiología</td>
                                <td>15/12/2025</td>
                                <td>10:00 AM</td>
                                <td><span class="status-badge status-confirmed">Confirmada</span></td>
                                <td><button class="btn btn-danger" onclick="cancelAppointment(this)">Cancelar</button></td>
                            </tr>
                            <tr>
                                <td>Dra. María López</td>
                                <td>Pediatría</td>
                                <td>18/12/2025</td>
                                <td>02:30 PM</td>
                                <td><span class="status-badge status-pending">Pendiente</span></td>
                                <td><button class="btn btn-danger" onclick="cancelAppointment(this)">Cancelar</button></td>
                            </tr>
                            <tr>
                                <td>Dr. Jorge Mendoza</td>
                                <td>Dermatología</td>
                                <td>10/12/2025</td>
                                <td>03:00 PM</td>
                                <td><span class="status-badge status-attended">Atendida</span></td>
                                <td><button class="btn btn-secondary" disabled>No se puede cancelar</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Solicitar Cita Section -->
            <div id="request-appointment" class="content-section" style="display:none;">
                <div class="section-title">➕ Solicitar Nueva Cita</div>
                <div class="section">
                    <form onsubmit="submitAppointment(event)">
                        <div class="form-group">
                            <label>Seleccionar Médico</label>
                            <select required>
                                <option value="">-- Selecciona un médico --</option>
                                <option value="1">Dr. Carlos Martínez - Cardiología</option>
                                <option value="2">Dra. María López - Pediatría</option>
                                <option value="3">Dr. Jorge Mendoza - Dermatología</option>
                                <option value="4">Dra. Ana Rodríguez - Neurología</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Fecha de la Cita</label>
                            <input type="date" required>
                        </div>

                        <div class="form-group">
                            <label>Hora Disponible</label>
                            <select required>
                                <option value="">-- Selecciona una hora --</option>
                                <option value="08:00">08:00 AM</option>
                                <option value="09:00">09:00 AM</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="14:00">02:00 PM</option>
                                <option value="15:00">03:00 PM</option>
                                <option value="16:00">04:00 PM</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Motivo de la Cita</label>
                            <textarea placeholder="Describe brevemente el motivo de tu consulta" required></textarea>
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

    <!-- Modal para solicitar cita -->
    <div id="appointmentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Solicitar Cita Médica</h2>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <form onsubmit="submitAppointment(event)">
                <div class="form-group">
                    <label>Seleccionar Médico</label>
                    <select required>
                        <option value="">-- Selecciona un médico --</option>
                        <option value="1">Dr. Carlos Martínez - Cardiología</option>
                        <option value="2">Dra. María López - Pediatría</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Fecha</label>
                    <input type="date" required>
                </div>

                <div class="form-group">
                    <label>Hora</label>
                    <select required>
                        <option value="">-- Selecciona una hora --</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="09:00">09:00 AM</option>
                    </select>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">Confirmar</button>
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

        // Abrir modal de solicitud de cita
        function openRequestModal() {
            document.getElementById('appointmentModal').classList.add('active');
        }

        // Cerrar modal
        function closeModal() {
            document.getElementById('appointmentModal').classList.remove('active');
        }

        // Enviar solicitud de cita
        function submitAppointment(event) {
            event.preventDefault();
            alert('✓ Cita solicitada exitosamente. El médico confirmará pronto.');
            closeModal();
        }

        // Cancelar cita
        function cancelAppointment(button) {
            if(confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
                alert('✓ Cita cancelada exitosamente.');
                button.closest('tr').style.opacity = '0.5';
            }
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
            const modal = document.getElementById('appointmentModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
