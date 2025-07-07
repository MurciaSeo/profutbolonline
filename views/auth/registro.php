<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h2 class="text-center mb-0">Registro de Usuario</h2>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="/registro" method="POST">
                        <div class="form-group mb-4">
                            <label for="nombre" class="form-label fw-bold">Nombre:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="apellidos" class="form-label fw-bold">Apellidos:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Tus apellidos" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="email" class="form-label fw-bold">Correo Electrónico:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@correo.com" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="telefono" class="form-label fw-bold">Teléfono Móvil:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="600123456" pattern="[0-9]{9}" title="Introduce un número de teléfono válido de 9 dígitos" required>
                            </div>
                            <div class="form-text">Este número se utilizará para enviar notificaciones por WhatsApp</div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" class="form-label fw-bold">Contraseña:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Tu contraseña" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="confirm_password" class="form-label fw-bold">Confirmar Contraseña:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirma tu contraseña" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Registrarse
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0">¿Ya tienes una cuenta? <a href="/login" class="text-primary fw-bold">Inicia sesión aquí</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 