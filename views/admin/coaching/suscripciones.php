<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Suscripciones de Coaching</h1>
                <a href="/admin/coaching" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Todas las Suscripciones</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Programa</th>
                                    <th>Estado</th>
                                    <th>Mes Actual</th>
                                    <th>Fecha Inicio</th>
                                    <th>Último Pago</th>
                                    <th>Próximo Pago</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($suscripciones as $suscripcion): ?>
                                <tr>
                                    <td><?php echo $suscripcion['id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($suscripcion['nombre_usuario'] . ' ' . $suscripcion['apellido_usuario']); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo $suscripcion['email_usuario']; ?></small>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($suscripcion['nombre_programa']); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo ucfirst($suscripcion['categoria']); ?></small>
                                    </td>
                                    <td>
                                        <?php
                                        $estadoClass = [
                                            'activa' => 'bg-success',
                                            'cancelada' => 'bg-danger',
                                            'pausada' => 'bg-warning',
                                            'expirada' => 'bg-secondary'
                                        ];
                                        $estadoClass = $estadoClass[$suscripcion['estado']] ?? 'bg-secondary';
                                        ?>
                                        <span class="badge <?php echo $estadoClass; ?>">
                                            <?php echo ucfirst($suscripcion['estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">Mes <?php echo $suscripcion['mes_actual']; ?></span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($suscripcion['fecha_inicio'])); ?></td>
                                    <td>
                                        <?php if ($suscripcion['ultimo_pago']): ?>
                                            <?php echo date('d/m/Y H:i', strtotime($suscripcion['ultimo_pago'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($suscripcion['proximo_pago']): ?>
                                            <?php echo date('d/m/Y', strtotime($suscripcion['proximo_pago'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="verDetalles(<?php echo $suscripcion['id']; ?>)" title="Ver Detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                    onclick="cambiarEstado(<?php echo $suscripcion['id']; ?>)" title="Cambiar Estado">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-success"><?php echo count(array_filter($suscripciones, fn($s) => $s['estado'] === 'activa')); ?></h4>
                            <small class="text-muted">Suscripciones Activas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-warning"><?php echo count(array_filter($suscripciones, fn($s) => $s['estado'] === 'pausada')); ?></h4>
                            <small class="text-muted">Suscripciones Pausadas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-danger"><?php echo count(array_filter($suscripciones, fn($s) => $s['estado'] === 'cancelada')); ?></h4>
                            <small class="text-muted">Suscripciones Canceladas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-primary"><?php echo count($suscripciones); ?></h4>
                            <small class="text-muted">Total Suscripciones</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="modalDetalles" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Suscripción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesContenido">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>

<script>
function verDetalles(id) {
    // Aquí podrías hacer una llamada AJAX para obtener los detalles
    // Por ahora solo mostramos un mensaje
    document.getElementById('detallesContenido').innerHTML = `
        <div class="text-center">
            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
            <p class="mt-2">Cargando detalles...</p>
        </div>
    `;
    
    new bootstrap.Modal(document.getElementById('modalDetalles')).show();
}

function cambiarEstado(id) {
    const nuevoEstado = prompt('Nuevo estado (activa, pausada, cancelada, expirada):');
    if (nuevoEstado && ['activa', 'pausada', 'cancelada', 'expirada'].includes(nuevoEstado)) {
        if (confirm(`¿Estás seguro de cambiar el estado a "${nuevoEstado}"?`)) {
            // Aquí podrías hacer una llamada AJAX para actualizar el estado
            alert('Función de cambio de estado en desarrollo');
        }
    }
}
</script>

<?php require_once 'views/layouts/footer.php'; ?> 