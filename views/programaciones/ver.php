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
                                                                    <th style="width: 20%">Día</th>
                                                                    <th style="width: 60%">Entrenamiento</th>
                                                                    <th style="width: 20%">Acciones</th>
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
                                                                                        // Convertir número de día a nombre
                                                                                        $dias_semana = [
                                                                                            1 => 'Lunes',
                                                                                            2 => 'Martes',
                                                                                            3 => 'Miércoles',
                                                                                            4 => 'Jueves',
                                                                                            5 => 'Viernes',
                                                                                            6 => 'Sábado',
                                                                                            7 => 'Domingo'
                                                                                        ];
                                                                                        echo $dias_semana[$dia['dia_semana']]; 
                                                                                    ?></strong>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <h6 class="mb-0"><?php echo htmlspecialchars($dia['nombre_entrenamiento']); ?></h6>
                                                                        </td>
                                                                        <td>
                                                                            <div class="btn-group">
                                                                                <a href="/entrenamientos/ver/<?php echo $dia['entrenamiento_id']; ?>" 
                                                                                   class="btn btn-sm btn-outline-primary">
                                                                                    <i class="fas fa-eye me-1"></i> Ver
                                                                                </a>
                                                                            </div>
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