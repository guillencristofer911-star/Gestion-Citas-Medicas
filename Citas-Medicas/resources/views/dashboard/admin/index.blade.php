<!DOCTYPE html>
<!-- Laravel Blade Template -->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediConnect - Dashboard Admin</title>
    <!-- CSS Compartido -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo-section">
                <div class="logo">
                    <div class="logo-icon">🥼</div>
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

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Administrador</h1>
            </div>

            <!-- Alerts -->
            <div id="alertContainer"></div>

            <!-- Dashboard Section -->
            <div id="dashboard" class="content-section">
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
                                        <button class="btn btn-primary btn-sm" onclick="editDoctor({{ $doctor->id }})">Editar</button>
                                        @if($doctor->active)
                                            <button class="btn btn-danger btn-sm" onclick="deactivateDoctor({{ $doctor->id }})">Desactivar</button>
                                        @else
                                            <button class="btn btn-success btn-sm" onclick="activateDoctor({{ $doctor->id }})">Activar</button>
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
                            @forelse($appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->patient->name }}</td>
                                    <td>{{ $appointment->doctor->user->name }}</td>
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
                                            <button class="btn btn-danger btn-sm" onclick="deactivateUser({{ $user->id }})">Desactivar</button>
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

    <!-- Modal para agregar/editar médico -->
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

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                          '{{ csrf_token() }}';

        // Cambiar secciones
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

        // Mostrar alertas
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();
            const alertHTML = `
                <div id="${alertId}" class="alert alert-${type}" style="margin: 10px 0; padding: 15px; border-radius: 5px; background: ${type === 'success' ? '#d4edda' : '#f8d7da'}; color: ${type === 'success' ? '#155724' : '#721c24'};">
                    ${message}
                </div>
            `;
            alertContainer.insertAdjacentHTML('beforeend', alertHTML);
            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if(alert) alert.remove();
            }, 5000);
        }

        // =================== MÉDICOS ===================

        function openAddDoctorModal() {
            document.getElementById('doctorModalTitle').textContent = 'Agregar Nuevo Médico';
            document.getElementById('doctorForm').reset();
            document.getElementById('doctorId').value = '';
            document.getElementById('doctorEmail').disabled = false;
            document.getElementById('doctorSubmitBtn').textContent = 'Guardar';
            document.getElementById('doctorModal').classList.add('active');
        }

        function editDoctor(doctorId) {
            const row = document.querySelector(`tr[data-doctor-id="${doctorId}"]`);
            const cells = row.querySelectorAll('td');

            document.getElementById('doctorModalTitle').textContent = 'Editar Médico';
            document.getElementById('doctorId').value = doctorId;
            document.getElementById('doctorName').value = cells[0].textContent;
            document.getElementById('doctorEmail').value = cells[1].textContent;
            document.getElementById('doctorEmail').disabled = true;
            document.getElementById('doctorSpecialty').value = cells[2].textContent;
            document.getElementById('doctorPhone').value = cells[3].textContent;
            document.getElementById('doctorLicense').value = '';
            document.getElementById('doctorSubmitBtn').textContent = 'Actualizar';
            document.getElementById('doctorModal').classList.add('active');
        }

        function submitDoctorForm(event) {
            event.preventDefault();

            const doctorId = document.getElementById('doctorId').value;
            const formData = new FormData(document.getElementById('doctorForm'));

            const url = doctorId 
                ? `/admin/doctors/${doctorId}`
                : `{{ route('admin.doctors.store') }}`;

            const method = doctorId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showAlert(data.message, 'success');
                    closeModal('doctorModal');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert(data.message, 'error');
                }
            })
            .catch(error => {
                showAlert('Error al procesar la solicitud', 'error');
                console.error('Error:', error);
            });
        }

        function activateDoctor(doctorId) {
            if(confirm('¿Activar este médico?')) {
                // Para activar, hacemos una actualización con status activo
                // Usamos PUT similar al edit, pero solo enviamos el estado
                
                fetch(`/admin/doctors/${doctorId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'PUT',
                        active: true
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        showAlert('Médico activado correctamente', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    showAlert('Error al activar el médico', 'error');
                    console.error('Error:', error);
                });
            }
        }


        function activateDoctor(doctorId) {
            alert('Activar médico: ' + doctorId);
        }

        // =================== CITAS ===================

        function viewAppointment(appointmentId) {
            alert('Ver detalles de cita: ' + appointmentId);
        }

        // =================== USUARIOS ===================

        function deactivateUser(userId) {
            if(confirm('¿Desactivar este usuario?')) {
                fetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        showAlert(data.message, 'success');
                        // Recargar tabla de usuarios
                        const row = document.querySelector(`tr[data-user-id="${userId}"]`);
                        if(row) row.remove();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    showAlert('Error al desactivar el usuario', 'error');
                    console.error('Error:', error);
                });
            }
        }


        // =================== MODAL ===================

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        window.onclick = function(event) {
            const modal = event.target;
            if (modal.classList && modal.classList.contains('modal')) {
                modal.classList.remove('active');
            }
        }
    </script>
</body>
</html>
