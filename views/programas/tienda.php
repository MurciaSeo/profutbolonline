<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-store me-2"></i>Tienda de Programas
                </h1>
                <a href="/programas/mis-compras" class="btn btn-outline-primary">
                    <i class="fas fa-shopping-bag me-2"></i>Mis Compras
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
    
    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-filter me-2"></i>Filtros
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary active" data-filter="todos">
                            <i class="fas fa-th me-1"></i>Todos
                        </button>
                        <button type="button" class="btn btn-outline-success" data-filter="entrenamiento">
                            <i class="fas fa-dumbbell me-1"></i>Entrenamiento
                        </button>
                        <button type="button" class="btn btn-outline-info" data-filter="coaching">
                            <i class="fas fa-graduation-cap me-1"></i>Coaching
                        </button>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $categoria): ?>
                                <button type="button" class="btn btn-outline-secondary" data-filter="<?php echo $categoria; ?>">
                                    <?php echo ucfirst($categoria); ?>
                                </button>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row" id="programas-container">
        <?php if (empty($programas)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    No hay programas disponibles para compra en este momento.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($programas as $programa): ?>
                <div class="col-md-6 col-lg-4 mb-4 programa-item" 
                     data-tipo="<?php echo $programa['tipo']; ?>" 
                     data-categoria="<?php echo $programa['categoria'] ?? ''; ?>">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header <?php echo $programa['tipo'] === 'entrenamiento' ? 'bg-success' : 'bg-info'; ?> text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <?php if ($programa['tipo'] === 'entrenamiento'): ?>
                                        <i class="fas fa-dumbbell me-2"></i>
                                    <?php else: ?>
                                        <i class="fas fa-graduation-cap me-2"></i>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($programa['nombre']); ?>
                                </h5>
                                <span class="badge bg-light text-dark">
                                    <?php echo ucfirst($programa['tipo']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <p class="card-text text-muted">
                                <?php echo htmlspecialchars($programa['descripcion']); ?>
                            </p>
                            
                            <?php if ($programa['tipo'] === 'coaching' && isset($programa['categoria'])): ?>
                                <div class="mb-3">
                                    <span class="badge bg-secondary">
                                        <?php echo ucfirst($programa['categoria']); ?>
                                    </span>
                                    <span class="badge bg-warning text-dark">
                                        <?php echo $programa['duracion_meses']; ?> meses
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($programa['tipo'] === 'entrenamiento'): ?>
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php echo $programa['duracion_semanas']; ?> semanas
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-signal me-1"></i>
                                        Nivel: <?php echo ucfirst($programa['nivel']); ?>
                                    </small>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mt-auto">
                                <?php if ($programa['ya_comprado']): ?>
                                    <div class="alert alert-success mb-0 text-center">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Ya tienes este programa
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-end">
                                            <div class="h4 text-primary mb-0">
                                                <?php if ($programa['tipo'] === 'entrenamiento'): ?>
                                                    <?php echo number_format($programa['precio'], 2); ?> €
                                                    <small class="text-muted d-block">Precio único</small>
                                                <?php else: ?>
                                                    <?php echo number_format($programa['precio_mensual'], 2); ?> €
                                                    <small class="text-muted d-block">/mes</small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <a href="/programas/comprar/<?php echo $programa['id']; ?>" 
                                           class="btn btn-primary">
                                            <i class="fas fa-shopping-cart me-2"></i>
                                            <?php echo $programa['tipo'] === 'entrenamiento' ? 'Comprar' : 'Suscribirse'; ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('[data-filter]');
    const programaItems = document.querySelectorAll('.programa-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Actualizar botones activos
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filtrar programas
            programaItems.forEach(item => {
                const tipo = item.getAttribute('data-tipo');
                const categoria = item.getAttribute('data-categoria');
                
                let show = false;
                
                if (filter === 'todos') {
                    show = true;
                } else if (filter === 'entrenamiento' && tipo === 'entrenamiento') {
                    show = true;
                } else if (filter === 'coaching' && tipo === 'coaching') {
                    show = true;
                } else if (filter === categoria) {
                    show = true;
                }
                
                if (show) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?> 