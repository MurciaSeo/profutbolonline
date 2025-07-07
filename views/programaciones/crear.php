<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Crear Nuevo Programa de Entrenamiento</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="/programaciones/guardar" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Programa</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="duracion_semanas" class="form-label">Duración (semanas)</label>
                            <input type="number" class="form-control" id="duracion_semanas" name="duracion_semanas" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="entrenamientos_por_semana" class="form-label">Entrenamientos por semana</label>
                            <input type="number" class="form-control" id="entrenamientos_por_semana" name="entrenamientos_por_semana" min="1" max="7" required>
                        </div>

                        <div class="mb-3">
                            <label for="nivel" class="form-label">Nivel</label>
                            <select class="form-select" id="nivel" name="nivel" required>
                                <option value="">Seleccione un nivel</option>
                                <option value="principiante">Principiante</option>
                                <option value="intermedio">Intermedio</option>
                                <option value="avanzado">Avanzado</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="objetivo" class="form-label">Objetivo</label>
                            <input type="text" class="form-control" id="objetivo" name="objetivo" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Días de entrenamiento</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_semana[]" value="1" id="dia1">
                                <label class="form-check-label" for="dia1">Lunes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_semana[]" value="2" id="dia2">
                                <label class="form-check-label" for="dia2">Martes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_semana[]" value="3" id="dia3">
                                <label class="form-check-label" for="dia3">Miércoles</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_semana[]" value="4" id="dia4">
                                <label class="form-check-label" for="dia4">Jueves</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_semana[]" value="5" id="dia5">
                                <label class="form-check-label" for="dia5">Viernes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_semana[]" value="6" id="dia6">
                                <label class="form-check-label" for="dia6">Sábado</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_semana[]" value="7" id="dia7">
                                <label class="form-check-label" for="dia7">Domingo</label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Crear Programa</button>
                            <a href="/programaciones" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 