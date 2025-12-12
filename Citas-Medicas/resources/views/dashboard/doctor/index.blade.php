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
                        <h4>Dr. Carlos Martínez</h4>
                        <p>Médico</p>
                    </div>
                </div>
                <button class="logout-btn-sidebar" onclick="logout()">Cerrar Sesión</button>
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
                            <h3>Citas Hoy</h3>
                            <p class="stat-number">4</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">✓</div>
                        <div class="stat-content">
                            <h3>Citas Confirmadas</h3>
                            <p class="stat-number">12</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">⏳</div>
                        <div class="stat-content">
                            <h3>Pendientes de Confirmar</h3>
                            <p class="stat-number">2</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">📋</div>
                        <div class="stat-content">
                            <h3>Total Este Mes</h3>
                            <p class="stat-number">28</p>
                        </div>
                    </div>
                </div>

                <div class="section-title">📋 Citas de Hoy</div>
                <div class="section">
                    <table>
                        <thead>
                            <tr>
                                <th>Paciente</th>
                                <th>Hora</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Juan Pérez</td>
                                <td>10:00 AM</td>
                                <td><span class="status-badge status-confirmed">Confirmada</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="openStatusModal('Juan Pérez', '10:00 AM')">Cambiar Estado</button>
                                </td>
                            </tr>
                            <tr>
                                <td>María González</td>
                                <td>11:30 AM</td>
                                <td><span class="status-badge status-pending">Pendiente</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="openStatusModal('María González', '11:30 AM')">Cambiar Estado</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Roberto López</td>
                                <td>02:00 PM</td>
                                <td><span class="status-badge status-confirmed">Confirmada</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="openStatusModal('Roberto López', '02:00 PM')">Cambiar Estado</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Ana Martínez</td>
                                <td>03:30 PM</td>
                                <td><span class="status-badge status-pending">Pendiente</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="openStatusModal('Ana Martínez', '03:30 PM')">Cambiar Estado</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mis Citas Section -->
            <div id="appointments" class="content-section" style="display:none;">
                <div class="section-title">📅 Citas Asignadas</div>
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
                            <tr>
                                <td>Juan Pérez</td>
                                <td>11/12/2025</td>
                                <td>10:00 AM</td>
                                <td>Revisión cardiovascular</td>
                                <td><span class="status-badge status-confirmed">Confirmada</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="openStatusModal('Juan Pérez', '10:00 AM')">Actualizar</button>
                                </td>
                            </tr>
                            <tr>
                                <td>María González</td>
                                <td>12/12/2025</td>
                                <td>02:30 PM</td>
                                <td>Consulta general</td>
                                <td><span class="status-badge status-pending">Pendiente</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="openStatusModal('María González', '02:30 PM')">Actualizar</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Roberto López</td>
                                <td>13/12/2025</td>
                                <td>11:00 AM</td>
                                <td>Control de presión</td>
                                <td><span class="status-badge status-attended">Atendida</span></td>
                                <td>
                                    <button class="btn btn-secondary btn-sm" disabled>Completada</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Ana Martínez</td>
                                <td>15/12/2025</td>
                                <td>03:00 PM</td>
                                <td>Chequeo anual</td>
                                <td><span class="status-badge status-pending">Pendiente</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="openStatusModal('Ana Martínez', '03:00 PM')">Actualizar</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Carlos Ruiz</td>
                                <td>16/12/2025</td>
                                <td>10:30 AM</td>
                                <td>Seguimiento</td>
                                <td><span class="status-badge status-confirmed">Confirmada</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="openStatusModal('Carlos Ruiz', '10:30 AM')">Actualizar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
