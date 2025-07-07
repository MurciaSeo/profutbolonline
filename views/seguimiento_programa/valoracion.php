<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Valoración del Entrenamiento</h3>
                </div>
                <div class="card-body">
                    <form action="/seguimiento-programa/guardar-valoracion" method="POST">
                        <input type="hidden" name="entrenamiento_id" value="<?php echo $entrenamiento_id; ?>">
                        <input type="hidden" name="dia_id" value="<?php echo $dia_id; ?>">

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
                            <label class="form-label">Percepción del esfuerzo</label>
                            <div class="rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="esfuerzo" id="esfuerzo<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                    <label for="esfuerzo<?php echo $i; ?>">★</label>
                                <?php endfor; ?>
                            </div>
                            <small class="text-muted">1 = Muy fácil, 5 = Muy intenso</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Complejidad</label>
                            <div class="rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="complejidad" id="complejidad<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                    <label for="complejidad<?php echo $i; ?>">★</label>
                                <?php endfor; ?>
                            </div>
                            <small class="text-muted">1 = Muy simple, 5 = Muy complejo</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Valoración de la duración</label>
                            <div class="rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="duracion" id="duracion<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                    <label for="duracion<?php echo $i; ?>">★</label>
                                <?php endfor; ?>
                            </div>
                            <small class="text-muted">1 = Muy corta, 5 = Muy larga</small>
                        </div>

                        <div class="mb-4">
                            <label for="comentarios" class="form-label">Comentarios adicionales</label>
                            <textarea class="form-control" id="comentarios" name="comentarios" rows="3"></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Enviar valoración</button>
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
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.3s;
}

.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #ffc107;
}

.rating input:checked + label {
    color: #ffc107;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?> 