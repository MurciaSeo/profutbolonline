<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2>Valorar Entrenamiento</h2>
                </div>
                <div class="card-body">
                    <form action="/entrenamientos/valorar/<?php echo $entrenamiento['id']; ?>" method="POST">
                        <div class="mb-4">
                            <label class="form-label">Valoración del Esfuerzo</label>
                            <div class="rating">
                                <?php for($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="valoracion[esfuerzo]" value="<?php echo $i; ?>" id="esfuerzo<?php echo $i; ?>" required>
                                    <label for="esfuerzo<?php echo $i; ?>">☆</label>
                                <?php endfor; ?>
                            </div>
                            <small class="text-muted">¿Qué tan exigente fue el entrenamiento?</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Valoración de la Calidad</label>
                            <div class="rating">
                                <?php for($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="valoracion[calidad]" value="<?php echo $i; ?>" id="calidad<?php echo $i; ?>" required>
                                    <label for="calidad<?php echo $i; ?>">☆</label>
                                <?php endfor; ?>
                            </div>
                            <small class="text-muted">¿Qué tan efectivo fue el entrenamiento?</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Valoración de los Videos</label>
                            <div class="rating">
                                <?php for($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="valoracion[videos]" value="<?php echo $i; ?>" id="videos<?php echo $i; ?>" required>
                                    <label for="videos<?php echo $i; ?>">☆</label>
                                <?php endfor; ?>
                            </div>
                            <small class="text-muted">¿Qué tan claros y útiles fueron los videos?</small>
                        </div>

                        <div class="mb-3">
                            <label for="comentario" class="form-label">Comentario (opcional)</label>
                            <textarea class="form-control" id="comentario" name="valoracion[comentarios]" rows="3" 
                                    placeholder="Escribe tus comentarios sobre el entrenamiento..."></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Enviar Valoración</button>
                            <a href="/entrenamientos/ver/<?php echo $entrenamiento['id']; ?>" class="btn btn-secondary">Cancelar</a>
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
    justify-content: flex-start;
    margin-bottom: 0.5rem;
}

.rating input {
    display: none;
}

.rating label {
    font-size: 30px;
    color: #ddd;
    cursor: pointer;
    padding: 5px;
    transition: color 0.2s;
}

.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #ffd700;
}

.rating-container {
    margin-bottom: 1.5rem;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?> 