<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Estadísticas de Coaching</h1>
                <a href="/admin/coaching" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            
            <!-- Resumen general -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-graduation-cap fa-2x text-primary mb-2"></i>
                            <h4 class="text-primary"><?php echo $estadisticas['total_programas'] ?? 0; ?></h4>
                            <small class="text-muted">Programas Activos</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-users fa-2x text-success mb-2"></i>
                            <h4 class="text-success"><?php echo $estadisticas['total_suscripciones'] ?? 0; ?></h4>
                            <small class="text-muted">Suscripciones Totales</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                            <h4 class="text-info"><?php echo number_format($estadisticas['ingresos_totales'] ?? 0, 2); ?> €</h4>
                            <small class="text-muted">Ingresos Totales</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-calendar-check fa-2x text-warning mb-2"></i>
                            <h4 class="text-warning"><?php echo $estadisticas['suscripciones_activas'] ?? 0; ?></h4>
                            <small class="text-muted">Suscripciones Activas</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Estadísticas por programa -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Estadísticas por Programa</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Programa</th>
                                    <th>Categoría</th>
                                    <th>Suscripciones</th>
                                    <th>Activas</th>
                                    <th>Ingresos</th>
                                    <th>Precio Mensual</th>
                                    <th>Duración</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($programas as $programa): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($programa['nombre']); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo ucfirst($programa['categoria']); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo $programa['total_suscripciones'] ?? 0; ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success"><?php echo $programa['suscripciones_activas'] ?? 0; ?></span>
                                    </td>
                                    <td>
                                        <strong><?php echo number_format($programa['ingresos_totales'] ?? 0, 2); ?> €</strong>
                                    </td>
                                    <td>
                                        <?php echo number_format($programa['precio_mensual'], 2); ?> <?php echo $programa['moneda']; ?>
                                    </td>
                                    <td>
                                        <?php echo $programa['duracion_meses']; ?> meses
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Gráficos y análisis -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Distribución por Categoría</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartCategorias" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Estado de Suscripciones</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chartEstados" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Análisis de rendimiento -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Análisis de Rendimiento</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>Programa Más Popular</h6>
                                    <?php 
                                    $programaPopular = null;
                                    $maxSuscripciones = 0;
                                    foreach ($programas as $programa) {
                                        if (($programa['total_suscripciones'] ?? 0) > $maxSuscripciones) {
                                            $maxSuscripciones = $programa['total_suscripciones'];
                                            $programaPopular = $programa;
                                        }
                                    }
                                    ?>
                                    <?php if ($programaPopular): ?>
                                    <p class="text-primary">
                                        <strong><?php echo htmlspecialchars($programaPopular['nombre']); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo $programaPopular['total_suscripciones']; ?> suscripciones</small>
                                    </p>
                                    <?php else: ?>
                                    <p class="text-muted">No hay datos suficientes</p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-4">
                                    <h6>Categoría Más Demandada</h6>
                                    <?php
                                    $categorias = [];
                                    foreach ($programas as $programa) {
                                        $categoria = $programa['categoria'];
                                        $categorias[$categoria] = ($categorias[$categoria] ?? 0) + ($programa['total_suscripciones'] ?? 0);
                                    }
                                    $categoriaPopular = array_keys($categorias, max($categorias))[0] ?? null;
                                    ?>
                                    <?php if ($categoriaPopular): ?>
                                    <p class="text-success">
                                        <strong><?php echo ucfirst($categoriaPopular); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo $categorias[$categoriaPopular]; ?> suscripciones</small>
                                    </p>
                                    <?php else: ?>
                                    <p class="text-muted">No hay datos suficientes</p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-4">
                                    <h6>Ingresos Promedio</h6>
                                    <?php
                                    $ingresosTotales = array_sum(array_column($programas, 'ingresos_totales'));
                                    $programasActivos = count(array_filter($programas, fn($p) => ($p['total_suscripciones'] ?? 0) > 0));
                                    $ingresosPromedio = $programasActivos > 0 ? $ingresosTotales / $programasActivos : 0;
                                    ?>
                                    <p class="text-info">
                                        <strong><?php echo number_format($ingresosPromedio, 2); ?> €</strong>
                                        <br>
                                        <small class="text-muted">por programa activo</small>
                                    </p>
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
const categorias = <?php echo json_encode(array_keys($categorias ?? [])); ?>;
const datosCategorias = <?php echo json_encode(array_values($categorias ?? [])); ?>;

const estados = ['Activas', 'Pausadas', 'Canceladas', 'Expiradas'];
const datosEstados = [
    <?php echo $estadisticas['suscripciones_activas'] ?? 0; ?>,
    <?php echo $estadisticas['suscripciones_pausadas'] ?? 0; ?>,
    <?php echo $estadisticas['suscripciones_canceladas'] ?? 0; ?>,
    <?php echo $estadisticas['suscripciones_expiradas'] ?? 0; ?>
];

// Gráfico de categorías
const ctxCategorias = document.getElementById('chartCategorias').getContext('2d');
new Chart(ctxCategorias, {
    type: 'doughnut',
    data: {
        labels: categorias,
        datasets: [{
            data: datosCategorias,
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40'
            ]
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

// Gráfico de estados
const ctxEstados = document.getElementById('chartEstados').getContext('2d');
new Chart(ctxEstados, {
    type: 'bar',
    data: {
        labels: estados,
        datasets: [{
            label: 'Suscripciones',
            data: datosEstados,
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#dc3545',
                '#6c757d'
            ]
        }]
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
</script>

<?php require_once 'views/layouts/footer.php'; ?> 