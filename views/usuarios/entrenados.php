<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Mis Entrenados</h2>
                <a href="/dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Total Entrenamientos</th>
                                    <th>Completados</th>
                                    <th>Última Actividad</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($entrenados)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No tienes entrenados asignados</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($entrenados as $entrenado): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($entrenado['nombre'] . ' ' . $entrenado['apellido']); ?></td>
                                        <td><?php echo htmlspecialchars($entrenado['email']); ?></td>
                                        <td><?php echo $estadisticas[$entrenado['id']]['total_entrenamientos']; ?></td>
                                        <td><?php echo $estadisticas[$entrenado['id']]['entrenamientos_completados']; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($estadisticas[$entrenado['id']]['ultima_actividad'])); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="/entrenamientos/asignar/<?php echo $entrenado['id']; ?>" 
                                                   class="btn btn-sm btn-success">
                                                    <i class="fas fa-plus"></i> Asignar Entrenamiento
                                                </a>
                                                <a href="/programas/asignar/<?php echo $entrenado['id']; ?>" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-calendar-plus"></i> Asignar Programa
                                                </a>
                                                <a href="/usuarios/perfil/<?php echo $entrenado['id']; ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Ver Perfil
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 