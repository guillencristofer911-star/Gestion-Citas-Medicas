<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediConnect - Sistema de Citas Médicas</title>
    <link rel="stylesheet" href="{{ asset('css/Landing.css') }}">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('welcome') }}" class="navbar-brand">
                MediConnect
            </a>

            <ul class="navbar-menu">
                <li><a href="#features">Características</a></li>
                <li><a href="#benefits">Beneficios</a></li>
                <li><a href="#contact">Contacto</a></li>
            </ul>

            <div class="navbar-buttons">
                <a href="{{ route('login') }}" class="btn btn-login">
                    Iniciar Sesión
                </a>
                <a href="{{ route('register') }}" class="btn btn-register">
                    Registrarse
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1>Gestiona tus citas médicas de forma inteligente</h1>
                <p>MediConnect es la plataforma más moderna para solicitar y administrar citas médicas. Conecta con los mejores especialistas de forma rápida y segura.</p>

                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        Comenzar Ahora
                    </a>
                    <a href="#features" class="btn btn-secondary">
                        Conocer Más
                    </a>
                </div>
            </div>

            <div class="hero-image">
                <svg width="300" height="300" viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="150" cy="150" r="140" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
                    <path d="M120 120 L150 90 L180 120 L180 180 Q180 200 160 200 L140 200 Q120 200 120 180 Z" fill="rgba(255,255,255,0.3)" stroke="rgba(255,255,255,0.5)" stroke-width="2"/>
                    <circle cx="150" cy="140" r="8" fill="rgba(255,255,255,0.5)"/>
                    <rect x="130" y="160" width="40" height="8" rx="4" fill="rgba(255,255,255,0.4)"/>
                </svg>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="section-container">
            <div class="section-header">
                <h2>¿Por qué elegir MediConnect?</h2>
                <p>Descubre las características que hacen de MediConnect la mejor opción para gestionar tus citas médicas</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3>Agenda Fácil</h3>
                    <p>Solicita citas médicas en pocos clics. Interfaz intuitiva y sencilla de usar.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">👨‍⚕️</div>
                    <h3>Mejores Especialistas</h3>
                    <p>Accede a una red de médicos especializados en diferentes áreas de la salud.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Historial Médico</h3>
                    <p>Mantén un registro completo de todos tus citas y consultas médicas.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Seguridad Garantizada</h3>
                    <p>Tus datos personales y médicos están protegidos con los más altos estándares.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">⏰</div>
                    <h3>Disponibilidad 24/7</h3>
                    <p>Accede a la plataforma en cualquier momento desde cualquier dispositivo.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>App Móvil</h3>
                    <p>Descarga nuestra aplicación para una experiencia móvil optimizada.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="benefits" class="stats">
        <div class="section-container">
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>15,000+</h3>
                    <p>Pacientes Satisfechos</p>
                </div>
                <div class="stat-item">
                    <h3>250+</h3>
                    <p>Médicos Especialistas</p>
                </div>
                <div class="stat-item">
                    <h3>50,000+</h3>
                    <p>Citas Realizadas</p>
                </div>
                <div class="stat-item">
                    <h3>98%</h3>
                    <p>Tasa de Satisfacción</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="contact" class="cta">
        <div class="section-container">
            <h2>¿Listo para comenzar?</h2>
            <p>Únete a miles de pacientes que ya están gestionando sus citas con MediConnect</p>

            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="btn btn-primary">
                    Crear Cuenta Gratis
                </a>
                <a href="{{ route('login') }}" class="btn btn-secondary">
                    Ya tengo cuenta
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-col">
                <h3>MediConnect</h3>
                <p>La plataforma inteligente para gestionar tus citas médicas de forma sencilla y segura.</p>
            </div>



            <div class="footer-col">
                <h3>Soporte</h3>
                <ul>
                    <li><a href="#">Centro de Ayuda</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Contacto</a></li>
                    <li><a href="#">Estado del Sistema</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3>Legal</h3>
                <ul>
                    <li><a href="#">Términos y Condiciones</a></li>
                    <li><a href="#">Política de Privacidad</a></li>
                    <li><a href="#">Política de Cookies</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 MediConnect. Todos los derechos reservados. | Diseñado con ❤️ para tu salud</p>
        </div>
    </footer>
</body>
</html>
