<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-shopping-bag me-2"></i>Mis Compras y Suscripciones
                </h1>
                <a href="/programas/tienda" class="btn btn-outline-primary">
                    <i class="fas fa-store me-2"></i>Ir a la Tienda
                </a>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Programas de Entrenamiento -->
    <?php if (!empty($compras_entrenamiento)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-dumbbell me-2"></i>Programas de Entrenamiento
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($compras_entrenamiento as $compra): ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100 border-success">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="card-title mb-0">
                                                <i class="fas fa-dumbbell me-2"></i>
                                                <?php echo htmlspecialchars($compra['programa_nombre']); ?>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text text-muted">
                                                <?php echo htmlspecialchars($compra['programa_descripcion']); ?>
                                            </p>
                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <small class="text-muted">Precio:</small>
                                                    <div class="fw-bold text-success">
                                                        <?php echo number_format($compra['monto'], 2); ?> €
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Fecha:</small>
                                                    <div class="fw-bold">
                                                        <?php echo date('d/m/Y', strtotime($compra['fecha_pago'])); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-grid">
                                                <a href="/programas/ver-programa/<?php echo $compra['programacion_id']; ?>" 
                                                   class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-play me-2"></i>Ver Programa
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Suscripciones de Coaching -->
    <?php if (!empty($suscripciones_coaching)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-graduation-cap me-2"></i>Suscripciones de Coaching
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($suscripciones_coaching as $suscripcion): ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100 border-info">
                                        <div class="card-header bg-info text-white">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="card-title mb-0">
                                                    <i class="fas fa-graduation-cap me-2"></i>
                                                    <?php echo htmlspecialchars($suscripcion['programa_nombre']); ?>
                                                </h6>
                                                <span class="badge bg-light text-dark">
                                                    <?php echo ucfirst($suscripcion['estado']); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text text-muted">
                                                <?php echo htmlspecialchars($suscripcion['programa_descripcion']); ?>
                                            </p>
                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <small class="text-muted">Precio/mes:</small>
                                                    <div class="fw-bold text-info">
                                                        <?php echo number_format($suscripcion['precio_mensual'], 2); ?> €
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Mes actual:</small>
                                                    <div class="fw-bold">
                                                        <?php echo $suscripcion['mes_actual']; ?> de <?php echo $suscripcion['duracion_meses']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <small class="text-muted">Inicio:</small>
                                                    <div class="fw-bold">
                                                        <?php echo date('d/m/Y', strtotime($suscripcion['fecha_inicio'])); ?>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Próximo pago:</small>
                                                    <div class="fw-bold">
                                                        <?php echo date('d/m/Y', strtotime($suscripcion['proximo_pago'])); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-grid">
                                                <a href="/programas/ver-programa/<?php echo $suscripcion['programa_coaching_id']; ?>" 
                                                   class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-play me-2"></i>Continuar Programa
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Mensaje si no hay compras -->
    <?php if (empty($compras_entrenamiento) && empty($suscripciones_coaching)): ?>
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No tienes compras ni suscripciones</h4>
                    <p class="text-muted">Explora nuestra tienda para encontrar programas que se adapten a tus necesidades.</p>
                    <a href="/programas/tienda" class="btn btn-primary">
                        <i class="fas fa-store me-2"></i>Ir a la Tienda
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 