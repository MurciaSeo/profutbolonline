<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad - ProfutbolOnline</title>
    <meta name="description" content="Política de privacidad de la plataforma ProfutbolOnline">
    
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
            <h1 class="legal-title">Política de Privacidad</h1>
            
            <div class="legal-section">
                <h3>1. Información que Recopilamos</h3>
                <p>
                    Recopilamos información que usted nos proporciona directamente, como cuando crea una cuenta, 
                    completa su perfil, o se comunica con nosotros. Esta información puede incluir:
                </p>
                <ul>
                    <li>Información de identificación personal (nombre, apellido, email)</li>
                    <li>Información de contacto (teléfono, dirección)</li>
                    <li>Información del perfil (rol, entrenador asignado)</li>
                    <li>Datos de entrenamiento y progreso</li>
                    <li>Comunicaciones con nuestro servicio de atención al cliente</li>
                </ul>
            </div>
            
            <div class="legal-section">
                <h3>2. Información Recopilada Automáticamente</h3>
                <p>
                    Cuando utiliza nuestra plataforma, recopilamos automáticamente cierta información, incluyendo:
                </p>
                <ul>
                    <li>Información del dispositivo (tipo de dispositivo, sistema operativo)</li>
                    <li>Información de uso (páginas visitadas, tiempo de permanencia)</li>
                    <li>Información de red (dirección IP, proveedor de servicios)</li>
                    <li>Cookies y tecnologías similares</li>
                </ul>
            </div>
            
            <div class="legal-section">
                <h3>3. Cómo Utilizamos su Información</h3>
                <p>
                    Utilizamos la información recopilada para:
                </p>
                <ul>
                    <li>Proporcionar, mantener y mejorar nuestros servicios</li>
                    <li>Procesar transacciones y enviar información relacionada</li>
                    <li>Enviar comunicaciones técnicas, actualizaciones y mensajes administrativos</li>
                    <li>Responder a sus comentarios y preguntas</li>
                    <li>Personalizar su experiencia en la plataforma</li>
                    <li>Detectar, prevenir y abordar problemas técnicos</li>
                </ul>
            </div>
            
            <div class="legal-section">
                <h3>4. Compartir de Información</h3>
                <p>
                    No vendemos, alquilamos ni compartimos su información personal con terceros, excepto en las siguientes circunstancias:
                </p>
                <ul>
                    <li>Con su consentimiento explícito</li>
                    <li>Para cumplir con obligaciones legales</li>
                    <li>Para proteger nuestros derechos y seguridad</li>
                    <li>Con proveedores de servicios que nos ayudan a operar la plataforma</li>
                    <li>En caso de fusión, adquisición o venta de activos</li>
                </ul>
            </div>
            
            <div class="legal-section">
                <h3>5. Seguridad de la Información</h3>
                <p>
                    Implementamos medidas de seguridad técnicas y organizativas apropiadas para proteger su información personal 
                    contra acceso no autorizado, alteración, divulgación o destrucción. Sin embargo, ningún método de transmisión 
                    por Internet o método de almacenamiento electrónico es 100% seguro.
                </p>
            </div>
            
            <div class="legal-section">
                <h3>6. Retención de Datos</h3>
                <p>
                    Conservamos su información personal durante el tiempo necesario para cumplir con los propósitos 
                    descritos en esta política, a menos que la ley requiera un período de retención más largo. 
                    Cuando ya no necesitemos su información, la eliminaremos de forma segura.
                </p>
            </div>
            
            <div class="legal-section">
                <h3>7. Sus Derechos</h3>
                <p>
                    Dependiendo de su ubicación, puede tener los siguientes derechos respecto a su información personal:
                </p>
                <ul>
                    <li>Acceso: Solicitar una copia de la información personal que tenemos sobre usted</li>
                    <li>Rectificación: Corregir información personal inexacta o incompleta</li>
                    <li>Eliminación: Solicitar la eliminación de su información personal</li>
                    <li>Portabilidad: Recibir su información personal en un formato estructurado</li>
                    <li>Oposición: Oponerse al procesamiento de su información personal</li>
                    <li>Limitación: Solicitar la limitación del procesamiento de su información</li>
                </ul>
            </div>
            
            <div class="legal-section">
                <h3>8. Cookies y Tecnologías Similares</h3>
                <p>
                    Utilizamos cookies y tecnologías similares para mejorar su experiencia en nuestra plataforma. 
                    Puede controlar el uso de cookies a través de la configuración de su navegador, 
                    aunque esto puede afectar la funcionalidad de nuestro sitio.
                </p>
            </div>
            
            <div class="legal-section">
                <h3>9. Transferencias Internacionales</h3>
                <p>
                    Su información puede ser transferida y procesada en países distintos al suyo. 
                    Nos aseguramos de que estas transferencias cumplan con las leyes de protección de datos aplicables 
                    y que su información reciba un nivel adecuado de protección.
                </p>
            </div>
            
            <div class="legal-section">
                <h3>10. Menores de Edad</h3>
                <p>
                    Nuestros servicios no están dirigidos a menores de 16 años. No recopilamos intencionalmente 
                    información personal de menores de 16 años. Si cree que hemos recopilado información de un menor, 
                    contáctenos inmediatamente.
                </p>
            </div>
            
            <div class="legal-section">
                <h3>11. Cambios a esta Política</h3>
                <p>
                    Podemos actualizar esta política de privacidad de vez en cuando. Le notificaremos cualquier cambio 
                    significativo publicando la nueva política en esta página y, si es necesario, enviándole una notificación por email.
                </p>
            </div>
            
            <div class="legal-section">
                <h3>12. Contacto</h3>
                <p>
                    Si tiene preguntas sobre esta política de privacidad o sobre nuestras prácticas de privacidad, puede contactarnos:
                </p>
                <ul>
                    <li>Email: privacidad@profutbolonline.com</li>
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