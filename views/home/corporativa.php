<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProfutbolOnline - Plataforma de Gestión de Entrenamientos Funcionales</title>
    <meta name="description" content="Plataforma integral para la gestión de entrenamientos funcionales. Conecta entrenadores y deportistas para optimizar el rendimiento físico.">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #000000;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --success-color: #4caf50;
            --warning-color: #ff9800;
            --danger-color: #f44336;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 600;
            font-size: 1.4rem;
            color: white !important;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: white !important;
            transform: translateY(-1px);
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .btn-hero {
            padding: 12px 32px;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-primary-hero {
            background: var(--accent-color);
            color: white;
            border: 2px solid var(--accent-color);
        }
        
        .btn-primary-hero:hover {
            background: transparent;
            color: var(--accent-color);
            transform: translateY(-2px);
        }
        
        .btn-secondary-hero {
            background: transparent;
            color: white;
            border: 2px solid white;
            margin-left: 1rem;
        }
        
        .btn-secondary-hero:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
        }
        
        .features-section {
            padding: 80px 0;
            background: var(--bg-light);
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 3rem;
            color: var(--text-dark);
        }
        
        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }
        
        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }
        
        .feature-description {
            color: var(--text-light);
            line-height: 1.6;
        }
        
        .benefits-section {
            padding: 80px 0;
            background: white;
        }
        
        .benefit-item {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .benefit-icon {
            width: 60px;
            height: 60px;
            background: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-right: 1.5rem;
            flex-shrink: 0;
        }
        
        .benefit-content h4 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .benefit-content p {
            color: var(--text-light);
            margin: 0;
        }
        
        .testimonials-section {
            padding: 80px 0;
            background: var(--bg-light);
        }
        
        .testimonial-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 1.5rem;
            color: var(--text-dark);
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
        }
        
        .testimonial-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 1rem;
        }
        
        .testimonial-info h5 {
            margin: 0;
            font-weight: 600;
        }
        
        .testimonial-info p {
            margin: 0;
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .cta-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            text-align: center;
        }
        
        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .cta-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .footer {
            background: var(--text-dark);
            color: white;
            padding: 40px 0 20px;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .footer-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--accent-color);
        }
        
        .footer-links a {
            color: white;
            text-decoration: none;
            margin-left: 2rem;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--accent-color);
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .btn-secondary-hero {
                margin-left: 0;
                margin-top: 1rem;
            }
            
            .footer-content {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-links {
                margin-top: 1rem;
            }
            
            .footer-links a {
                margin: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
        <a class="navbar-brand" href="/">
                <img src="/img/logo.png" alt="Logo" height="50">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#caracteristicas">Características</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#beneficios">Beneficios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonios">Testimonios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/login" class="btn btn-primary btn-sm">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="hero-title">Optimiza tu rendimiento deportivo</h1>
                    <p class="hero-subtitle">
                        Plataforma integral para la gestión de entrenamientos funcionales. 
                        Conecta entrenadores y deportistas para maximizar resultados.
                    </p>
                    <div class="hero-buttons">
                        <a href="/registro" class="btn-hero btn-primary-hero">
                            <i class="fas fa-rocket me-2"></i>
                            Comenzar Gratis
                        </a>
                        <a href="#caracteristicas" class="btn-hero btn-secondary-hero">
                            <i class="fas fa-play me-2"></i>
                            Conocer Más
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-image">
                        <i class="fas fa-running" style="font-size: 15rem; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="caracteristicas" class="features-section">
        <div class="container">
            <h2 class="section-title">Características Principales</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-dumbbell"></i>
                        </div>
                        <h3 class="feature-title">Gestión de Entrenamientos</h3>
                        <p class="feature-description">
                            Crea y gestiona entrenamientos personalizados con ejercicios específicos, 
                            series, repeticiones y tiempos de descanso.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="feature-title">Conexión Entrenador-Deportista</h3>
                        <p class="feature-description">
                            Sistema de asignación que permite a los entrenadores asignar 
                            entrenamientos específicos a sus deportistas.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">Seguimiento de Progreso</h3>
                        <p class="feature-description">
                            Monitorea el progreso con valoraciones, completitud de entrenamientos 
                            y estadísticas detalladas de rendimiento.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature-title">Acceso Móvil</h3>
                        <p class="feature-description">
                            Plataforma responsive que funciona perfectamente en dispositivos 
                            móviles, tablets y computadoras.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <h3 class="feature-title">Reportes PDF</h3>
                        <p class="feature-description">
                            Genera reportes detallados en PDF de los entrenamientos 
                            para compartir o archivar.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Seguridad Garantizada</h3>
                        <p class="feature-description">
                            Sistema de autenticación seguro con roles diferenciados 
                            para administradores, entrenadores y deportistas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="beneficios" class="benefits-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="section-title text-start">¿Por qué elegir ProfutbolOnline?</h2>
                    <div class="benefits-list">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Ahorra Tiempo</h4>
                                <p>Automatiza la gestión de entrenamientos y reduce el tiempo de planificación</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-target"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Mejora Resultados</h4>
                                <p>Seguimiento detallado que permite optimizar el rendimiento deportivo</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Mejor Comunicación</h4>
                                <p>Mantén una comunicación efectiva entre entrenadores y deportistas</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Datos Precisos</h4>
                                <p>Accede a estadísticas y reportes detallados del progreso</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="benefits-image">
                        <i class="fas fa-trophy" style="font-size: 20rem; color: var(--accent-color); opacity: 0.1;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonios" class="testimonials-section">
        <div class="container">
            <h2 class="section-title">Lo que dicen nuestros usuarios</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">
                            "ProfutbolOnline ha revolucionado la forma en que gestiono los entrenamientos 
                            de mi equipo. La plataforma es intuitiva y muy completa."
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">CM</div>
                            <div class="testimonial-info">
                                <h5>Carlos Martínez</h5>
                                <p>Entrenador de Fútbol</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">
                            "Como deportista, me encanta poder ver mis entrenamientos asignados 
                            y hacer seguimiento de mi progreso de manera tan fácil."
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">AG</div>
                            <div class="testimonial-info">
                                <h5>Ana García</h5>
                                <p>Deportista Profesional</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">
                            "La funcionalidad de reportes PDF es excelente para documentar 
                            el progreso de mis atletas y compartir con otros profesionales."
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">RL</div>
                            <div class="testimonial-info">
                                <h5>Roberto López</h5>
                                <p>Preparador Físico</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">¿Listo para optimizar tu rendimiento?</h2>
            <p class="cta-subtitle">
                Únete a cientos de entrenadores y deportistas que ya confían en ProfutbolOnline
            </p>
            <div class="cta-buttons">
                <a href="/registro" class="btn-hero btn-primary-hero">
                    <i class="fas fa-rocket me-2"></i>
                    Comenzar Ahora
                </a>
                <a href="/login" class="btn-hero btn-secondary-hero">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Iniciar Sesión
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                <a class="navbar-brand" href="/">
                    <img src="/img/logo.png" alt="Logo" height="50">
                </a>
                </div>
                <div class="footer-links">
                    <a href="#caracteristicas">Características</a>
                    <a href="#beneficios">Beneficios</a>
                    <a href="#testimonios">Testimonios</a>
                    <a href="/terminos-condiciones">Términos</a>
                    <a href="/politica-privacidad">Privacidad</a>
                    <a href="/login">Iniciar Sesión</a>
                    <a href="/registro">Registrarse</a>
                </div>
            </div>
            <div class="text-center mt-4">
                <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">
                    © 2025 ProfutbolOnline. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth Scrolling -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = 'none';
            }
        });
    </script>
</body>
</html> 