<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Dashboard de Administración</h1>
                <div class="btn-group" role="group">
                    <a href="/admin/coaching" class="btn btn-outline-primary">
                        <i class="fas fa-graduation-cap"></i> Coaching
                    </a>
                    <a href="/programas/estadisticas-ventas" class="btn btn-outline-info">
                        <i class="fas fa-chart-bar"></i> Entrenamiento
                    </a>
                </div>
            </div>
            
            <!-- Resumen General -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-euro-sign fa-2x text-success mb-2"></i>
                            <h4 class="text-success"><?php echo number_format($resumenGeneral['total_ingresos'], 2); ?> €</h4>
                            <small class="text-muted">Ingresos Totales</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-shopping-cart fa-2x text-primary mb-2"></i>
                            <h4 class="text-primary"><?php echo $resumenGeneral['total_ventas']; ?></h4>
                            <small class="text-muted">Ventas Totales</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-users fa-2x text-info mb-2"></i>
                            <h4 class="text-info"><?php echo $resumenGeneral['usuarios_activos']; ?></h4>
                            <small class="text-muted">Usuarios Activos</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-clipboard-list fa-2x text-warning mb-2"></i>
                            <h4 class="text-warning"><?php echo $resumenGeneral['programas_activos']; ?></h4>
                            <small class="text-muted">Programas Activos</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Estadísticas por Tipo -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-dumbbell text-primary"></i> Programas de Entrenamiento
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h5 class="text-success"><?php echo $estadisticasEntrenamiento['total_ventas'] ?? 0; ?></h5>
                                    <small class="text-muted">Ventas</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="text-info"><?php echo number_format($estadisticasEntrenamiento['total_recaudado'] ?? 0, 2); ?> €</h5>
                                    <small class="text-muted">Ingresos</small>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between">
                                    <span>Promedio por venta:</span>
                                    <strong><?php echo number_format(($estadisticasEntrenamiento['total_recaudado'] ?? 0) / max(($estadisticasEntrenamiento['total_ventas'] ?? 1), 1), 2); ?> €</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-graduation-cap text-warning"></i> Programas de Coaching
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h5 class="text-success"><?php echo $estadisticasCoaching['total_suscripciones'] ?? 0; ?></h5>
                                    <small class="text-muted">Suscripciones</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="text-info"><?php echo number_format($estadisticasPagosCoaching['ingresos_totales'] ?? 0, 2); ?> €</h5>
                                    <small class="text-muted">Ingresos</small>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between">
                                    <span>Suscripciones activas:</span>
                                    <strong><?php echo $estadisticasCoaching['suscripciones_activas'] ?? 0; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gráficos -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Evolución de Ventas (Últimos 6 meses)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartVentas" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Distribución por Tipo</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartDistribucion" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Top Programas -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Top Programas de Entrenamiento</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($datosGraficos['topProgramasEntrenamiento'])): ?>
                                <?php foreach ($datosGraficos['topProgramasEntrenamiento'] as $programa): ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong><?php echo htmlspecialchars($programa['nombre']); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo $programa['ventas']; ?> ventas</small>
                                    </div>
                                    <span class="badge bg-primary"><?php echo number_format($programa['ingresos'], 2); ?> €</span>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted text-center">No hay datos disponibles</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Top Programas de Coaching</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($datosGraficos['topProgramasCoaching'])): ?>
                                <?php foreach ($datosGraficos['topProgramasCoaching'] as $programa): ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong><?php echo htmlspecialchars($programa['nombre']); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo $programa['suscripciones']; ?> suscripciones</small>
                                    </div>
                                    <span class="badge bg-warning"><?php echo number_format($programa['ingresos'], 2); ?> €</span>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted text-center">No hay datos disponibles</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Acciones Rápidas -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Acciones Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="/admin/coaching/crear" class="btn btn-outline-primary w-100 mb-2">
                                        <i class="fas fa-plus"></i> Nuevo Programa Coaching
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="/programas/gestionar-precios" class="btn btn-outline-info w-100 mb-2">
                                        <i class="fas fa-tags"></i> Gestionar Precios
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="/admin/coaching/suscripciones" class="btn btn-outline-warning w-100 mb-2">
                                        <i class="fas fa-users"></i> Ver Suscripciones
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="/usuarios" class="btn btn-outline-success w-100 mb-2">
                                        <i class="fas fa-user-cog"></i> Gestionar Usuarios
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Datos para los gráficos
const ventasPorMes = <?php echo json_encode($datosGraficos['ventasPorMes'] ?? []); ?>;
const totalEntrenamiento = <?php echo $estadisticasEntrenamiento['total_recaudado'] ?? 0; ?>;
const totalCoaching = <?php echo $estadisticasPagosCoaching['ingresos_totales'] ?? 0; ?>;

// Gráfico de evolución de ventas
const ctxVentas = document.getElementById('chartVentas').getContext('2d');
new Chart(ctxVentas, {
    type: 'line',
    data: {
        labels: ventasPorMes.map(item => item.mes),
        datasets: [
            {
                label: 'Entrenamiento',
                data: ventasPorMes.map(item => item.ventas),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            },
            {
                label: 'Coaching',
                data: ventasPorMes.map(item => item.suscripciones),
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gráfico de distribución
const ctxDistribucion = document.getElementById('chartDistribucion').getContext('2d');
new Chart(ctxDistribucion, {
    type: 'doughnut',
    data: {
        labels: ['Entrenamiento', 'Coaching'],
        datasets: [{
            data: [totalEntrenamiento, totalCoaching],
            backgroundColor: ['#007bff', '#ffc107']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?> 