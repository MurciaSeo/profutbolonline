<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><?php echo htmlspecialchars($entrenamiento['nombre']); ?></h1>
                <div>
                    <a href="/entrenamientos" class="btn btn-secondary">Volver</a>
                    <a href="/entrenamientos/editar/<?php echo $entrenamiento['id']; ?>" class="btn btn-primary">Editar</a>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detalles del Entrenamiento</h5>
                    <a href="/entrenamientos/descargarPDF/<?php echo $entrenamiento['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-download"></i> Descargar PDF
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($entrenamiento['notas'])): ?>
                    <h5 class="card-title">Notas</h5>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($entrenamiento['notas'])); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($entrenamiento['bloques'])): ?>
                <?php 
                // Ordenar bloques por el campo 'orden'
                usort($entrenamiento['bloques'], function($a, $b) {
                    return $a['orden'] - $b['orden'];
                });
                
                foreach ($entrenamiento['bloques'] as $bloque): 
                ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <span class="badge bg-primary me-2">Bloque <?php echo $bloque['orden']; ?></span>
                            <?php echo htmlspecialchars($bloque['nombre']); ?>
                        </h5>
                        <?php if (!empty($bloque['descripcion'])): ?>
                            <small class="text-muted"><?php echo htmlspecialchars($bloque['descripcion']); ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($bloque['ejercicios'])): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
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
                                                <strong><?php echo htmlspecialchars($ejercicio['nombre']); ?></strong>
                                                <?php if (!empty($ejercicio['descripcion'])): ?>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($ejercicio['descripcion']); ?></small>
                                                <?php endif; ?>
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
            <?php else: ?>
                <div class="alert alert-info">
                    No hay bloques de ejercicios definidos para este entrenamiento.
                </div>
            <?php endif; ?>
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