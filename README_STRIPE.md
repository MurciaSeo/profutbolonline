# Configuración de Stripe para Programas de Entrenamiento

## Descripción

Este sistema permite a los entrenados comprar programas de entrenamiento usando Stripe como pasarela de pagos. Los administradores pueden gestionar precios y ver estadísticas de ventas.

## Características

- ✅ Tienda de programas para entrenados
- ✅ Integración completa con Stripe
- ✅ Gestión de precios por administradores
- ✅ Estadísticas de ventas
- ✅ Historial de compras
- ✅ Webhooks para confirmación automática de pagos
- ✅ Asignación automática de programas tras pago exitoso

## Configuración de Stripe

### 1. Crear cuenta en Stripe

1. Ve a [stripe.com](https://stripe.com) y crea una cuenta
2. Accede al Dashboard de Stripe
3. Obtén las claves de API desde la sección "Developers" > "API keys"

### 2. Configurar variables de entorno

Crea un archivo `.env` en la raíz del proyecto o configura las variables en tu servidor:

```env
# Claves de Stripe (modo test)
STRIPE_PUBLISHABLE_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET_KEY=sk_test_your_secret_key_here
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here

# Para producción, usa las claves live:
# STRIPE_PUBLISHABLE_KEY=pk_live_your_publishable_key_here
# STRIPE_SECRET_KEY=sk_live_your_secret_key_here
# STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here
```

### 3. Configurar Webhook

1. En el Dashboard de Stripe, ve a "Developers" > "Webhooks"
2. Crea un nuevo endpoint con la URL: `https://tudominio.com/programas/webhook-stripe`
3. Selecciona los siguientes eventos:
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
   - `payment_intent.canceled`
4. Copia el "Signing secret" y configúralo como `STRIPE_WEBHOOK_SECRET`

### 4. Instalar SDK de Stripe

```bash
composer require stripe/stripe-php
```

O descarga manualmente desde: https://github.com/stripe/stripe-php

## Estructura de Base de Datos

### Tabla `pagos`
```sql
CREATE TABLE `pagos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `programacion_id` int NOT NULL,
  `stripe_payment_intent_id` varchar(255) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `moneda` varchar(3) NOT NULL DEFAULT 'EUR',
  `estado` enum('pendiente','completado','fallido','cancelado') NOT NULL DEFAULT 'pendiente',
  `fecha_pago` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `programacion_id` (`programacion_id`),
  KEY `stripe_payment_intent_id` (`stripe_payment_intent_id`),
  CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`programacion_id`) REFERENCES `programaciones` (`id`) ON DELETE CASCADE
);
```

### Tabla `programas_precios`
```sql
CREATE TABLE `programas_precios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `programacion_id` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `moneda` varchar(3) NOT NULL DEFAULT 'EUR',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `programacion_id` (`programacion_id`),
  CONSTRAINT `programas_precios_ibfk_1` FOREIGN KEY (`programacion_id`) REFERENCES `programaciones` (`id`) ON DELETE CASCADE
);
```

## Uso del Sistema

### Para Entrenados

1. **Acceder a la tienda**: `/programas/tienda`
2. **Ver programas disponibles** con precios
3. **Comprar un programa**: Hacer clic en "Comprar"
4. **Completar el pago** con tarjeta de crédito/débito
5. **Ver historial de compras**: `/programas/mis-compras`

### Para Administradores

1. **Gestionar precios**: `/programas/gestionar-precios`
2. **Ver estadísticas**: `/programas/estadisticas-ventas`
3. **Configurar programas** con precios
4. **Monitorear ventas** y pagos

## Flujo de Pago

1. **Entrenado selecciona programa** en la tienda
2. **Sistema crea Payment Intent** en Stripe
3. **Entrenado ingresa datos de tarjeta**
4. **Stripe procesa el pago**
5. **Webhook confirma el pago**
6. **Sistema asigna programa** al usuario
7. **Entrenado puede acceder** al programa

## Archivos Principales

- `controllers/ProgramaController.php` - Controlador principal
- `models/PagoModel.php` - Modelo de pagos
- `models/ProgramaPrecioModel.php` - Modelo de precios
- `views/programas/` - Vistas de la tienda
- `config/stripe.php` - Configuración de Stripe

## Seguridad

- ✅ Validación de webhooks con firma
- ✅ Verificación de montos
- ✅ Logs de todas las transacciones
- ✅ Manejo de errores robusto
- ✅ Prevención de pagos duplicados

## Testing

Para probar en modo desarrollo:

1. Usa las claves de test de Stripe
2. Usa tarjetas de prueba:
   - `4242 4242 4242 4242` (pago exitoso)
   - `4000 0000 0000 0002` (pago rechazado)
3. Verifica los logs en `logs/stripe.log`

## Monitoreo

- Revisa los logs de Stripe regularmente
- Monitorea las estadísticas de ventas
- Verifica que los webhooks funcionen correctamente
- Revisa los pagos fallidos

## Soporte

Para problemas con Stripe:
- [Documentación de Stripe](https://stripe.com/docs)
- [Soporte de Stripe](https://support.stripe.com)

Para problemas con el sistema:
- Revisa los logs en `logs/stripe.log`
- Verifica la configuración en `config/stripe.php`
- Comprueba que las tablas de base de datos estén creadas 