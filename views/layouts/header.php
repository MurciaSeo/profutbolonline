<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Entrenamiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
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
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 0.5rem;
        }
        
        .dropdown-item {
            padding: 0.7rem 1rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: var(--light-color);
            transform: translateX(5px);
        }
        
        .dropdown-divider {
            margin: 0.5rem 0;
        }
        
        .btn {
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-success:hover {
            background-color: #43a047;
            border-color: #43a047;
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
            color: white;
        }
        
        .btn-warning:hover {
            background-color: #f57c00;
            border-color: #f57c00;
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .btn-danger:hover {
            background-color: #e53935;
            border-color: #e53935;
            transform: translateY(-2px);
        }
        
        .btn-info {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }
        
        .btn-info:hover {
            background-color: #3ab8df;
            border-color: #3ab8df;
            color: white;
            transform: translateY(-2px);
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .table thead th {
            background-color: var(--light-color);
            border-bottom: 2px solid #e9ecef;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .form-control, .form-select {
            border-radius: 6px;
            padding: 0.6rem 1rem;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 1.25rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
            border-radius: 6px;
        }
        
        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
        }
        
        .nav-tabs .nav-link {
            color: var(--dark-color) !important;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link:hover {
            border: none;
            color: var(--primary-color) !important;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color) !important;
            border: none;
            border-bottom: 3px solid var(--primary-color);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">
                <img src="/img/logo.png" alt="Logo" height="50">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">
                                <i class="fas fa-home me-1"></i> Inicio
                            </a>
                        </li>
                        <?php if ($_SESSION['user_role'] === 'entrenador' || $_SESSION['user_role'] === 'admin'): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-cog me-1"></i> Base de datos
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dataDropdown">
                                    <li class="nav-item">
                                        <a class="dropdown-item" href="/ejercicios">
                                            <i class="fas fa-dumbbell me-1"></i> Ejercicios
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="dropdown-item" href="/tipos-ejercicios">
                                            <i class="fas fa-dumbbell me-1"></i> Tipos de Ejercicios
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="dropdown-item" href="/entrenamientos">
                                            <i class="fas fa-clipboard-list me-1"></i> Entrenamientos
                                        </a>
                                    </li>
                                    <li><a class="dropdown-item" href="/programaciones"><i class="fas fa-calendar-alt me-2"></i> Programas de Entrenamiento</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        
                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-cog me-1"></i> Administración
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                    <li><a class="dropdown-item" href="/usuarios"><i class="fas fa-users me-2"></i> Gestionar Usuarios</a></li>
                                    <li><a class="dropdown-item" href="/usuarios/crear"><i class="fas fa-user-plus me-2"></i> Crear Usuario</a></li>
                                    <li><a class="dropdown-item" href="/usuarios/asignar-entrenador"><i class="fas fa-user-tie me-2"></i> Asignar Entrenador</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/programaciones"><i class="fas fa-calendar-alt me-2"></i> Programas de Entrenamiento</a></li>
                                    <li><a class="dropdown-item" href="/programacion_usuarios"><i class="fas fa-user-check me-2"></i> Asignar Programas</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/admin/configuracion"><i class="fas fa-cogs me-2"></i> Configuración</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        
                        <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'entrenador'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/sesiones">
                                    <i class="fas fa-calendar-alt"></i> Sesiones Individuales
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="/logout">
                                <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html> 