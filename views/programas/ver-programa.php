<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="/programas/tienda">Tienda</a></li>
                    <li class="breadcrumb-item"><a href="/programas/mis-compras">Mis Compras</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($programa['nombre']); ?></li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header <?php echo $tipo === 'entrenamiento' ? 'bg-success' : 'bg-info'; ?> text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <?php if ($tipo === 'entrenamiento'): ?>
                                <i class="fas fa-dumbbell me-2"></i>
                            <?php else: ?>
                                <i class="fas fa-graduation-cap me-2"></i>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($programa['nombre']); ?>
                        </h3>
                        <span class="badge bg-light text-dark">
                            <?php echo ucfirst($tipo); ?>
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary mb-3">Descripción</h5>
                            <p class="text-muted mb-4"><?php echo htmlspecialchars($programa['descripcion']); ?></p>
                            
                            <?php if ($tipo === 'entrenamiento'): ?>
                                <!-- Información para programas de entrenamiento -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-calendar text-success me-2"></i>
                                            <span><strong>Duración:</strong> <?php echo $programa['duracion_semanas']; ?> semanas</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-dumbbell text-success me-2"></i>
                                            <span><strong>Entrenamientos/semana:</strong> <?php echo $programa['entrenamientos_por_semana']; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-signal text-success me-2"></i>
                                            <span><strong>Nivel:</strong> <?php echo ucfirst($programa['nivel']); ?></span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-bullseye text-success me-2"></i>
                                            <span><strong>Objetivo:</strong> <?php echo htmlspecialchars($programa['objetivo']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <a href="/programaciones/ver/<?php echo $programa['id']; ?>" class="btn btn-success btn-lg">
                                        <i class="fas fa-play me-2"></i>Comenzar Programa de Entrenamiento
                                    </a>
                                </div>
                                
                            <?php else: ?>
                                <!-- Información para programas de coaching -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-calendar-alt text-info me-2"></i>
                                            <span><strong>Duración:</strong> <?php echo $programa['duracion_meses']; ?> meses</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-tag text-info me-2"></i>
                                            <span><strong>Categoría:</strong> <?php echo ucfirst($programa['categoria']); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-unlock text-info me-2"></i>
                                            <span><strong>Desbloqueo:</strong> Mensual</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-play-circle text-info me-2"></i>
                                            <span><strong>Contenido:</strong> Videos, textos, ejercicios</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Información de la suscripción -->
                                <?php if (isset($suscripcion)): ?>
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading">
                                            <i class="fas fa-info-circle me-2"></i>Información de tu Suscripción
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Estado:</strong> <?php echo ucfirst($suscripcion['estado']); ?><br>
                                                <strong>Mes actual:</strong> <?php echo $suscripcion['mes_actual']; ?> de <?php echo $programa['duracion_meses']; ?>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Inicio:</strong> <?php echo date('d/m/Y', strtotime($suscripcion['fecha_inicio'])); ?><br>
                                                <strong>Próximo pago:</strong> <?php echo date('d/m/Y', strtotime($suscripcion['proximo_pago'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Bloques del programa -->
                                <?php if (isset($programa['bloques']) && !empty($programa['bloques'])): ?>
                                    <h5 class="text-primary mb-3">Contenido del Programa</h5>
                                    <div class="row">
                                        <?php foreach ($programa['bloques'] as $bloque): ?>
                                            <?php 
                                            $acceso = null;
                                            if (isset($accesos)) {
                                                foreach ($accesos as $acc) {
                                                    if ($acc['bloque_id'] == $bloque['id']) {
                                                        $acceso = $acc;
                                                        break;
                                                    }
                                                }
                                            }
                                            $desbloqueado = $acceso && $acceso['desbloqueado'];
                                            $completado = $acceso && $acceso['completado'];
                                            ?>
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card h-100 <?php echo $desbloqueado ? 'border-success' : 'border-secondary'; ?>">
                                                    <div class="card-header <?php echo $desbloqueado ? 'bg-success' : 'bg-secondary'; ?> text-white">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h6 class="card-title mb-0">
                                                                <i class="fas fa-<?php echo $bloque['tipo_contenido'] === 'video' ? 'video' : 'file-text'; ?> me-2"></i>
                                                                Mes <?php echo $bloque['mes']; ?>
                                                            </h6>
                                                            <?php if ($desbloqueado): ?>
                                                                <span class="badge bg-light text-dark">
                                                                    <?php echo $completado ? 'Completado' : 'Disponible'; ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">
                                                                    Bloqueado
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <h6 class="card-title"><?php echo htmlspecialchars($bloque['titulo']); ?></h6>
                                                        <p class="card-text text-muted">
                                                            <?php echo htmlspecialchars($bloque['descripcion']); ?>
                                                        </p>
                                                        <div class="row mb-2">
                                                            <div class="col-6">
                                                                <small class="text-muted">Duración:</small>
                                                                <div class="fw-bold"><?php echo $bloque['duracion_minutos']; ?> min</div>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted">Tipo:</small>
                                                                <div class="fw-bold text-capitalize"><?php echo $bloque['tipo_contenido']; ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="d-grid">
                                                            <?php if ($desbloqueado): ?>
                                                                <a href="/programas/ver-bloque/<?php echo $bloque['id']; ?>" 
                                                                   class="btn btn-outline-success btn-sm">
                                                                    <i class="fas fa-play me-2"></i>
                                                                    <?php echo $completado ? 'Repasar' : 'Comenzar'; ?>
                                                                </a>
                                                            <?php else: ?>
                                                                <button class="btn btn-secondary btn-sm" disabled>
                                                                    <i class="fas fa-lock me-2"></i>Bloqueado
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-info-circle me-2"></i>Información del Programa
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php if ($tipo === 'entrenamiento'): ?>
                                        <div class="mb-3">
                                            <h6 class="text-success">Programa de Entrenamiento</h6>
                                            <p class="text-muted small">
                                                Este es un programa de entrenamiento físico que incluye ejercicios, 
                                                rutinas y seguimiento de progreso.
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <h6>Características:</h6>
                                            <ul class="list-unstyled">
                                                <li><i class="fas fa-check text-success me-2"></i>Entrenamientos estructurados</li>
                                                <li><i class="fas fa-check text-success me-2"></i>Seguimiento de progreso</li>
                                                <li><i class="fas fa-check text-success me-2"></i>Ejercicios detallados</li>
                                                <li><i class="fas fa-check text-success me-2"></i>Acceso permanente</li>
                                            </ul>
                                        </div>
                                    <?php else: ?>
                                        <div class="mb-3">
                                            <h6 class="text-info">Programa de Coaching</h6>
                                            <p class="text-muted small">
                                                Este es un programa de coaching que se desbloquea mensualmente 
                                                con contenido educativo y práctico.
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <h6>Características:</h6>
                                            <ul class="list-unstyled">
                                                <li><i class="fas fa-check text-info me-2"></i>Contenido mensual</li>
                                                <li><i class="fas fa-check text-info me-2"></i>Videos y textos</li>
                                                <li><i class="fas fa-check text-info me-2"></i>Seguimiento de progreso</li>
                                                <li><i class="fas fa-check text-info me-2"></i>Desbloqueo gradual</li>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="/programas/mis-compras" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-left me-2"></i>Volver a Mis Compras
                                        </a>
                                        <a href="/programas/tienda" class="btn btn-outline-secondary">
                                            <i class="fas fa-store me-2"></i>Ir a la Tienda
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 