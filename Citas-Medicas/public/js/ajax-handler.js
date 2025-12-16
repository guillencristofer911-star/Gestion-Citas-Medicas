/**
 * AJAX Handler - Manejador centralizado de peticiones AJAX
 * Sistema de Gestión de Citas Médicas - MediConnect
 */

const AjaxHandler = {
    // Obtener CSRF token
    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    },

    /**
     * Realizar petición AJAX genérica
     * @param {string} url - URL del endpoint
     * @param {string} method - Método HTTP (GET, POST, PUT, DELETE)
     * @param {object|FormData} data - Datos a enviar
     * @param {object} options - Opciones adicionales
     * @returns {Promise}
     */
    async request(url, method = 'GET', data = null, options = {}) {
        const config = {
            method: method,
            headers: {
                'X-CSRF-TOKEN': this.getCsrfToken(),
                'Accept': 'application/json',
                ...options.headers
            }
        };

        // Si es FormData, no agregar Content-Type (el navegador lo hace automáticamente)
        if (data && !(data instanceof FormData)) {
            config.headers['Content-Type'] = 'application/json';
            config.body = JSON.stringify(data);
        } else if (data instanceof FormData) {
            config.body = data;
        }

        try {
            const response = await fetch(url, config);
            const result = await response.json();

            if (!response.ok) {
                throw result;
            }

            return result;
        } catch (error) {
            console.error('AJAX Error:', error);
            throw error;
        }
    },

    // Métodos helpers
    get(url) {
        return this.request(url, 'GET');
    },

    post(url, data) {
        return this.request(url, 'POST', data);
    },

    put(url, data) {
        return this.request(url, 'PUT', data);
    },

    delete(url) {
        return this.request(url, 'DELETE');
    }
};

/**
 * Sistema de Notificaciones Toast
 */
const Toast = {
    show(message, type = 'success', duration = 5000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#f59e0b'};
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
            max-width: 400px;
            font-size: 14px;
        `;

        const icon = type === 'success' ? '✓' : type === 'error' ? '✗' : '⚠';
        toast.innerHTML = `<strong>${icon}</strong> ${message}`;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    },

    success(message, duration) {
        this.show(message, 'success', duration);
    },

    error(message, duration) {
        this.show(message, 'error', duration);
    },

    warning(message, duration) {
        this.show(message, 'warning', duration);
    }
};

// Añadir estilos de animación
if (!document.getElementById('toast-animations')) {
    const style = document.createElement('style');
    style.id = 'toast-animations';
    style.textContent = `
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
    `;
    document.head.appendChild(style);
}

/**
 * Loading Spinner
 */
const Loader = {
    show(targetElement, message = 'Cargando...') {
        if (typeof targetElement === 'string') {
            targetElement = document.querySelector(targetElement);
        }
        
        if (targetElement) {
            targetElement.innerHTML = `
                <div style="text-align: center; padding: 40px;">
                    <div class="spinner" style="
                        border: 4px solid #f3f3f3;
                        border-top: 4px solid #3b82f6;
                        border-radius: 50%;
                        width: 40px;
                        height: 40px;
                        animation: spin 1s linear infinite;
                        margin: 0 auto 15px;
                    "></div>
                    <p style="color: #666;">${message}</p>
                </div>
            `;
        }
    },

    hide(targetElement) {
        if (typeof targetElement === 'string') {
            targetElement = document.querySelector(targetElement);
        }
        
        if (targetElement) {
            targetElement.innerHTML = '';
        }
    }
};

// Añadir animación de spinner
if (!document.getElementById('spinner-animation')) {
    const style = document.createElement('style');
    style.id = 'spinner-animation';
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
}

/**
 * Confirmación de acciones
 */
const Confirm = {
    async show(message, title = '¿Estás seguro?') {
        return new Promise((resolve) => {
            const confirmed = confirm(`${title}\n\n${message}`);
            resolve(confirmed);
        });
    }
};

// Exportar para uso global
window.AjaxHandler = AjaxHandler;
window.Toast = Toast;
window.Loader = Loader;
window.Confirm = Confirm;
