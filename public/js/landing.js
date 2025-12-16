// Función para alternar visibilidad de contraseña
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        input.type = input.type === 'password' ? 'text' : 'password';
    }
}

// Función para limpiar errores al escribir
function setupInputErrorClearing() {
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', function() {
            this.closest('.form-group').classList.remove('error');
            const errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        });
    });
}

// Validación del formulario de login
function setupLoginForm() {
    const loginForm = document.getElementById('loginForm');
    if (!loginForm) return;

    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset errors
        document.querySelectorAll('.form-group').forEach(group => {
            group.classList.remove('error');
        });
        
        const errorMessage = document.getElementById('errorMessage');
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }

        let isValid = true;

        // Validar email
        const email = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            email.closest('.form-group').classList.add('error');
            isValid = false;
        }

        // Validar contraseña
        const password = document.getElementById('password');
        if (password.value.length === 0) {
            password.closest('.form-group').classList.add('error');
            isValid = false;
        }

        // Obtener rol seleccionado (si existe)
        const roleInput = document.querySelector('input[name="role"]:checked');
        const role = roleInput ? roleInput.value : null;

        if (isValid) {
            // Mostrar mensaje de éxito
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.style.display = 'block';
            }
            
            // Simular envío de formulario
            setTimeout(() => {
                console.log('Datos del formulario de login:', {
                    email: email.value,
                    password: password.value,
                    role: role,
                    remember: document.getElementById('remember') ? document.getElementById('remember').checked : false
                });
                // Aquí iría la petición AJAX a Laravel
                // fetch('/login', { method: 'POST', body: formData })
            }, 1000);
        }
    });
}

// Validación del formulario de registro
function setupRegisterForm() {
    const registroForm = document.getElementById('registroForm');
    if (!registroForm) return;

    registroForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset errors
        document.querySelectorAll('.form-group').forEach(group => {
            group.classList.remove('error');
        });

        let isValid = true;

        // Validar nombre
        const nombre = document.getElementById('nombre');
        if (nombre && nombre.value.trim().length < 3) {
            nombre.closest('.form-group').classList.add('error');
            isValid = false;
        }

        // Validar email
        const email = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            email.closest('.form-group').classList.add('error');
            isValid = false;
        }

        // Validar contraseña
        const password = document.getElementById('password');
        if (password.value.length < 8) {
            password.closest('.form-group').classList.add('error');
            isValid = false;
        }

        // Validar confirmación
        const passwordConfirmation = document.getElementById('password_confirmation');
        if (passwordConfirmation && password.value !== passwordConfirmation.value) {
            passwordConfirmation.closest('.form-group').classList.add('error');
            isValid = false;
        }

        if (isValid) {
            // Mostrar mensaje de éxito
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.style.display = 'block';
            }
            
            // Simular envío de formulario
            setTimeout(() => {
                console.log('Datos del formulario de registro:', {
                    nombre: nombre.value,
                    email: email.value,
                    password: password.value,
                    rol: 'paciente' // Rol por defecto
                });
                // Aquí iría la petición AJAX a Laravel
                // fetch('/register', { method: 'POST', body: formData })
            }, 1000);
        }
    });
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    setupLoginForm();
    setupRegisterForm();
    setupInputErrorClearing();
});