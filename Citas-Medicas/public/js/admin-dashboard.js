/**
 * Admin Dashboard AJAX Functions
 * Sistema de Gestión de Citas Médicas - MediConnect
 */

// =================== MÉDICOS ===================

/**
 * Cargar datos completos del médico al editar (AJAX)
 */
async function editDoctor(doctorId) {
    try {
        Loader.show('#doctorForm', 'Cargando datos...');
        
        const response = await AjaxHandler.get(`/admin/doctors/${doctorId}`);
        
        if (response.success) {
            const doctor = response.data;
            
            document.getElementById('doctorModalTitle').textContent = 'Editar Médico';
            document.getElementById('doctorId').value = doctor.id;
            document.getElementById('doctorName').value = doctor.name;
            document.getElementById('doctorEmail').value = doctor.email;
            document.getElementById('doctorEmail').disabled = true;
            document.getElementById('doctorSpecialty').value = doctor.specialty;
            document.getElementById('doctorPhone').value = doctor.phone;
            document.getElementById('doctorLicense').value = doctor.license_number;
            document.getElementById('doctorSubmitBtn').textContent = 'Actualizar';
            
            // Limpiar el loader
            const form = document.getElementById('doctorForm');
            form.querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);
            
            document.getElementById('doctorModal').classList.add('active');
        }
    } catch (error) {
        Toast.error('Error al cargar datos del médico');
        console.error(error);
    }
}

/**
 * Buscar/filtrar médicos en tiempo real
 */
let searchDoctorsTimeout;
async function searchDoctors() {
    clearTimeout(searchDoctorsTimeout);
    
    searchDoctorsTimeout = setTimeout(async () => {
        const name = document.getElementById('searchDoctorName')?.value || '';
        const specialty = document.getElementById('searchDoctorSpecialty')?.value || '';
        const active = document.getElementById('filterDoctorActive')?.value || '';
        
        try {
            Loader.show('#doctorsTableBody', 'Buscando...');
            
            const params = new URLSearchParams();
            if (name) params.append('name', name);
            if (specialty) params.append('specialty', specialty);
            if (active !== '') params.append('active', active);
            
            const response = await AjaxHandler.get(`/admin/doctors/search?${params.toString()}`);
            
            if (response.success) {
                renderDoctorsTable(response.data);
            }
        } catch (error) {
            Toast.error('Error al buscar médicos');
            console.error(error);
        }
    }, 500); // Esperar 500ms después de que el usuario deje de escribir
}

/**
 * Renderizar tabla de médicos
 */
function renderDoctorsTable(doctors) {
    const tbody = document.getElementById('doctorsTableBody');
    
    if (doctors.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 20px;">No se encontraron médicos</td></tr>';
        return;
    }
    
    tbody.innerHTML = doctors.map(doctor => `
        <tr data-doctor-id="${doctor.id}">
            <td>${doctor.name}</td>
            <td>${doctor.email}</td>
            <td>${doctor.specialty}</td>
            <td>${doctor.phone}</td>
            <td>
                ${doctor.active 
                    ? '<span class="status-badge status-active">Activo</span>' 
                    : '<span class="status-badge status-inactive">Inactivo</span>'}
            </td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="editDoctor(${doctor.id})">Editar</button>
                ${doctor.active 
                    ? `<button class="btn btn-danger btn-sm" onclick="deactivateDoctor(${doctor.id})">Desactivar</button>` 
                    : `<button class="btn btn-success btn-sm" onclick="activateDoctor(${doctor.id})">Activar</button>`}
            </td>
        </tr>
    `).join('');
}

// =================== CITAS ===================

/**
 * Ver detalles completos de una cita (Modal)
 */
async function viewAppointment(appointmentId) {
    try {
        const response = await AjaxHandler.get(`/admin/appointments/${appointmentId}`);
        
        if (response.success) {
            const apt = response.data;
            
            // Crear modal dinámico
            const modalHTML = `
                <div id="appointmentDetailModal" class="modal active">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>📅 Detalles de la Cita #${apt.id}</h2>
                            <button class="close-modal" onclick="closeModal('appointmentDetailModal')">&times;</button>
                        </div>
                        <div style="padding: 20px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                <div>
                                    <h3 style="margin-bottom: 10px; color: #4f46e5;">👤 Paciente</h3>
                                    <p><strong>Nombre:</strong> ${apt.patient.name}</p>
                                    <p><strong>Email:</strong> ${apt.patient.email}</p>
                                </div>
                                <div>
                                    <h3 style="margin-bottom: 10px; color: #059669;">👨‍⚕️ Doctor</h3>
                                    <p><strong>Nombre:</strong> ${apt.doctor.name}</p>
                                    <p><strong>Especialidad:</strong> ${apt.doctor.specialty}</p>
                                </div>
                            </div>
                            
                            <div style="background: #f9fafb; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                <h3 style="margin-bottom: 10px;">📆 Información de la Cita</h3>
                                <p><strong>Fecha:</strong> ${apt.date}</p>
                                <p><strong>Hora:</strong> ${apt.time}</p>
                                <p><strong>Estado:</strong> <span class="status-badge status-${apt.status}">${apt.status_label}</span></p>
                            </div>
                            
                            ${apt.consultation_reason ? `
                                <div style="margin-bottom: 15px;">
                                    <h3 style="margin-bottom: 10px;">📝 Motivo de Consulta</h3>
                                    <p>${apt.consultation_reason}</p>
                                </div>
                            ` : ''}
                            
                            ${apt.notes ? `
                                <div style="margin-bottom: 15px;">
                                    <h3 style="margin-bottom: 10px;">📝 Notas</h3>
                                    <p>${apt.notes}</p>
                                </div>
                            ` : ''}
                            
                            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                                <p style="color: #6b7280; font-size: 0.875rem;"><strong>Creada:</strong> ${apt.created_at}</p>
                            </div>
                        </div>
                        <div class="action-buttons" style="padding: 0 20px 20px;">
                            <button type="button" class="btn btn-secondary" onclick="closeModal('appointmentDetailModal')">Cerrar</button>
                        </div>
                    </div>
                </div>
            `;
            
            // Remover modal anterior si existe
            const existingModal = document.getElementById('appointmentDetailModal');
            if (existingModal) existingModal.remove();
            
            // Agregar nuevo modal
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }
    } catch (error) {
        Toast.error('Error al cargar detalles de la cita');
        console.error(error);
    }
}

/**
 * Buscar/filtrar citas
 */
let searchAppointmentsTimeout;
async function searchAppointments() {
    clearTimeout(searchAppointmentsTimeout);
    
    searchAppointmentsTimeout = setTimeout(async () => {
        const patientName = document.getElementById('searchPatientName')?.value || '';
        const doctorName = document.getElementById('searchDoctorName')?.value || '';
        const status = document.getElementById('filterAppointmentStatus')?.value || '';
        const dateFrom = document.getElementById('filterDateFrom')?.value || '';
        const dateTo = document.getElementById('filterDateTo')?.value || '';
        
        try {
            Loader.show('#appointmentsTableBody', 'Buscando citas...');
            
            const params = new URLSearchParams();
            if (patientName) params.append('patient_name', patientName);
            if (doctorName) params.append('doctor_name', doctorName);
            if (status) params.append('status', status);
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);
            
            const response = await AjaxHandler.get(`/admin/appointments/search?${params.toString()}`);
            
            if (response.success) {
                renderAppointmentsTable(response.data);
            }
        } catch (error) {
            Toast.error('Error al buscar citas');
            console.error(error);
        }
    }, 500);
}

/**
 * Renderizar tabla de citas
 */
function renderAppointmentsTable(appointments) {
    const tbody = document.querySelector('#appointments tbody');
    
    if (!tbody) return;
    
    if (appointments.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 20px;">No se encontraron citas</td></tr>';
        return;
    }
    
    tbody.innerHTML = appointments.map(apt => `
        <tr>
            <td>${apt.patient_name}</td>
            <td>${apt.doctor_name}</td>
            <td>${apt.date}</td>
            <td>${apt.time}</td>
            <td><span class="status-badge status-${apt.status}">${apt.status_label}</span></td>
            <td>
                <button class="btn btn-secondary btn-sm" onclick="viewAppointment(${apt.id})">Ver</button>
            </td>
        </tr>
    `).join('');
}

// =================== USUARIOS ===================

/**
 * Buscar/filtrar usuarios
 */
let searchUsersTimeout;
async function searchUsers() {
    clearTimeout(searchUsersTimeout);
    
    searchUsersTimeout = setTimeout(async () => {
        const name = document.getElementById('searchUserName')?.value || '';
        const email = document.getElementById('searchUserEmail')?.value || '';
        const role = document.getElementById('filterUserRole')?.value || '';
        const active = document.getElementById('filterUserActive')?.value || '';
        
        try {
            Loader.show('#usersTableBody', 'Buscando usuarios...');
            
            const params = new URLSearchParams();
            if (name) params.append('name', name);
            if (email) params.append('email', email);
            if (role) params.append('role', role);
            if (active !== '') params.append('active', active);
            
            const response = await AjaxHandler.get(`/admin/users/search?${params.toString()}`);
            
            if (response.success) {
                renderUsersTable(response.data);
            }
        } catch (error) {
            Toast.error('Error al buscar usuarios');
            console.error(error);
        }
    }, 500);
}

/**
 * Renderizar tabla de usuarios
 */
function renderUsersTable(users) {
    const tbody = document.getElementById('usersTableBody');
    
    if (!tbody) return;
    
    if (users.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 20px;">No se encontraron usuarios</td></tr>';
        return;
    }
    
    const currentUserId = window.currentUserId || null;
    
    tbody.innerHTML = users.map(user => {
        const roleClass = user.role === 'patient' ? 'background: #E8D4F7; color: #5A0A9E;' :
                         user.role === 'doctor' ? 'background: #D4E8F7; color: #0A5A9E;' :
                         'background: #D4F7D4; color: #0A5A0A;';
        
        const roleLabel = user.role === 'patient' ? 'Paciente' :
                         user.role === 'doctor' ? 'Médico' : 'Admin';
        
        return `
            <tr data-user-id="${user.id}">
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td><span class="status-badge" style="${roleClass}">${roleLabel}</span></td>
                <td>
                    ${user.active 
                        ? '<span class="status-badge status-active">Activo</span>' 
                        : '<span class="status-badge status-inactive">Inactivo</span>'}
                </td>
                <td>
                    ${user.id !== currentUserId ? `
                        ${user.active 
                            ? `<button class="btn btn-danger btn-sm" onclick="deactivateUser(${user.id})">Desactivar</button>` 
                            : `<button class="btn btn-success btn-sm" onclick="activateUser(${user.id})">Activar</button>`}
                    ` : '<span style="color: #999;">Tu usuario</span>'}
                </td>
            </tr>
        `;
    }).join('');
}

// =================== UTILIDADES ===================

/**
 * Cerrar modal genérico
 */
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        setTimeout(() => modal.remove(), 300);
    }
}

// Cerrar modales al hacer clic fuera
window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
    }
});
