<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">    
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Gestión de Ejercicios</h2>
                <a href="/ejercicios/crear" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Ejercicio
                </a>
            </div>
            
            <!-- Formulario de búsqueda y filtros -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="/ejercicios" class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" name="busqueda" 
                                       placeholder="Buscar ejercicio..." 
                                       value="<?php echo htmlspecialchars($busqueda); ?>">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <select class="form-select" name="tipo_id">
                                    <option value="">Todos los tipos</option>
                                    <?php foreach ($tipos as $tipo): ?>
                                        <option value="<?php echo $tipo['id']; ?>" 
                                                <?php echo $tipo_seleccionado == $tipo['id'] ? 'selected' : ''; ?>>
                                            <i class="<?php echo $tipo['icono']; ?>" style="color: <?php echo $tipo['color']; ?>"></i>
                                            <?php echo htmlspecialchars($tipo['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($ejercicios)): ?>
                                    <?php foreach ($ejercicios as $ejercicio): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($ejercicio['nombre']); ?></td>
                                            <td>
                                                <span class="badge" style="background-color: <?php echo $ejercicio['tipo_color']; ?>">
                                                    <i class="<?php echo $ejercicio['tipo_icono']; ?>"></i>
                                                    <?php echo htmlspecialchars($ejercicio['tipo_nombre']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php 
                                                $descripcion = $ejercicio['descripcion'];
                                                echo strlen($descripcion) > 100 ? 
                                                    htmlspecialchars(substr($descripcion, 0, 100)) . '...' : 
                                                    htmlspecialchars($descripcion);
                                                ?>
                                            </td>
                                            <td>
                                                <a href="/ejercicios/editar/<?php echo $ejercicio['id']; ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="/ejercicios/eliminar/<?php echo $ejercicio['id']; ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('¿Estás seguro de que deseas eliminar este ejercicio?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No se encontraron ejercicios</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <?php if ($paginas > 1): ?>
                        <nav aria-label="Navegación de páginas" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if ($pagina_actual > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1; ?>&busqueda=<?php echo urlencode($busqueda); ?>&tipo_id=<?php echo urlencode($tipo_seleccionado); ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $paginas; $i++): ?>
                                    <li class="page-item <?php echo $i === $pagina_actual ? 'active' : ''; ?>">
                                        <a class="page-link" href="?pagina=<?php echo $i; ?>&busqueda=<?php echo urlencode($busqueda); ?>&tipo_id=<?php echo urlencode($tipo_seleccionado); ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($pagina_actual < $paginas): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?pagina=<?php echo $pagina_actual + 1; ?>&busqueda=<?php echo urlencode($busqueda); ?>&tipo_id=<?php echo urlencode($tipo_seleccionado); ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 