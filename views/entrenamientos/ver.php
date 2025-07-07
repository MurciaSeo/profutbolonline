<?php require_once 'views/layouts/header.php'; ?>



<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><?php echo htmlspecialchars($entrenamiento['nombre']); ?></h1>
                <div>
                    <?php if ($_SESSION['user_role'] === 'entrenado' && isset($sesion) && $sesion): ?>
                        <?php if (!$sesion['completado']): ?>
                            <form action="/entrenamientos/completar/<?php echo $entrenamiento['id']; ?>" method="POST" class="d-inline">
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fas fa-check"></i> Marcar como Completado
                                </button>
                            </form>
                        <?php elseif ($sesion['completado'] && !$sesion['valorado']): ?>
                            <div class="alert alert-success d-inline-block me-2 mb-0">
                                <i class="fas fa-check-circle"></i> Entrenamiento Completado
                            </div>
                            <a href="/entrenamientos/valorar/<?php echo $entrenamiento['id']; ?>" class="btn btn-info me-2">
                                <i class="fas fa-star"></i> Valorar Entrenamiento
                            </a>
                        <?php elseif ($sesion['completado'] && $sesion['valorado']): ?>
                            <div class="alert alert-success d-inline-block me-2 mb-0">
                                <i class="fas fa-check-circle"></i> Entrenamiento Completado y Valorado
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <a href="/entrenamientos" class="btn btn-secondary">Volver</a>
                    <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'entrenador'): ?>
                        <a href="/entrenamientos/editar/<?php echo $entrenamiento['id']; ?>" class="btn btn-primary">Editar</a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (isset($sesion) && $sesion): ?>
            
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt"></i> Información de Asignación
                    </h5>
                    <a href="/entrenamientos/descargarPDF/<?php echo $entrenamiento['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-download"></i> Descargar PDF
                    </a>
                </div>
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar-plus text-primary me-2"></i>
                                <strong>Fecha de Asignación:</strong>
                                <span class="ms-2"><?php echo date('d/m/Y', strtotime($sesion['fecha'])); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?php if ($sesion['completado'] && $sesion['fecha_completado']): ?>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar-check text-success me-2"></i>
                                <strong>Fecha de Finalización:</strong>
                                <span class="ms-2"><?php echo date('d/m/Y', strtotime($sesion['fecha_completado'])); ?></span>
                            </div>
                            <?php else: ?>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clock text-warning me-2"></i>
                                <strong>Estado:</strong>
                                <span class="ms-2 badge bg-warning">Pendiente de completar</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php elseif ($_SESSION['user_role'] === 'entrenado'): ?>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle text-warning"></i> Entrenamiento No Asignado
                    </h5>
                    <a href="/entrenamientos/descargarPDF/<?php echo $entrenamiento['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-download"></i> Descargar PDF
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i>
                        <strong>Este entrenamiento no está asignado a tu cuenta.</strong><br>
                        Contacta a tu entrenador para que te asigne este entrenamiento y puedas ver tu progreso.
                    </div>
                </div>
            </div>
            <?php endif; ?>

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
                            <span class="badge bg-info ms-2">
                                <?php echo (isset($bloque['serie']) ? $bloque['serie'] : 1); ?> serie<?php echo ((isset($bloque['serie']) ? $bloque['serie'] : 1) > 1 ? 's' : ''); ?>
                            </span>
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
                                            <th>Configuración</th>
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
                                            <td>
                                                <?php 
                                                $tipo_config = $ejercicio['tipo_configuracion'] ?? 'repeticiones';
                                                switch($tipo_config) {
                                                    case 'tiempo':
                                                        echo '<span class="badge bg-info me-2">Por Tiempo</span>';
                                                        echo $ejercicio['tiempo'] ? htmlspecialchars($ejercicio['tiempo']) . ' seg' : '-';
                                                        break;
                                                    case 'repeticiones_reserva':
                                                        echo '<span class="badge bg-warning me-2">Por Repeticiones + Reserva</span>';
                                                        echo $ejercicio['repeticiones'] ? htmlspecialchars($ejercicio['repeticiones']) . ' rep' : '-';
                                                        if ($ejercicio['peso_kg']) {
                                                            echo '<br><small class="text-muted">Peso: ' . htmlspecialchars($ejercicio['peso_kg']) . ' kg</small>';
                                                        }
                                                        if ($ejercicio['repeticiones_por_hacer']) {
                                                            echo '<br><small class="text-info">Rep. por hacer: ' . htmlspecialchars($ejercicio['repeticiones_por_hacer']) . '</small>';
                                                        }
                                                        break;
                                                    default: // repeticiones
                                                        echo '<span class="badge bg-primary me-2">Por Repeticiones</span>';
                                                        echo $ejercicio['repeticiones'] ? htmlspecialchars($ejercicio['repeticiones']) . ' rep' : '-';
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $ejercicio['tiempo_descanso'] ? htmlspecialchars($ejercicio['tiempo_descanso']) . ' seg' : '-'; ?></td>
                                            <td>
                                                <?php if (!empty($ejercicio['video_url'])): ?>
                                                    <?php if (!empty($sesion) && $sesion['completado'] && $_SESSION['user_role'] === 'entrenado'): ?>
                                                        <span class="text-muted small">
                                                            <i class="fas fa-lock"></i> Video bloqueado
                                                        </span>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-sm btn-info ver-video" 
                                                                data-video-url="<?php echo htmlspecialchars($ejercicio['video_url']); ?>">
                                                            <i class="fas fa-play"></i> Ver
                                                        </button>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted small">
                                                        <i class="fas fa-info-circle"></i> Sin video
                                                    </span>
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
    
    <?php if ($_SESSION['user_role'] === 'entrenado'): ?>
        <?php
        // Usar los datos de valoración pasados desde el controlador
        if (isset($valoracion) && $valoracion && $valoracion['valorado'] && $valoracion['calidad']): ?>
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-star text-warning"></i> 
                                Mi Valoración del Entrenamiento
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Calificaciones:</h6>
                                    <div class="mb-3">
                                        <label class="form-label">Calidad del entrenamiento:</label>
                                        <div class="d-flex align-items-center">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?php echo $i <= $valoracion['calidad'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                            <?php endfor; ?>
                                            <span class="ms-2"><?php echo $valoracion['calidad']; ?>/5</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Nivel de esfuerzo:</label>
                                        <div class="d-flex align-items-center">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-fire <?php echo $i <= $valoracion['esfuerzo'] ? 'text-danger' : 'text-muted'; ?>"></i>
                                            <?php endfor; ?>
                                            <span class="ms-2"><?php echo $valoracion['esfuerzo']; ?>/5</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Complejidad:</label>
                                        <div class="d-flex align-items-center">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-brain <?php echo $i <= $valoracion['complejidad'] ? 'text-info' : 'text-muted'; ?>"></i>
                                            <?php endfor; ?>
                                            <span class="ms-2"><?php echo $valoracion['complejidad']; ?>/5</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Duración:</label>
                                        <div class="d-flex align-items-center">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-clock <?php echo $i <= $valoracion['duracion'] ? 'text-success' : 'text-muted'; ?>"></i>
                                            <?php endfor; ?>
                                            <span class="ms-2"><?php echo $valoracion['duracion']; ?>/5</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6>Promedio General:</h6>
                                    <?php 
                                    $promedio = round(($valoracion['calidad'] + $valoracion['esfuerzo'] + $valoracion['complejidad'] + $valoracion['duracion']) / 4, 1);
                                    ?>
                                    <div class="display-4 text-center mb-3">
                                        <span class="text-primary"><?php echo $promedio; ?></span>
                                        <small class="text-muted">/5</small>
                                    </div>
                                    
                                    <?php if (!empty($valoracion['comentarios'])): ?>
                                        <h6>Comentarios:</h6>
                                        <div class="alert alert-light">
                                            <i class="fas fa-comment"></i>
                                            <?php echo htmlspecialchars($valoracion['comentarios']); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="text-muted small">
                                        <i class="fas fa-calendar"></i>
                                        Valorado el <?php echo date('d/m/Y H:i', strtotime($valoracion['created_at'])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
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
                    <iframe id="videoFrame" src="" allowfullscreen 
                            onload="console.log('Video loaded successfully')"
                            onerror="console.log('Error loading video')"></iframe>
                </div>
                <div id="videoError" class="alert alert-warning mt-3" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    No se pudo cargar el video. Esto puede deberse a restricciones del sitio web.
                    <br>
                    <a href="#" id="openVideoLink" target="_blank" class="btn btn-sm btn-primary mt-2">
                        <i class="fas fa-external-link-alt"></i> Abrir en YouTube
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Función para convertir URLs de YouTube al formato de embed
function convertToEmbedUrl(url) {
    if (!url) return '';
    
    // Si ya es una URL de embed, devolverla tal como está
    if (url.includes('youtube.com/embed/')) {
        return url;
    }
    
    // Extraer el ID del video de diferentes formatos de URL de YouTube
    let videoId = '';
    
    // Formato: https://www.youtube.com/watch?v=VIDEO_ID
    if (url.includes('youtube.com/watch?v=')) {
        videoId = url.split('v=')[1];
    }
    // Formato: https://youtu.be/VIDEO_ID
    else if (url.includes('youtu.be/')) {
        videoId = url.split('youtu.be/')[1];
    }
    // Formato: https://www.youtube.com/embed/VIDEO_ID
    else if (url.includes('youtube.com/embed/')) {
        videoId = url.split('embed/')[1];
    }
    
    // Limpiar parámetros adicionales
    if (videoId && videoId.includes('&')) {
        videoId = videoId.split('&')[0];
    }
    if (videoId && videoId.includes('?')) {
        videoId = videoId.split('?')[0];
    }
    
    if (videoId) {
        return `https://www.youtube.com/embed/${videoId}`;
    }
    
    return url; // Si no se puede convertir, devolver la URL original
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing video modal...');
    
    const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
    const videoFrame = document.getElementById('videoFrame');
    const videoError = document.getElementById('videoError');
    const openVideoLink = document.getElementById('openVideoLink');
    
    console.log('Video modal element:', document.getElementById('videoModal'));
    console.log('Video frame element:', videoFrame);
    
    document.querySelectorAll('.ver-video').forEach(button => {
        console.log('Found video button:', button);
        button.addEventListener('click', function() {
            const videoUrl = this.getAttribute('data-video-url');
            console.log('Original Video URL:', videoUrl);
            
            const embedUrl = convertToEmbedUrl(videoUrl);
            console.log('Converted to embed URL:', embedUrl);
            
            // Ocultar mensaje de error
            videoError.style.display = 'none';
            
            // Configurar el enlace alternativo
            openVideoLink.href = videoUrl;
            
            // Intentar cargar el video
            videoFrame.src = embedUrl;
            videoModal.show();
            
            // Verificar si el video se carga correctamente después de un tiempo
            setTimeout(() => {
                try {
                    const iframeDoc = videoFrame.contentDocument || videoFrame.contentWindow.document;
                    if (!iframeDoc || iframeDoc.body.innerHTML.includes('error') || iframeDoc.body.innerHTML.includes('denied')) {
                        console.log('Video failed to load, showing error message');
                        videoError.style.display = 'block';
                    }
                } catch (e) {
                    console.log('Cross-origin error, video might be blocked');
                    videoError.style.display = 'block';
                }
            }, 3000);
        });
    });
    
    // Limpiar el iframe cuando se cierra el modal
    document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
        console.log('Modal hidden, clearing iframe');
        videoFrame.src = '';
        videoError.style.display = 'none';
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?> 