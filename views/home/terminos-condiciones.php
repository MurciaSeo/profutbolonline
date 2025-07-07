<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones - ProfutbolOnline</title>
    <meta name="description" content="Términos y condiciones de uso de la plataforma ProfutbolOnline">
    
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
        }
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: var(--bg-light);
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
        
        .legal-content {
            background: white;
            border-radius: 16px;
            padding: 3rem;
            margin: 2rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .legal-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 2rem;
            border-bottom: 3px solid var(--accent-color);
            padding-bottom: 1rem;
        }
        
        .legal-section {
            margin-bottom: 2rem;
        }
        
        .legal-section h3 {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .legal-section p {
            color: var(--text-light);
            margin-bottom: 1rem;
        }
        
        .legal-section ul {
            color: var(--text-light);
            margin-bottom: 1rem;
        }
        
        .legal-section li {
            margin-bottom: 0.5rem;
        }
        
        .footer {
            background: var(--text-dark);
            color: white;
            padding: 40px 0 20px;
            margin-top: 3rem;
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
            .legal-content {
                padding: 2rem;
                margin: 1rem 0;
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
                        <a class="nav-link" href="/">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/registro">Registrarse</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container" style="margin-top: 100px;">
        <div class="legal-content">
            <h1 class="legal-title">Términos y Condiciones</h1>
            
            <div class="legal-section">
                <h3>1. Aceptación de los Términos</h3>
                <p>
                    Al acceder y utilizar la plataforma ProfutbolOnline, usted acepta estar sujeto a estos términos y condiciones de uso. 
                    Si no está de acuerdo con alguna parte de estos términos, no debe utilizar nuestro servicio.
                </p>
            </div>
            
            <div class="legal-section">
                <h3>2. Descripción del Servicio</h3>
                <p>
                    ProfutbolOnline es una plataforma web diseñada para la gestión de entrenamientos funcionales. 
                    Nuestro servicio permite a entrenadores crear, gestionar y asignar entrenamientos a deportistas, 
                    así como realizar seguimiento del progreso y rendimiento.
                </p>
            </div>
            
            <div class="legal-section">
                <h3>3. Registro y Cuentas de Usuario</h3>
                <p>
                    Para utilizar nuestros servicios, debe registrarse y crear una cuenta. Usted es responsable de:
                </p>
                <ul>
                    <li>Proporcionar información precisa y completa durante el registro</li>
                    <li>Mantener la confidencialidad de su contraseña</li>
                    <li>Notificar inmediatamente cualquier uso no autorizado de su cuenta</li>
                    <li>Aceptar responsabilidad por todas las actividades que ocurran bajo su cuenta</li>
                </ul>
            </div>
            
            <div class="legal-section">
                <h3>4. Uso Aceptable</h3>
                <p>
                    Usted se compromete a utilizar la plataforma únicamente para fines legales y de acuerdo con estos términos. 
                    Está prohibido:
                </p>
                <ul>
                    <li>Usar la plataforma para actividades ilegales o fraudulentas</li>
                    <li>Intentar acceder a cuentas de otros usuarios</li>
                    <li>Interferir con el funcionamiento de la plataforma</li>
                    <li>Compartir información confidencial de otros usuarios</li>
                    <li>Usar la plataforma para spam o contenido inapropiado</li>
                </ul>
            </div>
            
            <div class="legal-section">
                <h3>5. Privacidad y Protección de Datos</h3>
                <p>
                    Su privacidad es importante para nosotros. El uso de su información personal está gobernado por nuestra 
                    Política de Privacidad, que forma parte de estos términos y condiciones.
                </p>
            </div>
            
            <div class="legal-section">
                <h3>6. Propiedad Intelectual</h3>
                <p>
                    Todo el contenido de la plataforma, incluyendo pero no limitado a textos, gráficos, logos, 
                    iconos, imágenes, clips de audio, descargas digitales y compilaciones de datos, es propiedad 
                    de ProfutbolOnline o de sus proveedores de contenido y está protegido por las leyes de propiedad intelectual.
                </p>
            </div>
            
            <div class="legal-section">
                <h3>7. Limitación de Responsabilidad</h3>
                <p>
                    ProfutbolOnline no será responsable por daños indirectos, incidentales, especiales, 
                    consecuentes o punitivos, incluyendo pero no limitado a pérdida de beneficios, datos o uso, 
                    incurridos por usted o cualquier tercero, ya sea en una acción contractual o extracontractual.
                </p>
            </div>
            
            <div class="legal-section">
                <h3>8. Modificaciones del Servicio</h3>
                <p>
                    Nos reservamos el derecho de modificar o discontinuar, temporal o permanentemente, 
                    el servicio con o sin previo aviso. No seremos responsables ante usted o cualquier tercero 
                    por cualquier modificación, suspensión o discontinuación del servicio.
                </p>
            </div>
            
            <div class="legal-section">
                <h3>9. Terminación</h3>
                <p>
                    Podemos terminar o suspender su cuenta y acceso a la plataforma en cualquier momento, 
                    con o sin causa, con o sin previo aviso, efectivo inmediatamente. Si desea terminar su cuenta, 
                    puede hacerlo a través de la configuración de su perfil o contactándonos directamente.
                </p>
            </div>
            
            <div class="legal-section">
                <h3>10. Ley Aplicable</h3>
                <p>
                    Estos términos se rigen por las leyes del país donde opera ProfutbolOnline. 
                    Cualquier disputa será resuelta en los tribunales competentes de dicha jurisdicción.
                </p>
            </div>
            
            <div class="legal-section">
                <h3>11. Contacto</h3>
                <p>
                    Si tiene preguntas sobre estos términos y condiciones, puede contactarnos a través de:
                </p>
                <ul>
                    <li>Email: legal@profutbolonline.com</li>
                    <li>Teléfono: +34 600 123 456</li>
                    <li>Dirección: Calle Ejemplo, 123, 28001 Madrid, España</li>
                </ul>
            </div>
            
            <div class="legal-section">
                <p><strong>Última actualización:</strong> Enero 2025</p>
            </div>
        </div>
    </div>

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
                    <a href="/">Inicio</a>
                    <a href="/terminos-condiciones">Términos</a>
                    <a href="/politica-privacidad">Privacidad</a>
                    <a href="/login">Iniciar Sesión</a>
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
</body>
</html> 