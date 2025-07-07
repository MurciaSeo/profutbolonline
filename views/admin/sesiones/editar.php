<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Editar Sesión</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <form action="/admin/sesiones/editar/<?php echo $sesion['id']; ?>" method="POST">
                        <div class="mb-3">
                            <label for="usuario_id" class="form-label">Usuario</label>
                            <select class="form-select" id="usuario_id" name="usuario_id" disabled>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?php echo $usuario['id']; ?>" 
                                            <?php echo ($usuario['id'] == $sesion['usuario_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="entrenamiento_id" class="form-label">Entrenamiento</label>
                            <select class="form-select" id="entrenamiento_id" name="entrenamiento_id" required>
                                <?php foreach ($entrenamientos as $entrenamiento): ?>
                                    <option value="<?php echo $entrenamiento['id']; ?>"
                                            <?php echo ($entrenamiento['id'] == $sesion['entrenamiento_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($entrenamiento['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="datetime-local" class="form-control" id="fecha" name="fecha" 
                                   value="<?php echo date('Y-m-d\TH:i', strtotime($sesion['fecha'])); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas</label>
                            <textarea class="form-control" id="notas" name="notas" rows="3"><?php echo htmlspecialchars($sesion['notas'] ?? ''); ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <a href="/admin/sesiones" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 