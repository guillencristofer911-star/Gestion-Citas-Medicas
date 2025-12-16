/* ==================== DASHBOARD.JS - LÓGICA COMPARTIDA ==================== */
/**
 * Script compartido para los 3 dashboards (Paciente, Médico, Administrador)
 * Compatible con Laravel y Blade
 */

// ==================== NAVEGACIÓN ENTRE SECCIONES ====================

/**
 * Muestra una sección del dashboard
 * @param {string} sectionId - ID de la sección a mostrar
 */
function showSection(sectionId) {
    // Ocultar todas las secciones
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    // Mostrar la sección seleccionada
    const section = document.getElementById(sectionId);
    if (section) {
        section.style.display = 'block';
    }

    // Actualizar estado activo del menú
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.classList.remove('active');
    });

    // Marcar como activo el elemento que se hizo click
    if (event && event.target) {
        event.target.closest('.menu-item').classList.add('active');
    }
}

// ==================== GESTIÓN DE MODALES ====================

/**
 * Abre un modal
 * @param {string} modalId - ID del modal a abrir
 */
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
    }
}

/**
 * Cierra un modal
 * @param {string} modalId - ID del modal a cerrar
 */
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
    }
}

/**
 * Abre modal de solicitud de cita (Paciente)
 * @param {string} doctorId - ID del médico (opcional)
 */
function openRequestModal(doctorId = '') {
    const modal = document.getElementById('appointmentModal');
    if (modal) {
        modal.classList.add('active');
        if (doctorId) {
            const doctorSelect = modal.querySelector('select[name="doctor_id"]');
            if (doctorSelect) {
                doctorSelect.value = doctorId;
            }
        }
    }
}

/**
 * Cierra modal al hacer clic fuera del contenido
 */
window.onclick = function(event) {
    if (event.target.classList && event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
    }
}

// ==================== FUNCIONES DE CITAS (PACIENTE) ====================

/**
 * Envía la solicitud de una cita
 * @param {Event} event - Evento del formulario
 */
function submitAppointment(event) {
    event.preventDefault();

    const form = event.target;
    const doctorId = form.querySelector('select[name="doctor_id"]')?.value;
    const appointmentDate = form.querySelector('input[name="appointment_date"]')?.value;
    const appointmentTime = form.querySelector('select[name="appointment_time"]')?.value;

    if (!doctorId || !appointmentDate || !appointmentTime) {
        showNotification('Por favor completa todos los campos requeridos', 'error');
        return;
    }

    // Si tiene acción POST, se envía el formulario
    if (form.action && form.action !== '') {
        form.submit();
    } else {
        // Validación cliente
        showNotification('✓ Cita solicitada exitosamente. El médico confirmará pronto.', 'success');
        closeModal('appointmentModal');
        form.reset();
    }
}

/**
 * Cancela una cita existente (Paciente)
 * @param {number} appointmentId - ID de la cita a cancelar
 */
function cancelAppointment(appointmentId) {
    if (confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
        showNotification('✓ Cita cancelada exitosamente.', 'success');
        // Aquí iría la petición AJAX real
    }
}

// ==================== FUNCIONES DE CITAS (MÉDICO) ====================

/**
 * Actualiza el estado de una cita (Médico)
 * @param {number} appointmentId - ID de la cita
 * @param {string} status - Nuevo estado
 */
function updateAppointmentStatus(appointmentId, status) {
    if (confirm('¿Confirmar cambio de estado?')) {
        showNotification('✓ Estado actualizado exitosamente.', 'success');
        // Aquí iría la petición AJAX real
    }
}

/**
 * Abre modal para editar estado de cita (Médico)
 * @param {number} appointmentId - ID de la cita
 */
function openEditStatusModal(appointmentId) {
    document.getElementById('appointmentId').value = appointmentId;
    openModal('statusModal');
}

/**
 * Envía actualización de estado
 * @param {Event} event - Evento del formulario
 */
function submitStatusUpdate(event) {
    event.preventDefault();
    showNotification('✓ Estado actualizado exitosamente.', 'success');
    closeModal('statusModal');
    // Aquí iría la petición AJAX real
}

/**
 * Actualiza estado desde selectbox (Médico)
 * @param {number} appointmentId - ID de la cita
 * @param {string} status - Nuevo estado
 */
function updateStatus(appointmentId, status) {
    if (status) {
        updateAppointmentStatus(appointmentId, status);
    }
}

/**
 * Filtra agenda por período (Médico)
 * @param {string} filter - Filtro (week, month, custom)
 */
function filterSchedule(filter) {
    showNotification(`Filtrando por: ${filter}`, 'info');
    // Aquí iría la lógica de filtrado
}

/**
 * Ver detalles de paciente (Médico)
 * @param {number} patientId - ID del paciente
 */
function viewPatientDetails(patientId) {
    // Redirigir o cargar detalles
    console.log(`Viewing patient: ${patientId}`);
}

// ==================== FUNCIONES DE ADMINISTRACIÓN (ADMIN) ====================

/**
 * Abre modal para agregar/editar médico (Admin)
 */
function openDoctorModal() {
    document.getElementById('doctorModalTitle').textContent = 'Agregar Médico';
    document.getElementById('doctorId').value = '';
    document.getElementById('doctorName').value = '';
    document.getElementById('doctorEmail').value = '';
    document.getElementById('doctorSpecialty').value = '';
    document.getElementById('doctorPhone').value = '';
    openModal('doctorModal');
}

/**
 * Edita un médico (Admin)
 * @param {number} doctorId - ID del médico
 */
function editDoctor(doctorId) {
    document.getElementById('doctorId').value = doctorId;
    document.getElementById('doctorModalTitle').textContent = 'Editar Médico';
    openModal('doctorModal');
}

/**
 * Elimina un médico (Admin)
 * @param {number} doctorId - ID del médico
 */
function deleteDoctor(doctorId) {
    if (confirm('¿Estás seguro de que deseas eliminar este médico?')) {
        showNotification('✓ Médico eliminado exitosamente.', 'success');
        // Aquí iría la petición DELETE AJAX real
    }
}

/**
 * Envía cambios de médico (Admin)
 * @param {Event} event - Evento del formulario
 */
function saveDoctorSubmit(event) {
    event.preventDefault();
    showNotification('✓ Médico guardado exitosamente.', 'success');
    closeModal('doctorModal');
    // Aquí iría la petición POST/PUT AJAX real
}

/**
 * Abre modal para agregar usuario (Admin)
 */
function openUserModal() {
    document.getElementById('userId').value = '';
    openModal('userModal');
}

/**
 * Edita un usuario (Admin)
 * @param {number} userId - ID del usuario
 */
function editUser(userId) {
    document.getElementById('userId').value = userId;
    openModal('userModal');
}

/**
 * Elimina un usuario (Admin)
 * @param {number} userId - ID del usuario
 */
function deleteUser(userId) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        showNotification('✓ Usuario eliminado exitosamente.', 'success');
        // Aquí iría la petición DELETE AJAX real
    }
}

/**
 * Envía cambios de usuario (Admin)
 * @param {Event} event - Evento del formulario
 */
function saveUserSubmit(event) {
    event.preventDefault();
    showNotification('✓ Usuario guardado exitosamente.', 'success');
    closeModal('userModal');
    // Aquí iría la petición POST/PUT AJAX real
}

/**
 * Carga horario de médico (Admin)
 * @param {string} doctorId - ID del médico
 */
function loadDoctorSchedule(doctorId) {
    const scheduleForm = document.getElementById('scheduleForm');
    if (doctorId && scheduleForm) {
        document.getElementById('doctorIdSchedule').value = doctorId;
        scheduleForm.style.display = 'block';
    } else if (scheduleForm) {
        scheduleForm.style.display = 'none';
    }
}

/**
 * Guarda horario de médico (Admin)
 * @param {Event} event - Evento del formulario
 */
function saveSchedule(event) {
    event.preventDefault();
    showNotification('✓ Horario guardado exitosamente.', 'success');
    // Aquí iría la petición POST AJAX real
}

/**
 * Filtra citas (Admin)
 * @param {string} filter - Filtro (all, pending, confirmed, attended)
 */
function filterAppointments(filter) {
    showNotification(`Filtrando citas por: ${filter}`, 'info');
    // Aquí iría la lógica de filtrado
}

/**
 * Ver detalles de cita (Admin)
 * @param {number} appointmentId - ID de la cita
 */
function viewAppointmentDetails(appointmentId) {
    // Redirigir o abrir modal con detalles
    console.log(`Viewing appointment: ${appointmentId}`);
}

/**
 * Edita horario (Admin)
 * @param {number} scheduleId - ID del horario
 */
function editSchedule(scheduleId) {
    showNotification(`Editando horario: ${scheduleId}`, 'info');
}

// ==================== FUNCIONES DE UTILIDAD ====================

/**
 * Formatea una fecha a formato local
 * @param {string} dateString - Fecha en formato YYYY-MM-DD
 * @returns {string} Fecha formateada DD/MM/YYYY
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    return new Date(dateString).toLocaleDateString('es-ES', options);
}

/**
 * Convierte hora en formato 24h a 12h
 * @param {string} timeString - Hora en formato HH:MM
 * @returns {string} Hora formateada con AM/PM
 */
function formatTime(timeString) {
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}

/**
 * Muestra una notificación en pantalla
 * @param {string} message - Mensaje a mostrar
 * @param {string} type - Tipo: 'success', 'error', 'info', 'warning'
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const bgColor = {
        'success': '#4caf50',
        'error': '#f44336',
        'warning': '#ff9800',
        'info': '#2196F3'
    }[type] || '#2196F3';

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${bgColor};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 3000;
        animation: slideIn 0.3s ease;
        max-width: 400px;
        word-wrap: break-word;
    `;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

/**
 * Obtiene token CSRF para peticiones AJAX
 * @returns {string} Token CSRF
 */
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

// ==================== PETICIONES AJAX ====================

/**
 * Realiza una petición POST AJAX
 * @param {string} url - URL del endpoint
 * @param {object} data - Datos a enviar
 * @param {function} callback - Función a ejecutar con la respuesta
 */
function ajaxPost(url, data, callback) {
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (callback) callback(data);
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al procesar la solicitud', 'error');
    });
}

/**
 * Realiza una petición DELETE AJAX
 * @param {string} url - URL del endpoint
 * @param {function} callback - Función a ejecutar con la respuesta
 */
function ajaxDelete(url, callback) {
    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (callback) callback(data);
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al procesar la solicitud', 'error');
    });
}

// ==================== INICIALIZACIÓN ====================

/**
 * Inicializa el dashboard cuando el DOM carga
 */
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar sección inicial si existe
    const firstSection = document.querySelector('.content-section');
    if (firstSection && firstSection.id) {
        showSection(firstSection.id);
    }

    // Cerrar modales al presionar ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('.modal.active');
            modals.forEach(modal => modal.classList.remove('active'));
        }
    });
});

// ==================== ANIMACIONES ====================

// Agregar estilos de animación dinámicamente
const animationStyles = document.createElement('style');
animationStyles.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
`;
document.head.appendChild(animationStyles);

console.log('✓ Dashboard.js cargado exitosamente');
