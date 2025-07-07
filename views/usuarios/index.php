<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Gestión de Usuarios</h2>
                <a href="/usuarios/crear" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Usuario
                </a>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($usuarios)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Entrenador</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></td>
                                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                            <td><?php echo ucfirst($usuario['rol']); ?></td>
                                            <td>
                                                <?php if ($usuario['rol'] === 'entrenado'): ?>
                                                    <form action="/usuarios/asignar-entrenador" method="POST" class="d-inline" id="form-<?php echo $usuario['id']; ?>">
                                                        <input type="hidden" name="entrenado_id" value="<?php echo $usuario['id']; ?>">
                                                        <select name="entrenador_id" class="form-select form-select-sm d-inline-block w-auto" id="entrenador-<?php echo $usuario['id']; ?>">
                                                            <option value="">Sin entrenador</option>
                                                            <?php foreach ($entrenadores as $entrenador): ?>
                                                                <option value="<?php echo $entrenador['id']; ?>" 
                                                                        <?php echo $usuario['entrenador_id'] == $entrenador['id'] ? 'selected' : ''; ?>>
                                                                    <?php echo htmlspecialchars($entrenador['nombre'] . ' ' . $entrenador['apellido']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-primary ms-2" id="btn-<?php echo $usuario['id']; ?>" style="display: none;">
                                                            <i class="fas fa-save"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="/usuarios/editar/<?php echo $usuario['id']; ?>" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="/usuarios/eliminar/<?php echo $usuario['id']; ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            No hay usuarios registrados.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Para cada select de entrenador
    document.querySelectorAll('select[name="entrenador_id"]').forEach(function(select) {
        const userId = select.getAttribute('id').split('-')[1];
        const form = document.getElementById('form-' + userId);
        const submitBtn = document.getElementById('btn-' + userId);
        let originalValue = select.value;
        
        // Mostrar/ocultar botón de guardar cuando cambia el valor
        select.addEventListener('change', function() {
            if (this.value !== originalValue) {
                submitBtn.style.display = 'inline-block';
            } else {
                submitBtn.style.display = 'none';
            }
        });
        
        // Actualizar el valor original después de enviar el formulario
        form.addEventListener('submit', function() {
            originalValue = select.value;
        });
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?> 