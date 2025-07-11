<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestión de Programas de Coaching</h1>
                <a href="/admin/coaching/crear" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Programa
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
                    <h5 class="card-title mb-0">Programas de Coaching</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Duración</th>
                                    <th>Precio</th>
                                    <th>Estado</th>
                                    <th>Suscripciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($programas as $programa): ?>
                                <tr>
                                    <td><?php echo $programa['id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($programa['nombre']); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo htmlspecialchars(substr($programa['descripcion'], 0, 100)); ?>...</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo ucfirst($programa['categoria']); ?></span>
                                    </td>
                                    <td><?php echo $programa['duracion_meses']; ?> meses</td>
                                    <td><?php echo number_format($programa['precio_mensual'], 2); ?> <?php echo $programa['moneda']; ?>/mes</td>
                                    <td>
                                        <?php if ($programa['activo']): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo $programa['total_suscripciones'] ?? 0; ?> total</span>
                                        <span class="badge bg-success"><?php echo $programa['suscripciones_activas'] ?? 0; ?> activas</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/admin/coaching/editar/<?php echo $programa['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/admin/coaching/bloques/<?php echo $programa['id']; ?>" 
                                               class="btn btn-sm btn-outline-info" title="Gestionar Bloques">
                                                <i class="fas fa-list"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmarEliminar(<?php echo $programa['id']; ?>)" title="Eliminar">
                                                <i class="fas fa-trash"></i>
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
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Acciones Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="/admin/coaching/suscripciones" class="btn btn-outline-primary">
                                    <i class="fas fa-users"></i> Ver Suscripciones
                                </a>
                                <a href="/admin/coaching/estadisticas" class="btn btn-outline-info">
                                    <i class="fas fa-chart-bar"></i> Ver Estadísticas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Resumen</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-primary"><?php echo count($programas); ?></h4>
                                    <small class="text-muted">Programas</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success"><?php echo array_sum(array_column($programas, 'suscripciones_activas')); ?></h4>
                                    <small class="text-muted">Suscripciones Activas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminar(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este programa? Esta acción no se puede deshacer.')) {
        window.location.href = '/admin/coaching/eliminar/' + id;
    }
}
</script>

<?php require_once 'views/layouts/footer.php'; ?> 