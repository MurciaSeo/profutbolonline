<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="/programas/tienda">Tienda</a></li>
                    <li class="breadcrumb-item"><a href="/programas/mis-compras">Mis Compras</a></li>
                    <li class="breadcrumb-item"><a href="/programas/ver-programa/<?php echo $bloque['programa_coaching_id']; ?>">Programa</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($bloque['titulo']); ?></li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-graduation-cap me-2"></i>
                            <?php echo htmlspecialchars($bloque['titulo']); ?>
                        </h3>
                        <span class="badge bg-light text-dark">
                            Mes <?php echo $bloque['mes']; ?>
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="text-primary">Descripción</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($bloque['descripcion']); ?></p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clock text-info me-2"></i>
                                <span><strong>Duración:</strong> <?php echo $bloque['duracion_minutos']; ?> minutos</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-file-alt text-info me-2"></i>
                                <span><strong>Tipo:</strong> <?php echo ucfirst($bloque['tipo_contenido']); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar text-info me-2"></i>
                                <span><strong>Mes:</strong> <?php echo $bloque['mes']; ?></span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span><strong>Estado:</strong> 
                                    <?php if ($bloque['acceso']['completado']): ?>
                                        <span class="text-success">Completado</span>
                                    <?php else: ?>
                                        <span class="text-warning">En progreso</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contenido del bloque -->
                    <div class="mb-4">
                        <h5 class="text-primary">Contenido</h5>
                        <div class="card">
                            <div class="card-body">
                                <?php if ($bloque['tipo_contenido'] === 'video' && $bloque['url_contenido']): ?>
                                    <div class="ratio ratio-16x9 mb-3">
                                        <iframe src="<?php echo htmlspecialchars($bloque['url_contenido']); ?>" 
                                                title="<?php echo htmlspecialchars($bloque['titulo']); ?>"
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                allowfullscreen></iframe>
                                    </div>
                                <?php elseif ($bloque['tipo_contenido'] === 'audio' && $bloque['url_contenido']): ?>
                                    <audio controls class="w-100 mb-3">
                                        <source src="<?php echo htmlspecialchars($bloque['url_contenido']); ?>" type="audio/mpeg">
                                        Tu navegador no soporta el elemento de audio.
                                    </audio>
                                <?php elseif ($bloque['tipo_contenido'] === 'pdf' && $bloque['url_contenido']): ?>
                                    <div class="text-center mb-3">
                                        <a href="<?php echo htmlspecialchars($bloque['url_contenido']); ?>" 
                                           target="_blank" class="btn btn-info">
                                            <i class="fas fa-file-pdf me-2"></i>Ver PDF
                                        </a>
                                    </div>
                                <?php elseif ($bloque['tipo_contenido'] === 'interactivo' && $bloque['url_contenido']): ?>
                                    <div class="text-center mb-3">
                                        <a href="<?php echo htmlspecialchars($bloque['url_contenido']); ?>" 
                                           target="_blank" class="btn btn-info">
                                            <i class="fas fa-external-link-alt me-2"></i>Abrir Contenido Interactivo
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Contenido de texto -->
                                <?php if ($bloque['contenido']): ?>
                                    <div class="contenido-texto">
                                        <?php echo $bloque['contenido']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <?php if (!$bloque['acceso']['completado']): ?>
                                <button id="completar-btn" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i>Marcar como Completado
                                </button>
                            <?php else: ?>
                                <div class="alert alert-success mb-0">
                                    <i class="fas fa-check-circle me-2"></i>Bloque completado
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <a href="/programas/ver-programa/<?php echo $bloque['programa_coaching_id']; ?>" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Volver al Programa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información del Bloque
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Progreso</h6>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: <?php echo $bloque['acceso']['progreso']; ?>%"
                                 aria-valuenow="<?php echo $bloque['acceso']['progreso']; ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <?php echo $bloque['acceso']['progreso']; ?>%
                            </div>
                        </div>
                        <small class="text-muted">
                            <?php if ($bloque['acceso']['completado']): ?>
                                Completado el <?php echo date('d/m/Y', strtotime($bloque['acceso']['fecha_completado'])); ?>
                            <?php else: ?>
                                Desbloqueado el <?php echo date('d/m/Y', strtotime($bloque['acceso']['fecha_desbloqueo'])); ?>
                            <?php endif; ?>
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Características del contenido:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-<?php echo $bloque['tipo_contenido'] === 'video' ? 'video' : 'file-text'; ?> text-info me-2"></i>
                                <?php echo ucfirst($bloque['tipo_contenido']); ?>
                            </li>
                            <li><i class="fas fa-clock text-info me-2"></i>
                                <?php echo $bloque['duracion_minutos']; ?> minutos
                            </li>
                            <li><i class="fas fa-calendar text-info me-2"></i>
                                Mes <?php echo $bloque['mes']; ?>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="/programas/mis-compras" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Mis Compras
                        </a>
                        <a href="/programas/tienda" class="btn btn-outline-primary">
                            <i class="fas fa-store me-2"></i>Ir a la Tienda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const completarBtn = document.getElementById('completar-btn');
    
    if (completarBtn) {
        completarBtn.addEventListener('click', async function() {
            const button = this;
            const originalText = button.innerHTML;
            
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
            
            try {
                const response = await fetch('/programas/completar-bloque', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        bloque_id: <?php echo $bloque['id']; ?>
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Actualizar la interfaz
                    button.innerHTML = '<i class="fas fa-check me-2"></i>Completado';
                    button.classList.remove('btn-success');
                    button.classList.add('btn-secondary');
                    button.disabled = true;
                    
                    // Mostrar mensaje de éxito
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show';
                    alert.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>¡Bloque completado exitosamente!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    
                    const cardBody = button.closest('.card-body');
                    cardBody.insertBefore(alert, cardBody.firstChild);
                    
                } else {
                    throw new Error(data.error || 'Error al completar el bloque');
                }
                
            } catch (error) {
                console.error('Error:', error);
                
                // Restaurar botón
                button.disabled = false;
                button.innerHTML = originalText;
                
                // Mostrar error
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show';
                alert.innerHTML = `
                    <i class="fas fa-exclamation-triangle me-2"></i>${error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                const cardBody = button.closest('.card-body');
                cardBody.insertBefore(alert, cardBody.firstChild);
            }
        });
    }
});
</script>

<style>
.contenido-texto {
    line-height: 1.6;
}

.contenido-texto h1, .contenido-texto h2, .contenido-texto h3, 
.contenido-texto h4, .contenido-texto h5, .contenido-texto h6 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    color: #333;
}

.contenido-texto p {
    margin-bottom: 1rem;
}

.contenido-texto ul, .contenido-texto ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.contenido-texto blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
    margin: 1rem 0;
    font-style: italic;
    color: #666;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?> 