<?php 
$title = "Panel de Entrenador - Dashboard";
$breadcrumbs = [
    ['title' => 'Inicio', 'icon' => 'fas fa-home'],
    ['title' => 'Panel Entrenador', 'icon' => 'fas fa-user-tie']
];

require_once 'views/layouts/header_mejorado.php'; 
?>

<div class="container py-4">
    <!-- Header del entrenador -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="trainer-avatar-large">
                                <i class="fas fa-user-tie fa-2x"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="mb-1">Panel de Entrenador</h2>
                            <p class="mb-0 opacity-75">
                                Gestiona tus clientes y entrenamientos - <?php echo date('d/m/Y'); ?>
                            </p>
                        </div>
                        <div class="col-auto">
                            <div class="text-end">
                                <h5 class="mb-0"><?php echo $total_clientes_activos ?? 0; ?> Clientes Activos</h5>
                                <small class="opacity-75">Entrenamientos hoy: <?php echo $entrenamientos_hoy ?? 0; ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métricas del entrenador -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-muted text-uppercase small">Clientes Totales</div>
                            <div class="h3 mb-0"><?php echo $total_clientes ?? 0; ?></div>
                            <div class="text-success small mt-1">
                                <i class="fas fa-user-plus"></i> +<?php echo $nuevos_clientes_mes ?? 0; ?> este mes
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
                            <div class="text-muted text-uppercase small">Entrenamientos Asignados</div>
                            <div class="h3 mb-0"><?php echo $entrenamientos_asignados ?? 0; ?></div>
                            <div class="text-info small mt-1">
                                <i class="fas fa-calendar-check"></i> Esta semana
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success text-white">
                                <i class="fas fa-clipboard-list"></i>
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
                            <div class="text-muted text-uppercase small">Valoración Promedio</div>
                            <div class="h3 mb-0"><?php echo number_format($valoracion_promedio ?? 0, 1); ?></div>
                            <div class="text-warning small mt-1">
                                <i class="fas fa-star"></i> De tus entrenamientos
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning text-white">
                                <i class="fas fa-star"></i>
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
                            <div class="text-muted text-uppercase small">Programas Activos</div>
                            <div class="h3 mb-0"><?php echo $programas_activos ?? 0; ?></div>
                            <div class="text-danger small mt-1">
                                <i class="fas fa-chart-line"></i> En progreso
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-danger text-white">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Clientes recientes -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-friends me-2 text-primary"></i>
                        Clientes Recientes
                    </h5>
                    <a href="/clientes" class="btn btn-sm btn-outline-primary">Ver todos</a>
                </div>
                <div class="card-body">
                    <div class="client-list">
                        <?php if (!empty($clientes_recientes)): ?>
                            <?php foreach (array_slice($clientes_recientes, 0, 4) as $cliente): ?>
                                <div class="client-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="client-avatar me-3">
                                            <?php echo strtoupper(substr($cliente['nombre'], 0, 2)); ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="client-name"><?php echo htmlspecialchars($cliente['nombre']); ?></div>
                                            <div class="client-info text-muted small">
                                                <?php echo $cliente['entrenamientos_completados'] ?? 0; ?> entrenamientos completados
                                            </div>
                                        </div>
                                        <div class="client-actions">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="/clientes/perfil/<?php echo $cliente['id']; ?>">Ver perfil</a></li>
                                                    <li><a class="dropdown-item" href="/entrenamientos/asignar/<?php echo $cliente['id']; ?>">Asignar entrenamiento</a></li>
                                                    <li><a class="dropdown-item" href="/programas/crear/<?php echo $cliente['id']; ?>">Crear programa</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-user-plus fa-2x mb-2"></i>
                                <p>No hay clientes registrados</p>
                                <a href="/clientes/invitar" class="btn btn-sm btn-primary">Invitar cliente</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Entrenamientos pendientes de revisión -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2 text-warning"></i>
                        Pendientes de Revisión
                    </h5>
                    <span class="badge bg-warning"><?php echo count($entrenamientos_pendientes ?? []); ?></span>
                </div>
                <div class="card-body">
                    <div class="review-list">
                        <?php if (!empty($entrenamientos_pendientes)): ?>
                            <?php foreach (array_slice($entrenamientos_pendientes, 0, 4) as $entrenamiento): ?>
                                <div class="review-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="review-icon me-3">
                                            <i class="fas fa-exclamation-triangle text-warning"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="review-title"><?php echo htmlspecialchars($entrenamiento['nombre']); ?></div>
                                            <div class="review-client text-muted small">
                                                <?php echo htmlspecialchars($entrenamiento['cliente_nombre']); ?>
                                                - <?php echo date('d/m/Y', strtotime($entrenamiento['fecha_completado'])); ?>
                                            </div>
                                        </div>
                                        <div class="review-actions">
                                            <a href="/entrenamientos/revisar/<?php echo $entrenamiento['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <p>No hay entrenamientos pendientes</p>
                                <small>¡Excelente trabajo!</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas de rendimiento -->
        <div class="col-xl-4 col-lg-12 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-info"></i>
                        Rendimiento Semanal
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Herramientas de gestión -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2 text-success"></i>
                        Progreso de Clientes
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="clientProgressChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                        Agenda de Hoy
                    </h5>
                </div>
                <div class="card-body">
                    <div class="agenda-list">
                        <?php 
                        $agenda_hoy = [
                            ['tiempo' => '09:00', 'cliente' => 'Juan Pérez', 'tipo' => 'Entrenamiento personal'],
                            ['tiempo' => '10:30', 'cliente' => 'María González', 'tipo' => 'Seguimiento programa'],
                            ['tiempo' => '14:00', 'cliente' => 'Carlos Ruiz', 'tipo' => 'Valoración inicial'],
                            ['tiempo' => '16:00', 'cliente' => 'Ana Torres', 'tipo' => 'Entrenamiento grupal'],
                        ];
                        ?>
                        
                        <?php foreach ($agenda_hoy as $cita): ?>
                            <div class="agenda-item mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="agenda-time me-3">
                                        <span class="badge bg-primary"><?php echo $cita['tiempo']; ?></span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="agenda-client"><?php echo $cita['cliente']; ?></div>
                                        <div class="agenda-type text-muted small"><?php echo $cita['tipo']; ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas para entrenadores -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2 text-danger"></i>
                        Herramientas de Entrenador
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <a href="/entrenamientos/crear" class="trainer-tool-card">
                                <div class="tool-icon bg-primary">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <div class="tool-content">
                                    <h6>Crear Entrenamiento</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="/programas/crear" class="trainer-tool-card">
                                <div class="tool-icon bg-success">
                                    <i class="fas fa-project-diagram"></i>
                                </div>
                                <div class="tool-content">
                                    <h6>Nuevo Programa</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="/clientes/invitar" class="trainer-tool-card">
                                <div class="tool-icon bg-info">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="tool-content">
                                    <h6>Invitar Cliente</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="/ejercicios/biblioteca" class="trainer-tool-card">
                                <div class="tool-icon bg-warning">
                                    <i class="fas fa-dumbbell"></i>
                                </div>
                                <div class="tool-content">
                                    <h6>Biblioteca Ejercicios</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="/reportes/clientes" class="trainer-tool-card">
                                <div class="tool-icon bg-danger">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <div class="tool-content">
                                    <h6>Reportes</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="/configuracion/entrenador" class="trainer-tool-card">
                                <div class="tool-icon bg-secondary">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div class="tool-content">
                                    <h6>Configuración</h6>
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
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.trainer-avatar-large {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.client-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.8rem;
}

.client-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.client-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.client-name {
    font-weight: 600;
    color: #2c3e50;
}

.review-item {
    padding: 12px;
    border-left: 4px solid #ffc107;
    background: rgba(255, 193, 7, 0.1);
    border-radius: 0 8px 8px 0;
    margin-bottom: 10px;
}

.review-title {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
}

.agenda-item {
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.agenda-item:last-child {
    border-bottom: none;
}

.agenda-client {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
}

.agenda-type {
    font-size: 0.8rem;
}

.trainer-tool-card {
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

.trainer-tool-card:hover {
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
// Gráfico de rendimiento semanal
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
new Chart(performanceCtx, {
    type: 'bar',
    data: {
        labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
        datasets: [{
            label: 'Entrenamientos Asignados',
            data: [5, 8, 6, 9, 7, 4, 2],
            backgroundColor: 'rgba(40, 167, 69, 0.8)',
            borderColor: '#28a745',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Gráfico de progreso de clientes
const clientProgressCtx = document.getElementById('clientProgressChart').getContext('2d');
new Chart(clientProgressCtx, {
    type: 'line',
    data: {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
        datasets: [{
            label: 'Progreso Promedio (%)',
            data: [45, 52, 61, 68, 75, 82],
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
                beginAtZero: true,
                max: 100
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>