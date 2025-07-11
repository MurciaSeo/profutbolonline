<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-tags me-2"></i>Gestionar Precios de Programas
                </h1>
                <a href="/programas/estadisticas-ventas" class="btn btn-outline-info">
                    <i class="fas fa-chart-bar me-2"></i>Estadísticas de Ventas
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
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Precios Actuales</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($precios)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay precios configurados. Usa el formulario para añadir precios.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Programa</th>
                                        <th>Precio</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($precios as $precio): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold"><?php echo htmlspecialchars($precio['programa_nombre']); ?></div>
                                                <small class="text-muted"><?php echo htmlspecialchars($precio['programa_descripcion']); ?></small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary">
                                                    <?php echo number_format($precio['precio'], 2); ?> <?php echo $precio['moneda']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($precio['activo']): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" 
                                                        onclick="editarPrecio(<?php echo $precio['programacion_id']; ?>, '<?php echo $precio['precio']; ?>', '<?php echo $precio['moneda']; ?>', <?php echo $precio['activo']; ?>)">
                                                    <i class="fas fa-edit me-1"></i>Editar
                                                </button>
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
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Configurar Precio</h5>
                </div>
                <div class="card-body">
                    <form action="/programas/guardar-precio" method="POST">
                        <div class="mb-3">
                            <label for="programacion_id" class="form-label">Programa</label>
                            <select class="form-select" id="programacion_id" name="programacion_id" required>
                                <option value="">Seleccionar programa</option>
                                <?php foreach ($programas as $programa): ?>
                                    <option value="<?php echo $programa['id']; ?>">
                                        <?php echo htmlspecialchars($programa['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="precio" name="precio" 
                                       step="0.01" min="0" required placeholder="0.00">
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="moneda" class="form-label">Moneda</label>
                            <select class="form-select" id="moneda" name="moneda">
                                <option value="EUR" selected>EUR (Euro)</option>
                                <option value="USD">USD (Dólar)</option>
                                <option value="GBP">GBP (Libra)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="activo" name="activo" checked>
                                <label class="form-check-label" for="activo">
                                    Activo (disponible para compra)
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Precio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editarPrecio(programacionId, precio, moneda, activo) {
    document.getElementById('programacion_id').value = programacionId;
    document.getElementById('precio').value = precio;
    document.getElementById('moneda').value = moneda;
    document.getElementById('activo').checked = activo == 1;
    
    // Scroll al formulario
    document.querySelector('.col-md-4').scrollIntoView({ behavior: 'smooth' });
}
</script>

<?php require_once 'views/layouts/footer.php'; ?> 