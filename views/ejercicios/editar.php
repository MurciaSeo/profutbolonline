<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Editar Ejercicio</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="/ejercicios/editar/<?php echo $ejercicio['id']; ?>">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Ejercicio</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?php echo htmlspecialchars($ejercicio['nombre']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tipo_id" class="form-label">Tipo de Ejercicio</label>
                            <select class="form-select" id="tipo_id" name="tipo_id" required>
                                <option value="">Seleccione un tipo</option>
                                <?php foreach ($tipos as $tipo): ?>
                                    <option value="<?php echo $tipo['id']; ?>" 
                                            data-color="<?php echo $tipo['color']; ?>"
                                            data-icon="<?php echo $tipo['icono']; ?>"
                                            <?php echo $ejercicio['tipo_id'] == $tipo['id'] ? 'selected' : ''; ?>>
                                        <i class="<?php echo $tipo['icono']; ?>" style="color: <?php echo $tipo['color']; ?>"></i>
                                        <?php echo htmlspecialchars($tipo['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required><?php 
                                echo htmlspecialchars($ejercicio['descripcion']); 
                            ?></textarea>
                            <div class="form-text">
                                Describe detalladamente cómo se realiza el ejercicio, incluyendo la posición inicial, 
                                el movimiento y las precauciones a tener en cuenta.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="video_url" class="form-label">URL del Vídeo Explicativo</label>
                            <input type="url" class="form-control" id="video_url" name="video_url" 
                                   value="<?php echo htmlspecialchars($ejercicio['video_url'] ?? ''); ?>"
                                   placeholder="https://www.youtube.com/watch?v=...">
                            <div class="form-text">
                                Ingresa la URL de un vídeo explicativo o demostrativo del ejercicio (YouTube, Vimeo, etc.)
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Actualizar Ejercicio</button>
                            <a href="/ejercicios" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 