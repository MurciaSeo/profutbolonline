<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3><?php echo isset($tipo) ? 'Editar Tipo de Ejercicio' : 'Nuevo Tipo de Ejercicio'; ?></h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo isset($tipo) ? '/tipos-ejercicios/editar/' . $tipo['id'] : '/tipos-ejercicios/crear'; ?>" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?php echo isset($tipo) ? htmlspecialchars($tipo['nombre']) : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php 
                                echo isset($tipo) ? htmlspecialchars($tipo['descripcion']) : ''; 
                            ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="color" class="form-label">Color</label>
                            <input type="color" class="form-control form-control-color" id="color" name="color" 
                                   value="<?php echo isset($tipo) ? htmlspecialchars($tipo['color']) : '#000000'; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="icono" class="form-label">Icono (Clase de Font Awesome)</label>
                            <input type="text" class="form-control" id="icono" name="icono" 
                                   value="<?php echo isset($tipo) ? htmlspecialchars($tipo['icono']) : ''; ?>" required>
                            <small class="form-text text-muted">
                                Ejemplo: fas fa-dumbbell, fas fa-running, fas fa-yoga, etc.
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <?php echo isset($tipo) ? 'Actualizar' : 'Crear'; ?>
                            </button>
                            <a href="/tipos-ejercicios" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 