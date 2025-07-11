<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestionar Bloques - <?php echo htmlspecialchars($programa['nombre']); ?></h1>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBloque">
                        <i class="fas fa-plus"></i> Nuevo Bloque
                    </button>
                    <a href="/admin/coaching/editar/<?php echo $programa['id']; ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
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
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Bloques del Programa</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($programa['bloques'])): ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-list fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No hay bloques creados</h5>
                                    <p class="text-muted">Crea el primer bloque para comenzar a estructurar el programa.</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBloque">
                                        <i class="fas fa-plus"></i> Crear Primer Bloque
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="accordion" id="accordionBloques">
                                    <?php foreach ($programa['bloques'] as $index => $bloque): ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading<?php echo $bloque['id']; ?>">
                                            <button class="accordion-button <?php echo ($index > 0) ? 'collapsed' : ''; ?>" type="button" 
                                                    data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $bloque['id']; ?>">
                                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                    <div>
                                                        <strong>Mes <?php echo $bloque['mes']; ?> - <?php echo htmlspecialchars($bloque['titulo']); ?></strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <?php echo $bloque['duracion_minutos']; ?> min | 
                                                            <?php echo ucfirst($bloque['tipo_contenido']); ?>
                                                        </small>
                                                    </div>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                                onclick="editarBloque(<?php echo htmlspecialchars(json_encode($bloque)); ?>)">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                onclick="confirmarEliminarBloque(<?php echo $bloque['id']; ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse<?php echo $bloque['id']; ?>" class="accordion-collapse collapse <?php echo ($index === 0) ? 'show' : ''; ?>" 
                                             data-bs-parent="#accordionBloques">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h6>Descripción</h6>
                                                        <p><?php echo nl2br(htmlspecialchars($bloque['descripcion'])); ?></p>
                                                        
                                                        <?php if ($bloque['url_contenido']): ?>
                                                        <h6>Contenido Externo</h6>
                                                        <p><a href="<?php echo htmlspecialchars($bloque['url_contenido']); ?>" target="_blank">
                                                            <?php echo htmlspecialchars($bloque['url_contenido']); ?>
                                                        </a></p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h6>Detalles</h6>
                                                                <ul class="list-unstyled">
                                                                    <li><strong>Orden:</strong> <?php echo $bloque['orden']; ?></li>
                                                                    <li><strong>Duración:</strong> <?php echo $bloque['duracion_minutos']; ?> min</li>
                                                                    <li><strong>Tipo:</strong> <?php echo ucfirst($bloque['tipo_contenido']); ?></li>
                                                                    <li><strong>Mes:</strong> <?php echo $bloque['mes']; ?></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <?php if ($bloque['tipo_contenido'] === 'texto' && !empty($bloque['contenido'])): ?>
                                                <div class="mt-3">
                                                    <h6>Contenido</h6>
                                                    <div class="border rounded p-3 bg-light">
                                                        <?php echo $bloque['contenido']; ?>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Información del Programa</h5>
                        </div>
                        <div class="card-body">
                            <h6><?php echo htmlspecialchars($programa['nombre']); ?></h6>
                            <p class="text-muted small"><?php echo htmlspecialchars($programa['descripcion']); ?></p>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <h5 class="text-primary"><?php echo count($programa['bloques']); ?></h5>
                                    <small class="text-muted">Bloques</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="text-success"><?php echo $programa['duracion_meses']; ?></h5>
                                    <small class="text-muted">Meses</small>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h6>Distribución por Meses</h6>
                            <?php
                            $meses = [];
                            foreach ($programa['bloques'] as $bloque) {
                                $meses[$bloque['mes']] = ($meses[$bloque['mes']] ?? 0) + 1;
                            }
                            ?>
                            <?php for ($i = 1; $i <= $programa['duracion_meses']; $i++): ?>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Mes <?php echo $i; ?></span>
                                <span class="badge bg-<?php echo isset($meses[$i]) ? 'success' : 'secondary'; ?>">
                                    <?php echo $meses[$i] ?? 0; ?> bloques
                                </span>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar bloque -->
<div class="modal fade" id="modalBloque" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBloqueTitle">Nuevo Bloque</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/coaching/guardar-bloque" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="bloque_id">
                    <input type="hidden" name="programa_coaching_id" value="<?php echo $programa['id']; ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título del Bloque *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mes" class="form-label">Mes *</label>
                                <select class="form-select" id="mes" name="mes" required>
                                    <?php for ($i = 1; $i <= $programa['duracion_meses']; $i++): ?>
                                    <option value="<?php echo $i; ?>">Mes <?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción *</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_contenido" class="form-label">Tipo de Contenido *</label>
                                <select class="form-select" id="tipo_contenido" name="tipo_contenido" required>
                                    <?php foreach ($tipos_contenido as $key => $value): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duracion_minutos" class="form-label">Duración (minutos) *</label>
                                <input type="number" class="form-control" id="duracion_minutos" name="duracion_minutos" min="1" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="orden" class="form-label">Orden *</label>
                                <input type="number" class="form-control" id="orden" name="orden" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="url_contenido" class="form-label">URL de Contenido</label>
                                <input type="url" class="form-control" id="url_contenido" name="url_contenido" 
                                       placeholder="https://ejemplo.com/video.mp4">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contenido" class="form-label">Contenido (HTML permitido)</label>
                        <textarea class="form-control" id="contenido" name="contenido" rows="8" 
                                  placeholder="<h2>Título</h2><p>Contenido del bloque...</p>"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Bloque</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editarBloque(bloque) {
    document.getElementById('modalBloqueTitle').textContent = 'Editar Bloque';
    document.getElementById('bloque_id').value = bloque.id;
    document.getElementById('titulo').value = bloque.titulo;
    document.getElementById('descripcion').value = bloque.descripcion;
    document.getElementById('mes').value = bloque.mes;
    document.getElementById('tipo_contenido').value = bloque.tipo_contenido;
    document.getElementById('duracion_minutos').value = bloque.duracion_minutos;
    document.getElementById('orden').value = bloque.orden;
    document.getElementById('url_contenido').value = bloque.url_contenido || '';
    document.getElementById('contenido').value = bloque.contenido || '';
    
    new bootstrap.Modal(document.getElementById('modalBloque')).show();
}

function confirmarEliminarBloque(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este bloque? Esta acción no se puede deshacer.')) {
        window.location.href = '/admin/coaching/eliminar-bloque/' + id;
    }
}

// Resetear modal al cerrar
document.getElementById('modalBloque').addEventListener('hidden.bs.modal', function () {
    document.getElementById('modalBloqueTitle').textContent = 'Nuevo Bloque';
    document.getElementById('bloque_id').value = '';
    document.getElementById('titulo').value = '';
    document.getElementById('descripcion').value = '';
    document.getElementById('mes').value = '1';
    document.getElementById('tipo_contenido').value = 'texto';
    document.getElementById('duracion_minutos').value = '';
    document.getElementById('orden').value = '';
    document.getElementById('url_contenido').value = '';
    document.getElementById('contenido').value = '';
});
</script>

<?php require_once 'views/layouts/footer.php'; ?> 