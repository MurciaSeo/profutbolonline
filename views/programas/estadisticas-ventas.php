<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Estadísticas de Ventas
                </h1>
                <a href="/programas/gestionar-precios" class="btn btn-outline-primary">
                    <i class="fas fa-tags me-2"></i>Gestionar Precios
                </a>
            </div>
        </div>
    </div>
    
    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo $estadisticas['total_pagos'] ?? 0; ?></h4>
                            <small>Total de Pagos</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-credit-card fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo $estadisticas['pagos_completados'] ?? 0; ?></h4>
                            <small>Pagos Completados</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo $estadisticas['pagos_pendientes'] ?? 0; ?></h4>
                            <small>Pagos Pendientes</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo number_format($estadisticas['total_recaudado'] ?? 0, 2); ?> €</h4>
                            <small>Total Recaudado</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-euro-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gráfico de estados -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Estados de Pagos</h5>
                </div>
                <div class="card-body">
                    <canvas id="estadosChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Resumen</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-success"><?php echo $estadisticas['pagos_completados'] ?? 0; ?></h3>
                                <small class="text-muted">Completados</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-warning"><?php echo $estadisticas['pagos_pendientes'] ?? 0; ?></h3>
                                <small class="text-muted">Pendientes</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-danger"><?php echo $estadisticas['pagos_fallidos'] ?? 0; ?></h3>
                                <small class="text-muted">Fallidos</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-info"><?php echo number_format($estadisticas['total_recaudado'] ?? 0, 2); ?> €</h3>
                                <small class="text-muted">Recaudado</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla de pagos recientes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pagos Recientes</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($pagos_recientes)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay pagos registrados.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Programa</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($pagos_recientes, 0, 10) as $pago): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold"><?php echo htmlspecialchars($pago['usuario_nombre'] . ' ' . $pago['usuario_apellido']); ?></div>
                                                <small class="text-muted">ID: <?php echo $pago['usuario_id']; ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?php echo htmlspecialchars($pago['programa_nombre']); ?></div>
                                                <small class="text-muted"><?php echo htmlspecialchars($pago['programa_descripcion']); ?></small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary">
                                                    <?php echo number_format($pago['monto'], 2); ?> <?php echo $pago['moneda']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $estado_class = '';
                                                $estado_text = '';
                                                switch ($pago['estado']) {
                                                    case 'completado':
                                                        $estado_class = 'success';
                                                        $estado_text = 'Completado';
                                                        break;
                                                    case 'pendiente':
                                                        $estado_class = 'warning';
                                                        $estado_text = 'Pendiente';
                                                        break;
                                                    case 'fallido':
                                                        $estado_class = 'danger';
                                                        $estado_text = 'Fallido';
                                                        break;
                                                    case 'cancelado':
                                                        $estado_class = 'secondary';
                                                        $estado_text = 'Cancelado';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge bg-<?php echo $estado_class; ?>">
                                                    <?php echo $estado_text; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div><?php echo date('d/m/Y', strtotime($pago['created_at'])); ?></div>
                                                <small class="text-muted"><?php echo date('H:i', strtotime($pago['created_at'])); ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de estados de pagos
const ctx = document.getElementById('estadosChart').getContext('2d');
const estadosChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Completados', 'Pendientes', 'Fallidos'],
        datasets: [{
            data: [
                <?php echo $estadisticas['pagos_completados'] ?? 0; ?>,
                <?php echo $estadisticas['pagos_pendientes'] ?? 0; ?>,
                <?php echo $estadisticas['pagos_fallidos'] ?? 0; ?>
            ],
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#dc3545'
            ],
            borderWidth: 2,
            borderColor: '#fff'
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