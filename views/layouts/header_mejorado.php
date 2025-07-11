<?php
// Incluir los sistemas de notificación y middleware
require_once 'utils/NotificationManager.php';
require_once 'middlewares/AuthMiddleware.php';

// Convertir notificaciones legacy
NotificationManager::convertLegacyNotifications();

// Obtener información del usuario actual
$currentUser = null;
$userRole = AuthMiddleware::getCurrentUserRole();
$userId = AuthMiddleware::getCurrentUserId();

if ($userId) {
    require_once 'models/UsuarioModel.php';
    $usuarioModel = new UsuarioModel();
    $currentUser = $usuarioModel->findById($userId);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Sistema de Entrenamiento'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-1px);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
        }
        
        .dropdown-item {
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background-color: var(--light-color);
            transform: translateX(5px);
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary-color), var(--info-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .notification-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 1050;
            max-width: 350px;
        }
        
        .notification-item {
            margin-bottom: 10px;
            animation: slideInRight 0.3s ease;
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .role-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 1rem;
            font-weight: 500;
            margin-left: 0.5rem;
        }
        
        .role-admin { background-color: var(--danger-color); color: white; }
        .role-entrenador { background-color: var(--warning-color); color: white; }
        .role-entrenado { background-color: var(--success-color); color: white; }
        
        .quick-stats {
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            margin: 0.5rem 0;
        }
        
        .quick-stats small {
            opacity: 0.9;
        }
        
        .navbar-toggler {
            border: none;
            padding: 0.25rem 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .breadcrumb {
            background-color: transparent;
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        
        .breadcrumb-item a {
            text-decoration: none;
            color: var(--primary-color);
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box input {
            border-radius: 20px;
            padding-left: 2.5rem;
            border: 1px solid #dee2e6;
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        .search-box .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-dumbbell me-2"></i>
                ProFútbol Online
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if ($currentUser): ?>
                    <!-- Menú principal -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">
                                <i class="fas fa-home me-1"></i> Inicio
                            </a>
                        </li>
                        
                        <?php if ($userRole === 'entrenado'): ?>
                            <!-- Menú para entrenados -->
                            <li class="nav-item">
                                <a class="nav-link" href="/entrenamientos">
                                    <i class="fas fa-clipboard-list me-1"></i> Mis Entrenamientos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/seguimiento-programa">
                                    <i class="fas fa-chart-line me-1"></i> Mis Programas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/usuarios/perfil/<?php echo $userId; ?>">
                                    <i class="fas fa-user me-1"></i> Mi Perfil
                                </a>
                            </li>
                            
                        <?php elseif ($userRole === 'entrenador'): ?>
                            <!-- Menú para entrenadores -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="entrenamientosDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-dumbbell me-1"></i> Entrenamientos
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/entrenamientos"><i class="fas fa-list me-2"></i>Ver Todos</a></li>
                                    <li><a class="dropdown-item" href="/entrenamientos/crear"><i class="fas fa-plus me-2"></i>Crear Nuevo</a></li>
                                    <li><a class="dropdown-item" href="/entrenamientos/asignar-rapido"><i class="fas fa-user-plus me-2"></i>Asignar Rápido</a></li>
                                </ul>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="entrenadosDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-users me-1"></i> Mis Entrenados
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/usuarios/entrenados"><i class="fas fa-list me-2"></i>Ver Todos</a></li>
                                    <li><a class="dropdown-item" href="/programacion_usuarios"><i class="fas fa-calendar-plus me-2"></i>Asignar Programas</a></li>
                                </ul>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="baseDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-database me-1"></i> Base de Datos
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/ejercicios"><i class="fas fa-dumbbell me-2"></i>Ejercicios</a></li>
                                    <li><a class="dropdown-item" href="/tipos-ejercicios"><i class="fas fa-tags me-2"></i>Tipos de Ejercicios</a></li>
                                    <li><a class="dropdown-item" href="/programaciones"><i class="fas fa-calendar-alt me-2"></i>Programas</a></li>
                                </ul>
                            </li>
                            
                        <?php elseif ($userRole === 'admin'): ?>
                            <!-- Menú para administradores -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-cog me-1"></i> Administración
                                </a>
                                <ul class="dropdown-menu">
                                    <li><h6 class="dropdown-header">Usuarios</h6></li>
                                    <li><a class="dropdown-item" href="/usuarios"><i class="fas fa-users me-2"></i>Gestionar Usuarios</a></li>
                                    <li><a class="dropdown-item" href="/usuarios/crear"><i class="fas fa-user-plus me-2"></i>Crear Usuario</a></li>
                                    <li><a class="dropdown-item" href="/usuarios/asignar-entrenador"><i class="fas fa-user-tie me-2"></i>Asignar Entrenador</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Sistema</h6></li>
                                    <li><a class="dropdown-item" href="/admin/configuracion"><i class="fas fa-cogs me-2"></i>Configuración</a></li>
                                    <li><a class="dropdown-item" href="/admin/reportes"><i class="fas fa-chart-bar me-2"></i>Reportes</a></li>
                                </ul>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="contentDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-database me-1"></i> Contenido
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/ejercicios"><i class="fas fa-dumbbell me-2"></i>Ejercicios</a></li>
                                    <li><a class="dropdown-item" href="/tipos-ejercicios"><i class="fas fa-tags me-2"></i>Tipos de Ejercicios</a></li>
                                    <li><a class="dropdown-item" href="/entrenamientos"><i class="fas fa-clipboard-list me-2"></i>Entrenamientos</a></li>
                                    <li><a class="dropdown-item" href="/programaciones"><i class="fas fa-calendar-alt me-2"></i>Programas</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                    
                    <!-- Barra de búsqueda -->
                    <div class="search-box me-3">
                        <input type="text" class="form-control" placeholder="Buscar..." id="globalSearch">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                    
                    <!-- Menú de usuario -->
                    <ul class="navbar-nav">
                        <!-- Notificaciones -->
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <?php if (NotificationManager::hasNotifications()): ?>
                                    <span class="notification-badge"><?php echo count($_SESSION['notifications'] ?? []); ?></span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                                <li><h6 class="dropdown-header">Notificaciones</h6></li>
                                <?php if (NotificationManager::hasNotifications()): ?>
                                    <?php foreach ($_SESSION['notifications'] as $notification): ?>
                                        <li>
                                            <div class="dropdown-item-text">
                                                <strong><?php echo htmlspecialchars($notification['title']); ?></strong><br>
                                                <small><?php echo htmlspecialchars($notification['message']); ?></small>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li><span class="dropdown-item-text text-muted">No hay notificaciones nuevas</span></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        
                        <!-- Perfil de usuario -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar me-2">
                                    <?php echo strtoupper(substr($currentUser['nombre'], 0, 1)); ?>
                                </div>
                                <span class="d-none d-md-inline">
                                    <?php echo htmlspecialchars($currentUser['nombre']); ?>
                                    <span class="role-badge role-<?php echo $userRole; ?>">
                                        <?php echo ucfirst($userRole); ?>
                                    </span>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><h6 class="dropdown-header">Mi Cuenta</h6></li>
                                <li><a class="dropdown-item" href="/usuarios/perfil/<?php echo $userId; ?>"><i class="fas fa-user me-2"></i>Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="/configuracion"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                                <li><hr class="dropdown-divider"></li>
                                
                                <?php if ($userRole === 'entrenado'): ?>
                                    <li>
                                        <div class="quick-stats">
                                            <small>Entrenamientos completados</small><br>
                                            <strong>15 de 20</strong>
                                        </div>
                                    </li>
                                <?php elseif ($userRole === 'entrenador'): ?>
                                    <li>
                                        <div class="quick-stats">
                                            <small>Entrenados activos</small><br>
                                            <strong>8 usuarios</strong>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                    
                <?php else: ?>
                    <!-- Menú para usuarios no autenticados -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/login">
                                <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/registro">
                                <i class="fas fa-user-plus me-1"></i> Registrarse
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Breadcrumb -->
    <?php if (isset($breadcrumbs) && $currentUser): ?>
        <div class="container mt-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb): ?>
                        <?php if (isset($breadcrumb['url'])): ?>
                            <li class="breadcrumb-item">
                                <a href="<?php echo $breadcrumb['url']; ?>">
                                    <?php if (isset($breadcrumb['icon'])): ?>
                                        <i class="<?php echo $breadcrumb['icon']; ?> me-1"></i>
                                    <?php endif; ?>
                                    <?php echo $breadcrumb['title']; ?>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="breadcrumb-item active">
                                <?php if (isset($breadcrumb['icon'])): ?>
                                    <i class="<?php echo $breadcrumb['icon']; ?> me-1"></i>
                                <?php endif; ?>
                                <?php echo $breadcrumb['title']; ?>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
        </div>
    <?php endif; ?>
    
    <!-- Contenedor de notificaciones -->
    <?php echo NotificationManager::renderNotifications(); ?>
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Búsqueda global
        document.getElementById('globalSearch')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            // Implementar lógica de búsqueda en tiempo real
            console.log('Buscando:', searchTerm);
        });
        
        // Auto-ocultar notificaciones después de 5 segundos
        setTimeout(() => {
            const notifications = document.querySelectorAll('.notification-item');
            notifications.forEach(notification => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            });
        }, 5000);
        
        // Efectos visuales para dropdowns
        document.querySelectorAll('.dropdown-toggle').forEach(dropdown => {
            dropdown.addEventListener('click', function() {
                const menu = this.nextElementSibling;
                if (menu) {
                    menu.style.animation = 'slideIn 0.3s ease';
                }
            });
        });
    </script>
    
    <!-- Renderizar notificaciones SweetAlert -->
    <?php echo NotificationManager::renderSweetAlertNotifications(); ?>
</body>
</html>