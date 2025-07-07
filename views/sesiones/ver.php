<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3><?php echo htmlspecialchars($sesion['entrenamiento_nombre']); ?></h3>
                    <?php if (!$sesion['completado']): ?>
                        <form action="/sesiones/completar/<?php echo $sesion['id']; ?>" method="POST" class="d-inline">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Marcar como Completado
                            </button>
                        </form>
                    <?php else: ?>
                        <span class="badge bg-success">Completado</span>
                    <?php endif; ?>
                </div>
                
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Detalles de la Sesión</h5>
                            <ul class="list-unstyled">
                                <li><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($sesion['fecha'])); ?></li>
                                <li><strong>Estado:</strong> 
                                    <?php if ($sesion['completado']): ?>
                                        <span class="text-success">Completado</span>
                                    <?php else: ?>
                                        <span class="text-warning">Pendiente</span>
                                    <?php endif; ?>
                                </li>
                                <li><strong>Entrenador:</strong> <?php echo htmlspecialchars($sesion['creador_nombre'] . ' ' . $sesion['creador_apellido']); ?></li>
                            </ul>
                        </div>
                    </div>

                    <?php if (!empty($sesion['entrenamiento_descripcion'])): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Descripción del Entrenamiento</h5>
                            <div class="card">
                                <div class="card-body">
                                    <?php echo nl2br(htmlspecialchars($sesion['entrenamiento_descripcion'])); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($sesion['bloques'])): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mb-4">Bloques de Ejercicios</h5>
                                <?php foreach ($sesion['bloques'] as $bloque): ?>
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <span class="badge bg-primary me-2">Bloque <?php echo $bloque['orden']; ?></span>
                                                <?php echo htmlspecialchars($bloque['nombre']); ?>
                                            </h6>
                                            <?php if (!empty($bloque['descripcion'])): ?>
                                                <small class="text-muted"><?php echo htmlspecialchars($bloque['descripcion']); ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body">
                                            <?php if (!empty($bloque['ejercicios'])): ?>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Ejercicio</th>
                                                                <th>Repeticiones</th>
                                                                <th>Tiempo</th>
                                                                <th>Descanso</th>
                                                                <th>Video</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($bloque['ejercicios'] as $ejercicio): ?>
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="<?php echo $ejercicio['tipo_icono']; ?> me-2" 
                                                                               style="color: <?php echo $ejercicio['tipo_color']; ?>"></i>
                                                                            <div>
                                                                                <strong><?php echo htmlspecialchars($ejercicio['nombre']); ?></strong>
                                                                                <?php if (!empty($ejercicio['descripcion'])): ?>
                                                                                    <br>
                                                                                    <small class="text-muted">
                                                                                        <?php echo htmlspecialchars($ejercicio['descripcion']); ?>
                                                                                    </small>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td><?php echo $ejercicio['repeticiones'] ? htmlspecialchars($ejercicio['repeticiones']) : '-'; ?></td>
                                                                    <td><?php echo $ejercicio['tiempo'] ? htmlspecialchars($ejercicio['tiempo']) . ' seg' : '-'; ?></td>
                                                                    <td><?php echo $ejercicio['tiempo_descanso'] ? htmlspecialchars($ejercicio['tiempo_descanso']) . ' seg' : '-'; ?></td>
                                                                    <td>
                                                                        <?php if (!empty($ejercicio['video_url'])): ?>
                                                                            <button type="button" class="btn btn-sm btn-info ver-video" 
                                                                                    data-video-url="<?php echo htmlspecialchars($ejercicio['video_url']); ?>">
                                                                                <i class="fas fa-play"></i> Ver
                                                                            </button>
                                                                        <?php else: ?>
                                                                            -
                                                                        <?php endif; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php else: ?>
                                                <div class="alert alert-info">
                                                    No hay ejercicios asignados a este bloque.
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($sesion['completado'] && !$sesion['valorado']): ?>
                        <div class="alert alert-info">
                            <p>¡Has completado esta sesión! Nos gustaría conocer tu opinión.</p>
                            <a href="/sesiones/valorar/<?php echo $sesion['id']; ?>" class="btn btn-primary">
                                Valorar Sesión
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="/dashboard" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para el video -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Video del Ejercicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ratio ratio-16x9">
                    <iframe id="videoFrame" src="" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
    const videoFrame = document.getElementById('videoFrame');
    
    document.querySelectorAll('.ver-video').forEach(button => {
        button.addEventListener('click', function() {
            const videoUrl = this.getAttribute('data-video-url');
            videoFrame.src = videoUrl;
            videoModal.show();
        });
    });
    
    // Limpiar el iframe cuando se cierra el modal
    document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
        videoFrame.src = '';
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?> 