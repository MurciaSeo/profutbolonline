<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">    
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Gestión de Tipos de Ejercicios</h2>
                <a href="/tipos-ejercicios/crear" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Tipo
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
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Color</th>
                                    <th>Icono</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($tipos)): ?>
                                    <?php foreach ($tipos as $tipo): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($tipo['nombre']); ?></td>
                                            <td>
                                                <?php 
                                                $descripcion = $tipo['descripcion'];
                                                echo strlen($descripcion) > 100 ? 
                                                    htmlspecialchars(substr($descripcion, 0, 100)) . '...' : 
                                                    htmlspecialchars($descripcion);
                                                ?>
                                            </td>
                                            <td>
                                                <span class="badge" style="background-color: <?php echo htmlspecialchars($tipo['color']); ?>">
                                                    <?php echo htmlspecialchars($tipo['color']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <i class="<?php echo htmlspecialchars($tipo['icono']); ?> fa-lg"></i>
                                            </td>
                                            <td>
                                                <a href="/tipos-ejercicios/editar/<?php echo $tipo['id']; ?>" 
                                                   class="btn btn-sm btn-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="/tipos-ejercicios/eliminar/<?php echo $tipo['id']; ?>" 
                                                   class="btn btn-sm btn-danger" title="Eliminar"
                                                   onclick="return confirm('¿Estás seguro de eliminar este tipo de ejercicio?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No se encontraron tipos de ejercicios</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 