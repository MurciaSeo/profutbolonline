<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Configuración del Sistema</h2>
            </div>
        </div>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Configuración General</h5>
                </div>
                <div class="card-body">
                    <form action="/admin/configuracion" method="POST">
                        <div class="mb-3">
                            <label for="nombre_sitio" class="form-label">Nombre del Sitio</label>
                            <input type="text" class="form-control" id="nombre_sitio" name="nombre_sitio" 
                                   value="<?php echo isset($config['nombre_sitio']) ? htmlspecialchars($config['nombre_sitio']) : 'Sistema de Entrenamiento'; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email_contacto" class="form-label">Email de Contacto</label>
                            <input type="email" class="form-control" id="email_contacto" name="email_contacto" 
                                   value="<?php echo isset($config['email_contacto']) ? htmlspecialchars($config['email_contacto']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="max_entrenamientos" class="form-label">Máximo de Entrenamientos por Usuario</label>
                            <input type="number" class="form-control" id="max_entrenamientos" name="max_entrenamientos" 
                                   value="<?php echo isset($config['max_entrenamientos']) ? htmlspecialchars($config['max_entrenamientos']) : '10'; ?>" min="1" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="max_ejercicios" class="form-label">Máximo de Ejercicios por Entrenamiento</label>
                            <input type="number" class="form-control" id="max_ejercicios" name="max_ejercicios" 
                                   value="<?php echo isset($config['max_ejercicios']) ? htmlspecialchars($config['max_ejercicios']) : '20'; ?>" min="1" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="dias_vencimiento" class="form-label">Días para Vencimiento de Entrenamientos</label>
                            <input type="number" class="form-control" id="dias_vencimiento" name="dias_vencimiento" 
                                   value="<?php echo isset($config['dias_vencimiento']) ? htmlspecialchars($config['dias_vencimiento']) : '30'; ?>" min="1" required>
                            <div class="form-text">Número de días antes de que un entrenamiento se considere vencido.</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Configuración
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Configuración de Seguridad</h5>
                </div>
                <div class="card-body">
                    <form action="/admin/configuracion/seguridad" method="POST">
                        <div class="mb-3">
                            <label for="intentos_login" class="form-label">Intentos Máximos de Login</label>
                            <input type="number" class="form-control" id="intentos_login" name="intentos_login" 
                                   value="<?php echo isset($config['intentos_login']) ? htmlspecialchars($config['intentos_login']) : '3'; ?>" min="1" required>
                            <div class="form-text">Número de intentos fallidos antes de bloquear temporalmente la cuenta.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tiempo_bloqueo" class="form-label">Tiempo de Bloqueo (minutos)</label>
                            <input type="number" class="form-control" id="tiempo_bloqueo" name="tiempo_bloqueo" 
                                   value="<?php echo isset($config['tiempo_bloqueo']) ? htmlspecialchars($config['tiempo_bloqueo']) : '15'; ?>" min="1" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="longitud_minima_password" class="form-label">Longitud Mínima de Contraseña</label>
                            <input type="number" class="form-control" id="longitud_minima_password" name="longitud_minima_password" 
                                   value="<?php echo isset($config['longitud_minima_password']) ? htmlspecialchars($config['longitud_minima_password']) : '8'; ?>" min="6" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="requerir_mayuscula" name="requerir_mayuscula" 
                                   <?php echo (isset($config['requerir_mayuscula']) && $config['requerir_mayuscula'] == 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="requerir_mayuscula">Requerir al menos una mayúscula en la contraseña</label>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="requerir_numero" name="requerir_numero" 
                                   <?php echo (isset($config['requerir_numero']) && $config['requerir_numero'] == 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="requerir_numero">Requerir al menos un número en la contraseña</label>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="requerir_caracter_especial" name="requerir_caracter_especial" 
                                   <?php echo (isset($config['requerir_caracter_especial']) && $config['requerir_caracter_especial'] == 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="requerir_caracter_especial">Requerir al menos un carácter especial en la contraseña</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Configuración de Seguridad
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Acciones Peligrosas</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#backupModal">
                            <i class="fas fa-database me-2"></i>Realizar Backup de la Base de Datos
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#limpiarCacheModal">
                            <i class="fas fa-broom me-2"></i>Limpiar Caché del Sistema
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Backup -->
<div class="modal fade" id="backupModal" tabindex="-1" aria-labelledby="backupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="backupModalLabel">Realizar Backup de la Base de Datos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Esta acción creará una copia de seguridad de toda la base de datos. ¿Desea continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="/admin/configuracion/backup" class="btn btn-primary">
                    <i class="fas fa-download me-2"></i>Realizar Backup
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Limpiar Caché -->
<div class="modal fade" id="limpiarCacheModal" tabindex="-1" aria-labelledby="limpiarCacheModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="limpiarCacheModalLabel">Limpiar Caché del Sistema</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Esta acción eliminará todos los archivos de caché del sistema. ¿Desea continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="/admin/configuracion/limpiar-cache" class="btn btn-danger">
                    <i class="fas fa-broom me-2"></i>Limpiar Caché
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 