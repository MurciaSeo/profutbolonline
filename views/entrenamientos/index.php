<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Entrenamientos</h2>
                <?php if ($_SESSION['user_role'] === 'entrenador' || $_SESSION['user_role'] === 'admin'): ?>
                    <a href="/entrenamientos/crear" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Entrenamiento
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="/entrenamientos" class="row g-3">
                        <div class="col-md-4">
                            <label for="busqueda" class="form-label">Buscar</label>
                            <input type="text" class="form-control" id="busqueda" name="busqueda" 
                                   value="<?php echo htmlspecialchars($_GET['busqueda'] ?? ''); ?>" 
                                   placeholder="Buscar por nombre...">
                        </div>
                        <div class="col-md-4">
                            <label for="tipo" class="form-label">Tipo de Entrenamiento</label>
                            <select class="form-select" id="tipo" name="tipo">
                                <option value="">Todos los tipos</option>
                                <option value="fuerza" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'fuerza') ? 'selected' : ''; ?>>Fuerza</option>
                                <option value="cardio" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'cardio') ? 'selected' : ''; ?>>Cardio</option>
                                <option value="flexibilidad" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'flexibilidad') ? 'selected' : ''; ?>>Flexibilidad</option>
                                <option value="equilibrio" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'equilibrio') ? 'selected' : ''; ?>>Equilibrio</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                            <a href="/entrenamientos" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($entrenamientos)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Ejercicios</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($entrenamientos as $entrenamiento): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($entrenamiento['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($entrenamiento['descripcion']); ?></td>
                                            <td>
                                                <?php 
                                                if (!empty($entrenamiento['ejercicios'])) {
                                                    echo '<ul class="list-unstyled mb-0">';
                                                    foreach ($entrenamiento['ejercicios'] as $ejercicio) {
                                                        echo '<li>' . htmlspecialchars($ejercicio['nombre']) . '</li>';
                                                    }
                                                    echo '</ul>';
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <a href="/entrenamientos/ver/<?php echo $entrenamiento['id']; ?>" 
                                                   class="btn btn-sm btn-info" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($_SESSION['user_role'] === 'entrenador' || $_SESSION['user_role'] === 'admin'): ?>
                                                    <a href="/entrenamientos/editar/<?php echo $entrenamiento['id']; ?>" 
                                                       class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="/entrenamientos/eliminar/<?php echo $entrenamiento['id']; ?>" 
                                                       class="btn btn-sm btn-danger" title="Eliminar"
                                                       onclick="return confirm('¿Estás seguro de que deseas eliminar este entrenamiento?')">
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
                            No hay entrenamientos registrados.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 