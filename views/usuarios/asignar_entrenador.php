<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Asignar Entrenador</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="/usuarios/asignar-entrenador">
                        <div class="mb-3">
                            <label for="entrenado_id" class="form-label">Usuario Entrenado</label>
                            <select class="form-select" id="entrenado_id" name="entrenado_id" required>
                                <option value="">Seleccione un usuario</option>
                                <?php foreach ($entrenados as $entrenado): ?>
                                    <option value="<?php echo $entrenado['id']; ?>">
                                        <?php echo htmlspecialchars($entrenado['nombre'] . ' ' . $entrenado['apellido']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="entrenador_id" class="form-label">Entrenador</label>
                            <select class="form-select" id="entrenador_id" name="entrenador_id" required>
                                <option value="">Seleccione un entrenador</option>
                                <?php foreach ($entrenadores as $entrenador): ?>
                                    <option value="<?php echo $entrenador['id']; ?>">
                                        <?php echo htmlspecialchars($entrenador['nombre'] . ' ' . $entrenador['apellido']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Asignar Entrenador</button>
                            <a href="/usuarios" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 