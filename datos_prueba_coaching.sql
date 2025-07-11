-- Datos de prueba para el sistema de coaching
-- Este script crea un programa completo con suscripciones, accesos y pagos de prueba

-- 1. Crear un nuevo programa de coaching más detallado
INSERT INTO `programas_coaching` (`id`, `nombre`, `descripcion`, `categoria`, `duracion_meses`, `precio_mensual`, `moneda`, `imagen_url`, `activo`, `created_at`, `updated_at`) VALUES
(5, 'Coaching de Alto Rendimiento', 'Programa integral para deportistas que buscan alcanzar su máximo potencial. Incluye técnicas de mentalización, nutrición avanzada, recuperación y planificación estratégica.', 'mental', 8, 39.99, 'EUR', '/img/programas/alto-rendimiento.jpg', 1, NOW(), NOW());

-- 2. Crear bloques detallados para el programa
INSERT INTO `coaching_bloques` (`id`, `programa_coaching_id`, `mes`, `titulo`, `descripcion`, `contenido`, `tipo_contenido`, `url_contenido`, `duracion_minutos`, `orden`, `created_at`) VALUES
-- Mes 1: Fundamentos
(10, 5, 1, 'Fundamentos del Alto Rendimiento', 'Introducción a los principios básicos del alto rendimiento deportivo', 
'<h2>Fundamentos del Alto Rendimiento</h2>
<p>Bienvenido al programa de Coaching de Alto Rendimiento. En este primer módulo estableceremos las bases fundamentales para tu desarrollo como deportista de élite.</p>

<h3>¿Qué aprenderás en este módulo?</h3>
<ul>
<li>Los 5 pilares del alto rendimiento deportivo</li>
<li>Evaluación de tu estado actual</li>
<li>Establecimiento de objetivos SMART</li>
<li>Creación de tu plan de acción personalizado</li>
</ul>

<h3>Contenido del módulo:</h3>
<ol>
<li><strong>Introducción al Alto Rendimiento</strong>
<p>Definiremos qué significa realmente el alto rendimiento y cómo se aplica a tu deporte específico.</p>
</li>

<li><strong>Evaluación Personal</strong>
<p>Realizarás una evaluación completa de tu estado actual en todas las áreas del rendimiento.</p>
</li>

<li><strong>Establecimiento de Objetivos</strong>
<p>Aprenderás a crear objetivos específicos, medibles, alcanzables, relevantes y con tiempo definido.</p>
</li>

<li><strong>Plan de Acción</strong>
<p>Desarrollaremos tu plan personalizado para los próximos 8 meses.</p>
</li>
</ol>

<h3>Ejercicios Prácticos:</h3>
<p>Al final de este módulo tendrás:</p>
<ul>
<li>Tu evaluación personal completa</li>
<li>3 objetivos principales definidos</li>
<li>Plan de acción detallado</li>
<li>Métricas de seguimiento</li>
</ul>', 'texto', NULL, 90, 1, NOW()),

-- Mes 2: Mentalización
(11, 5, 2, 'Técnicas de Mentalización Avanzada', 'Desarrolla la fortaleza mental necesaria para competir al máximo nivel', 
'<h2>Técnicas de Mentalización Avanzada</h2>
<p>La mentalización es la clave del éxito en el deporte de alto rendimiento. En este módulo aprenderás técnicas probadas que utilizan los mejores atletas del mundo.</p>

<h3>Contenido del módulo:</h3>
<ol>
<li><strong>Visualización Creativa</strong>
<p>Técnicas de visualización para mejorar el rendimiento y la confianza.</p>
</li>

<li><strong>Control de la Respiración</strong>
<p>Ejercicios de respiración para manejar la presión y mantener la calma.</p>
</li>

<li><strong>Rutinas Pre-Competitivas</strong>
<p>Cómo crear y mantener rutinas efectivas antes de la competencia.</p>
</li>

<li><strong>Gestión del Estrés</strong>
<p>Estrategias para convertir la presión en motivación.</p>
</li>
</ol>', 'video', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 75, 2, NOW()),

-- Mes 3: Nutrición
(12, 5, 3, 'Nutrición para el Alto Rendimiento', 'Optimiza tu alimentación para maximizar tu rendimiento', 
'<h2>Nutrición para el Alto Rendimiento</h2>
<p>La nutrición es fundamental para el rendimiento deportivo. Aprenderás a alimentarte como un atleta de élite.</p>

<h3>Contenido del módulo:</h3>
<ul>
<li>Timing nutricional para entrenamientos</li>
<li>Alimentación pre-competitiva</li>
<li>Recuperación nutricional</li>
<li>Suplementación básica</li>
</ul>', 'texto', NULL, 60, 3, NOW()),

-- Mes 4: Recuperación
(13, 5, 4, 'Sistemas de Recuperación Avanzados', 'Maximiza tu recuperación para entrenar más duro y mejor', 
'<h2>Sistemas de Recuperación Avanzados</h2>
<p>La recuperación es tan importante como el entrenamiento. Aprenderás técnicas avanzadas de recuperación.</p>

<h3>Contenido del módulo:</h3>
<ul>
<li>Protocolos de recuperación post-entrenamiento</li>
<li>Técnicas de masaje y estiramiento</li>
<li>Hidratación y suplementación para recuperación</li>
<li>Sueño y descanso optimizado</li>
</ul>', 'audio', 'https://ejemplo.com/recuperacion.mp3', 45, 4, NOW()),

-- Mes 5: Planificación
(14, 5, 5, 'Planificación Estratégica del Entrenamiento', 'Aprende a planificar tu temporada como un profesional', 
'<h2>Planificación Estratégica del Entrenamiento</h2>
<p>La planificación es la base del éxito a largo plazo. Aprenderás a estructurar tu temporada completa.</p>

<h3>Contenido del módulo:</h3>
<ul>
<li>Periodización del entrenamiento</li>
<li>Planificación de picos de rendimiento</li>
<li>Gestión de la carga de entrenamiento</li>
<li>Adaptación del plan según resultados</li>
</ul>', 'interactivo', 'https://ejemplo.com/planificador', 90, 5, NOW()),

-- Mes 6: Competición
(15, 5, 6, 'Psicología de la Competición', 'Preparación mental para competir al máximo nivel', 
'<h2>Psicología de la Competición</h2>
<p>La competición requiere una preparación mental específica. Aprenderás técnicas para rendir bajo presión.</p>

<h3>Contenido del módulo:</h3>
<ul>
<li>Preparación mental pre-competición</li>
<li>Gestión de la presión durante la competición</li>
<li>Análisis post-competición</li>
<li>Desarrollo de la resiliencia</li>
</ul>', 'video', 'https://www.youtube.com/watch?v=ejemplo-competicion', 80, 6, NOW()),

-- Mes 7: Tecnología
(16, 5, 7, 'Tecnología y Análisis de Datos', 'Utiliza la tecnología para mejorar tu rendimiento', 
'<h2>Tecnología y Análisis de Datos</h2>
<p>La tecnología moderna puede ser una gran aliada. Aprenderás a usar herramientas de análisis para mejorar.</p>

<h3>Contenido del módulo:</h3>
<ul>
<li>Herramientas de análisis de rendimiento</li>
<li>Wearables y dispositivos de seguimiento</li>
<li>Análisis de datos para optimización</li>
<li>Integración de tecnología en tu rutina</li>
</ul>', 'texto', NULL, 70, 7, NOW()),

-- Mes 8: Maestría
(17, 5, 8, 'Maestría y Liderazgo Deportivo', 'Desarrolla el liderazgo y la maestría en tu deporte', 
'<h2>Maestría y Liderazgo Deportivo</h2>
<p>El último módulo te preparará para convertirte en un líder en tu deporte y comunidad.</p>

<h3>Contenido del módulo:</h3>
<ul>
<li>Desarrollo del liderazgo deportivo</li>
<li>Mentoría y coaching de otros</li>
<li>Construcción de una marca personal</li>
<li>Legado y contribución al deporte</li>
</ul>', 'video', 'https://www.youtube.com/watch?v=ejemplo-liderazgo', 85, 8, NOW());

-- 3. Crear una suscripción de prueba para el usuario 5 (Ana Martínez)
INSERT INTO `suscripciones_coaching` (`id`, `usuario_id`, `programa_coaching_id`, `stripe_subscription_id`, `estado`, `fecha_inicio`, `fecha_fin`, `mes_actual`, `ultimo_pago`, `proximo_pago`, `created_at`, `updated_at`) VALUES
(1, 5, 5, 'sub_test_123456', 'activa', '2025-01-15', NULL, 3, '2025-03-15 10:30:00', '2025-04-15 10:30:00', '2025-01-15 10:30:00', '2025-03-15 10:30:00');

-- 4. Crear accesos a los bloques para el usuario 5
INSERT INTO `accesos_bloques` (`id`, `usuario_id`, `bloque_id`, `suscripcion_id`, `desbloqueado`, `fecha_desbloqueo`, `completado`, `fecha_completado`, `progreso`, `created_at`, `updated_at`) VALUES
-- Mes 1: Desbloqueado y completado
(1, 5, 10, 1, 1, '2025-01-15 10:30:00', 1, '2025-01-20 14:25:00', 100, '2025-01-15 10:30:00', '2025-01-20 14:25:00'),

-- Mes 2: Desbloqueado y completado
(2, 5, 11, 1, 1, '2025-02-15 10:30:00', 1, '2025-02-25 16:45:00', 100, '2025-02-15 10:30:00', '2025-02-25 16:45:00'),

-- Mes 3: Desbloqueado pero no completado
(3, 5, 12, 1, 1, '2025-03-15 10:30:00', 0, NULL, 65, '2025-03-15 10:30:00', '2025-03-20 11:15:00'),

-- Mes 4: Bloqueado (aún no disponible)
(4, 5, 13, 1, 0, NULL, 0, NULL, 0, '2025-01-15 10:30:00', '2025-01-15 10:30:00'),

-- Mes 5: Bloqueado
(5, 5, 14, 1, 0, NULL, 0, NULL, 0, '2025-01-15 10:30:00', '2025-01-15 10:30:00'),

-- Mes 6: Bloqueado
(6, 5, 15, 1, 0, NULL, 0, NULL, 0, '2025-01-15 10:30:00', '2025-01-15 10:30:00'),

-- Mes 7: Bloqueado
(7, 5, 16, 1, 0, NULL, 0, NULL, 0, '2025-01-15 10:30:00', '2025-01-15 10:30:00'),

-- Mes 8: Bloqueado
(8, 5, 17, 1, 0, NULL, 0, NULL, 0, '2025-01-15 10:30:00', '2025-01-15 10:30:00');

-- 5. Crear pagos de suscripción para el usuario 5
INSERT INTO `pagos_suscripcion` (`id`, `suscripcion_id`, `stripe_payment_intent_id`, `mes`, `monto`, `moneda`, `estado`, `fecha_pago`, `created_at`, `updated_at`) VALUES
-- Pago del mes 1
(1, 1, 'pi_test_001', 1, 39.99, 'EUR', 'completado', '2025-01-15 10:30:00', '2025-01-15 10:30:00', '2025-01-15 10:30:00'),

-- Pago del mes 2
(2, 1, 'pi_test_002', 2, 39.99, 'EUR', 'completado', '2025-02-15 10:30:00', '2025-02-15 10:30:00', '2025-02-15 10:30:00'),

-- Pago del mes 3
(3, 1, 'pi_test_003', 3, 39.99, 'EUR', 'completado', '2025-03-15 10:30:00', '2025-03-15 10:30:00', '2025-03-15 10:30:00');

-- 6. Crear una segunda suscripción para el usuario 4 (Carlos López) en el programa de nutrición
INSERT INTO `suscripciones_coaching` (`id`, `usuario_id`, `programa_coaching_id`, `stripe_subscription_id`, `estado`, `fecha_inicio`, `fecha_fin`, `mes_actual`, `ultimo_pago`, `proximo_pago`, `created_at`, `updated_at`) VALUES
(2, 4, 1, 'sub_test_789012', 'activa', '2025-02-01', NULL, 2, '2025-03-01 09:15:00', '2025-04-01 09:15:00', '2025-02-01 09:15:00', '2025-03-01 09:15:00');

-- 7. Crear accesos para el usuario 4 en el programa de nutrición
INSERT INTO `accesos_bloques` (`id`, `usuario_id`, `bloque_id`, `suscripcion_id`, `desbloqueado`, `fecha_desbloqueo`, `completado`, `fecha_completado`, `progreso`, `created_at`, `updated_at`) VALUES
-- Mes 1: Desbloqueado y completado
(9, 4, 1, 2, 1, '2025-02-01 09:15:00', 1, '2025-02-10 15:30:00', 100, '2025-02-01 09:15:00', '2025-02-10 15:30:00'),

-- Mes 2: Desbloqueado pero no completado
(10, 4, 2, 2, 1, '2025-03-01 09:15:00', 0, NULL, 40, '2025-03-01 09:15:00', '2025-03-05 12:20:00'),

-- Mes 3: Bloqueado
(11, 4, 3, 2, 0, NULL, 0, NULL, 0, '2025-02-01 09:15:00', '2025-02-01 09:15:00');

-- 8. Crear pagos para el usuario 4
INSERT INTO `pagos_suscripcion` (`id`, `suscripcion_id`, `stripe_payment_intent_id`, `mes`, `monto`, `moneda`, `estado`, `fecha_pago`, `created_at`, `updated_at`) VALUES
-- Pago del mes 1
(4, 2, 'pi_test_004', 1, 19.99, 'EUR', 'completado', '2025-02-01 09:15:00', '2025-02-01 09:15:00', '2025-02-01 09:15:00'),

-- Pago del mes 2
(5, 2, 'pi_test_005', 2, 19.99, 'EUR', 'completado', '2025-03-01 09:15:00', '2025-03-01 09:15:00', '2025-03-01 09:15:00');

-- 9. Crear una suscripción cancelada para el usuario 6 (Roberto Sánchez)
INSERT INTO `suscripciones_coaching` (`id`, `usuario_id`, `programa_coaching_id`, `stripe_subscription_id`, `estado`, `fecha_inicio`, `fecha_fin`, `mes_actual`, `ultimo_pago`, `proximo_pago`, `created_at`, `updated_at`) VALUES
(3, 6, 3, 'sub_test_345678', 'cancelada', '2025-01-01', '2025-03-01', 2, '2025-02-01 11:45:00', NULL, '2025-01-01 11:45:00', '2025-03-01 11:45:00');

-- 10. Crear accesos para el usuario 6 (solo los primeros 2 meses)
INSERT INTO `accesos_bloques` (`id`, `usuario_id`, `bloque_id`, `suscripcion_id`, `desbloqueado`, `fecha_desbloqueo`, `completado`, `fecha_completado`, `progreso`, `created_at`, `updated_at`) VALUES
-- Mes 1: Desbloqueado y completado
(12, 6, 6, 3, 1, '2025-01-01 11:45:00', 1, '2025-01-15 13:20:00', 100, '2025-01-01 11:45:00', '2025-01-15 13:20:00'),

-- Mes 2: Desbloqueado y completado
(13, 6, 7, 3, 1, '2025-02-01 11:45:00', 1, '2025-02-20 16:10:00', 100, '2025-02-01 11:45:00', '2025-02-20 16:10:00');

-- 11. Crear pagos para el usuario 6
INSERT INTO `pagos_suscripcion` (`id`, `suscripcion_id`, `stripe_payment_intent_id`, `mes`, `monto`, `moneda`, `estado`, `fecha_pago`, `created_at`, `updated_at`) VALUES
-- Pago del mes 1
(6, 3, 'pi_test_006', 1, 29.99, 'EUR', 'completado', '2025-01-01 11:45:00', '2025-01-01 11:45:00', '2025-01-01 11:45:00'),

-- Pago del mes 2
(7, 3, 'pi_test_007', 2, 29.99, 'EUR', 'completado', '2025-02-01 11:45:00', '2025-02-01 11:45:00', '2025-02-01 11:45:00');

-- 12. Agregar algunos programas de entrenamiento con precios para completar la tienda
INSERT INTO `programaciones` (`id`, `nombre`, `descripcion`, `duracion_semanas`, `entrenamientos_por_semana`, `nivel`, `objetivo`, `created_at`, `updated_at`) VALUES
(5, 'Programa de Fuerza Avanzada', 'Programa completo de 12 semanas para desarrollar fuerza máxima y potencia. Incluye ejercicios compuestos, progresión de cargas y periodización avanzada.', 12, 4, 'avanzado', 'Desarrollo de fuerza máxima', NOW(), NOW()),
(6, 'Programa de Resistencia Cardio', 'Programa de 8 semanas para mejorar la resistencia cardiovascular y la capacidad aeróbica. Ideal para corredores, ciclistas y deportistas de resistencia.', 8, 3, 'intermedio', 'Mejora de resistencia cardiovascular', NOW(), NOW()),
(7, 'Programa de Hipertrofia', 'Programa de 10 semanas enfocado en el desarrollo muscular y la hipertrofia. Incluye técnicas avanzadas de entrenamiento y nutrición específica.', 10, 5, 'intermedio', 'Desarrollo muscular', NOW(), NOW());

-- 13. Agregar precios para los nuevos programas de entrenamiento
INSERT INTO `programas_precios` (`id`, `programacion_id`, `precio`, `moneda`, `activo`, `created_at`, `updated_at`) VALUES
(4, 5, 89.99, 'EUR', 1, NOW(), NOW()),
(5, 6, 59.99, 'EUR', 1, NOW(), NOW()),
(6, 7, 69.99, 'EUR', 1, NOW(), NOW());

-- 14. Crear algunas compras de prueba para programas de entrenamiento
INSERT INTO `pagos` (`id`, `usuario_id`, `programacion_id`, `stripe_payment_intent_id`, `monto`, `moneda`, `estado`, `fecha_pago`, `created_at`, `updated_at`) VALUES
(1, 5, 5, 'pi_entrenamiento_001', 89.99, 'EUR', 'completado', '2025-02-10 14:30:00', '2025-02-10 14:30:00', '2025-02-10 14:30:00'),
(2, 4, 6, 'pi_entrenamiento_002', 59.99, 'EUR', 'completado', '2025-01-20 16:45:00', '2025-01-20 16:45:00', '2025-01-20 16:45:00'),
(3, 6, 7, 'pi_entrenamiento_003', 69.99, 'EUR', 'completado', '2025-03-05 11:20:00', '2025-03-05 11:20:00', '2025-03-05 11:20:00');

-- 15. Asignar programas de entrenamiento a usuarios
INSERT INTO `programacion_usuarios` (`id`, `programacion_id`, `usuario_id`, `fecha_inicio`, `fecha_fin`, `estado`, `created_at`, `updated_at`) VALUES
(9, 5, 5, '2025-02-10', NULL, 'activo', '2025-02-10 14:30:00', '2025-02-10 14:30:00'),
(10, 6, 4, '2025-01-20', NULL, 'activo', '2025-01-20 16:45:00', '2025-01-20 16:45:00'),
(11, 7, 6, '2025-03-05', NULL, 'activo', '2025-03-05 11:20:00', '2025-03-05 11:20:00');

-- Mensaje de confirmación
SELECT 'Datos de prueba creados exitosamente!' as mensaje; 