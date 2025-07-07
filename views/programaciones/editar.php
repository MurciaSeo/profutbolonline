<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Editar Programa de Entrenamiento</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <form action="/programaciones/actualizar/<?php echo $programacion['id']; ?>" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del Programa</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars($programacion['nombre']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" 
                                              rows="3"><?php echo htmlspecialchars($programacion['descripcion']); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="nivel" class="form-label">Nivel</label>
                                    <select class="form-select" id="nivel" name="nivel">
                                        <option value="principiante" <?php echo $programacion['nivel'] == 'principiante' ? 'selected' : ''; ?>>Principiante</option>
                                        <option value="intermedio" <?php echo $programacion['nivel'] == 'intermedio' ? 'selected' : ''; ?>>Intermedio</option>
                                        <option value="avanzado" <?php echo $programacion['nivel'] == 'avanzado' ? 'selected' : ''; ?>>Avanzado</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="objetivo" class="form-label">Objetivo</label>
                                    <input type="text" class="form-control" id="objetivo" name="objetivo" 
                                           value="<?php echo htmlspecialchars($programacion['objetivo']); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Se eliminó la sección de días de entrenamiento -->
                            </div>
                        </div>

                        <hr>
                        <h4 class="mb-3">Semanas y Entrenamientos</h4>
                        
                        <div id="semanas-container">
                            <?php foreach ($programacion['semanas'] as $index => $semana): ?>
                                <div class="semana-group mb-4">
                                    <h5>Semana <?php echo $semana['numero_semana']; ?></h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Día</th>
                                                    <th>Entrenamiento</th>
                                                    <th>Orden</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($semana['dias'] as $dia): ?>
                                                    <tr>
                                                        <td>
                                                            <select class="form-select" name="semanas[<?php echo $index; ?>][dias][<?php echo $dia['id']; ?>][dia_semana]">
                                                                <option value="1" <?php echo $dia['dia_semana'] == 1 ? 'selected' : ''; ?>>Lunes</option>
                                                                <option value="2" <?php echo $dia['dia_semana'] == 2 ? 'selected' : ''; ?>>Martes</option>
                                                                <option value="3" <?php echo $dia['dia_semana'] == 3 ? 'selected' : ''; ?>>Miércoles</option>
                                                                <option value="4" <?php echo $dia['dia_semana'] == 4 ? 'selected' : ''; ?>>Jueves</option>
                                                                <option value="5" <?php echo $dia['dia_semana'] == 5 ? 'selected' : ''; ?>>Viernes</option>
                                                                <option value="6" <?php echo $dia['dia_semana'] == 6 ? 'selected' : ''; ?>>Sábado</option>
                                                                <option value="7" <?php echo $dia['dia_semana'] == 7 ? 'selected' : ''; ?>>Domingo</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="semanas[<?php echo $index; ?>][dias][<?php echo $dia['id']; ?>][entrenamiento_id]" class="form-select" required>
                                                                <option value="">Seleccione un entrenamiento</option>
                                                                <?php foreach ($entrenamientos as $entrenamiento): ?>
                                                                    <option value="<?php echo $entrenamiento['id']; ?>" 
                                                                            <?php echo (isset($dia['entrenamiento_id']) && $dia['entrenamiento_id'] == $entrenamiento['id']) ? 'selected' : ''; ?>>
                                                                        <?php echo htmlspecialchars($entrenamiento['nombre']); ?> - 
                                                                        <?php echo htmlspecialchars($entrenamiento['usuario_nombre'] . ' ' . $entrenamiento['usuario_apellido']); ?>
                                                                        (<?php echo $entrenamiento['total_ejercicios']; ?> ejercicios)
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" 
                                                                   name="semanas[<?php echo $index; ?>][dias][<?php echo $dia['id']; ?>][orden]"
                                                                   value="<?php echo $dia['orden']; ?>" min="1">
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Actualizar Programa</button>
                            <a href="/programaciones" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const entrenamientosPorSemana = document.getElementById('entrenamientos_por_semana');
    const diasCheckboxes = document.querySelectorAll('input[name="dias_semana[]"]');
    
    // Función para validar días seleccionados
    function validarDiasSeleccionados() {
        const diasSeleccionados = Array.from(diasCheckboxes).filter(cb => cb.checked).length;
        const maxEntrenamientos = parseInt(entrenamientosPorSemana.value);
        
        if (diasSeleccionados > maxEntrenamientos) {
            alert(`No puedes seleccionar más días que el número de entrenamientos por semana (${maxEntrenamientos})`);
            return false;
        }
        return true;
    }
    
    // Manejar cambios en entrenamientos por semana
    entrenamientosPorSemana.addEventListener('change', function() {
        const maxEntrenamientos = parseInt(this.value);
        const diasSeleccionados = Array.from(diasCheckboxes).filter(cb => cb.checked).length;
        
        if (diasSeleccionados > maxEntrenamientos) {
            alert(`El número de días seleccionados (${diasSeleccionados}) no puede ser mayor que el número de entrenamientos por semana (${maxEntrenamientos})`);
            this.value = diasSeleccionados;
        }
    });
    
    // Manejar cambios en los checkboxes de días
    diasCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!validarDiasSeleccionados()) {
                this.checked = false;
            }
        });
    });
    
    // Validar formulario antes de enviar
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!validarDiasSeleccionados()) {
            e.preventDefault();
            return;
        }
        
        // Validar que cada semana tenga al menos un día con entrenamiento
        const semanas = document.querySelectorAll('.semana-group');
        let error = false;
        
        semanas.forEach((semana, index) => {
            const entrenamientos = semana.querySelectorAll('select[name*="[entrenamiento_id]"]');
            let tieneEntrenamiento = false;
            
            entrenamientos.forEach(select => {
                if (select.value !== '') {
                    tieneEntrenamiento = true;
                }
            });
            
            if (!tieneEntrenamiento) {
                alert(`La semana ${index + 1} debe tener al menos un entrenamiento asignado.`);
                error = true;
            }
        });
        
        if (error) {
            e.preventDefault();
        }
    });
    
    // Manejar eliminación de días
    document.querySelectorAll('.eliminar-dia').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('¿Estás seguro de que deseas eliminar este día?')) {
                this.closest('tr').remove();
            }
        });
    });
    
    // Manejar agregar días
    document.querySelectorAll('.agregar-dia').forEach(button => {
        button.addEventListener('click', function() {
            const semanaIndex = this.dataset.semana;
            const tbody = this.closest('table').querySelector('tbody');
            const newRow = document.createElement('tr');
            const diaId = 'nuevo_' + Date.now(); // ID temporal para nuevo día
            
            newRow.innerHTML = `
                <td>
                    <select class="form-select" name="semanas[${semanaIndex}][dias][${diaId}][dia_semana]">
                        <option value="1">Lunes</option>
                        <option value="2">Martes</option>
                        <option value="3">Miércoles</option>
                        <option value="4">Jueves</option>
                        <option value="5">Viernes</option>
                        <option value="6">Sábado</option>
                        <option value="7">Domingo</option>
                    </select>
                </td>
                <td>
                    <select name="semanas[${semanaIndex}][dias][${diaId}][entrenamiento_id]" class="form-select" required>
                        <option value="">Seleccione un entrenamiento</option>
                        ${Array.from(document.querySelector('select[name*="[entrenamiento_id]"]').options)
                            .map(opt => `<option value="${opt.value}">${opt.text}</option>`).join('')}
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control" 
                           name="semanas[${semanaIndex}][dias][${diaId}][orden]"
                           value="1" min="1">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm eliminar-dia">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(newRow);
            
            // Agregar evento de eliminación al nuevo botón
            newRow.querySelector('.eliminar-dia').addEventListener('click', function() {
                if (confirm('¿Estás seguro de que deseas eliminar este día?')) {
                    this.closest('tr').remove();
                }
            });
        });
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?> 