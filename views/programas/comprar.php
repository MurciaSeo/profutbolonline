<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header <?php echo $tipo === 'entrenamiento' ? 'bg-success' : 'bg-info'; ?> text-white">
                    <h3 class="card-title mb-0">
                        <?php if ($tipo === 'entrenamiento'): ?>
                            <i class="fas fa-dumbbell me-2"></i>
                        <?php else: ?>
                            <i class="fas fa-graduation-cap me-2"></i>
                        <?php endif; ?>
                        <?php echo $tipo === 'entrenamiento' ? 'Comprar Programa' : 'Suscribirse al Programa'; ?>
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="text-primary mb-3"><?php echo htmlspecialchars($programa['nombre']); ?></h4>
                            <p class="text-muted mb-4"><?php echo htmlspecialchars($programa['descripcion']); ?></p>
                            
                            <?php if ($tipo === 'entrenamiento'): ?>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-calendar text-success me-2"></i>
                                            <span><strong>Duración:</strong> <?php echo $programa['duracion_semanas']; ?> semanas</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-dumbbell text-success me-2"></i>
                                            <span><strong>Entrenamientos/semana:</strong> <?php echo $programa['entrenamientos_por_semana']; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-signal text-success me-2"></i>
                                            <span><strong>Nivel:</strong> <?php echo ucfirst($programa['nivel']); ?></span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-bullseye text-success me-2"></i>
                                            <span><strong>Objetivo:</strong> <?php echo htmlspecialchars($programa['objetivo']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-calendar-alt text-info me-2"></i>
                                            <span><strong>Duración:</strong> <?php echo $programa['duracion_meses']; ?> meses</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-tag text-info me-2"></i>
                                            <span><strong>Categoría:</strong> <?php echo ucfirst($programa['categoria']); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-unlock text-info me-2"></i>
                                            <span><strong>Desbloqueo:</strong> Mensual</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-play-circle text-info me-2"></i>
                                            <span><strong>Contenido:</strong> Videos, textos, ejercicios</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Precio</h5>
                                    <div class="display-4 text-primary mb-2">
                                        <?php if ($tipo === 'entrenamiento'): ?>
                                            <?php echo number_format($precio['precio'], 2); ?> €
                                        <?php else: ?>
                                            <?php echo number_format($programa['precio_mensual'], 2); ?> €
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-muted">
                                        <?php if ($tipo === 'entrenamiento'): ?>
                                            <i class="fas fa-check-circle text-success me-1"></i>
                                            Pago único
                                        <?php else: ?>
                                            <i class="fas fa-sync-alt text-info me-1"></i>
                                            Pago mensual
                                        <?php endif; ?>
                                    </p>
                                    
                                    <div class="mt-3">
                                        <button id="pagar-btn" class="btn btn-primary btn-lg w-100">
                                            <i class="fas fa-credit-card me-2"></i>
                                            <?php echo $tipo === 'entrenamiento' ? 'Pagar Ahora' : 'Suscribirse Ahora'; ?>
                                        </button>
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

<!-- Modal de pago -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">
                    <i class="fas fa-credit-card me-2"></i>
                    <?php echo $tipo === 'entrenamiento' ? 'Completar Compra' : 'Completar Suscripción'; ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="payment-form">
                    <div id="card-element" class="mb-3">
                        <!-- Stripe Elements se insertará aquí -->
                    </div>
                    <div id="card-errors" class="alert alert-danger" style="display: none;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="confirm-payment" class="btn btn-primary" disabled>
                    <i class="fas fa-spinner fa-spin me-2" style="display: none;"></i>
                    <?php echo $tipo === 'entrenamiento' ? 'Confirmar Compra' : 'Confirmar Suscripción'; ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('<?php echo $_ENV['STRIPE_PUBLISHABLE_KEY']; ?>');
const elements = stripe.elements();
const card = elements.create('card', {
    style: {
        base: {
            fontSize: '16px',
            color: '#424770',
            '::placeholder': {
                color: '#aab7c4',
            },
        },
        invalid: {
            color: '#9e2146',
        },
    },
});

card.mount('#card-element');

card.addEventListener('change', function(event) {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
        displayError.style.display = 'block';
    } else {
        displayError.textContent = '';
        displayError.style.display = 'none';
    }
});

document.getElementById('pagar-btn').addEventListener('click', function() {
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
});

document.getElementById('confirm-payment').addEventListener('click', async function() {
    const button = this;
    const spinner = button.querySelector('.fa-spinner');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    spinner.style.display = 'inline-block';
    
    try {
        // Crear Payment Intent
        const response = await fetch('/programas/crear-payment-intent', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                programa_id: <?php echo $programa['id']; ?>,
                tipo: '<?php echo $tipo; ?>'
            })
        });
        
        const data = await response.json();
        
        if (data.error) {
            throw new Error(data.error);
        }
        
        // Confirmar pago
        const result = await stripe.confirmCardPayment(data.client_secret, {
            payment_method: {
                card: card,
                billing_details: {
                    name: '<?php echo $_SESSION['user_name'] ?? 'Usuario'; ?>',
                },
            }
        });
        
        if (result.error) {
            throw new Error(result.error.message);
        }
        
        // Procesar pago exitoso
        const processResponse = await fetch('/programas/procesar-pago', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                payment_intent_id: result.paymentIntent.id,
                programa_id: <?php echo $programa['id']; ?>,
                tipo: '<?php echo $tipo; ?>'
            })
        });
        
        const processData = await processResponse.json();
        
        if (processData.error) {
            throw new Error(processData.error);
        }
        
        // Éxito
        window.location.href = '/programas/mis-compras';
        
    } catch (error) {
        console.error('Error:', error);
        const errorDiv = document.getElementById('card-errors');
        errorDiv.textContent = error.message;
        errorDiv.style.display = 'block';
        
        button.disabled = false;
        spinner.style.display = 'none';
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?> 