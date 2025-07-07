<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Perfil de Usuario</h2>
                <div>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <a href="/usuarios" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Volver a Usuarios
                        </a>
                    <?php else: ?>
                        <a href="/usuarios/entrenados" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Volver a Entrenados
                        </a>
                    <?php endif; ?>
                    <a href="/usuarios/editar/<?php echo $usuario['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Editar Usuario
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información Personal -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="card-title">Información Personal</h5>
                    <div class="mb-3">
                        <strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?>
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?>
                    </div>
                    <div class="mb-3">
                        <strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['telefono'] ?? 'No especificado'); ?>
                    </div>
                    <div class="mb-3">
                        <strong>Rol:</strong> 
                        <span class="badge bg-<?php 
                            echo $usuario['rol'] === 'admin' ? 'danger' : 
                                ($usuario['rol'] === 'entrenador' ? 'primary' : 'success'); 
                        ?>">
                            <?php echo ucfirst($usuario['rol']); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="card-title">Estadísticas</h5>
                    <div class="mb-3">
                        <strong>Total Entrenamientos:</strong> <?php echo $estadisticas['total_entrenamientos']; ?>
                    </div>
                    <div class="mb-3">
                        <strong>Entrenamientos Completados:</strong> <?php echo $estadisticas['entrenamientos_completados']; ?>
                    </div>
                    <?php if ($usuario['rol'] === 'entrenador'): ?>
                        <div class="mb-3">
                            <strong>Total Entrenados:</strong> <?php echo $estadisticas['total_entrenados']; ?>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <strong>Última Actividad:</strong> 
                        <?php echo $estadisticas['ultima_actividad'] ? date('d/m/Y H:i', strtotime($estadisticas['ultima_actividad'])) : 'Sin actividad'; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="col-md-8">
            <?php if ($usuario['rol'] === 'entrenador'): ?>
                <!-- Lista de Entrenados -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Entrenados Asignados</h5>
                        <?php if (empty($entrenados)): ?>
                            <p class="text-muted">No hay entrenados asignados.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($entrenados as $entrenado): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($entrenado['nombre'] . ' ' . $entrenado['apellido']); ?></td>
                                                <td><?php echo htmlspecialchars($entrenado['email']); ?></td>
                                                <td>
                                                    <a href="/usuarios/perfil/<?php echo $entrenado['id']; ?>" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> Ver Perfil
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Entrenamientos Asignados -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="card-title">Entrenamientos Asignados</h5>
                    <?php if (empty($entrenamientos)): ?>
                        <p class="text-muted">No hay entrenamientos asignados.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Entrenamiento</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($entrenamientos as $entrenamiento): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($entrenamiento['nombre']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($entrenamiento['fecha'])); ?></td>
                                            <td>
                                                <?php if ($entrenamiento['completado']): ?>
                                                    <span class="badge bg-success">Completado</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Pendiente</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Programas Asignados -->
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Programas Asignados</h5>
                    <?php if (empty($programaciones)): ?>
                        <p class="text-muted">No hay programas asignados.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Programa</th>
                                        <th>Fecha Inicio</th>
                                        <th>Progreso</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($programaciones as $programacion): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($programacion['nombre_programa']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($programacion['fecha_inicio'])); ?></td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" 
                                                         style="width: <?php echo $programacion['progreso']; ?>%"
                                                         aria-valuenow="<?php echo $programacion['progreso']; ?>" 
                                                         aria-valuemin="0" aria-valuemax="100">
                                                        <?php echo $programacion['progreso']; ?>%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $programacion['estado'] === 'activo' ? 'success' : 
                                                        ($programacion['estado'] === 'completado' ? 'primary' : 'warning'); 
                                                ?>">
                                                    <?php echo ucfirst($programacion['estado']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 