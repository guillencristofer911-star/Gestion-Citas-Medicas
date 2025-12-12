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

            <div class="user-profile">
                <div class="user-info">
                    <div class="user-avatar">⚙️</div>
                    <div>
                        <h4>Admin Sistema</h4>
                        <p>Administrador</p>
                    </div>
                </div>
                <button class="logout-btn-sidebar" onclick="logout()">Cerrar Sesión</button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Administrador</h1>
            </div>

            <!-- Dashboard Section -->
            <div id="dashboard" class="content-section">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">👥</div>
                        <div class="stat-content">
                            <h3>Total Médicos</h3>
                            <p class="stat-number">8</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">👤</div>
                        <div class="stat-content">
                            <h3>Total Pacientes</h3>
                            <p class="stat-number">156</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">📅</div>
                        <div class="stat-content">
                            <h3>Citas Este Mes</h3>
                            <p class="stat-number">324</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon red">⏳</div>
                        <div class="stat-content">
                            <h3>Pendientes de Confirmar</h3>
                            <p class="stat-number">12</p>
                        </div>
                    </div>
                </div>

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
                                <td>8</td>
                                <td><span class="status-badge status-active">Activo</span></td>
                            </tr>
                            <tr>
                                <td>Pacientes Registrados</td>
                                <td>156</td>
                                <td><span class="status-badge status-active">Activo</span></td>
                            </tr>
                            <tr>
                                <td>Citas Confirmadas</td>
                                <td>312</td>
                                <td><span class="status-badge status-confirmed">Confirmadas</span></td>
                            </tr>
                            <tr>
                                <td>Citas Pendientes</td>
                                <td>12</td>
                                <td><span class="status-badge status-pending">Pendientes</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Gestionar Médicos Section -->
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
                                <th>Especialidad</th>
                                <th>Teléfono</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Dr. Carlos Martínez</td>
                                <td>Cardiología</td>
                                <td>+34 912 345 678</td>
                                <td><span class="status-badge status-active">Activo</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="editDoctor('Carlos Martínez')">Editar</button>
                                    <button class="btn btn-danger btn-sm" onclick="deactivateDoctor('Carlos Martínez')">Desactivar</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Dra. María López</td>
                                <td>Pediatría</td>
                                <td>+34 912 345 679</td>
                                <td><span class="status-badge status-active">Activo</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="editDoctor('María López')">Editar</button>
                                    <button class="btn btn-danger btn-sm" onclick="deactivateDoctor('María López')">Desactivar</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Dr. Jorge Mendoza</td>
                                <td>Dermatología</td>
                                <td>+34 912 345 680</td>
                                <td><span class="status-badge status-active">Activo</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="editDoctor('Jorge Mendoza')">Editar</button>
                                    <button class="btn btn-danger btn-sm" onclick="deactivateDoctor('Jorge Mendoza')">Desactivar</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Dra. Ana Rodríguez</td>
                                <td>Neurología</td>
                                <td>+34 912 345 681</td>
                                <td><span class="status-badge status-inactive">Inactivo</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="editDoctor('Ana Rodríguez')">Editar</button>
                                    <button class="btn btn-success btn-sm" onclick="activateDoctor('Ana Rodríguez')">Activar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Todas las Citas Section -->
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
                            <tr>
                                <td>Juan Pérez</td>
                                <td>Dr. Carlos Martínez</td>
                                <td>11/12/2025</td>
                                <td>10:00 AM</td>
                                <td><span class="status-badge status-confirmed">Confirmada</span></td>
                                <td>
                                    <button class="btn btn-secondary btn-sm" onclick="viewAppointment('Juan Pérez')">Ver</button>
                                </td>
                            </tr>
                            <tr>
                                <td>María González</td>
                                <td>Dra. María López</td>
                                <td>12/12/2025</td>
                                <td>02:30 PM</td>
                                <td><span class="status-badge status-pending">Pendiente</span></td>
                                <td>
                                    <button class="btn btn-secondary btn-sm" onclick="viewAppointment('María González')">Ver</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Roberto López</td>
                                <td>Dr. Jorge Mendoza</td>
                                <td>13/12/2025</td>
                                <td>11:00 AM</td>
                                <td><span class="status-badge status-attended">Atendida</span></td>
                                <td>
                                    <button class="btn btn-secondary btn-sm" onclick="viewAppointment('Roberto López')">Ver</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Ana Martínez</td>
                                <td>Dra. Ana Rodríguez</td>
                                <td>15/12/2025</td>
                                <td>03:00 PM</td>
                                <td><span class="status-badge status-pending">Pendiente</span></td>
                                <td>
                                    <button class="btn btn-secondary btn-sm" onclick="viewAppointment('Ana Martínez')">Ver</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Gestionar Usuarios Section -->
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
                            <tr>
                                <td>Juan Pérez</td>
                                <td>juan.perez@example.com</td>
                                <td><span class="status-badge" style="background: #E8D4F7; color: #5A0A9E;">Paciente</span></td>
                                <td><span class="status-badge status-active">Activo</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="editUser('Juan Pérez')">Editar</button>
                                    <button class="btn btn-danger btn-sm" onclick="deactivateUser('Juan Pérez')">Desactivar</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Dr. Carlos Martínez</td>
                                <td>carlos.martinez@example.com</td>
                                <td><span class="status-badge" style="background: #D4E8F7; color: #0A5A9E;">Médico</span></td>
                                <td><span class="status-badge status-active">Activo</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="editUser('Carlos Martínez')">Editar</button>
                                    <button class="btn btn-danger btn-sm" onclick="deactivateUser('Carlos Martínez')">Desactivar</button>
                                </td>
                            </tr>
                            <tr>
                                <td>María González</td>
                                <td>maria.gonzalez@example.com</td>
                                <td><span class="status-badge" style="background: #E8D4F7; color: #5A0A9E;">Paciente</span></td>
                                <td><span class="status-badge status-active">Activo</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="editUser('María González')">Editar</button>
                                    <button class="btn btn-danger btn-sm" onclick="deactivateUser('María González')">Desactivar</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Dra. María López</td>
                                <td>maria.lopez@example.com</td>
                                <td><span class="status-badge" style="background: #D4E8F7; color: #0A5A9E;">Médico</span></td>
                                <td><span class="status-badge status-active">Activo</span></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="editUser('María López')">Editar</button>
                                    <button class="btn btn-danger btn-sm" onclick="deactivateUser('María López')">Desactivar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar médico -->
    <div id="addDoctorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Agregar Nuevo Médico</h2>
                <button class="close-modal" onclick="closeModal('addDoctorModal')">&times;</button>
            </div>
            <form onsubmit="submitAddDoctor(event)">
                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" required>
                </div>

                <div class="form-group">
                    <label>Especialidad</label>
                    <select required>
                        <option value="">-- Selecciona especialidad --</option>
                        <option value="cardiology">Cardiología</option>
                        <option value="pediatrics">Pediatría</option>
                        <option value="dermatology">Dermatología</option>
                        <option value="neurology">Neurología</option>
                        <option value="orthopedics">Ortopedia</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" required>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="tel" required>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addDoctorModal')">Cancelar</button>
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

        // Abrir modal para agregar médico
        function openAddDoctorModal() {
            document.getElementById('addDoctorModal').classList.add('active');
        }

        // Cerrar modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Acciones de médicos
        function editDoctor(name) {
            alert(`Editando médico: ${name}`);
            openAddDoctorModal();
        }

        function deactivateDoctor(name) {
            if(confirm(`¿Desactivar a ${name}?`)) {
                alert(`✓ ${name} ha sido desactivado.`);
            }
        }

        function activateDoctor(name) {
            if(confirm(`¿Activar a ${name}?`)) {
                alert(`✓ ${name} ha sido activado.`);
            }
        }

        function submitAddDoctor(event) {
            event.preventDefault();
            alert('✓ Médico agregado exitosamente.');
            closeModal('addDoctorModal');
        }

        // Acciones de citas
        function viewAppointment(patientName) {
            alert(`Ver detalles de cita de: ${patientName}`);
        }

        // Acciones de usuarios
        function editUser(name) {
            alert(`Editando usuario: ${name}`);
        }

        function deactivateUser(name) {
            if(confirm(`¿Desactivar usuario ${name}?`)) {
                alert(`✓ Usuario ${name} ha sido desactivado.`);
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
            const modal = event.target;
            if (modal.classList && modal.classList.contains('modal')) {
                modal.classList.remove('active');
            }
        }
    </script>
</body>
</html>
