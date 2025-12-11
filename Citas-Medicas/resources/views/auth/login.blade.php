<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - MediConnect</title>
    <link rel="stylesheet" href="{{ asset('css/Auth.css') }}">
</head>
<body>

    <div class="container">
        <div class="left-section">
            <div class="left-content">
                <div class="logo">MediConnect</div>
                <h2>¡Bienvenido de nuevo!</h2>
                <p>Inicia sesión para acceder a tu cuenta y gestionar tus citas médicas de forma rápida y segura.</p>
                
                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <span>Gestiona tus citas médicas</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <span>Consulta tu historial</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <span>Acceso seguro y protegido</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <span>Interfaz intuitiva y moderna</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="right-section">
            <div class="form-header">
                <h1>Iniciar sesión</h1>
                <p>¿No tienes una cuenta? <a href="#registro">Regístrate gratis</a></p>
            </div>

            <div class="success-message" id="successMessage">
                ¡Inicio de sesión exitoso! Redirigiendo...
            </div>

            <div class="alert-error" id="errorMessage">
                Credenciales incorrectas. Por favor verifica tu email y contraseña.
            </div>

            <form id="loginForm">
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
                        <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">👁️</button>
                    </div>
                    <span class="error-message">Por favor ingresa tu contraseña</span>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <span>Recordarme</span>
                    </label>
                    <a href="#forgot-password" class="forgot-password">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="submit-btn">Iniciar sesión</button>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/Auth.js') }}"></script>
</body>
</html>
