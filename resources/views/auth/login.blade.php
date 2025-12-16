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
        <!-- Sección Izquierda - Información -->
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

        <!-- Sección Derecha - Formulario -->
        <div class="right-section">
            <div class="form-container">
                <h1>Iniciar Sesión</h1>
                <p>Accede a tu cuenta con tus credenciales</p>

                <!-- Mostrar errores si existen -->
                @if ($errors->any())
                    <div class="alert alert-error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- Mostrar mensajes de sesión -->
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            placeholder="tu@email.com"
                            required
                            @error('email') aria-invalid="true" @enderror
                        >
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            placeholder="Ingresa tu contraseña"
                            required
                            @error('password') aria-invalid="true" @enderror
                        >
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="checkbox-group">
                        <div class="checkbox-wrapper">
                            <input 
                                type="checkbox" 
                                id="remember" 
                                name="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <label for="remember">Recuérdame</label>
                        </div>
                    </div>

                    <!-- Botón Submit -->
                    <button type="submit" class="btn-primary btn-full">
                        Iniciar Sesión
                    </button>
                </form>

                <!-- Forgot Password y Register Links -->
                <div class="form-footer">
                    @if (Route::has('password.request'))
                        <div style="margin-bottom: 10px;">
                            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                        </div>
                    @endif

                    <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
