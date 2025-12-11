<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - MediConnect</title>
    <link rel="stylesheet" href="{{ asset('css/Auth.css') }}">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="left-content">
                <div class="logo">MediConnect</div>
                <h2>Únete a nuestra plataforma</h2>
                <p>Crea tu cuenta y comienza a gestionar tus citas médicas de forma simple, rápida y segura.</p>
                
                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <span>Agenda citas con facilidad</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <span>Consulta tu historial médico</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <span>Acceso 24/7 desde cualquier lugar</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <span>Plataforma segura y confiable</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="right-section">
            <div class="form-header">
                <h1>Crear cuenta</h1>
                <p>¿Ya tienes una cuenta? <a href="#login">Inicia sesión</a></p>
            </div>

            <div class="info-note">
                <strong>Nota:</strong> Al registrarte, tu cuenta será creada como paciente. Los roles de médico o administrador son asignados por el equipo administrativo.
            </div>

            <div class="success-message" id="successMessage">
                ¡Cuenta creada exitosamente! Redirigiendo...
            </div>

            <form action="{{ route('register.store') }}" method="POST" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="name">Nombre completo</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text" id="name" name="name" placeholder="Ingresa tu nombre completo" required>
                    </div>
                    <span class="error-message">Por favor ingresa tu nombre</span>
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <div class="input-wrapper">
                        <span class="input-icon">📧</span>
                        <input type="email" id="email" name="email" placeholder="tu@email.com" required>
                    </div>
                    <span class="error-message">Por favor ingresa un email válido</span>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">👁️</button>
                    </div>
                    <span class="error-message">La contraseña debe tener al menos 8 caracteres</span>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repite tu contraseña" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">👁️</button>
                    </div>
                    <span class="error-message">Las contraseñas no coinciden</span>
                </div>

                <button type="submit" class="submit-btn">Crear cuenta</button>
            </form>
        </div>
    </div>
        <script src="{{ asset('js/Auth.js') }}"></script>

</body>
</html>
