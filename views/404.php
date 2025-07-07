<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-template p-5">
                <h1 class="display-1 text-primary fw-bold">404</h1>
                <h2 class="mb-4">Página no encontrada</h2>
                <div class="error-details mb-4">
                    <p class="lead text-muted">Lo sentimos, la página que estás buscando no existe o ha sido movida.</p>
                </div>
                <div class="error-actions">
                    <a href="/dashboard" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-home me-2"></i> Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 