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
        <!-- Sección Izquierda - Información -->
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

        <!-- Sección Derecha - Formulario -->
        <div class="right-section">
            <div class="form-container">
                <h1>Crear Cuenta</h1>
                <p>Completa los datos para registrarte</p>

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

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Nombre Completo -->
                    <div class="form-group">
                        <label for="name">Nombre Completo</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Juan Pérez García"
                            required
                            @error('name') aria-invalid="true" @enderror
                        >
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

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
                            placeholder="Mínimo 8 caracteres"
                            required
                            @error('password') aria-invalid="true" @enderror
                        >
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirmar Password -->
                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation"
                            placeholder="Confirma tu contraseña"
                            required
                        >
                    </div>

                    <!-- Aceptar Términos -->
                    <div class="checkbox-group">
                        <div class="checkbox-wrapper">
                            <input 
                                type="checkbox" 
                                id="terms" 
                                name="terms"
                                required
                                @error('terms') aria-invalid="true" @enderror
                            >
                            <label for="terms">
                                Acepto los <a href="#">términos y condiciones</a>
                            </label>
                        </div>
                    </div>
                    @error('terms')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <!-- Botón Submit -->
                    <button type="submit" class="btn-primary btn-full">
                        Crear Cuenta
                    </button>
                </form>

                <!-- Login Link -->
                <div class="form-footer">
                    <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
