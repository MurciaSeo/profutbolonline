<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Asignar Entrenamiento Rápidamente</h2>
                <a href="/dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Asignar Entrenamiento</h5>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="/entrenamientos/asignar-rapido" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="usuario_id" class="form-label">Usuario</label>
                                    <select class="form-select" id="usuario_id" name="usuario_id" required>
                                        <option value="">Seleccionar usuario</option>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <option value="<?php echo $usuario['id']; ?>">
                                                <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
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
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Asignar Entrenamiento
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Asignaciones Recientes</h5>
                    <?php if (empty($asignaciones_recientes)): ?>
                        <p class="text-muted">No hay asignaciones recientes.</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($asignaciones_recientes as $asignacion): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?php echo htmlspecialchars($asignacion['nombre_usuario']); ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($asignacion['nombre_entrenamiento']); ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-<?php echo $asignacion['completado'] ? 'success' : 'warning'; ?>">
                                            <?php echo $asignacion['completado'] ? 'Completado' : 'Pendiente'; ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 