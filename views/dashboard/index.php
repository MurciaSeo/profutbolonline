<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Panel de Control</h2>
                <?php if ($rol === 'entrenador'): ?>
                    <div>
                        <a href="/ejercicios/crear" class="btn btn-success me-2">
                            <i class="fas fa-plus"></i> Nuevo Ejercicio
                        </a>
                        <a href="/entrenamientos/crear" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nuevo Entrenamiento
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($rol === 'admin'): ?>
    <!-- Tarjetas de métricas principales -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Usuarios</h6>
                            <h2 class="mb-0"><?php echo $total_usuarios; ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-white-50">Último mes: +<?php echo $usuarios_nuevos_mes; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Entrenamientos Completados</h6>
                            <h2 class="mb-0"><?php echo $entrenamientos_completados; ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-white-50">Tasa de éxito: <?php echo number_format($tasa_completitud, 1); ?>%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-info text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Tasa de Completitud</h6>
                            <h2 class="mb-0"><?php echo number_format($tasa_completitud, 1); ?>%</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-white-50">Meta: 85%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Usuarios Activos</h6>
                            <h2 class="mb-0"><?php echo $usuarios_activos_mes; ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-user-clock fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-white-50">Últimos 30 días</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y tablas -->
    <div class="row">
        <!-- Gráfico de entrenamientos por mes -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Entrenamientos por Mes</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="#">Ver Detalles</a></li>
                            <li><a class="dropdown-item" href="#">Exportar Datos</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="entrenamientosPorMes"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de distribución de usuarios -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribución de Usuarios</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="distribucionUsuarios"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="me-2">
                            <i class="fas fa-circle text-primary"></i> Entrenadores
                        </span>
                        <span class="me-2">
                            <i class="fas fa-circle text-success"></i> Entrenados
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tablas de datos -->
    <div class="row">
        <!-- Últimos usuarios registrados -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Últimos Usuarios Registrados</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                    <th>Fecha Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ultimos_usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $usuario['rol'] === 'admin' ? 'danger' : 
                                                ($usuario['rol'] === 'entrenador' ? 'primary' : 'success'); 
                                        ?>">
                                            <?php echo ucfirst($usuario['rol']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($usuario['created_at'])); ?></td>
                                    <td>
                                        <a href="/usuarios/perfil/<?php echo $usuario['id']; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Entrenamientos recientes -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Entrenamientos Recientes</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Entrenamiento</th>
                                    <th>Usuario</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($entrenamientos_recientes as $entrenamiento): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($entrenamiento['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($entrenamiento['usuario']); ?></td>
                                    <td><?php echo isset($entrenamiento['fecha']) ? date('d/m/Y', strtotime($entrenamiento['fecha'])) : 'N/A'; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo isset($entrenamiento['completado']) && $entrenamiento['completado'] ? 'success' : 'primary'; ?>">
                                            <?php echo isset($entrenamiento['completado']) && $entrenamiento['completado'] ? 'Completado' : 'En Progreso'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($rol === 'entrenador'): ?>
    <!-- Métricas del entrenador -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Entrenados</h6>
                            <h2 class="mb-0"><?php echo $total_entrenados; ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Entrenamientos Completados</h6>
                            <h2 class="mb-0"><?php echo $entrenamientos_completados; ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-info text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Programas Activos</h6>
                            <h2 class="mb-0"><?php echo isset($total_programas_activos) ? $total_programas_activos : $programas_activos; ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Valoración Promedio</h6>
                            <h2 class="mb-0"><?php echo number_format($valoracion_promedio, 1); ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tablas de datos del entrenador -->
    <div class="row">
        <!-- Entrenados -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mis Entrenados</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Entrenamientos</th>
                                    <th>Última Actividad</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($entrenados as $entrenado): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($entrenado['nombre'] . ' ' . $entrenado['apellido']); ?></td>
                                    <td>
                                        <span class="badge bg-success"><?php echo $entrenado['completados']; ?></span> /
                                        <span class="badge bg-primary"><?php echo $entrenado['total']; ?></span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($entrenado['ultima_actividad'])); ?></td>
                                    <td>
                                        <a href="/usuarios/perfil/<?php echo $entrenado['id']; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Entrenamientos Completados -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Entrenamientos Completados</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Entrenamiento</th>
                                    <th>Entrenado</th>
                                    <th>Fecha</th>
                                    <th>Valoración</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($entrenamientos_completados as $entrenamiento): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($entrenamiento['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($entrenamiento['entrenado']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($entrenamiento['fecha_completado'])); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?php echo $i <= $entrenamiento['valoracion'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($rol === 'entrenado'): ?>
    <!-- Métricas del entrenado -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Entrenamientos</h6>
                            <h2 class="mb-0"><?php echo $total_entrenamientos; ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-dumbbell fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Completados</h6>
                            <h2 class="mb-0"><?php echo $entrenamientos_completados; ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-info text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Programas Activos</h6>
                            <h2 class="mb-0"><?php echo isset($total_programas_activos) ? $total_programas_activos : $programas_activos; ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-white h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Progreso General</h6>
                            <h2 class="mb-0"><?php echo number_format($progreso_general, 1); ?>%</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tablas de datos del entrenado -->
    <div class="row">
        <!-- Próximos Entrenamientos -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Próximos Entrenamientos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Entrenamiento</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($proximos_entrenamientos as $entrenamiento): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($entrenamiento['nombre']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($entrenamiento['fecha'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $entrenamiento['completado'] ? 'success' : 'primary'; ?>">
                                            <?php echo $entrenamiento['completado'] ? 'Completado' : 'Pendiente'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/entrenamientos/ver/<?php echo $entrenamiento['id']; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Programas Activos -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Programas Activos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Programa</th>
                                    <th>Progreso</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($programas_activos as $programa): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($programa['nombre']); ?></td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: <?php echo $programa['progreso']; ?>%"
                                                 aria-valuenow="<?php echo $programa['progreso']; ?>" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                <?php echo $programa['progreso']; ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $programa['estado'] === 'activo' ? 'success' : 
                                                ($programa['estado'] === 'completado' ? 'primary' : 'warning'); 
                                        ?>">
                                            <?php echo ucfirst($programa['estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/programaciones/ver/<?php echo $programa['id']; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Scripts para los gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Función para verificar si un elemento existe antes de crear el gráfico
function createChartIfElementExists(elementId, chartConfig) {
    const element = document.getElementById(elementId);
    if (element) {
        const ctx = element.getContext('2d');
        return new Chart(ctx, chartConfig);
    }
    return null;
}

// Gráfico de entrenamientos por mes (solo para admin)
const entrenamientosChart = createChartIfElementExists('entrenamientosPorMes', {
    type: 'line',
    data: {
        labels: <?php echo json_encode(isset($entrenamientos_por_mes) && is_array($entrenamientos_por_mes) ? array_column($entrenamientos_por_mes, 'mes') : []); ?>,
        datasets: [{
            label: 'Entrenamientos',
            data: <?php echo json_encode(isset($entrenamientos_por_mes) && is_array($entrenamientos_por_mes) ? array_column($entrenamientos_por_mes, 'total') : []); ?>,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Gráfico de distribución de usuarios (solo para admin)
const usuariosChart = createChartIfElementExists('distribucionUsuarios', {
    type: 'doughnut',
    data: {
        labels: ['Entrenadores', 'Entrenados'],
        datasets: [{
            data: [<?php echo isset($total_entrenadores) ? $total_entrenadores : 0; ?>, <?php echo isset($total_entrenados) ? $total_entrenados : 0; ?>],
            backgroundColor: ['rgb(54, 162, 235)', 'rgb(75, 192, 192)']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.bg-opacity-25 {
    background-color: rgba(255, 255, 255, 0.25);
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.badge {
    padding: 0.5em 0.8em;
    font-weight: 500;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?> 