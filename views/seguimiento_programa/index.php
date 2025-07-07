<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <h1>Mis Programas de Entrenamiento</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($programas)): ?>
        <div class="alert alert-info">
            No tienes programas de entrenamiento asignados.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($programas as $programa): ?>
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($programa['nombre']); ?></h5>
                            <p class="card-text">
                                <strong>Descripción:</strong> <?php echo htmlspecialchars($programa['descripcion']); ?><br>
                                <strong>Duración:</strong> <?php echo $programa['duracion_semanas']; ?> semanas<br>
                                <strong>Entrenamientos por semana:</strong> <?php echo $programa['entrenamientos_por_semana']; ?><br>
                                <strong>Nivel:</strong> <?php echo htmlspecialchars($programa['nivel']); ?><br>
                                <strong>Objetivo:</strong> <?php echo htmlspecialchars($programa['objetivo']); ?>
                            </p>
                            <a href="/seguimiento-programa/ver/<?php echo $programa['programacion_id']; ?>" class="btn btn-primary">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 