<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="fas fa-dumbbell me-2"></i><?php echo htmlspecialchars($programacion['nombre']); ?></h3>
                        <div>
                            <a href="/programaciones" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Volver
                            </a>
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <a href="/programaciones/editar/<?php echo $programacion['id']; ?>" class="btn btn-light btn-sm ms-2">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Información general con diseño mejorado -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>Información del Programa</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3"><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($programacion['descripcion'])); ?></p>
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded me-2">
                                                    <i class="fas fa-calendar-alt text-primary"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Duración</small>
                                                    <strong><?php echo $programacion['duracion_semanas']; ?> semanas</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded me-2">
                                                    <i class="fas fa-fire text-primary"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Entrenamientos/semana</small>
                                                    <strong><?php echo $programacion['entrenamientos_por_semana']; ?></strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded me-2">
                                                    <i class="fas fa-chart-line text-primary"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Nivel</small>
                                                    <strong><?php echo ucfirst($programacion['nivel']); ?></strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded me-2">
                                                    <i class="fas fa-bullseye text-primary"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Objetivo</small>
                                                    <strong><?php echo htmlspecialchars($programacion['objetivo']); ?></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calendario de entrenamientos -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-calendar-week me-2 text-primary"></i>Calendario de Entrenamientos</h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if (!empty($programacion['semanas'])): ?>
                                <div class="accordion" id="programaAccordion">
                                    <?php foreach ($programacion['semanas'] as $index => $semana): ?>
                                        <div class="accordion-item border-0 mb-2">
                                            <h2 class="accordion-header" id="heading<?php echo $semana['numero_semana']; ?>">
                                                <button class="accordion-button <?php echo $index === 0 ? '' : 'collapsed'; ?>" type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#collapse<?php echo $semana['numero_semana']; ?>" 
                                                        aria-expanded="<?php echo $index === 0 ? 'true' : 'false'; ?>" 
                                                        aria-controls="collapse<?php echo $semana['numero_semana']; ?>">
                                                    <div class="d-flex align-items-center w-100">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                            <span class="fw-bold"><?php echo $semana['numero_semana']; ?></span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">Semana <?php echo $semana['numero_semana']; ?></h6>
                                                            <small class="text-muted"><?php echo count($semana['dias']); ?> entrenamientos programados</small>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse<?php echo $semana['numero_semana']; ?>" 
                                                 class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" 
                                                 aria-labelledby="heading<?php echo $semana['numero_semana']; ?>" 
                                                 data-bs-parent="#programaAccordion">
                                                <div class="accordion-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover mb-0">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th style="width: 15%">Día</th>
                                                                    <th style="width: 45%">Entrenamiento</th>
                                                                    <th style="width: 25%">Estado</th>
                                                                    <th style="width: 15%">Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($semana['dias'] as $dia): ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="bg-secondary bg-opacity-10 p-2 rounded me-2">
                                                                                    <i class="fas fa-calendar-day text-secondary"></i>
                                                                                </div>
                                                                                <div>
                                                                                    <strong><?php 
                                                                                        $dias_semana = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];
                                                                                        echo $dias_semana[$dia['dia_semana']]; 
                                                                                    ?></strong>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <h6 class="mb-0"><?php echo htmlspecialchars($dia['nombre_entrenamiento']); ?></h6>
                                                                        </td>
                                                                        <td>
                                                                            <?php if ($_SESSION['user_role'] === 'entrenado'): ?>
                                                                                <?php
                                                                                    $mi_sesion = null;
                                                                                    if (isset($dia['estados_sesiones'])) {
                                                                                        foreach ($dia['estados_sesiones'] as $sesion) {
                                                                                            if ($sesion['usuario_id'] == $_SESSION['user_id']) {
                                                                                                $mi_sesion = $sesion;
                                                                                                break;
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                ?>
                                                                                <div class="d-flex align-items-center">
                                                                                    <?php if ($mi_sesion): ?>
                                                                                        <span class="badge <?php echo $mi_sesion['completado'] ? 'bg-success' : 'bg-secondary'; ?> me-2">
                                                                                            <?php echo $mi_sesion['completado'] ? 'Completado' : 'Pendiente'; ?>
                                                                                        </span>
                                                                                        <?php if ($mi_sesion['valorado']): ?>
                                                                                            <span class="badge bg-info badge-sm">
                                                                                                <i class="fas fa-star"></i> Valorado
                                                                                            </span>
                                                                                        <?php endif; ?>
                                                                                    <?php else: ?>
                                                                                        <span class="badge bg-warning">
                                                                                            Sin sesión
                                                                                        </span>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            <?php else: ?>
                                                                                <?php if (isset($dia['estadisticas']) && $dia['estadisticas']['total_sesiones'] > 0): ?>
                                                                                    <div class="d-flex align-items-center">
                                                                                        <div class="me-2">
                                                                                            <div class="progress" style="width: 60px; height: 8px;">
                                                                                                <div class="progress-bar bg-success" 
                                                                                                     style="width: <?php echo $dia['estadisticas']['porcentaje_completado']; ?>%"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div>
                                                                                            <small class="text-muted d-block">
                                                                                                <?php echo $dia['estadisticas']['sesiones_completadas']; ?>/<?php echo $dia['estadisticas']['total_sesiones']; ?> completado
                                                                                            </small>
                                                                                            <small class="text-success fw-bold">
                                                                                                <?php echo $dia['estadisticas']['porcentaje_completado']; ?>%
                                                                                            </small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <?php if (isset($dia['estados_sesiones']) && !empty($dia['estados_sesiones'])): ?>
                                                                                        <div class="mt-1">
                                                                                            <small class="text-muted">Sesiones:</small>
                                                                                            <div class="d-flex flex-wrap gap-1 mt-1">
                                                                                                <?php foreach ($dia['estados_sesiones'] as $sesion): ?>
                                                                                                    <span class="badge <?php echo $sesion['completado'] ? 'bg-success' : 'bg-secondary'; ?> badge-sm">
                                                                                                        <?php echo htmlspecialchars($sesion['nombre'] . ' ' . $sesion['apellido']); ?>
                                                                                                        <?php if ($sesion['valorado']): ?>
                                                                                                            <i class="fas fa-star text-warning"></i>
                                                                                                        <?php endif; ?>
                                                                                                    </span>
                                                                                                <?php endforeach; ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                <?php else: ?>
                                                                                    <div class="text-center">
                                                                                        <small class="text-muted">Sin sesiones registradas</small>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php 
                                                                            // Verificar si el usuario tiene acceso al entrenamiento
                                                                            $tiene_acceso = false;
                                                                            if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'entrenador') {
                                                                                // Admins y entrenadores pueden ver todos los entrenamientos
                                                                                $tiene_acceso = true;
                                                                            } else {
                                                                                // Para entrenados, verificar si tienen acceso
                                                                                require_once 'models/EntrenamientoModel.php';
                                                                                $entrenamientoModel = new EntrenamientoModel();
                                                                                $tiene_acceso = $entrenamientoModel->verificarAccesoUsuario($dia['entrenamiento_id'], $_SESSION['user_id']);
                                                                            }
                                                                            ?>
                                                                            
                                                                            <?php if ($tiene_acceso): ?>
                                                                                <div class="btn-group">
                                                                                    <a href="/entrenamientos/ver/<?php echo $dia['entrenamiento_id']; ?>" 
                                                                                       class="btn btn-sm btn-outline-primary">
                                                                                        <i class="fas fa-eye me-1"></i> Ver
                                                                                    </a>
                                                                                
                                                                                <?php if ($_SESSION['user_role'] === 'entrenado'): ?>
                                                                                    <?php
                                                                                        $mi_sesion = null;
                                                                                        if (isset($dia['estados_sesiones'])) {
                                                                                            foreach ($dia['estados_sesiones'] as $sesion) {
                                                                                                if ($sesion['usuario_id'] == $_SESSION['user_id']) {
                                                                                                    $mi_sesion = $sesion;
                                                                                                    break;
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                    
                                                                                    <?php if ($mi_sesion && $mi_sesion['completado']): ?>
                                                                                        <form method="POST" action="/sesiones/marcar-no-completada" style="display: inline;">
                                                                                            <input type="hidden" name="programacion_id" value="<?php echo $programacion['id']; ?>">
                                                                                            <input type="hidden" name="dia_id" value="<?php echo $dia['id']; ?>">
                                                                                            <input type="hidden" name="entrenamiento_id" value="<?php echo $dia['entrenamiento_id']; ?>">
                                                                                            <button type="submit" class="btn btn-sm btn-outline-warning" 
                                                                                                    onclick="return confirm('¿Desmarcar como completado?')">
                                                                                                <i class="fas fa-times me-1"></i> Desmarcar
                                                                                            </button>
                                                                                        </form>
                                                                                    <?php else: ?>
                                                                                        <form method="POST" action="/sesiones/marcar-completada" style="display: inline;">
                                                                                            <input type="hidden" name="programacion_id" value="<?php echo $programacion['id']; ?>">
                                                                                            <input type="hidden" name="dia_id" value="<?php echo $dia['id']; ?>">
                                                                                            <input type="hidden" name="entrenamiento_id" value="<?php echo $dia['entrenamiento_id']; ?>">
                                                                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                                                                <i class="fas fa-check me-1"></i> Completar
                                                                                            </button>
                                                                                        </form>
                                                                                    <?php endif; ?>
                                                                                <?php endif; ?>
                                                                                </div>
                                                                            <?php else: ?>
                                                                                <div class="d-flex align-items-center">
                                                                                    <div class="bg-warning bg-opacity-10 p-2 rounded me-2">
                                                                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                                                                    </div>
                                                                                    <div>
                                                                                        <small class="text-muted">No asignado</small>
                                                                                        <br>
                                                                                        <small class="text-warning">Contacta a tu entrenador</small>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info m-3">
                                    <i class="fas fa-info-circle me-2"></i> No hay entrenamientos asignados a este programa.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 