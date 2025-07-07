<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Asignar Entrenamiento</h2>
                <a href="/usuarios/perfil/<?php echo $usuario['id']; ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Perfil
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Asignar Nuevo Entrenamiento</h5>
                    <form action="/entrenamientos/asignar/<?php echo $usuario['id']; ?>" method="POST">
                        <div class="mb-3">
                            <label for="entrenamiento_id" class="form-label">Entrenamiento</label>
                            <select class="form-select" id="entrenamiento_id" name="entrenamiento_id" required>
                                <option value="">Seleccionar entrenamiento</option>
                                <?php foreach ($entrenamientos as $entrenamiento): ?>
                                    <option value="<?php echo $entrenamiento['id']; ?>">
                                        <?php echo htmlspecialchars($entrenamiento['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required 
                                   min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Asignar Entrenamiento
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Entrenamientos Asignados</h5>
                    <?php if (empty($entrenamientos_asignados)): ?>
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
                                    <?php foreach ($entrenamientos_asignados as $asignacion): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($asignacion['nombre']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($asignacion['fecha'])); ?></td>
                                            <td>
                                                <?php if ($asignacion['completado']): ?>
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
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 