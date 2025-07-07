<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Editar Entrenamiento</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['error']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/entrenamientos/editar/<?php echo $entrenamiento['id']; ?>" id="editarEntrenamientoForm" novalidate>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Entrenamiento</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?php echo htmlspecialchars($entrenamiento['nombre']); ?>" required>
                            <div class="invalid-feedback">Por favor, ingrese un nombre válido.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Entrenamiento</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Seleccione un tipo</option>
                                <?php foreach ($tipos_ejercicios as $tipo): ?>
                                    <option value="<?php echo $tipo['id']; ?>" <?php echo $entrenamiento['tipo'] == $tipo['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($tipo['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Por favor, seleccione un tipo de entrenamiento.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php 
                                echo htmlspecialchars($entrenamiento['descripcion']); 
                            ?></textarea>
                            <div class="invalid-feedback">Por favor, ingrese una descripción.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Bloques de Ejercicios</label>
                            <div id="bloques-container">
                                <?php if (isset($entrenamiento['bloques']) && is_array($entrenamiento['bloques'])): ?>
                                    <?php foreach ($entrenamiento['bloques'] as $bloqueIndex => $bloque): ?>
                                        <div class="bloque-ejercicios card mb-3" data-bloque-index="<?php echo $bloqueIndex; ?>">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0">Bloque <?php echo (int)$bloqueIndex + 1; ?></h5>
                                                <button type="button" class="btn btn-danger btn-sm eliminar-bloque">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nombre del Bloque</label>
                                                    <input type="text" class="form-control" name="bloques[<?php echo $bloqueIndex; ?>][nombre]" 
                                                           value="<?php echo htmlspecialchars($bloque['nombre']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Descripción del Bloque</label>
                                                    <textarea class="form-control" name="bloques[<?php echo $bloqueIndex; ?>][descripcion]" rows="2"><?php 
                                                        echo htmlspecialchars($bloque['descripcion']); 
                                                    ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Número de Series</label>
                                                    <input type="number" class="form-control" name="bloques[<?php echo $bloqueIndex; ?>][serie]" 
                                                           placeholder="Número de series" min="1" value="<?php echo htmlspecialchars($bloque['serie'] ?? 1); ?>" required>
                                                    <small class="form-text text-muted">Número de veces que se repite este bloque</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ejercicios</label>
                                                    <div class="ejercicios-container">
                                                        <?php if (isset($bloque['ejercicios']) && is_array($bloque['ejercicios'])): ?>
                                                            <?php foreach ($bloque['ejercicios'] as $ejercicioIndex => $ejercicio): ?>
                                                                <div class="ejercicio-item mb-3 p-3 border rounded bg-light" data-ejercicio-index="<?php echo $ejercicioIndex; ?>">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label small text-muted">Ejercicio</label>
                                                                            <select class="form-select ejercicio-select" name="bloques[<?php echo $bloqueIndex; ?>][ejercicios][<?php echo $ejercicioIndex; ?>][ejercicio_id]" required>
                                                                                <option value="">Seleccione un ejercicio</option>
                                                                                <?php foreach ($ejercicios as $ej): ?>
                                                                                    <option value="<?php echo $ej['id']; ?>" 
                                                                                            <?php echo $ejercicio['ejercicio_id'] == $ej['id'] ? 'selected' : ''; ?>>
                                                                                        <?php echo htmlspecialchars($ej['nombre']); ?>
                                                                                    </option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="form-label small text-muted">Tipo de Configuración</label>
                                                                            <select class="form-select tipo-configuracion" name="bloques[<?php echo $bloqueIndex; ?>][ejercicios][<?php echo $ejercicioIndex; ?>][tipo_configuracion]" required>
                                                                                <option value="repeticiones" <?php echo ($ejercicio['tipo_configuracion'] ?? 'repeticiones') == 'repeticiones' ? 'selected' : ''; ?>>Por Repeticiones</option>
                                                                                <option value="tiempo" <?php echo ($ejercicio['tipo_configuracion'] ?? '') == 'tiempo' ? 'selected' : ''; ?>>Por Tiempo</option>
                                                                                <option value="repeticiones_reserva" <?php echo ($ejercicio['tipo_configuracion'] ?? '') == 'repeticiones_reserva' ? 'selected' : ''; ?>>Por Repeticiones + Reserva</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label class="form-label small text-muted">Descanso (seg)</label>
                                                                            <input type="number" class="form-control" name="bloques[<?php echo $bloqueIndex; ?>][ejercicios][<?php echo $ejercicioIndex; ?>][tiempo_descanso]" 
                                                                                   placeholder="60" min="0"
                                                                                   value="<?php echo htmlspecialchars($ejercicio['tiempo_descanso'] ?? 60); ?>">
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label class="form-label small text-muted">Acción</label>
                                                                            <button type="button" class="btn btn-danger btn-sm w-100 eliminar-ejercicio">
                                                                                <i class="fas fa-trash"></i> Eliminar
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <!-- Campos dinámicos según tipo de configuración -->
                                                                    <div class="row g-3 mt-2">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label small text-muted campo-repeticiones-label" style="display: <?php echo ($ejercicio['tipo_configuracion'] ?? 'repeticiones') != 'tiempo' ? 'block' : 'none'; ?>;">Repeticiones</label>
                                                                            <input type="number" class="form-control campo-repeticiones" name="bloques[<?php echo $bloqueIndex; ?>][ejercicios][<?php echo $ejercicioIndex; ?>][repeticiones]" 
                                                                                   placeholder="Ej: 12" min="1" 
                                                                                   value="<?php echo htmlspecialchars($ejercicio['repeticiones'] ?? ''); ?>"
                                                                                   style="display: <?php echo ($ejercicio['tipo_configuracion'] ?? 'repeticiones') != 'tiempo' ? 'block' : 'none'; ?>;">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label class="form-label small text-muted campo-tiempo-label" style="display: <?php echo ($ejercicio['tipo_configuracion'] ?? '') == 'tiempo' ? 'block' : 'none'; ?>;">Tiempo (segundos)</label>
                                                                            <input type="number" class="form-control campo-tiempo" name="bloques[<?php echo $bloqueIndex; ?>][ejercicios][<?php echo $ejercicioIndex; ?>][tiempo]" 
                                                                                   placeholder="Ej: 45" min="1"
                                                                                   value="<?php echo htmlspecialchars($ejercicio['tiempo'] ?? ''); ?>" 
                                                                                   style="display: <?php echo ($ejercicio['tipo_configuracion'] ?? '') == 'tiempo' ? 'block' : 'none'; ?>;">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label class="form-label small text-muted campo-peso-label" style="display: <?php echo ($ejercicio['tipo_configuracion'] ?? '') == 'repeticiones_reserva' ? 'block' : 'none'; ?>;">Peso (kg)</label>
                                                                            <input type="number" class="form-control campo-peso" name="bloques[<?php echo $bloqueIndex; ?>][ejercicios][<?php echo $ejercicioIndex; ?>][peso_kg]" 
                                                                                   placeholder="Ej: 20" min="0" step="0.5"
                                                                                   value="<?php echo htmlspecialchars($ejercicio['peso_kg'] ?? ''); ?>" 
                                                                                   style="display: <?php echo ($ejercicio['tipo_configuracion'] ?? '') == 'repeticiones_reserva' ? 'block' : 'none'; ?>;">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label class="form-label small text-muted campo-rep-por-hacer-label" style="display: <?php echo ($ejercicio['tipo_configuracion'] ?? '') == 'repeticiones_reserva' ? 'block' : 'none'; ?>;">Repeticiones por hacer</label>
                                                                            <input type="number" class="form-control campo-rep-por-hacer" name="bloques[<?php echo $bloqueIndex; ?>][ejercicios][<?php echo $ejercicioIndex; ?>][repeticiones_por_hacer]" 
                                                                                   placeholder="Ej: 10" min="1"
                                                                                   value="<?php echo htmlspecialchars($ejercicio['repeticiones_por_hacer'] ?? ''); ?>" 
                                                                                   style="display: <?php echo ($ejercicio['tipo_configuracion'] ?? '') == 'repeticiones_reserva' ? 'block' : 'none'; ?>;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                    <button type="button" class="btn btn-secondary agregar-ejercicio">
                                                        <i class="fas fa-plus"></i> Agregar Ejercicio
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-primary" id="agregar-bloque">
                                <i class="fas fa-plus"></i> Agregar Bloque
                            </button>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Actualizar Entrenamiento</button>
                            <a href="/entrenamientos" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editarEntrenamientoForm');
    const bloquesContainer = document.getElementById('bloques-container');
    let bloqueCount = <?php echo isset($entrenamiento['bloques']) ? count($entrenamiento['bloques']) : 0; ?>;
    
    // Plantilla para un nuevo bloque
    const bloqueTemplate = `
        <div class="bloque-ejercicios card mb-3" data-bloque-index="{index}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Bloque {numero}</h5>
                <button type="button" class="btn btn-danger btn-sm eliminar-bloque">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Nombre del Bloque</label>
                    <input type="text" class="form-control" name="bloques[{index}][nombre]" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción del Bloque</label>
                    <textarea class="form-control" name="bloques[{index}][descripcion]" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Número de Series</label>
                    <input type="number" class="form-control" name="bloques[{index}][serie]" 
                           placeholder="Número de series" min="1" value="1" required>
                    <small class="form-text text-muted">Número de veces que se repite este bloque</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ejercicios</label>
                    <div class="ejercicios-container">
                    </div>
                    <button type="button" class="btn btn-secondary agregar-ejercicio">
                        <i class="fas fa-plus"></i> Agregar Ejercicio
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Plantilla para un nuevo ejercicio
    const ejercicioTemplate = `
        <div class="ejercicio-item mb-3 p-3 border rounded bg-light" data-ejercicio-index="{ejercicioIndex}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Ejercicio</label>
                    <select class="form-select ejercicio-select" name="bloques[{bloqueIndex}][ejercicios][{ejercicioIndex}][ejercicio_id]" required>
                        <option value="">Seleccione un ejercicio</option>
                        <?php foreach ($ejercicios as $ej): ?>
                            <option value="<?php echo $ej['id']; ?>"><?php echo htmlspecialchars($ej['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Tipo de Configuración</label>
                    <select class="form-select tipo-configuracion" name="bloques[{bloqueIndex}][ejercicios][{ejercicioIndex}][tipo_configuracion]" required>
                        <option value="repeticiones">Por Repeticiones</option>
                        <option value="tiempo">Por Tiempo</option>
                        <option value="repeticiones_reserva">Por Repeticiones + Reserva</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Descanso (seg)</label>
                    <input type="number" class="form-control" name="bloques[{bloqueIndex}][ejercicios][{ejercicioIndex}][tiempo_descanso]" 
                           placeholder="60" min="0" value="60">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Acción</label>
                    <button type="button" class="btn btn-danger btn-sm w-100 eliminar-ejercicio">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
            
            <!-- Campos dinámicos según tipo de configuración -->
            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <label class="form-label small text-muted campo-repeticiones-label">Repeticiones</label>
                    <input type="number" class="form-control campo-repeticiones" name="bloques[{bloqueIndex}][ejercicios][{ejercicioIndex}][repeticiones]" 
                           placeholder="Ej: 12" min="1">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted campo-tiempo-label" style="display: none;">Tiempo (segundos)</label>
                    <input type="number" class="form-control campo-tiempo" name="bloques[{bloqueIndex}][ejercicios][{ejercicioIndex}][tiempo]" 
                           placeholder="Ej: 45" min="1" style="display: none;">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted campo-peso-label" style="display: none;">Peso (kg)</label>
                    <input type="number" class="form-control campo-peso" name="bloques[{bloqueIndex}][ejercicios][{ejercicioIndex}][peso_kg]" 
                           placeholder="Ej: 20" min="0" step="0.5" style="display: none;">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted campo-rep-por-hacer-label" style="display: none;">Repeticiones por hacer</label>
                    <input type="number" class="form-control campo-rep-por-hacer" name="bloques[{bloqueIndex}][ejercicios][{ejercicioIndex}][repeticiones_por_hacer]" 
                           placeholder="Ej: 10" min="1" style="display: none;">
                </div>
            </div>
        </div>
    `;
    
    // Agregar un nuevo bloque
    document.getElementById('agregar-bloque').addEventListener('click', function() {
        const nuevoBloque = bloqueTemplate
            .replace(/{index}/g, bloqueCount)
            .replace(/{numero}/g, bloqueCount + 1);
        
        bloquesContainer.insertAdjacentHTML('beforeend', nuevoBloque);
        bloqueCount++;
    });
    
    // Eliminar un bloque
    bloquesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.eliminar-bloque')) {
            const bloque = e.target.closest('.bloque-ejercicios');
            bloque.remove();
            actualizarNumerosBloques();
        }
    });
    
    // Agregar un nuevo ejercicio
    bloquesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.agregar-ejercicio')) {
            const bloque = e.target.closest('.bloque-ejercicios');
            const ejerciciosContainer = bloque.querySelector('.ejercicios-container');
            const bloqueIndex = bloque.dataset.bloqueIndex;
            const ejercicioCount = ejerciciosContainer.querySelectorAll('.ejercicio-item').length;
            
            const nuevoEjercicio = ejercicioTemplate
                .replace(/{bloqueIndex}/g, bloqueIndex)
                .replace(/{ejercicioIndex}/g, ejercicioCount);
            
            ejerciciosContainer.insertAdjacentHTML('beforeend', nuevoEjercicio);
        }
    });
    
    // Eliminar un ejercicio
    bloquesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.eliminar-ejercicio')) {
            const ejercicio = e.target.closest('.ejercicio-item');
            ejercicio.remove();
        }
    });
    
    // Actualizar números de bloques
    function actualizarNumerosBloques() {
        const bloques = bloquesContainer.querySelectorAll('.bloque-ejercicios');
        bloques.forEach((bloque, index) => {
            bloque.querySelector('h5').textContent = `Bloque ${index + 1}`;
            bloque.dataset.bloqueIndex = index;
            
            // Actualizar índices en los inputs
            const inputs = bloque.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/bloques\[\d+\]/, `bloques[${index}]`));
                }
            });
        });
    }
    
    // Validación del formulario
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    });

    // Manejar cambio de tipo de configuración
    bloquesContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('tipo-configuracion')) {
            const ejercicioItem = e.target.closest('.ejercicio-item');
            const tipo = e.target.value;
            
            // Ocultar todos los campos y etiquetas primero
            ejercicioItem.querySelector('.campo-repeticiones').style.display = 'none';
            ejercicioItem.querySelector('.campo-repeticiones-label').style.display = 'none';
            ejercicioItem.querySelector('.campo-tiempo').style.display = 'none';
            ejercicioItem.querySelector('.campo-tiempo-label').style.display = 'none';
            ejercicioItem.querySelector('.campo-peso').style.display = 'none';
            ejercicioItem.querySelector('.campo-peso-label').style.display = 'none';
            ejercicioItem.querySelector('.campo-rep-por-hacer').style.display = 'none';
            ejercicioItem.querySelector('.campo-rep-por-hacer-label').style.display = 'none';
            
            // Mostrar campos según el tipo seleccionado
            switch(tipo) {
                case 'repeticiones':
                    ejercicioItem.querySelector('.campo-repeticiones').style.display = 'block';
                    ejercicioItem.querySelector('.campo-repeticiones-label').style.display = 'block';
                    break;
                case 'tiempo':
                    ejercicioItem.querySelector('.campo-tiempo').style.display = 'block';
                    ejercicioItem.querySelector('.campo-tiempo-label').style.display = 'block';
                    break;
                case 'repeticiones_reserva':
                    ejercicioItem.querySelector('.campo-repeticiones').style.display = 'block';
                    ejercicioItem.querySelector('.campo-repeticiones-label').style.display = 'block';
                    ejercicioItem.querySelector('.campo-peso').style.display = 'block';
                    ejercicioItem.querySelector('.campo-peso-label').style.display = 'block';
                    ejercicioItem.querySelector('.campo-rep-por-hacer').style.display = 'block';
                    ejercicioItem.querySelector('.campo-rep-por-hacer-label').style.display = 'block';
                    break;
            }
        }
    });

    // Inicializar campos según el tipo seleccionado al cargar la página
    document.querySelectorAll('.tipo-configuracion').forEach(select => {
        select.dispatchEvent(new Event('change'));
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?> 