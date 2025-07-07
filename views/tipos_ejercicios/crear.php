<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Crear Nuevo Tipo de Ejercicio</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['error']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/tipos-ejercicios/crear" id="crearTipoForm" novalidate>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                            <div class="invalid-feedback">Por favor, ingrese un nombre válido.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                            <div class="invalid-feedback">Por favor, ingrese una descripción válida.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="color" class="form-label">Color</label>
                            <input type="color" class="form-control form-control-color" id="color" name="color" 
                                   value="#007bff" title="Seleccione un color">
                            <div class="form-text">Este color se utilizará para identificar visualmente el tipo de ejercicio.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="icono" class="form-label">Icono</label>
                            <input type="text" class="form-control" id="icono" name="icono" 
                                   placeholder="fas fa-dumbbell" required>
                            <div class="form-text">
                                Ingrese el nombre de clase del icono de Font Awesome (ej: fas fa-dumbbell).
                                <a href="https://fontawesome.com/icons" target="_blank">Ver iconos disponibles</a>
                            </div>
                            <div class="invalid-feedback">Por favor, ingrese un icono válido.</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Crear Tipo de Ejercicio</button>
                            <a href="/tipos-ejercicios" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('crearTipoForm');
    
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?> 