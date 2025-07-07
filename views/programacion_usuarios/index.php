<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            outline: 0;
        }
        
        .modal-dialog {
            position: relative;
            width: auto;
            margin: 1.75rem auto;
            max-width: 500px;
            z-index: 10000;
        }
        
        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: #fff;
            border: 1px solid rgba(0,0,0,.2);
            border-radius: 0.3rem;
            outline: 0;
        }
        
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9998;
            width: 100vw;
            height: 100vh;
            background-color: #000;
        }
        
        .modal-backdrop.show {
            opacity: 0.5;
        }
        
        .card {
            position: relative;
            z-index: 1;
        }
        
        .btn-group {
            position: relative;
            z-index: 2;
        }
        
        .table-responsive {
            position: relative;
            z-index: 1;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Asignaciones de Programas</h1>
        <a href="/programacion_usuarios/asignar" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Asignación
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Programa</th>
                            <th>Usuario</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asignaciones as $asignacion): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($asignacion['nombre_programa']); ?></td>
                                <td><?php echo htmlspecialchars($asignacion['nombre_usuario']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($asignacion['fecha_inicio'])); ?></td>
                                <td><?php echo $asignacion['fecha_fin'] ? date('d/m/Y', strtotime($asignacion['fecha_fin'])) : '-'; ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $asignacion['estado'] === 'activo' ? 'success' : 
                                            ($asignacion['estado'] === 'completado' ? 'info' : 'danger'); 
                                    ?>">
                                        <?php echo ucfirst($asignacion['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#estadoModal<?php echo $asignacion['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="/programacion_usuarios/eliminar/<?php echo $asignacion['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('¿Está seguro de eliminar esta asignación?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Mover los modales fuera del card y al final del documento -->
<?php foreach ($asignaciones as $asignacion): ?>
    <div class="modal fade" id="estadoModal<?php echo $asignacion['id']; ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Actualizar Estado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/programacion_usuarios/actualizarEstado/<?php echo $asignacion['id']; ?>" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" name="estado" id="estado" required>
                                <option value="activo" <?php echo $asignacion['estado'] === 'activo' ? 'selected' : ''; ?>>Activo</option>
                                <option value="completado" <?php echo $asignacion['estado'] === 'completado' ? 'selected' : ''; ?>>Completado</option>
                                <option value="cancelado" <?php echo $asignacion['estado'] === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar el envío del formulario de actualización de estado
    const forms = document.querySelectorAll('form[action^="/programacion_usuarios/actualizarEstado/"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    // Cerrar el modal
                    const modalId = this.closest('.modal').id;
                    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
                    modal.hide();
                    
                    // Recargar la página para mostrar los cambios
                    window.location.reload();
                } else {
                    alert('Error al actualizar el estado');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el estado');
            });
        });
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?> 