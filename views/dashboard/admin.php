<?php 
$title = "Panel de Administración - Dashboard";
$breadcrumbs = [
    ['title' => 'Inicio', 'icon' => 'fas fa-home'],
    ['title' => 'Administración', 'icon' => 'fas fa-cogs']
];

require_once 'views/layouts/header_mejorado.php'; 
?>

<div class="container py-4">
    <!-- Header del administrador -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card bg-gradient-danger text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="admin-avatar-large">
                                <i class="fas fa-user-shield fa-2x"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="mb-1">Panel de Administración</h2>
                            <p class="mb-0 opacity-75">
                                Control total del sistema - <?php echo date('d/m/Y H:i'); ?>
                            </p>
                        </div>
                        <div class="col-auto">
                            <div class="text-end">
                                <h5 class="mb-0">Sistema Activo</h5>
                                <small class="opacity-75">Uptime: 99.9%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métricas principales del sistema -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-muted text-uppercase small">Usuarios Totales</div>
                            <div class="h3 mb-0"><?php echo $total_usuarios ?? 0; ?></div>
                            <div class="text-success small mt-1">
                                <i class="fas fa-arrow-up"></i> +<?php echo $nuevos_usuarios_mes ?? 0; ?> este mes
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary text-white">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-muted text-uppercase small">Entrenamientos Activos</div>
                            <div class="h3 mb-0"><?php echo $entrenamientos_activos ?? 0; ?></div>
                            <div class="text-info small mt-1">
                                <i class="fas fa-chart-line"></i> En progreso
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success text-white">
                                <i class="fas fa-dumbbell"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-muted text-uppercase small">Ingresos Mensuales</div>
                            <div class="h3 mb-0">$<?php echo number_format($ingresos_mensuales ?? 0, 0); ?></div>
                            <div class="text-warning small mt-1">
                                <i class="fas fa-dollar-sign"></i> <?php echo $crecimiento_ingresos ?? 0; ?>% vs mes anterior
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning text-white">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-muted text-uppercase small">Uso del Sistema</div>
                            <div class="h3 mb-0"><?php echo $uso_sistema ?? 0; ?>%</div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-danger" style="width: <?php echo $uso_sistema ?? 0; ?>%"></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-danger text-white">
                                <i class="fas fa-server"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métricas por tipo de usuario -->
    <div class="row mb-4">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-user-tie me-2 text-success"></i>
                        Entrenadores
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Total Activos</span>
                        <span class="h4 mb-0"><?php echo $total_entrenadores ?? 0; ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Promedio Clientes</span>
                        <span class="h5 mb-0"><?php echo $promedio_clientes_entrenador ?? 0; ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Valoración Promedio</span>
                        <span class="h5 mb-0 text-warning">
                            <i class="fas fa-star"></i> <?php echo number_format($valoracion_entrenadores ?? 0, 1); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-user-friends me-2 text-primary"></i>
                        Entrenados
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Total Activos</span>
                        <span class="h4 mb-0"><?php echo $total_entrenados ?? 0; ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Entrenamientos/Mes</span>
                        <span class="h5 mb-0"><?php echo $entrenamientos_mes_promedio ?? 0; ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Retención</span>
                        <span class="h5 mb-0 text-success"><?php echo $tasa_retencion ?? 0; ?>%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-info"></i>
                        Actividad Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Sesiones Activas</span>
                        <span class="h4 mb-0"><?php echo $sesiones_activas ?? 0; ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Uso Almacenamiento</span>
                        <span class="h5 mb-0"><?php echo $uso_almacenamiento ?? 0; ?>GB</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Respaldo</span>
                        <span class="h5 mb-0 text-success">
                            <i class="fas fa-check-circle"></i> OK
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Usuarios recientes -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-plus me-2 text-success"></i>
                        Usuarios Recientes
                    </h5>
                    <a href="/admin/usuarios" class="btn btn-sm btn-outline-primary">Ver todos</a>
                </div>
                <div class="card-body">
                    <div class="user-list">
                        <?php 
                        $usuarios_recientes = [
                            ['nombre' => 'Juan Pérez', 'tipo' => 'entrenado', 'fecha' => '2024-01-15'],
                            ['nombre' => 'María González', 'tipo' => 'entrenador', 'fecha' => '2024-01-14'],
                            ['nombre' => 'Carlos Ruiz', 'tipo' => 'entrenado', 'fecha' => '2024-01-13'],
                            ['nombre' => 'Ana Torres', 'tipo' => 'entrenado', 'fecha' => '2024-01-12'],
                        ];
                        ?>
                        
                        <?php foreach ($usuarios_recientes as $usuario): ?>
                            <div class="user-item mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3">
                                        <?php echo strtoupper(substr($usuario['nombre'], 0, 2)); ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="user-name"><?php echo $usuario['nombre']; ?></div>
                                        <div class="user-info text-muted small">
                                            <span class="badge badge-<?php echo $usuario['tipo'] === 'entrenador' ? 'success' : 'primary'; ?>">
                                                <?php echo ucfirst($usuario['tipo']); ?>
                                            </span>
                                            - <?php echo date('d/m/Y', strtotime($usuario['fecha'])); ?>
                                        </div>
                                    </div>
                                    <div class="user-actions">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="/admin/usuarios/ver/<?php echo $usuario['id'] ?? 1; ?>">Ver perfil</a></li>
                                                <li><a class="dropdown-item" href="/admin/usuarios/editar/<?php echo $usuario['id'] ?? 1; ?>">Editar</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="/admin/usuarios/suspender/<?php echo $usuario['id'] ?? 1; ?>">Suspender</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas del sistema -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                        Alertas del Sistema
                    </h5>
                    <span class="badge bg-warning"><?php echo $total_alertas ?? 3; ?></span>
                </div>
                <div class="card-body">
                    <div class="alert-list">
                        <div class="alert-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon me-3">
                                    <i class="fas fa-database text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="alert-title">Uso de base de datos alto</div>
                                    <div class="alert-description text-muted small">
                                        85% de capacidad utilizada
                                    </div>
                                </div>
                                <div class="alert-actions">
                                    <button class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="alert-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon me-3">
                                    <i class="fas fa-shield-alt text-danger"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="alert-title">Intentos de acceso fallidos</div>
                                    <div class="alert-description text-muted small">
                                        15 intentos en la última hora
                                    </div>
                                </div>
                                <div class="alert-actions">
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="alert-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon me-3">
                                    <i class="fas fa-clock text-info"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="alert-title">Respaldo programado</div>
                                    <div class="alert-description text-muted small">
                                        Próximo respaldo en 2 horas
                                    </div>
                                </div>
                                <div class="alert-actions">
                                    <button class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="col-xl-4 col-lg-12 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                        Estadísticas Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-clock text-primary"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value"><?php echo $tiempo_promedio_sesion ?? 45; ?>min</div>
                                <div class="stat-label">Sesión promedio</div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-mobile-alt text-success"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value"><?php echo $usuarios_movil ?? 68; ?>%</div>
                                <div class="stat-label">Usuarios móvil</div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value"><?php echo $satisfaccion_promedio ?? 4.7; ?></div>
                                <div class="stat-label">Satisfacción</div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-sync-alt text-info"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value"><?php echo $actualizaciones_pendientes ?? 2; ?></div>
                                <div class="stat-label">Actualizaciones</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos del sistema -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-area me-2 text-info"></i>
                        Crecimiento de Usuarios
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="userGrowthChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-doughnut me-2 text-primary"></i>
                        Distribución Usuarios
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="userDistributionChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Herramientas administrativas -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2 text-danger"></i>
                        Herramientas Administrativas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <a href="/admin/usuarios" class="admin-tool-card">
                                <div class="tool-icon bg-primary">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="tool-content">
                                    <h6>Gestionar Usuarios</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="/admin/reportes" class="admin-tool-card">
                                <div class="tool-icon bg-success">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="tool-content">
                                    <h6>Reportes</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="/admin/configuracion" class="admin-tool-card">
                                <div class="tool-icon bg-info">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <div class="tool-content">
                                    <h6>Configuración</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="/admin/respaldos" class="admin-tool-card">
                                <div class="tool-icon bg-warning">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div class="tool-content">
                                    <h6>Respaldos</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="/admin/logs" class="admin-tool-card">
                                <div class="tool-icon bg-danger">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="tool-content">
                                    <h6>Logs del Sistema</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="/admin/mantenimiento" class="admin-tool-card">
                                <div class="tool-icon bg-secondary">
                                    <i class="fas fa-wrench"></i>
                                </div>
                                <div class="tool-content">
                                    <h6>Mantenimiento</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.admin-avatar-large {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6f42c1, #5a32a3);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.8rem;
}

.user-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.user-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.user-name {
    font-weight: 600;
    color: #2c3e50;
}

.badge-success {
    background-color: #28a745;
}

.badge-primary {
    background-color: #007bff;
}

.alert-item {
    padding: 12px;
    border-radius: 8px;
    background: #f8f9fa;
    border-left: 4px solid #dee2e6;
}

.alert-title {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(0, 123, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.stat-value {
    font-size: 1.2rem;
    font-weight: bold;
    color: #2c3e50;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
}

.admin-tool-card {
    display: block;
    padding: 20px;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    text-align: center;
    height: 100%;
}

.admin-tool-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    text-decoration: none;
    color: inherit;
}

.tool-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin: 0 auto 15px;
}

.tool-content h6 {
    margin: 0;
    font-weight: 600;
    font-size: 0.85rem;
}
</style>

<script>
// Gráfico de crecimiento de usuarios
const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
new Chart(userGrowthCtx, {
    type: 'line',
    data: {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
        datasets: [{
            label: 'Usuarios Totales',
            data: [120, 145, 180, 220, 280, 350],
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Usuarios Activos',
            data: [95, 120, 150, 185, 230, 290],
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gráfico de distribución de usuarios
const userDistributionCtx = document.getElementById('userDistributionChart').getContext('2d');
new Chart(userDistributionCtx, {
    type: 'doughnut',
    data: {
        labels: ['Entrenados', 'Entrenadores', 'Administradores'],
        datasets: [{
            data: [<?php echo $total_entrenados ?? 280; ?>, <?php echo $total_entrenadores ?? 15; ?>, <?php echo $total_admins ?? 3; ?>],
            backgroundColor: ['#007bff', '#28a745', '#dc3545'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>