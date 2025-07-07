<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Configuración del Sistema</h2>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <ul class="nav nav-tabs mb-4" id="configTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-tab" data-bs-toggle="tab" href="#general" role="tab">
                                <i class="fas fa-cog me-2"></i>General
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="seguridad-tab" data-bs-toggle="tab" href="#seguridad" role="tab">
                                <i class="fas fa-shield-alt me-2"></i>Seguridad
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="mantenimiento-tab" data-bs-toggle="tab" href="#mantenimiento" role="tab">
                                <i class="fas fa-tools me-2"></i>Mantenimiento
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="configTabContent">
                        <!-- Configuración General --> 
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <form action="/admin/configuracion/guardar" method="POST">
                                <div class="mb-3">
                                    <label for="site_name" class="form-label">Nombre del Sitio</label>
                                    <input type="text" class="form-control" id="site_name" name="site_name" 
                                           value="<?php echo htmlspecialchars($configuracion['site_name'] ?? ''); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="site_description" class="form-label">Descripción del Sitio</label>
                                    <textarea class="form-control" id="site_description" name="site_description" rows="3"><?php echo htmlspecialchars($configuracion['site_description'] ?? ''); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">Email de Contacto</label>
                                    <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                           value="<?php echo htmlspecialchars($configuracion['contact_email'] ?? ''); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="items_per_page" class="form-label">Elementos por Página</label>
                                    <input type="number" class="form-control" id="items_per_page" name="items_per_page" 
                                           value="<?php echo htmlspecialchars($configuracion['items_per_page'] ?? '10'); ?>" required>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Guardar Configuración General
                                </button>
                            </form>
                        </div>

                        <!-- Configuración de Seguridad -->
                        <div class="tab-pane fade" id="seguridad" role="tabpanel">
                            <form action="/admin/configuracion/seguridad" method="POST">
                                <div class="mb-3">
                                    <label for="max_login_attempts" class="form-label">Intentos Máximos de Inicio de Sesión</label>
                                    <input type="number" class="form-control" id="max_login_attempts" name="max_login_attempts" 
                                           value="<?php echo htmlspecialchars($configuracion['max_login_attempts'] ?? '3'); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password_min_length" class="form-label">Longitud Mínima de Contraseña</label>
                                    <input type="number" class="form-control" id="password_min_length" name="password_min_length" 
                                           value="<?php echo htmlspecialchars($configuracion['password_min_length'] ?? '8'); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="require_special_chars" name="require_special_chars" 
                                               value="1" <?php echo ($configuracion['require_special_chars'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="require_special_chars">
                                            Requerir Caracteres Especiales en Contraseña
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="session_timeout" class="form-label">Tiempo de Expiración de Sesión (minutos)</label>
                                    <input type="number" class="form-control" id="session_timeout" name="session_timeout" 
                                           value="<?php echo htmlspecialchars($configuracion['session_timeout'] ?? '30'); ?>" required>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Guardar Configuración de Seguridad
                                </button>
                            </form>
                        </div>

                        <!-- Mantenimiento -->
                        <div class="tab-pane fade" id="mantenimiento" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Backup de Base de Datos</h5>
                                            <p class="card-text">Crear una copia de seguridad de la base de datos.</p>
                                            <a href="/admin/configuracion/backup" class="btn btn-primary">
                                                <i class="fas fa-database me-2"></i>Crear Backup
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Limpiar Cache</h5>
                                            <p class="card-text">Eliminar archivos temporales y cache del sistema.</p>
                                            <a href="/admin/configuracion/limpiar-cache" class="btn btn-warning">
                                                <i class="fas fa-broom me-2"></i>Limpiar Cache
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 