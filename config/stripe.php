<?php
// Configuración de Stripe
// Estas variables deben configurarse en el servidor o en un archivo .env

// Claves de Stripe (reemplazar con las claves reales)
define('STRIPE_PUBLISHABLE_KEY', $_ENV['STRIPE_PUBLISHABLE_KEY'] ?? 'pk_test_your_publishable_key');
define('STRIPE_SECRET_KEY', $_ENV['STRIPE_SECRET_KEY'] ?? 'sk_test_your_secret_key');
define('STRIPE_WEBHOOK_SECRET', $_ENV['STRIPE_WEBHOOK_SECRET'] ?? 'whsec_your_webhook_secret');

// Configuración adicional
define('STRIPE_CURRENCY', 'eur'); // Moneda por defecto
define('STRIPE_MODE', 'test'); // 'test' o 'live'

// URLs de webhook
define('STRIPE_WEBHOOK_URL', '/programas/webhook-stripe');

// Configuración de productos
define('STRIPE_PRODUCT_NAME', 'Programa de Entrenamiento');
define('STRIPE_PRODUCT_DESCRIPTION', 'Acceso completo al programa de entrenamiento personalizado');

// Configuración de pagos
define('STRIPE_PAYMENT_METHODS', ['card']); // Métodos de pago permitidos
define('STRIPE_CAPTURE_METHOD', 'automatic'); // 'automatic' o 'manual'

// Configuración de devoluciones
define('STRIPE_REFUND_ENABLED', true);
define('STRIPE_REFUND_DAYS', 30); // Días para solicitar devolución

// Configuración de impuestos (si aplica)
define('STRIPE_TAX_ENABLED', false);
define('STRIPE_TAX_RATE', 0.21); // 21% IVA en España

// Configuración de notificaciones
define('STRIPE_EMAIL_RECEIPTS', true);
define('STRIPE_EMAIL_RECEIPT_DESCRIPTION', 'Recibo de compra - Programa de Entrenamiento');

// Configuración de seguridad
define('STRIPE_3D_SECURE', true); // Requerir autenticación 3D Secure
define('STRIPE_SAVE_PAYMENT_METHOD', false); // No guardar métodos de pago por defecto

// Configuración de webhooks
define('STRIPE_WEBHOOK_EVENTS', [
    'payment_intent.succeeded',
    'payment_intent.payment_failed',
    'payment_intent.canceled',
    'charge.refunded',
    'customer.subscription.created',
    'customer.subscription.updated',
    'customer.subscription.deleted'
]);

// Configuración de logs
define('STRIPE_LOG_ENABLED', true);
define('STRIPE_LOG_FILE', __DIR__ . '/../logs/stripe.log');

// Función para obtener la clave de Stripe según el modo
function getStripeKey($type = 'publishable') {
    if (STRIPE_MODE === 'live') {
        return $type === 'publishable' ? STRIPE_PUBLISHABLE_KEY : STRIPE_SECRET_KEY;
    } else {
        return $type === 'publishable' ? STRIPE_PUBLISHABLE_KEY : STRIPE_SECRET_KEY;
    }
}

// Función para validar la configuración
function validateStripeConfig() {
    $errors = [];
    
    if (empty(STRIPE_PUBLISHABLE_KEY) || STRIPE_PUBLISHABLE_KEY === 'pk_test_your_publishable_key') {
        $errors[] = 'STRIPE_PUBLISHABLE_KEY no está configurada';
    }
    
    if (empty(STRIPE_SECRET_KEY) || STRIPE_SECRET_KEY === 'sk_test_your_secret_key') {
        $errors[] = 'STRIPE_SECRET_KEY no está configurada';
    }
    
    if (empty(STRIPE_WEBHOOK_SECRET) || STRIPE_WEBHOOK_SECRET === 'whsec_your_webhook_secret') {
        $errors[] = 'STRIPE_WEBHOOK_SECRET no está configurada';
    }
    
    return $errors;
}

// Función para log de Stripe
function logStripe($message, $level = 'info') {
    if (!STRIPE_LOG_ENABLED) {
        return;
    }
    
    $logDir = dirname(STRIPE_LOG_FILE);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$level] $message" . PHP_EOL;
    
    file_put_contents(STRIPE_LOG_FILE, $logMessage, FILE_APPEND | LOCK_EX);
} 