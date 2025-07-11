<?php 
$title = "Mi Progreso - Dashboard";
$breadcrumbs = [
    ['title' => 'Inicio', 'icon' => 'fas fa-home']
];

require_once 'views/layouts/header_mejorado.php'; 
?>

<div class="container py-4">
    <div class="row">
        <!-- Saludo personalizado -->
        <div class="col-12 mb-4">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="user-avatar-large">
                                <?php echo strtoupper(substr($usuario['nombre'], 0, 2)); ?>
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="mb-1">¡Hola, <?php echo htmlspecialchars($usuario['nombre']); ?>!</h2>
                            <p class="mb-0 opacity-75">
                                <?php
                                $hora = date('H');
                                if ($hora < 12) {
                                    echo "Buenos días";
                                } elseif ($hora < 18) {
                                    echo "Buenas tardes";
                                } else {
                                    echo "Buenas noches";
                                }
                                ?>
                                - Es hora de entrenar 💪
                            </p>
                        </div>
                        <div class="col-auto">
                            <div class="text-end">
                                <h5 class="mb-0"><?php echo date('l, d F'); ?></h5>
                                <small class="opacity-75">Semana <?php echo date('W'); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métricas principales -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-muted text-uppercase small">Entrenamientos</div>
                            <div class="h3 mb-0"><?php echo $total_entrenamientos; ?></div>
                            <div class="text-success small mt-1">
                                <i class="fas fa-arrow-up"></i> Total asignados
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary text-white">
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
                            <div class="text-muted text-uppercase small">Completados</div>
                            <div class="h3 mb-0"><?php echo $entrenamientos_completados; ?></div>
                            <div class="text-success small mt-1">
                                <i class="fas fa-check"></i> 
                                <?php echo $total_entrenamientos > 0 ? round(($entrenamientos_completados / $total_entrenamientos) * 100) : 0; ?>% completitud
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success text-white">
                                <i class="fas fa-trophy"></i>
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
                            <div class="h3 mb-0"><?php echo $total_programas_activos; ?></div>
                            <div class="text-info small mt-1">
                                <i class="fas fa-calendar-alt"></i> En progreso
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-info text-white">
                                <i class="fas fa-chart-line"></i>
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
                            <div class="text-muted text-uppercase small">Progreso General</div>
                            <div class="h3 mb-0"><?php echo round($progreso_general ?? 0); ?>%</div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-warning" style="width: <?php echo round($progreso_general ?? 0); ?>%"></div>
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
    </div>

    <div class="row">
        <!-- Progreso circular -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>
                        Progreso Semanal
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="progress-circle mb-3">
                        <canvas id="progressChart" width="200" height="200"></canvas>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-value"><?php echo $entrenamientos_completados; ?></div>
                                <div class="stat-label">Completados</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-value"><?php echo $total_entrenamientos - $entrenamientos_completados; ?></div>
                                <div class="stat-label">Pendientes</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Próximos entrenamientos -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-day me-2 text-success"></i>
                        Próximos Entrenamientos
                    </h5>
                    <a href="/entrenamientos" class="btn btn-sm btn-outline-primary">Ver todos</a>
                </div>
                <div class="card-body">
                    <div class="training-list">
                        <?php if (!empty($proximos_entrenamientos)): ?>
                            <?php foreach (array_slice($proximos_entrenamientos, 0, 3) as $entrenamiento): ?>
                                <div class="training-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="training-icon me-3">
                                            <i class="fas fa-dumbbell"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="training-name"><?php echo htmlspecialchars($entrenamiento['nombre']); ?></div>
                                            <div class="training-date text-muted small">
                                                <?php echo date('d M Y', strtotime($entrenamiento['fecha'])); ?>
                                            </div>
                                        </div>
                                        <div class="training-actions">
                                            <a href="/entrenamientos/ver/<?php echo $entrenamiento['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-play"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                <p>No hay entrenamientos programados</p>
                                <small>Contacta con tu entrenador para obtener nuevos entrenamientos</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logros y badges -->
        <div class="col-xl-4 col-lg-12 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-award me-2 text-warning"></i>
                        Logros Recientes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="achievements">
                        <?php 
                        $logros = [
                            ['icon' => 'fas fa-fire', 'title' => 'Racha de 7 días', 'description' => 'Entrena 7 días consecutivos', 'earned' => true],
                            ['icon' => 'fas fa-star', 'title' => 'Primera valoración', 'description' => 'Completa tu primera valoración', 'earned' => true],
                            ['icon' => 'fas fa-trophy', 'title' => '10 entrenamientos', 'description' => 'Completa 10 entrenamientos', 'earned' => $entrenamientos_completados >= 10],
                            ['icon' => 'fas fa-medal', 'title' => 'Programa completado', 'description' => 'Completa tu primer programa', 'earned' => false],
                        ];
                        ?>
                        
                        <?php foreach ($logros as $logro): ?>
                            <div class="achievement-item mb-3 <?php echo $logro['earned'] ? 'earned' : 'locked'; ?>">
                                <div class="d-flex align-items-center">
                                    <div class="achievement-icon me-3">
                                        <i class="<?php echo $logro['icon']; ?>"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="achievement-title"><?php echo $logro['title']; ?></div>
                                        <div class="achievement-description"><?php echo $logro['description']; ?></div>
                                    </div>
                                    <?php if ($logro['earned']): ?>
                                        <div class="achievement-badge">
                                            <i class="fas fa-check-circle text-success"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos de progreso -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2 text-info"></i>
                        Progreso Mensual
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyProgressChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2 text-primary"></i>
                        Actividad Reciente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-feed">
                        <div class="activity-item">
                            <div class="activity-time">Hace 2 horas</div>
                            <div class="activity-content">
                                <i class="fas fa-check-circle text-success"></i>
                                Completaste "Entrenamiento de Fuerza"
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-time">Ayer</div>
                            <div class="activity-content">
                                <i class="fas fa-star text-warning"></i>
                                Valoraste "Cardio Intenso" con 5 estrellas
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-time">Hace 2 días</div>
                            <div class="activity-content">
                                <i class="fas fa-calendar-plus text-info"></i>
                                Se te asignó un nuevo programa
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2 text-danger"></i>
                        Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="/entrenamientos" class="quick-action-card">
                                <div class="quick-action-icon bg-primary">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="quick-action-content">
                                    <h6>Iniciar Entrenamiento</h6>
                                    <p>Comienza tu próximo entrenamiento</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/seguimiento-programa" class="quick-action-card">
                                <div class="quick-action-icon bg-success">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="quick-action-content">
                                    <h6>Ver Progreso</h6>
                                    <p>Revisa tu progreso en programas</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/usuarios/perfil/<?php echo $userId; ?>" class="quick-action-card">
                                <div class="quick-action-icon bg-info">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="quick-action-content">
                                    <h6>Mi Perfil</h6>
                                    <p>Actualiza tu información</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="/configuracion" class="quick-action-card">
                                <div class="quick-action-icon bg-warning">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div class="quick-action-content">
                                    <h6>Configuración</h6>
                                    <p>Personaliza tu experiencia</p>
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
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.user-avatar-large {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.5rem;
}

.icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: bold;
    color: #2c3e50;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
}

.training-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.training-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.training-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

.training-name {
    font-weight: 600;
    color: #2c3e50;
}

.achievement-item {
    padding: 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.achievement-item.earned {
    background: rgba(40, 167, 69, 0.1);
    border-left: 4px solid #28a745;
}

.achievement-item.locked {
    background: rgba(108, 117, 125, 0.1);
    opacity: 0.7;
}

.achievement-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #ffc107;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.achievement-title {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
}

.achievement-description {
    color: #6c757d;
    font-size: 0.8rem;
}

.activity-feed {
    max-height: 300px;
    overflow-y: auto;
}

.activity-item {
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-time {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 5px;
}

.activity-content {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
}

.quick-action-card {
    display: block;
    padding: 20px;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    height: 100%;
}

.quick-action-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    text-decoration: none;
    color: inherit;
}

.quick-action-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-bottom: 15px;
}

.quick-action-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.quick-action-content p {
    margin: 0;
    font-size: 0.85rem;
    color: #6c757d;
}
</style>

<script>
// Gráfico de progreso circular
const progressCtx = document.getElementById('progressChart').getContext('2d');
const completedPercentage = <?php echo $total_entrenamientos > 0 ? ($entrenamientos_completados / $total_entrenamientos) * 100 : 0; ?>;

new Chart(progressCtx, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [completedPercentage, 100 - completedPercentage],
            backgroundColor: ['#28a745', '#e9ecef'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Gráfico de progreso mensual
const monthlyCtx = document.getElementById('monthlyProgressChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
        datasets: [{
            label: 'Entrenamientos Completados',
            data: [3, 7, 12, 15, 20, <?php echo $entrenamientos_completados; ?>],
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
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