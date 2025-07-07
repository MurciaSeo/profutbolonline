<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Programas de Entrenamiento</h2>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <a href="/programaciones/crear" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Programa
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($programaciones)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Duración (semanas)</th>
                                        <th>Entrenamientos/semana</th>
                                        <th>Nivel</th>
                                        <th>Objetivo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($programaciones as $programacion): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($programacion['nombre']); ?></td>
                                            <td><?php echo $programacion['duracion_semanas']; ?></td>
                                            <td><?php echo $programacion['entrenamientos_por_semana']; ?></td>
                                            <td><?php echo ucfirst($programacion['nivel']); ?></td>
                                            <td><?php echo htmlspecialchars($programacion['objetivo']); ?></td>
                                            <td>
                                                <a href="/programaciones/ver/<?php echo $programacion['id']; ?>" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                                    <a href="/programaciones/editar/<?php echo $programacion['id']; ?>" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="/programaciones/eliminar/<?php echo $programacion['id']; ?>" 
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('¿Estás seguro de que deseas eliminar este programa?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            No hay programas de entrenamiento registrados.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 