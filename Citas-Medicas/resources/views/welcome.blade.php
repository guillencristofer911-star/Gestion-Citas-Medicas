<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediConnect - Plataforma de Citas Médicas</title>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>

<body> 
        <!-- Navbar -->
    <nav>
        <div class="logo">
            <div class="logo-icon">🏥</div>
            <span>MediConnect</span>
        </div>
        <ul class="nav-links">
            <li><a href="#inicio">Inicio</a></li>
            <li><a href="#caracteristicas">Características</a></li>
            <li><a href="#roles">Roles</a></li>
            <li><a href="#contacto">Contacto</a></li>
        </ul>
            <div class="nav-buttons">
        <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>
        <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
    </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="hero-content">
            <h1>Gestiona tus citas médicas de forma simple y segura</h1>
            <p>Plataforma integral para pacientes, médicos y administradores. Agenda, consulta y administra citas médicas en un solo lugar.</p>
            <div class="hero-buttons">
                <button class="btn-primary">Comenzar Ahora</button>
                <button class="btn-secondary">Ver Demo</button>
            </div>
        </div>
        <div class="hero-image">
            <svg width="500" height="500" viewBox="0 0 500 500">
                <circle cx="250" cy="250" r="200" fill="rgba(179,207,229,0.2)"/>
                <rect x="150" y="150" width="200" height="250" rx="20" fill="white" opacity="0.9"/>
                <circle cx="250" cy="200" r="30" fill="#FF9F43"/>
                <rect x="180" y="260" width="140" height="15" rx="7" fill="#B3CFE5"/>
                <rect x="180" y="290" width="100" height="15" rx="7" fill="#4A7FA7"/>
                <rect x="180" y="320" width="120" height="15" rx="7" fill="#B3CFE5"/>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="caracteristicas">
        <h2 class="section-title">Características Principales</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📅</div>
                <h3>Gestión de Citas</h3>
                <p>Agenda, modifica y cancela citas médicas de manera rápida y eficiente con validación de disponibilidad en tiempo real.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👨‍⚕️</div>
                <h3>Directorio Médico</h3>
                <p>Accede a un catálogo completo de médicos disponibles con sus especialidades y horarios de atención.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Seguridad Total</h3>
                <p>Protección de datos personales con control de acceso por roles y sesiones seguras para todos los usuarios.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Panel de Control</h3>
                <p>Visualiza estadísticas, historial de citas y gestiona toda la información desde un dashboard intuitivo.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⏰</div>
                <h3>Horarios Flexibles</h3>
                <p>Consulta disponibilidad en tiempo real y selecciona el horario que mejor se adapte a tus necesidades.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">✅</div>
                <h3>Estados de Cita</h3>
                <p>Seguimiento completo del estado de tus citas: pendiente, confirmada, atendida o cancelada.</p>
            </div>
        </div>
    </section>

    <!-- Roles Section -->
    <section class="roles" id="roles">
        <h2 class="section-title">Diseñado para Todos</h2>
        <div class="roles-container">
            <div class="role-card">
                <div class="role-icon">👤</div>
                <h3>Pacientes</h3>
                <p>Gestiona tu salud de forma autónoma</p>
                <ul>
                    <li>Ver médicos disponibles</li>
                    <li>Agendar citas fácilmente</li>
                    <li>Consultar historial de citas</li>
                    <li>Cancelar citas si es necesario</li>
                    <li>Ver estados en tiempo real</li>
                </ul>
            </div>
            <div class="role-card">
                <div class="role-icon">⚕️</div>
                <h3>Médicos</h3>
                <p>Administra tu agenda profesional</p>
                <ul>
                    <li>Ver citas asignadas</li>
                    <li>Actualizar estados de citas</li>
                    <li>Consultar agenda diaria/semanal</li>
                    <li>Gestionar disponibilidad</li>
                    <li>Confirmar atención</li>
                </ul>
            </div>
            <div class="role-card">
                <div class="role-icon">👨‍💼</div>
                <h3>Administradores</h3>
                <p>Control total del sistema</p>
                <ul>
                    <li>Gestionar médicos</li>
                    <li>Definir horarios de atención</li>
                    <li>Ver todas las citas</li>
                    <li>Administrar usuarios</li>
                    <li>Gestionar estados del sistema</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <h2>500+</h2>
                <p>Citas Gestionadas</p>
            </div>
            <div class="stat-item">
                <h2>40+</h2>
                <p>Médicos Registrados</p>
            </div>
            <div class="stat-item">
                <h2>140+</h2>
                <p>Pacientes Activos</p>
            </div>
            <div class="stat-item">
                <h2>99%</h2>
                <p>Satisfacción</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <h2>¿Listo para transformar la gestión de citas médicas?</h2>
        <p>Únete a MediConnect y experimenta una nueva forma de gestionar la atención médica</p>
        <a href="{{ route('register') }}" class="btn-register">   
            <button>Registrarse Gratis</button>
        </a>
        
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="#">Términos de Servicio</a>
                <a href="#">Política de Privacidad</a>
                <a href="#">Contacto</a>
                <a href="#">Ayuda</a>
                <a href="#">API</a>
            </div>
            <p>&copy; 2024 MediConnect - Sistema de Citas Médicas. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>