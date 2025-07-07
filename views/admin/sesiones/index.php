<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>Sesiones Asignadas</h2>
        </div>
        <div class="col text-end">
            <a href="/admin/sesiones/asignar" class="btn btn-primary">
                <i class="fas fa-plus"></i> Asignar Nueva Sesión
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Entrenamiento</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Valoración</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sesiones as $sesion): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($sesion['usuario_nombre'] . ' ' . $sesion['usuario_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($sesion['entrenamiento_nombre']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($sesion['fecha'])); ?></td>
                                <td>
                                    <?php if ($sesion['completado']): ?>
                                        <span class="badge bg-success">Completado</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($sesion['completado']): ?>
                                        <?php 
                                            $valoracion = round(($sesion['calidad'] + $sesion['esfuerzo'] + $sesion['complejidad'] + $sesion['duracion']) / 4, 1);
                                            echo $valoracion . ' / 5';
                                        ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/admin/sesiones/editar/<?php echo $sesion['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/admin/sesiones/eliminar/<?php echo $sesion['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('¿Está seguro de que desea eliminar esta sesión?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($sesiones)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay sesiones asignadas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 