-- Crear tablas faltantes para el sistema de coaching

-- Tabla de suscripciones de coaching
CREATE TABLE `suscripciones_coaching` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `programa_coaching_id` int NOT NULL,
  `stripe_subscription_id` varchar(100) DEFAULT NULL,
  `estado` enum('activa','cancelada','pausada','expirada') NOT NULL DEFAULT 'activa',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `mes_actual` int NOT NULL DEFAULT '1' COMMENT 'Mes actual de la suscripción',
  `ultimo_pago` datetime DEFAULT NULL,
  `proximo_pago` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_usuario_programa` (`usuario_id`,`programa_coaching_id`),
  KEY `programa_coaching_id` (`programa_coaching_id`),
  KEY `stripe_subscription_id` (`stripe_subscription_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabla de accesos a bloques
CREATE TABLE `accesos_bloques` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `bloque_id` int NOT NULL,
  `suscripcion_id` int NOT NULL,
  `desbloqueado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_desbloqueo` datetime DEFAULT NULL,
  `completado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_completado` datetime DEFAULT NULL,
  `progreso` int DEFAULT '0' COMMENT 'Porcentaje de progreso (0-100)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_usuario_bloque_suscripcion` (`usuario_id`,`bloque_id`,`suscripcion_id`),
  KEY `bloque_id` (`bloque_id`),
  KEY `suscripcion_id` (`suscripcion_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabla de pagos de suscripción
CREATE TABLE `pagos_suscripcion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `suscripcion_id` int NOT NULL,
  `stripe_payment_intent_id` varchar(100) NOT NULL,
  `mes` int NOT NULL COMMENT 'Mes correspondiente al pago',
  `monto` decimal(10,2) NOT NULL,
  `moneda` varchar(3) NOT NULL DEFAULT 'EUR',
  `estado` enum('pendiente','completado','fallido','cancelado') NOT NULL DEFAULT 'pendiente',
  `fecha_pago` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `suscripcion_id` (`suscripcion_id`),
  KEY `stripe_payment_intent_id` (`stripe_payment_intent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Mensaje de confirmación
SELECT 'Tablas de coaching creadas exitosamente!' as mensaje; 