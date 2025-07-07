<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Valorar Sesión</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <h4><?php echo htmlspecialchars($sesion['entrenamiento_nombre']); ?></h4>
                        <p class="text-muted">
                            Fecha: <?php echo date('d/m/Y', strtotime($sesion['fecha'])); ?>
                        </p>
                    </div>

                    <form action="/sesiones/guardar-valoracion" method="POST">
                        <input type="hidden" name="sesion_id" value="<?php echo $sesion['id']; ?>">
                        <input type="hidden" name="entrenamiento_id" value="<?php echo $sesion['entrenamiento_id']; ?>">
                        
                        <div class="mb-4">
                            <label class="form-label">Calidad de la sesión</label>
                            <div class="rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="calidad" id="calidad<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                    <label for="calidad<?php echo $i; ?>">★</label>
                                <?php endfor; ?>
                            </div>
                            <small class="text-muted">1 = Muy mala, 5 = Excelente</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Nivel de esfuerzo</label>
                            <div class="rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="esfuerzo" id="esfuerzo<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                    <label for="esfuerzo<?php echo $i; ?>">★</label>
                                <?php endfor; ?>
                            </div>
                            <small class="text-muted">1 = Muy bajo, 5 = Muy alto</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Complejidad</label>
                            <div class="rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="complejidad" id="complejidad<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                    <label for="complejidad<?php echo $i; ?>">★</label>
                                <?php endfor; ?>
                            </div>
                            <small class="text-muted">1 = Muy fácil, 5 = Muy difícil</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Duración</label>
                            <div class="rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="duracion" id="duracion<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                    <label for="duracion<?php echo $i; ?>">★</label>
                                <?php endfor; ?>
                            </div>
                            <small class="text-muted">1 = Muy corta, 5 = Muy larga</small>
                        </div>

                        <div class="mb-4">
                            <label for="comentarios" class="form-label">Comentarios (opcional)</label>
                            <textarea class="form-control" id="comentarios" name="comentarios" rows="3"></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Enviar Valoración</button>
                            <a href="/dashboard" class="btn btn-secondary">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating input {
    display: none;
}

.rating label {
    cursor: pointer;
    width: 40px;
    height: 40px;
    margin-top: 0;
    background-color: transparent;
    margin-bottom: 10px;
    color: #ddd;
    font-size: 30px;
    line-height: 40px;
    text-align: center;
}

.rating input:checked ~ label,
.rating:not(:checked) > label:hover,
.rating:not(:checked) > label:hover ~ label {
    color: #ffd700;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?> 