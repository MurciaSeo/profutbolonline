<?php
// Script para insertar datos de prueba del sistema de coaching
require_once 'config/database.php';

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    echo "Conectado a la base de datos exitosamente.\n";
    
    // 1. Crear un nuevo programa de coaching más detallado
    $sql = "INSERT INTO `programas_coaching` (`id`, `nombre`, `descripcion`, `categoria`, `duracion_meses`, `precio_mensual`, `moneda`, `imagen_url`, `activo`, `created_at`, `updated_at`) VALUES
    (5, 'Coaching de Alto Rendimiento', 'Programa integral para deportistas que buscan alcanzar su máximo potencial. Incluye técnicas de mentalización, nutrición avanzada, recuperación y planificación estratégica.', 'mental', 8, 39.99, 'EUR', '/img/programas/alto-rendimiento.jpg', 1, NOW(), NOW())";
    
    $pdo->exec($sql);
    echo "✓ Programa de coaching creado.\n";
    
    // 2. Crear bloques detallados para el programa
    $bloques = [
        [
            'id' => 10, 'programa_coaching_id' => 5, 'mes' => 1,
            'titulo' => 'Fundamentos del Alto Rendimiento',
            'descripcion' => 'Introducción a los principios básicos del alto rendimiento deportivo',
            'contenido' => '<h2>Fundamentos del Alto Rendimiento</h2>
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
</ul>',
            'tipo_contenido' => 'texto', 'url_contenido' => null, 'duracion_minutos' => 90, 'orden' => 1
        ],
        [
            'id' => 11, 'programa_coaching_id' => 5, 'mes' => 2,
            'titulo' => 'Técnicas de Mentalización Avanzada',
            'descripcion' => 'Desarrolla la fortaleza mental necesaria para competir al máximo nivel',
            'contenido' => '<h2>Técnicas de Mentalización Avanzada</h2>
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
</ol>',
            'tipo_contenido' => 'video', 'url_contenido' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'duracion_minutos' => 75, 'orden' => 2
        ],
        [
            'id' => 12, 'programa_coaching_id' => 5, 'mes' => 3,
            'titulo' => 'Nutrición para el Alto Rendimiento',
            'descripcion' => 'Optimiza tu alimentación para maximizar tu rendimiento',
            'contenido' => '<h2>Nutrición para el Alto Rendimiento</h2>
<p>La nutrición es fundamental para el rendimiento deportivo. Aprenderás a alimentarte como un atleta de élite.</p>

<h3>Contenido del módulo:</h3>
<ul>
<li>Timing nutricional para entrenamientos</li>
<li>Alimentación pre-competitiva</li>
<li>Recuperación nutricional</li>
<li>Suplementación básica</li>
</ul>',
            'tipo_contenido' => 'texto', 'url_contenido' => null, 'duracion_minutos' => 60, 'orden' => 3
        ],
        [
            'id' => 13, 'programa_coaching_id' => 5, 'mes' => 4,
            'titulo' => 'Sistemas de Recuperación Avanzados',
            'descripcion' => 'Maximiza tu recuperación para entrenar más duro y mejor',
            'contenido' => '<h2>Sistemas de Recuperación Avanzados</h2>
<p>La recuperación es tan importante como el entrenamiento. Aprenderás técnicas avanzadas de recuperación.</p>

<h3>Contenido del módulo:</h3>
<ul>
<li>Protocolos de recuperación post-entrenamiento</li>
<li>Técnicas de masaje y estiramiento</li>
<li>Hidratación y suplementación para recuperación</li>
<li>Sueño y descanso optimizado</li>
</ul>',
            'tipo_contenido' => 'audio', 'url_contenido' => 'https://ejemplo.com/recuperacion.mp3', 'duracion_minutos' => 45, 'orden' => 4
        ],
        [
            'id' => 14, 'programa_coaching_id' => 5, 'mes' => 5,
            'titulo' => 'Planificación Estratégica del Entrenamiento',
            'descripcion' => 'Aprende a planificar tu temporada como un profesional',
            'contenido' => '<h2>Planificación Estratégica del Entrenamiento</h2>
<p>La planificación es la base del éxito a largo plazo. Aprenderás a estructurar tu temporada completa.</p>

<h3>Contenido del módulo:</h3>
<ul>
<li>Periodización del entrenamiento</li>
<li>Planificación de picos de rendimiento</li>
<li>Gestión de la carga de entrenamiento</li>
<li>Adaptación del plan según resultados</li>
</ul>',
            'tipo_contenido' => 'interactivo', 'url_contenido' => 'https://ejemplo.com/planificador', 'duracion_minutos' => 90, 'orden' => 5
        ],
        [
            'id' => 15, 'programa_coaching_id' => 5, 'mes' => 6,
            'titulo' => 'Psicología de la Competición',
            'descripcion' => 'Preparación mental para competir al máximo nivel',
            'contenido' => '<h2>Psicología de la Competición</h2>
<p>La competición requiere una preparación mental específica. Aprenderás técnicas para rendir bajo presión.</p>

<h3>Contenido del módulo:</h3>
<ul>
<li>Preparación mental pre-competición</li>
<li>Gestión de la presión durante la competición</li>
<li>Análisis post-competición</li>
<li>Desarrollo de la resiliencia</li>
</ul>',
            'tipo_contenido' => 'video', 'url_contenido' => 'https://www.youtube.com/watch?v=ejemplo-competicion', 'duracion_minutos' => 80, 'orden' => 6
        ],
        [
            'id' => 16, 'programa_coaching_id' => 5, 'mes' => 7,
            'titulo' => 'Tecnología y Análisis de Datos',
            'descripcion' => 'Utiliza la tecnología para mejorar tu rendimiento',
            'contenido' => '<h2>Tecnología y Análisis de Datos</h2>
<p>La tecnología moderna puede ser una gran aliada. Aprenderás a usar herramientas de análisis para mejorar.</p>

<h3>Contenido del módulo:</h3>
<ul>
<li>Herramientas de análisis de rendimiento</li>
<li>Wearables y dispositivos de seguimiento</li>
<li>Análisis de datos para optimización</li>
<li>Integración de tecnología en tu rutina</li>
</ul>',
            'tipo_contenido' => 'texto', 'url_contenido' => null, 'duracion_minutos' => 70, 'orden' => 7
        ],
        [
            'id' => 17, 'programa_coaching_id' => 5, 'mes' => 8,
            'titulo' => 'Maestría y Liderazgo Deportivo',
            'descripcion' => 'Desarrolla el liderazgo y la maestría en tu deporte',
            'contenido' => '<h2>Maestría y Liderazgo Deportivo</h2>
<p>El último módulo te preparará para convertirte en un líder en tu deporte y comunidad.</p>

<h3>Contenido del módulo:</h3>
<ul>
<li>Desarrollo del liderazgo deportivo</li>
<li>Mentoría y coaching de otros</li>
<li>Construcción de una marca personal</li>
<li>Legado y contribución al deporte</li>
</ul>',
            'tipo_contenido' => 'video', 'url_contenido' => 'https://www.youtube.com/watch?v=ejemplo-liderazgo', 'duracion_minutos' => 85, 'orden' => 8
        ]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO `coaching_bloques` (`id`, `programa_coaching_id`, `mes`, `titulo`, `descripcion`, `contenido`, `tipo_contenido`, `url_contenido`, `duracion_minutos`, `orden`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    
    foreach ($bloques as $bloque) {
        $stmt->execute([
            $bloque['id'], $bloque['programa_coaching_id'], $bloque['mes'],
            $bloque['titulo'], $bloque['descripcion'], $bloque['contenido'],
            $bloque['tipo_contenido'], $bloque['url_contenido'], $bloque['duracion_minutos'], $bloque['orden']
        ]);
    }
    echo "✓ Bloques de coaching creados.\n";
    
    // 3. Crear suscripciones de prueba
    $suscripciones = [
        [1, 5, 5, 'sub_test_123456', 'activa', '2025-01-15', null, 3, '2025-03-15 10:30:00', '2025-04-15 10:30:00'],
        [2, 4, 1, 'sub_test_789012', 'activa', '2025-02-01', null, 2, '2025-03-01 09:15:00', '2025-04-01 09:15:00'],
        [3, 6, 3, 'sub_test_345678', 'cancelada', '2025-01-01', '2025-03-01', 2, '2025-02-01 11:45:00', null]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO `suscripciones_coaching` (`id`, `usuario_id`, `programa_coaching_id`, `stripe_subscription_id`, `estado`, `fecha_inicio`, `fecha_fin`, `mes_actual`, `ultimo_pago`, `proximo_pago`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    
    foreach ($suscripciones as $suscripcion) {
        $stmt->execute($suscripcion);
    }
    echo "✓ Suscripciones de coaching creadas.\n";
    
    // 4. Crear accesos a bloques
    $accesos = [
        // Usuario 5 - Programa 5 (Alto Rendimiento)
        [1, 5, 10, 1, 1, '2025-01-15 10:30:00', 1, '2025-01-20 14:25:00', 100],
        [2, 5, 11, 1, 1, '2025-02-15 10:30:00', 1, '2025-02-25 16:45:00', 100],
        [3, 5, 12, 1, 1, '2025-03-15 10:30:00', 0, null, 65],
        [4, 5, 13, 1, 0, null, 0, null, 0],
        [5, 5, 14, 1, 0, null, 0, null, 0],
        [6, 5, 15, 1, 0, null, 0, null, 0],
        [7, 5, 16, 1, 0, null, 0, null, 0],
        [8, 5, 17, 1, 0, null, 0, null, 0],
        
        // Usuario 4 - Programa 1 (Nutrición)
        [9, 4, 1, 2, 1, '2025-02-01 09:15:00', 1, '2025-02-10 15:30:00', 100],
        [10, 4, 2, 2, 1, '2025-03-01 09:15:00', 0, null, 40],
        [11, 4, 3, 2, 0, null, 0, null, 0],
        
        // Usuario 6 - Programa 3 (Técnica) - Cancelado
        [12, 6, 6, 3, 1, '2025-01-01 11:45:00', 1, '2025-01-15 13:20:00', 100],
        [13, 6, 7, 3, 1, '2025-02-01 11:45:00', 1, '2025-02-20 16:10:00', 100]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO `accesos_bloques` (`id`, `usuario_id`, `bloque_id`, `suscripcion_id`, `desbloqueado`, `fecha_desbloqueo`, `completado`, `fecha_completado`, `progreso`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    
    foreach ($accesos as $acceso) {
        $stmt->execute($acceso);
    }
    echo "✓ Accesos a bloques creados.\n";
    
    // 5. Crear pagos de suscripción
    $pagos = [
        [1, 1, 'pi_test_001', 1, 39.99, 'EUR', 'completado', '2025-01-15 10:30:00'],
        [2, 1, 'pi_test_002', 2, 39.99, 'EUR', 'completado', '2025-02-15 10:30:00'],
        [3, 1, 'pi_test_003', 3, 39.99, 'EUR', 'completado', '2025-03-15 10:30:00'],
        [4, 2, 'pi_test_004', 1, 19.99, 'EUR', 'completado', '2025-02-01 09:15:00'],
        [5, 2, 'pi_test_005', 2, 19.99, 'EUR', 'completado', '2025-03-01 09:15:00'],
        [6, 3, 'pi_test_006', 1, 29.99, 'EUR', 'completado', '2025-01-01 11:45:00'],
        [7, 3, 'pi_test_007', 2, 29.99, 'EUR', 'completado', '2025-02-01 11:45:00']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO `pagos_suscripcion` (`id`, `suscripcion_id`, `stripe_payment_intent_id`, `mes`, `monto`, `moneda`, `estado`, `fecha_pago`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    
    foreach ($pagos as $pago) {
        $stmt->execute($pago);
    }
    echo "✓ Pagos de suscripción creados.\n";
    
    // 6. Agregar programas de entrenamiento con precios
    $programas_entrenamiento = [
        [5, 'Programa de Fuerza Avanzada', 'Programa completo de 12 semanas para desarrollar fuerza máxima y potencia. Incluye ejercicios compuestos, progresión de cargas y periodización avanzada.', 12, 4, 'avanzado', 'Desarrollo de fuerza máxima'],
        [6, 'Programa de Resistencia Cardio', 'Programa de 8 semanas para mejorar la resistencia cardiovascular y la capacidad aeróbica. Ideal para corredores, ciclistas y deportistas de resistencia.', 8, 3, 'intermedio', 'Mejora de resistencia cardiovascular'],
        [7, 'Programa de Hipertrofia', 'Programa de 10 semanas enfocado en el desarrollo muscular y la hipertrofia. Incluye técnicas avanzadas de entrenamiento y nutrición específica.', 10, 5, 'intermedio', 'Desarrollo muscular']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO `programaciones` (`id`, `nombre`, `descripcion`, `duracion_semanas`, `entrenamientos_por_semana`, `nivel`, `objetivo`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    
    foreach ($programas_entrenamiento as $programa) {
        $stmt->execute($programa);
    }
    echo "✓ Programas de entrenamiento creados.\n";
    
    // 7. Agregar precios para los programas de entrenamiento
    $precios = [
        [4, 5, 89.99, 'EUR'],
        [5, 6, 59.99, 'EUR'],
        [6, 7, 69.99, 'EUR']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO `programas_precios` (`id`, `programacion_id`, `precio`, `moneda`, `activo`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, 1, NOW(), NOW())");
    
    foreach ($precios as $precio) {
        $stmt->execute($precio);
    }
    echo "✓ Precios de programas creados.\n";
    
    // 8. Crear compras de prueba para programas de entrenamiento
    $compras = [
        [1, 5, 5, 'pi_entrenamiento_001', 89.99, 'EUR', 'completado', '2025-02-10 14:30:00'],
        [2, 4, 6, 'pi_entrenamiento_002', 59.99, 'EUR', 'completado', '2025-01-20 16:45:00'],
        [3, 6, 7, 'pi_entrenamiento_003', 69.99, 'EUR', 'completado', '2025-03-05 11:20:00']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO `pagos` (`id`, `usuario_id`, `programacion_id`, `stripe_payment_intent_id`, `monto`, `moneda`, `estado`, `fecha_pago`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    
    foreach ($compras as $compra) {
        $stmt->execute($compra);
    }
    echo "✓ Compras de programas creadas.\n";
    
    // 9. Asignar programas de entrenamiento a usuarios
    $asignaciones = [
        [9, 5, 5, '2025-02-10', null, 'activo'],
        [10, 6, 4, '2025-01-20', null, 'activo'],
        [11, 7, 6, '2025-03-05', null, 'activo']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO `programacion_usuarios` (`id`, `programacion_id`, `usuario_id`, `fecha_inicio`, `fecha_fin`, `estado`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
    
    foreach ($asignaciones as $asignacion) {
        $stmt->execute($asignacion);
    }
    echo "✓ Asignaciones de programas creadas.\n";
    
    echo "\n🎉 ¡Datos de prueba creados exitosamente!\n";
    echo "\nResumen de lo creado:\n";
    echo "- 1 programa de coaching completo (8 bloques)\n";
    echo "- 3 suscripciones de coaching (2 activas, 1 cancelada)\n";
    echo "- 13 accesos a bloques con diferentes estados\n";
    echo "- 7 pagos de suscripción\n";
    echo "- 3 programas de entrenamiento con precios\n";
    echo "- 3 compras de programas de entrenamiento\n";
    echo "- 3 asignaciones de programas a usuarios\n";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 