<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Crear Nuevo Entrenamiento</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['error']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/entrenamientos/crear" id="crearEntrenamientoForm" novalidate>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Entrenamiento</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?php echo htmlspecialchars($form_data['nombre'] ?? ''); ?>" required>
                            <div class="invalid-feedback">Por favor, ingrese un nombre válido.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Entrenamiento</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Seleccione un tipo</option>
                                <?php foreach ($tipos_ejercicios as $tipo): ?>
                                    <option value="<?php echo $tipo['id']; ?>" <?php echo (isset($form_data['tipo']) && $form_data['tipo'] == $tipo['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($tipo['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Por favor, seleccione un tipo de entrenamiento.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php 
                                echo htmlspecialchars($form_data['descripcion'] ?? ''); 
                            ?></textarea>
                            <div class="invalid-feedback">Por favor, ingrese una descripción.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Bloques de Ejercicios</label>
                            <div id="bloques-container">
                                <div class="bloque-ejercicios card mb-3">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Bloque 1</h5>
                                        <button type="button" class="btn btn-danger btn-sm eliminar-bloque">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nombre del Bloque</label>
                                            <input type="text" class="form-control" name="bloques[0][nombre]" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Descripción del Bloque</label>
                                            <textarea class="form-control" name="bloques[0][descripcion]" rows="2"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Número de Series</label>
                                            <input type="number" class="form-control" name="bloques[0][serie]" 
                                                   placeholder="Número de series" min="1" value="1" required>
                                            <small class="form-text text-muted">Número de veces que se repite este bloque</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Ejercicios</label>
                                            <div class="ejercicios-container">
                                                <div class="ejercicio-item mb-3 p-3 border rounded bg-light">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label small text-muted">Ejercicio</label>
                                                            <select class="form-select ejercicio-select" name="bloques[0][ejercicios][0][ejercicio_id]" required>
                                                                <option value="">Seleccione un ejercicio</option>
                                                                <?php foreach ($ejercicios as $ejercicio): ?>
                                                                    <option value="<?php echo $ejercicio['id']; ?>">
                                                                        <?php echo htmlspecialchars($ejercicio['nombre']); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small text-muted">Tipo de Configuración</label>
                                                            <select class="form-select tipo-configuracion" name="bloques[0][ejercicios][0][tipo_configuracion]" required>
                                                                <option value="repeticiones">Por Repeticiones</option>
                                                                <option value="tiempo">Por Tiempo</option>
                                                                <option value="repeticiones_reserva">Por Repeticiones + Reserva</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label small text-muted">Descanso (seg)</label>
                                                            <input type="number" class="form-control" name="bloques[0][ejercicios][0][tiempo_descanso]" 
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
                                                            <input type="number" class="form-control campo-repeticiones" name="bloques[0][ejercicios][0][repeticiones]" 
                                                                   placeholder="Ej: 12" min="1">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small text-muted campo-tiempo-label" style="display: none;">Tiempo (segundos)</label>
                                                            <input type="number" class="form-control campo-tiempo" name="bloques[0][ejercicios][0][tiempo]" 
                                                                   placeholder="Ej: 45" min="1" style="display: none;">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small text-muted campo-peso-label" style="display: none;">Peso (kg)</label>
                                                            <input type="number" class="form-control campo-peso" name="bloques[0][ejercicios][0][peso_kg]" 
                                                                   placeholder="Ej: 20" min="0" step="0.5" style="display: none;">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small text-muted campo-rep-por-hacer-label" style="display: none;">Repeticiones por hacer</label>
                                                            <input type="number" class="form-control campo-rep-por-hacer" name="bloques[0][ejercicios][0][repeticiones_por_hacer]" 
                                                                   placeholder="Ej: 10" min="1" style="display: none;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-secondary btn-sm agregar-ejercicio">
                                                <i class="fas fa-plus"></i> Agregar Ejercicio
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" id="agregar-bloque">
                                <i class="fas fa-plus"></i> Agregar Bloque
                            </button>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Crear Entrenamiento</button>
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
    const form = document.getElementById('crearEntrenamientoForm');
    const bloquesContainer = document.getElementById('bloques-container');
    let bloqueCount = 1;
    let ejercicioCount = 1;

    // Agregar nuevo bloque
    document.getElementById('agregar-bloque').addEventListener('click', function() {
        const nuevoBloque = document.createElement('div');
        nuevoBloque.className = 'bloque-ejercicios card mb-3';
        nuevoBloque.innerHTML = `
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Bloque ${bloqueCount + 1}</h5>
                    <button type="button" class="btn btn-danger btn-sm eliminar-bloque">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Bloque</label>
                        <input type="text" class="form-control" name="bloques[${bloqueCount}][nombre]" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción del Bloque</label>
                        <textarea class="form-control" name="bloques[${bloqueCount}][descripcion]" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Número de Series</label>
                        <input type="number" class="form-control" name="bloques[${bloqueCount}][serie]" 
                               placeholder="Número de series" min="1" value="1" required>
                        <small class="form-text text-muted">Número de veces que se repite este bloque</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ejercicios</label>
                        <div class="ejercicios-container">
                            <div class="ejercicio-item mb-3 p-3 border rounded bg-light">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted">Ejercicio</label>
                                        <select class="form-select ejercicio-select" name="bloques[${bloqueCount}][ejercicios][0][ejercicio_id]" required>
                                            <option value="">Seleccione un ejercicio</option>
                                            <?php foreach ($ejercicios as $ejercicio): ?>
                                                <option value="<?php echo $ejercicio['id']; ?>">
                                                    <?php echo htmlspecialchars($ejercicio['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted">Tipo de Configuración</label>
                                        <select class="form-select tipo-configuracion" name="bloques[${bloqueCount}][ejercicios][0][tipo_configuracion]" required>
                                            <option value="repeticiones">Por Repeticiones</option>
                                            <option value="tiempo">Por Tiempo</option>
                                            <option value="repeticiones_reserva">Por Repeticiones + Reserva</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small text-muted">Descanso (seg)</label>
                                        <input type="number" class="form-control" name="bloques[${bloqueCount}][ejercicios][0][tiempo_descanso]" 
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
                                        <input type="number" class="form-control campo-repeticiones" name="bloques[${bloqueCount}][ejercicios][0][repeticiones]" 
                                               placeholder="Ej: 12" min="1">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted campo-tiempo-label" style="display: none;">Tiempo (segundos)</label>
                                        <input type="number" class="form-control campo-tiempo" name="bloques[${bloqueCount}][ejercicios][0][tiempo]" 
                                               placeholder="Ej: 45" min="1" style="display: none;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted campo-peso-label" style="display: none;">Peso (kg)</label>
                                        <input type="number" class="form-control campo-peso" name="bloques[${bloqueCount}][ejercicios][0][peso_kg]" 
                                               placeholder="Ej: 20" min="0" step="0.5" style="display: none;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted campo-rep-por-hacer-label" style="display: none;">Repeticiones por hacer</label>
                                        <input type="number" class="form-control campo-rep-por-hacer" name="bloques[${bloqueCount}][ejercicios][0][repeticiones_por_hacer]" 
                                               placeholder="Ej: 10" min="1" style="display: none;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm agregar-ejercicio">
                            <i class="fas fa-plus"></i> Agregar Ejercicio
                        </button>
                </div>
            </div>
        `;
        bloquesContainer.appendChild(nuevoBloque);
        bloqueCount++;
    });

    // Eliminar bloque
    bloquesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.eliminar-bloque')) {
            const bloque = e.target.closest('.bloque-ejercicios');
            if (bloquesContainer.children.length > 1) {
                bloque.remove();
                // Renumerar bloques
                const bloques = bloquesContainer.querySelectorAll('.bloque-ejercicios');
                bloques.forEach((bloque, index) => {
                    bloque.querySelector('h5').textContent = `Bloque ${index + 1}`;
                    // Actualizar índices en los nombres de los campos
                    const inputs = bloque.querySelectorAll('[name^="bloques["]');
                    inputs.forEach(input => {
                        const name = input.name;
                        const newName = name.replace(/bloques\[\d+\]/, `bloques[${index}]`);
                        input.name = newName;
                    });
                });
                bloqueCount = bloques.length;
            }
        }
    });

    // Agregar ejercicio a un bloque
    bloquesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.agregar-ejercicio')) {
            const bloque = e.target.closest('.bloque-ejercicios');
            const ejerciciosContainer = bloque.querySelector('.ejercicios-container');
            const ejercicioCount = ejerciciosContainer.children.length;
            const bloqueIndex = Array.from(bloquesContainer.children).indexOf(bloque);
            
            const ejercicioTemplate = `
                <div class="ejercicio-item mb-3 p-3 border rounded bg-light">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Ejercicio</label>
                            <select class="form-select ejercicio-select" name="bloques[${bloqueIndex}][ejercicios][${ejercicioCount}][ejercicio_id]" required>
                                <option value="">Seleccione un ejercicio</option>
                                <?php foreach ($ejercicios as $ejercicio): ?>
                                    <option value="<?php echo $ejercicio['id']; ?>">
                                        <?php echo htmlspecialchars($ejercicio['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Tipo de Configuración</label>
                            <select class="form-select tipo-configuracion" name="bloques[${bloqueIndex}][ejercicios][${ejercicioCount}][tipo_configuracion]" required>
                                <option value="repeticiones">Por Repeticiones</option>
                                <option value="tiempo">Por Tiempo</option>
                                <option value="repeticiones_reserva">Por Repeticiones + Reserva</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Descanso (seg)</label>
                            <input type="number" class="form-control" name="bloques[${bloqueIndex}][ejercicios][${ejercicioCount}][tiempo_descanso]" 
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
                            <input type="number" class="form-control campo-repeticiones" name="bloques[${bloqueIndex}][ejercicios][${ejercicioCount}][repeticiones]" 
                                   placeholder="Ej: 12" min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted campo-tiempo-label" style="display: none;">Tiempo (segundos)</label>
                            <input type="number" class="form-control campo-tiempo" name="bloques[${bloqueIndex}][ejercicios][${ejercicioCount}][tiempo]" 
                                   placeholder="Ej: 45" min="1" style="display: none;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted campo-peso-label" style="display: none;">Peso (kg)</label>
                            <input type="number" class="form-control campo-peso" name="bloques[${bloqueIndex}][ejercicios][${ejercicioCount}][peso_kg]" 
                                   placeholder="Ej: 20" min="0" step="0.5" style="display: none;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted campo-rep-por-hacer-label" style="display: none;">Repeticiones por hacer</label>
                            <input type="number" class="form-control campo-rep-por-hacer" name="bloques[${bloqueIndex}][ejercicios][${ejercicioCount}][repeticiones_por_hacer]" 
                                   placeholder="Ej: 10" min="1" style="display: none;">
                        </div>
                    </div>
                </div>
            `;
            ejerciciosContainer.insertAdjacentHTML('beforeend', ejercicioTemplate);
        }
    });

    // Eliminar ejercicio de un bloque
    bloquesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.eliminar-ejercicio')) {
            const ejercicio = e.target.closest('.ejercicio-item');
            const ejerciciosContainer = ejercicio.parentElement;
            if (ejerciciosContainer.children.length > 1) {
                ejercicio.remove();
            }
        }
    });

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