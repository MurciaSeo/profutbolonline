<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Editar Programa de Coaching</h1>
                <a href="/admin/coaching" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información del Programa</h5>
                </div>
                <div class="card-body">
                    <form action="/admin/coaching/guardar" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $programa['id']; ?>">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del Programa *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars($programa['nombre']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción *</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?php echo htmlspecialchars($programa['descripcion']); ?></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="categoria" class="form-label">Categoría *</label>
                                            <select class="form-select" id="categoria" name="categoria" required>
                                                <option value="">Seleccionar categoría</option>
                                                <?php foreach ($categorias as $key => $value): ?>
                                                    <option value="<?php echo $key; ?>" <?php echo ($programa['categoria'] === $key) ? 'selected' : ''; ?>>
                                                        <?php echo $value; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="duracion_meses" class="form-label">Duración (meses) *</label>
                                            <input type="number" class="form-control" id="duracion_meses" name="duracion_meses" 
                                                   value="<?php echo $programa['duracion_meses']; ?>" min="1" max="24" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="precio_mensual" class="form-label">Precio Mensual *</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="precio_mensual" name="precio_mensual" 
                                                       value="<?php echo $programa['precio_mensual']; ?>" step="0.01" min="0" required>
                                                <select class="form-select" name="moneda" style="max-width: 100px;">
                                                    <option value="EUR" <?php echo ($programa['moneda'] === 'EUR') ? 'selected' : ''; ?>>EUR</option>
                                                    <option value="USD" <?php echo ($programa['moneda'] === 'USD') ? 'selected' : ''; ?>>USD</option>
                                                    <option value="GBP" <?php echo ($programa['moneda'] === 'GBP') ? 'selected' : ''; ?>>GBP</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="imagen_url" class="form-label">URL de Imagen</label>
                                            <input type="url" class="form-control" id="imagen_url" name="imagen_url" 
                                                   value="<?php echo htmlspecialchars($programa['imagen_url'] ?? ''); ?>" 
                                                   placeholder="https://ejemplo.com/imagen.jpg">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" 
                                               <?php echo ($programa['activo']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="activo">
                                            Programa activo
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Vista Previa</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <img id="preview-imagen" src="<?php echo $programa['imagen_url'] ?? '/img/placeholder.jpg'; ?>" 
                                                 class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;">
                                        </div>
                                        
                                        <h6 id="preview-nombre"><?php echo htmlspecialchars($programa['nombre']); ?></h6>
                                        <p id="preview-descripcion" class="text-muted small"><?php echo htmlspecialchars(substr($programa['descripcion'], 0, 100)); ?>...</p>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-info" id="preview-categoria"><?php echo ucfirst($programa['categoria']); ?></span>
                                            <span class="text-muted" id="preview-duracion"><?php echo $programa['duracion_meses']; ?> meses</span>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <strong id="preview-precio"><?php echo number_format($programa['precio_mensual'], 2); ?> <?php echo $programa['moneda']; ?>/mes</strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Información del Programa</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <h6 class="text-primary"><?php echo $programa['total_bloques'] ?? 0; ?></h6>
                                                <small class="text-muted">Bloques</small>
                                            </div>
                                            <div class="col-6">
                                                <h6 class="text-success"><?php echo $programa['suscripciones_activas'] ?? 0; ?></h6>
                                                <small class="text-muted">Suscripciones</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="/admin/coaching" class="btn btn-secondary">Cancelar</a>
                            <a href="/admin/coaching/bloques/<?php echo $programa['id']; ?>" class="btn btn-info">
                                <i class="fas fa-list"></i> Gestionar Bloques
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Actualizar vista previa en tiempo real
document.getElementById('nombre').addEventListener('input', function() {
    document.getElementById('preview-nombre').textContent = this.value || 'Nombre del Programa';
});

document.getElementById('descripcion').addEventListener('input', function() {
    document.getElementById('preview-descripcion').textContent = this.value || 'Descripción del programa...';
});

document.getElementById('categoria').addEventListener('change', function() {
    const categoria = this.options[this.selectedIndex].text;
    document.getElementById('preview-categoria').textContent = categoria || 'Categoría';
});

document.getElementById('duracion_meses').addEventListener('input', function() {
    document.getElementById('preview-duracion').textContent = (this.value || 0) + ' meses';
});

document.getElementById('precio_mensual').addEventListener('input', function() {
    const precio = this.value || 0;
    const moneda = document.querySelector('select[name="moneda"]').value;
    document.getElementById('preview-precio').textContent = precio + ' ' + moneda + '/mes';
});

document.querySelector('select[name="moneda"]').addEventListener('change', function() {
    const precio = document.getElementById('precio_mensual').value || 0;
    document.getElementById('preview-precio').textContent = precio + ' ' + this.value + '/mes';
});

document.getElementById('imagen_url').addEventListener('input', function() {
    const img = document.getElementById('preview-imagen');
    if (this.value) {
        img.src = this.value;
    } else {
        img.src = '/img/placeholder.jpg';
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?> 