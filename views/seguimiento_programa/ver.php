<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3><?php echo htmlspecialchars($programa['nombre']); ?></h3>
                    <div class="text-muted">
                        <strong>Período:</strong> 
                        <?php echo date('d/m/Y', strtotime($asignacion['fecha_inicio'])); ?> 
                        - 
                        <?php echo $asignacion['fecha_fin'] ? date('d/m/Y', strtotime($asignacion['fecha_fin'])) : 'Sin fecha de finalización'; ?>
                    </div>
    </div>
                <div class="card-body">
                    <?php if (empty($programa['semanas'])): ?>
                        <div class="alert alert-warning">
                            No hay semanas definidas en este programa.
        </div>
                    <?php else: ?>
                        <!-- Barra de progreso -->
                        <div class="mb-4">
                            <h5>Progreso del Programa</h5>
            <?php 
                            $totalDias = 0;
                            $diasCompletados = 0;
                            foreach ($programa['semanas'] as $semana) {
                                $totalDias += count($semana['dias']);
                                foreach ($semana['dias'] as $dia) {
                                    if ($dia['completado']) {
                                        $diasCompletados++;
                                    }
                                }
                            }
                            $porcentaje = $totalDias > 0 ? round(($diasCompletados / $totalDias) * 100) : 0;
                            ?>
                            <div class="progress mb-2" style="height: 25px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: <?php echo $porcentaje; ?>%;" 
                                     aria-valuenow="<?php echo $porcentaje; ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    <?php echo $porcentaje; ?>%
        </div>
                            </div>
                            <small class="text-muted">
                                <?php echo $diasCompletados; ?> de <?php echo $totalDias; ?> días completados
                            </small>
    </div>

                        <!-- Día actual -->
                        <?php if (isset($diaActual)): ?>
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="mb-0">
                                        Día <?php echo $diaActual['dia_semana']; ?> - Semana <?php echo $diaActual['numero_semana']; ?>
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <?php if ($diaActual['entrenamiento_id']): ?>
                                        <div class="mb-3">
                                            <h5>Entrenamiento del día</h5>
                                            <p class="mb-0"><?php echo htmlspecialchars($diaActual['entrenamiento']['nombre']); ?></p>
                                        </div>
                                        
                                        <!-- Detalles del entrenamiento -->
                                        <div class="entrenamiento-detalles">
                                            <?php foreach ($diaActual['entrenamiento']['bloques'] as $bloque): ?>
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <h5 class="mb-0"><?php echo htmlspecialchars($bloque['nombre']); ?></h5>
                                                        <?php if (!empty($bloque['descripcion'])): ?>
                                                            <p class="mb-0 text-muted"><?php echo htmlspecialchars($bloque['descripcion']); ?></p>
                                    <?php endif; ?>
                                                    </div>
                                                    <div class="card-body">
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
                                                                                <?php echo htmlspecialchars($ejercicio['nombre']); ?>
                                                                                <?php if (!empty($ejercicio['descripcion'])): ?>
                                                                                    <small class="text-muted d-block"><?php echo htmlspecialchars($ejercicio['descripcion']); ?></small>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                            <td><?php echo $ejercicio['repeticiones']; ?></td>
                                                                            <td><?php echo $ejercicio['tiempo']; ?> seg</td>
                                                                            <td><?php echo $ejercicio['tiempo_descanso']; ?> seg</td>
                                                                            <td>
                                                                                <?php if (!empty($ejercicio['video_url'])): ?>
                                                                                    <button type="button" class="btn btn-sm btn-primary" 
                                                                                            data-bs-toggle="modal" 
                                                                                            data-bs-target="#videoModal"
                                                                                            data-video-url="<?php echo htmlspecialchars($ejercicio['video_url']); ?>">
                                                                                        <i class="fas fa-play"></i> Ver video
                                                                                    </button>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        
                                        <?php if (!$diaActual['completado']): ?>
                                            <form action="/seguimiento-programa/marcar-completado/<?php echo $diaActual['id']; ?>" method="POST">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check"></i> Marcar como completado
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <div class="alert alert-success">
                                                <i class="fas fa-check-circle"></i> Este día ya ha sido completado.
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="alert alert-info">
                                            No hay entrenamiento asignado para este día
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Resumen de semanas -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Resumen del Programa</h4>
                            </div>
                            <div class="card-body">
                                <?php foreach ($programa['semanas'] as $semana): ?>
                                    <div class="mb-4">
                                        <h5>Semana <?php echo $semana['numero_semana']; ?></h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Día</th>
                                                        <th>Estado</th>
                                                        <th>Valoración</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($semana['dias'] as $dia): ?>
                                                        <tr>
                                                            <td>
                                                                <?php if ($dia['entrenamiento_id']): ?>
                                                                    <?php if ($dia['completado'] || isset($dia['es_actual'])): ?>
                                                                        <a href="#" class="ver-entrenamiento" 
                                                                           data-bs-toggle="modal" 
                                                                           data-bs-target="#entrenamientoModal"
                                                                           data-entrenamiento='<?php echo json_encode($dia['entrenamiento']); ?>'>
                                                                            Día <?php echo $dia['dia_semana']; ?>
                                                                            <i class="fas fa-eye ms-1"></i>
                                                                        </a>
                                                                    <?php else: ?>
                                                                        <span class="text-muted">
                                                                            Día <?php echo $dia['dia_semana']; ?>
                                                                            <i class="fas fa-lock ms-1"></i>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    Día <?php echo $dia['dia_semana']; ?>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($dia['completado']): ?>
                                                                    <span class="badge bg-success">Completado</span>
                                                                <?php elseif (isset($dia['es_actual'])): ?>
                                                                    <span class="badge bg-primary">Día actual</span>
                                                                <?php elseif (isset($dia['bloqueado'])): ?>
                                                                    <span class="badge bg-secondary">Bloqueado</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-warning">Pendiente</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($dia['completado'] && isset($dia['valoracion'])): ?>
                                                                    <div class="d-flex">
                                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                            <i class="fas fa-star <?php echo $i <= $dia['valoracion'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                                                        <?php endfor; ?>
                                                                    </div>
                                                                <?php elseif ($dia['completado']): ?>
                                                                    <span class="text-muted">Sin valorar</span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para videos -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Video del ejercicio</h5>
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

<!-- Modal para ver entrenamiento -->
<div class="modal fade" id="entrenamientoModal" tabindex="-1" aria-labelledby="entrenamientoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="entrenamientoModalLabel">Detalles del Entrenamiento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="entrenamiento-detalles"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoModal = document.getElementById('videoModal');
    const videoFrame = document.getElementById('videoFrame');
    
    videoModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const videoUrl = button.getAttribute('data-video-url');
        videoFrame.src = videoUrl;
    });
    
    videoModal.addEventListener('hidden.bs.modal', function() {
        videoFrame.src = '';
    });

    // Nuevo script para el modal de entrenamiento
    document.querySelectorAll('.ver-entrenamiento').forEach(function(button) {
        button.addEventListener('click', function() {
            const entrenamiento = JSON.parse(this.getAttribute('data-entrenamiento'));
            let html = `<h4>${entrenamiento.nombre}</h4>`;
            
            if (entrenamiento.descripcion) {
                html += `<p class="text-muted">${entrenamiento.descripcion}</p>`;
            }

            if (entrenamiento.bloques && entrenamiento.bloques.length > 0) {
                entrenamiento.bloques.forEach(function(bloque) {
                    html += `
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">${bloque.nombre}</h5>
                                ${bloque.descripcion ? `<p class="mb-0 text-muted">${bloque.descripcion}</p>` : ''}
                            </div>
                            <div class="card-body">
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
                                        <tbody>`;

                    bloque.ejercicios.forEach(function(ejercicio) {
                        html += `
                            <tr>
                                <td>
                                    ${ejercicio.nombre}
                                    ${ejercicio.descripcion ? `<small class="text-muted d-block">${ejercicio.descripcion}</small>` : ''}
                                </td>
                                <td>${ejercicio.repeticiones || '-'}</td>
                                <td>${ejercicio.tiempo ? ejercicio.tiempo + ' seg' : '-'}</td>
                                <td>${ejercicio.tiempo_descanso ? ejercicio.tiempo_descanso + ' seg' : '-'}</td>
                                <td>
                                    ${ejercicio.video_url ? `
                                        <button type="button" class="btn btn-sm btn-primary ver-video" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#videoModal"
                                                data-video-url="${ejercicio.video_url}">
                                            <i class="fas fa-play"></i> Ver video
                                        </button>
                                    ` : '-'}
                                </td>
                            </tr>`;
                    });

                    html += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>`;
                });
            } else {
                html += '<div class="alert alert-info">No hay ejercicios definidos para este entrenamiento.</div>';
            }

            document.getElementById('entrenamiento-detalles').innerHTML = html;
        });
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?> 