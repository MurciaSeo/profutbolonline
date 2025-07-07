-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 07-07-2025 a las 08:26:32
-- Versión del servidor: 8.0.42
-- Versión de PHP: 8.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `profutbolonline_qwb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int NOT NULL,
  `clave` varchar(50) NOT NULL,
  `valor` text,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `clave`, `valor`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Sistema de Entrenamiento', 'Nombre del sitio web', '2025-05-01 06:39:38', '2025-05-01 06:39:38'),
(2, 'site_description', 'Plataforma de gestión de entrenamientos funcionales', 'Descripción del sitio web', '2025-05-01 06:39:38', '2025-05-01 06:39:38'),
(3, 'contact_email', 'admin@example.com', 'Email de contacto', '2025-05-01 06:39:38', '2025-05-01 06:39:38'),
(4, 'items_per_page', '10', 'Número de elementos por página', '2025-05-01 06:39:38', '2025-05-01 06:39:38'),
(5, 'max_login_attempts', '3', 'Número máximo de intentos de inicio de sesión', '2025-05-01 06:39:38', '2025-05-01 06:39:38'),
(6, 'password_min_length', '8', 'Longitud mínima de contraseña', '2025-05-01 06:39:38', '2025-05-01 06:39:38'),
(7, 'require_special_chars', '1', 'Requerir caracteres especiales en contraseña', '2025-05-01 06:39:38', '2025-05-01 06:39:38'),
(8, 'session_timeout', '30', 'Tiempo de expiración de sesión en minutos', '2025-05-01 06:39:38', '2025-05-01 06:39:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicios`
--

CREATE TABLE `ejercicios` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `tipo_id` int NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ejercicios`
--

INSERT INTO `ejercicios` (`id`, `nombre`, `descripcion`, `tipo_id`, `video_url`, `created_at`) VALUES
(1, 'Sentadilla con barra', 'Ejercicio compuesto que trabaja cuádriceps, glúteos y espalda baja con una barra sobre los hombros.', 1, 'https://www.youtube.com/watch?v=dsCuiccYNGs', '2025-05-01 06:55:59'),
(2, 'Peso muerto', 'Movimiento que fortalece glúteos, isquiotibiales y zona lumbar levantando una barra desde el suelo.', 1, 'https://www.youtube.com/watch?v=mDSjJB1vj4E', '2025-05-01 06:56:17'),
(3, 'Press de banca', 'Ejercicio de pecho, tríceps y deltoides que se realiza empujando una barra sobre el pecho.', 1, 'https://www.youtube.com/watch?v=jlFl7WJ1TzI', '2025-05-01 06:56:17'),
(4, 'Remo con barra', 'Fortalece la espalda media y los bíceps tirando de una barra hacia el abdomen.', 1, 'https://www.youtube.com/watch?v=P_kNA_HElgA', '2025-05-01 06:56:17'),
(5, 'Dominadas', 'Ejercicio de peso corporal para espalda y brazos al levantar el cuerpo sobre una barra.', 1, 'https://www.youtube.com/watch?v=AhGIa-3eSLw', '2025-05-01 06:56:17'),
(6, 'Fondos en paralelas', 'Trabaja tríceps, pectorales y deltoides bajando el cuerpo entre barras paralelas.', 1, NULL, '2025-05-01 06:56:17'),
(7, 'Press militar', 'Empuje vertical con barra o mancuernas para fortalecer hombros.', 1, NULL, '2025-05-01 06:56:17'),
(8, 'Zancadas con peso', 'Paso adelante con flexión de rodilla, ideal para glúteos y piernas.', 1, NULL, '2025-05-01 06:56:17'),
(9, 'Curl de bíceps', 'Flexión del codo con mancuernas o barra para fortalecer los bíceps.', 1, NULL, '2025-05-01 06:56:17'),
(10, 'Extensión de tríceps', 'Movimientos con mancuernas o poleas que estiran el codo para trabajar el tríceps.', 1, NULL, '2025-05-01 06:56:17'),
(11, 'Correr', 'Actividad aeróbica que mejora la resistencia cardiovascular.', 2, 'https://www.youtube.com/watch?v=l2_7DERD86s', '2025-05-01 06:56:17'),
(12, 'Saltar la cuerda', 'Ejercicio rítmico que mejora coordinación y capacidad aeróbica.', 2, 'https://www.youtube.com/watch?v=8z9aJH3vYVo', '2025-05-01 06:56:17'),
(13, 'Burpees', 'Combina sentadilla, flexión y salto, excelente para resistencia general.', 2, 'https://www.youtube.com/watch?v=qLBImHhCXSw', '2025-05-01 06:56:17'),
(14, 'Ciclismo', 'Fortalece piernas y sistema cardiovascular en bicicleta estática o de ruta.', 2, 'https://www.youtube.com/watch?v=SR7Hx1sJr2E', '2025-05-01 06:56:17'),
(15, 'Natación', 'Ejercicio completo de bajo impacto ideal para la salud cardiovascular.', 2, 'https://www.youtube.com/watch?v=rJ3eWva2kg0', '2025-05-01 06:56:17'),
(16, 'Elíptica', 'Entrenamiento suave de bajo impacto para todo el cuerpo.', 2, NULL, '2025-05-01 06:56:17'),
(17, 'Subir escaleras', 'Ejercicio aeróbico que fortalece las piernas.', 2, NULL, '2025-05-01 06:56:17'),
(18, 'Remo', 'Simula el movimiento del remo, mejorando fuerza y cardio.', 2, NULL, '2025-05-01 06:56:17'),
(19, 'Jumping Jacks', 'Saltos con apertura y cierre de piernas y brazos para calentar o entrenar.', 2, NULL, '2025-05-01 06:56:17'),
(20, 'Shadow boxing', 'Simulación de boxeo para trabajar resistencia y reflejos.', 2, NULL, '2025-05-01 06:56:17'),
(21, 'Estiramiento de isquiotibiales', 'Inclinarse hacia los pies para estirar la parte posterior de las piernas.', 3, NULL, '2025-05-01 06:56:17'),
(22, 'Postura del niño', 'Posición de descanso que estira la espalda baja y relaja el cuerpo.', 3, NULL, '2025-05-01 06:56:17'),
(23, 'Estiramiento de cuádriceps', 'Flexión de pierna hacia atrás para estirar el muslo anterior.', 3, NULL, '2025-05-01 06:56:17'),
(24, 'Estiramiento de hombros', 'Cruzar el brazo sobre el pecho para estirar el deltoide.', 3, NULL, '2025-05-01 06:56:17'),
(25, 'Estiramiento de cuello', 'Inclinar la cabeza lateralmente para liberar tensión cervical.', 3, NULL, '2025-05-01 06:56:17'),
(26, 'Estiramiento de gemelos', 'Apoyar el pie contra una pared y empujar para estirar la pantorrilla.', 3, NULL, '2025-05-01 06:56:17'),
(27, 'Torsión espinal', 'Giro suave del torso para mejorar la movilidad de la columna.', 3, NULL, '2025-05-01 06:56:17'),
(28, 'Mariposa', 'Estiramiento de aductores al juntar las plantas de los pies.', 3, NULL, '2025-05-01 06:56:17'),
(29, 'Puente de glúteos estático', 'Elevar la pelvis y mantener para abrir la cadera.', 3, NULL, '2025-05-01 06:56:17'),
(30, 'Estiramiento de muñeca', 'Extender la palma hacia abajo o arriba y presionar suavemente.', 3, NULL, '2025-05-01 06:56:17'),
(31, 'Postura del árbol', 'Mantenerse en una pierna con la otra apoyada en el muslo y palmas juntas.', 4, NULL, '2025-05-01 06:56:17'),
(32, 'Equilibrio en una pierna', 'Sostenerse sobre una pierna con los ojos cerrados.', 4, NULL, '2025-05-01 06:56:17'),
(33, 'Tabla lateral', 'Plancha lateral sobre un antebrazo para trabajar estabilidad y core.', 4, NULL, '2025-05-01 06:56:17'),
(34, 'Caminata en línea recta', 'Caminar en línea colocando un pie delante del otro.', 4, NULL, '2025-05-01 06:56:17'),
(35, 'Equilibrio con bosu', 'Mantener el equilibrio sobre una base inestable.', 4, NULL, '2025-05-01 06:56:17'),
(36, 'Sentadilla a una pierna', 'Ejercicio unilateral para fuerza y coordinación.', 4, NULL, '2025-05-01 06:56:17'),
(37, 'Elevación de pierna lateral', 'Levantar una pierna de lado para trabajar glúteos y estabilidad.', 4, NULL, '2025-05-01 06:56:17'),
(38, 'Plancha con elevación de brazo', 'Plancha normal añadiendo levantamiento de brazo alternado.', 4, NULL, '2025-05-01 06:56:17'),
(39, 'Giros con balón en una pierna', 'Rotar el torso con balón mientras se mantiene una pierna elevada.', 4, NULL, '2025-05-01 06:56:17'),
(40, 'Desplante cruzado', 'Cruzar una pierna detrás de la otra al hacer desplante.', 4, NULL, '2025-05-01 06:56:17'),
(41, 'Plancha abdominal', 'Mantener posición horizontal sobre antebrazos activando el abdomen.', 5, NULL, '2025-05-01 06:56:17'),
(42, 'Crunch abdominal', 'Elevar el torso desde el suelo contrayendo el abdomen.', 5, NULL, '2025-05-01 06:56:17'),
(43, 'Elevación de piernas', 'Levantar piernas rectas desde posición supina para trabajar el abdomen inferior.', 5, NULL, '2025-05-01 06:56:17'),
(44, 'Bicicleta abdominal', 'Alternar rodilla con codo opuesto simulando pedaleo.', 5, NULL, '2025-05-01 06:56:17'),
(45, 'Mountain climbers', 'Desde plancha, llevar rodillas alternadas hacia el pecho rápidamente.', 5, NULL, '2025-05-01 06:56:17'),
(46, 'Toques de talón', 'Acostado, tocar alternadamente los talones para activar oblicuos.', 5, NULL, '2025-05-01 06:56:17'),
(47, 'Plancha lateral con elevación de pierna', 'Agregar elevación de pierna a la plancha lateral.', 5, NULL, '2025-05-01 06:56:17'),
(48, 'Ab rollouts', 'Rodar una rueda desde rodillas hacia adelante y volver.', 5, 'https://www.youtube.com/watch?v=DOFkcqW56Zw', '2025-05-01 06:56:17'),
(49, 'V-ups', 'Levantar brazos y piernas simultáneamente desde el suelo.', 5, NULL, '2025-05-01 06:56:17'),
(50, 'Russian twists', 'Girar el torso con o sin peso sentado con pies elevados.', 5, NULL, '2025-05-01 06:56:17'),
(51, 'Círculos de cadera', 'Movimiento circular de caderas para liberar tensión en la pelvis.', 6, NULL, '2025-05-01 06:56:17'),
(52, 'Rotaciones de hombros', 'Giros hacia adelante y atrás con los hombros para ganar movilidad.', 6, NULL, '2025-05-01 06:56:17'),
(53, 'Movilidad de tobillos', 'Rotaciones suaves del tobillo para mejorar su rango de movimiento.', 6, NULL, '2025-05-01 06:56:17'),
(54, 'Estiramiento gato-vaca', 'Movimiento alterno de la columna desde cuatro apoyos.', 6, NULL, '2025-05-01 06:56:17'),
(55, 'Rotaciones de cuello', 'Girar lentamente la cabeza de lado a lado.', 6, NULL, '2025-05-01 06:56:17'),
(56, 'Movilidad de muñecas', 'Flexionar y extender las muñecas para evitar rigidez.', 6, NULL, '2025-05-01 06:56:17'),
(57, 'Péndulo de brazos', 'Brazos sueltos que oscilan hacia adelante y atrás.', 6, NULL, '2025-05-01 06:56:17'),
(58, 'Desplante con torsión', 'Añadir rotación del torso a un desplante frontal.', 6, NULL, '2025-05-01 06:56:17'),
(59, 'Círculos de rodilla', 'Rotar suavemente las rodillas en posición semi flexionada.', 6, NULL, '2025-05-01 06:56:17'),
(60, 'Estiramiento dinámico de isquiotibiales', 'Bajar y subir piernas alternadamente desde el suelo.', 6, NULL, '2025-05-01 06:56:17'),
(61, 'Saltos en caja', 'Saltar desde el suelo sobre una plataforma elevada.', 7, NULL, '2025-05-01 06:56:17'),
(62, 'Lanzamiento de balón medicinal', 'Arrojar un balón medicinal al suelo o pared con fuerza.', 7, NULL, '2025-05-01 06:56:17'),
(63, 'Sprint corto', 'Carreras a máxima velocidad en tramos cortos.', 7, NULL, '2025-05-01 06:56:17'),
(64, 'Kettlebell swing', 'Balanceo explosivo con pesa rusa hacia el pecho.', 7, NULL, '2025-05-01 06:56:17'),
(65, 'Burpees con salto alto', 'Burpee seguido de un salto vertical máximo.', 7, NULL, '2025-05-01 06:56:17'),
(66, 'Clean con barra', 'Levantamiento técnico desde el suelo hasta los hombros.', 7, NULL, '2025-05-01 06:56:17'),
(67, 'Push press', 'Empuje explosivo con barra desde los hombros.', 7, NULL, '2025-05-01 06:56:17'),
(68, 'Salto de rana', 'Saltos largos hacia adelante desde posición agachada.', 7, NULL, '2025-05-01 06:56:17'),
(69, 'Skater jumps', 'Saltos laterales de una pierna a otra.', 7, NULL, '2025-05-01 06:56:17'),
(70, 'Lanzamiento con pierna', 'Impulsar una pierna hacia adelante como si se pateara con potencia.', 7, NULL, '2025-05-01 06:56:17'),
(71, 'Respiración diafragmática', 'Técnica de respiración profunda para calmar el sistema nervioso.', 8, NULL, '2025-05-01 06:56:17'),
(72, 'Estiramiento completo', 'Secuencia de estiramientos suaves para todo el cuerpo.', 8, NULL, '2025-05-01 06:56:17'),
(73, 'Rodillo de espuma', 'Automasaje con foam roller para liberar tensión muscular.', 8, NULL, '2025-05-01 06:56:17'),
(74, 'Baño de contraste', 'Alternar agua fría y caliente para estimular la circulación.', 8, NULL, '2025-05-01 06:56:17'),
(75, 'Meditación guiada', 'Ejercicio de relajación con foco en la respiración y conciencia.', 8, NULL, '2025-05-01 06:56:17'),
(76, 'Estiramiento de espalda', 'Flexión suave para liberar tensión lumbar.', 8, NULL, '2025-05-01 06:56:17'),
(77, 'Masaje con pelota', 'Aplicar presión con pelota en puntos de tensión.', 8, NULL, '2025-05-01 06:56:17'),
(78, 'Descanso activo', 'Caminar suave o pedalear para favorecer la circulación.', 8, NULL, '2025-05-01 06:56:17'),
(79, 'Elevación de piernas', 'Acostarse y elevar las piernas para favorecer retorno venoso.', 8, NULL, '2025-05-01 06:56:17'),
(80, 'Hidratación y respiración', 'Beber agua y realizar respiración consciente tras el ejercicio.', 8, NULL, '2025-05-01 06:56:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenamientos`
--

CREATE TABLE `entrenamientos` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `tipo` int NOT NULL,
  `creado_por` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `entrenamientos`
--

INSERT INTO `entrenamientos` (`id`, `nombre`, `descripcion`, `tipo`, `creado_por`, `created_at`) VALUES
(11, 'Entrenamiento de Fuerza', 'Este es un entrenamiento de Fuerza', 1, 1, '2025-05-31 11:26:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenamiento_bloques`
--

CREATE TABLE `entrenamiento_bloques` (
  `id` int NOT NULL,
  `entrenamiento_id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `orden` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `entrenamiento_bloques`
--

INSERT INTO `entrenamiento_bloques` (`id`, `entrenamiento_id`, `nombre`, `descripcion`, `orden`, `created_at`) VALUES
(43, 11, 'Calentamiento', '', 1, '2025-05-31 11:26:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenamiento_ejercicios`
--

CREATE TABLE `entrenamiento_ejercicios` (
  `id` int NOT NULL,
  `entrenamiento_id` int NOT NULL,
  `ejercicio_id` int NOT NULL,
  `tiempo` int DEFAULT NULL,
  `repeticiones` int DEFAULT NULL,
  `tiempo_descanso` int NOT NULL,
  `orden` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `bloque_id` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `entrenamiento_ejercicios`
--

INSERT INTO `entrenamiento_ejercicios` (`id`, `entrenamiento_id`, `ejercicio_id`, `tiempo`, `repeticiones`, `tiempo_descanso`, `orden`, `created_at`, `bloque_id`) VALUES
(75, 11, 37, 45, 3, 15, 2, '2025-05-31 11:26:45', 43),
(74, 11, 74, 45, 4, 60, 1, '2025-05-31 11:26:45', 43);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programaciones`
--

CREATE TABLE `programaciones` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `duracion_semanas` int NOT NULL,
  `entrenamientos_por_semana` int NOT NULL,
  `nivel` enum('principiante','intermedio','avanzado') NOT NULL,
  `objetivo` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programacion_dias`
--

CREATE TABLE `programacion_dias` (
  `id` int NOT NULL,
  `semana_id` int NOT NULL,
  `dia_semana` int NOT NULL,
  `entrenamiento_id` int DEFAULT NULL,
  `orden` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `programacion_dias`
--

INSERT INTO `programacion_dias` (`id`, `semana_id`, `dia_semana`, `entrenamiento_id`, `orden`, `created_at`) VALUES
(48, 21, 2, 6, 2, '2025-05-02 09:45:41'),
(47, 21, 1, 6, 1, '2025-05-02 09:45:41'),
(46, 20, 3, 6, 3, '2025-05-02 09:45:41'),
(45, 20, 2, 10, 2, '2025-05-02 09:45:41'),
(44, 20, 1, 10, 1, '2025-05-02 09:45:41'),
(43, 19, 3, 6, 3, '2025-05-02 07:50:06'),
(42, 19, 2, 6, 2, '2025-05-02 07:50:06'),
(41, 19, 1, 6, 1, '2025-05-02 07:50:06'),
(40, 18, 3, 6, 3, '2025-05-02 07:50:06'),
(39, 18, 2, 6, 2, '2025-05-02 07:50:06'),
(38, 18, 1, 6, 1, '2025-05-02 07:50:06'),
(37, 17, 3, 4, 3, '2025-05-02 07:50:06'),
(36, 17, 2, 4, 2, '2025-05-02 07:50:06'),
(35, 17, 1, 4, 1, '2025-05-02 07:50:06'),
(34, 16, 3, 4, 3, '2025-05-02 07:50:06'),
(33, 16, 2, 4, 2, '2025-05-02 07:50:06'),
(32, 16, 1, 4, 1, '2025-05-02 07:50:06'),
(31, 15, 3, 4, 3, '2025-05-02 07:50:06'),
(30, 15, 2, 4, 2, '2025-05-02 07:50:06'),
(29, 15, 1, 4, 1, '2025-05-02 07:50:06'),
(28, 14, 2, 6, 2, '2025-05-02 05:43:43'),
(27, 14, 1, 4, 1, '2025-05-02 05:43:43'),
(26, 13, 2, 4, 2, '2025-05-02 05:43:43'),
(25, 13, 1, 6, 1, '2025-05-02 05:43:43'),
(49, 21, 3, 6, 3, '2025-05-02 09:45:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programacion_dias_usuarios`
--

CREATE TABLE `programacion_dias_usuarios` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `dia_id` int NOT NULL,
  `completado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_completado` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `programacion_dias_usuarios`
--

INSERT INTO `programacion_dias_usuarios` (`id`, `usuario_id`, `dia_id`, `completado`, `fecha_completado`, `created_at`, `updated_at`) VALUES
(1, 5, 28, 1, '2025-05-02 10:42:12', '2025-05-02 08:15:34', '2025-05-02 08:42:12'),
(2, 5, 27, 1, '2025-05-02 10:41:51', '2025-05-02 08:15:34', '2025-05-02 08:41:51'),
(3, 5, 26, 1, '2025-05-02 10:40:28', '2025-05-02 08:15:34', '2025-05-02 08:40:28'),
(4, 5, 25, 1, '2025-05-02 10:40:14', '2025-05-02 08:15:34', '2025-05-02 08:40:14'),
(5, 5, 34, 0, NULL, '2025-05-02 08:53:02', '2025-05-02 08:53:02'),
(6, 5, 33, 0, NULL, '2025-05-02 08:53:02', '2025-05-02 08:53:02'),
(7, 5, 32, 0, NULL, '2025-05-02 08:53:02', '2025-05-02 08:53:02'),
(8, 5, 31, 0, NULL, '2025-05-02 08:53:02', '2025-05-02 08:53:02'),
(9, 5, 30, 1, '2025-05-02 11:28:03', '2025-05-02 08:53:02', '2025-05-02 09:28:03'),
(10, 5, 29, 1, '2025-05-02 10:57:04', '2025-05-02 08:53:02', '2025-05-02 08:57:04'),
(11, 5, 37, 0, NULL, '2025-05-02 08:53:02', '2025-05-02 08:53:02'),
(12, 5, 36, 0, NULL, '2025-05-02 08:53:02', '2025-05-02 08:53:02'),
(13, 5, 35, 0, NULL, '2025-05-02 08:53:02', '2025-05-02 08:53:02'),
(14, 5, 40, 0, NULL, '2025-05-02 08:53:02', '2025-05-02 08:53:02'),
(15, 5, 39, 0, NULL, '2025-05-02 08:53:02', '2025-05-02 08:53:02'),
(16, 5, 38, 0, NULL, '2025-05-02 08:53:02', '2025-05-02 08:53:02'),
(17, 5, 43, 0, NULL, '2025-05-02 08:53:02', '2025-05-02 08:53:02'),
(18, 5, 42, 0, NULL, '2025-05-02 08:53:02', '2025-05-02 08:53:02'),
(19, 5, 41, 0, NULL, '2025-05-02 08:53:02', '2025-05-02 08:53:02'),
(20, 4, 46, 0, NULL, '2025-05-02 09:49:05', '2025-05-02 09:49:05'),
(21, 4, 45, 0, NULL, '2025-05-02 09:49:05', '2025-05-02 09:49:05'),
(22, 4, 44, 1, '2025-05-02 11:50:16', '2025-05-02 09:49:05', '2025-05-02 09:50:16'),
(23, 4, 48, 0, NULL, '2025-05-02 09:49:05', '2025-05-02 09:49:05'),
(24, 4, 47, 0, NULL, '2025-05-02 09:49:05', '2025-05-02 09:49:05'),
(25, 4, 49, 0, NULL, '2025-05-02 09:49:05', '2025-05-02 09:49:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programacion_semanas`
--

CREATE TABLE `programacion_semanas` (
  `id` int NOT NULL,
  `programacion_id` int NOT NULL,
  `numero_semana` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `programacion_semanas`
--

INSERT INTO `programacion_semanas` (`id`, `programacion_id`, `numero_semana`, `created_at`) VALUES
(16, 3, 2, '2025-05-02 07:50:06'),
(15, 3, 1, '2025-05-02 07:50:06'),
(14, 2, 2, '2025-05-02 05:43:43'),
(13, 2, 1, '2025-05-02 05:43:43'),
(17, 3, 3, '2025-05-02 07:50:06'),
(18, 3, 4, '2025-05-02 07:50:06'),
(19, 3, 5, '2025-05-02 07:50:06'),
(20, 4, 1, '2025-05-02 09:45:41'),
(21, 4, 2, '2025-05-02 09:45:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programacion_usuarios`
--

CREATE TABLE `programacion_usuarios` (
  `id` int NOT NULL,
  `programacion_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('activo','completado','cancelado') DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `programacion_usuarios`
--

INSERT INTO `programacion_usuarios` (`id`, `programacion_id`, `usuario_id`, `fecha_inicio`, `fecha_fin`, `estado`, `created_at`, `updated_at`) VALUES
(6, 2, 5, '2025-05-02', '2025-05-23', 'completado', '2025-05-02 08:15:34', '2025-05-02 08:44:13'),
(7, 3, 5, '2025-05-02', '2025-05-31', 'activo', '2025-05-02 08:53:02', '2025-05-02 08:53:35'),
(8, 4, 4, '2025-04-01', '2025-05-01', 'activo', '2025-05-02 09:49:05', '2025-05-02 09:51:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `id` int NOT NULL,
  `entrenamiento_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `fecha` date NOT NULL,
  `notas` text,
  `completado` tinyint(1) DEFAULT '0',
  `fecha_completado` datetime DEFAULT NULL,
  `valorado` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `sesiones`
--

INSERT INTO `sesiones` (`id`, `entrenamiento_id`, `usuario_id`, `fecha`, `notas`, `completado`, `fecha_completado`, `valorado`, `created_at`) VALUES
(1, 6, 5, '2025-05-02', 'vamos', 1, '2025-05-02 11:13:44', 1, '2025-05-02 09:03:48'),
(2, 10, 5, '2025-05-02', 'hazlo antes de comer', 1, '2025-05-02 11:49:31', 1, '2025-05-02 09:48:38'),
(3, 11, 8, '2025-06-02', 'vamos a trabajar fuerza', 1, '2025-05-31 13:27:49', 1, '2025-05-31 11:27:19'),
(4, 11, 4, '2025-06-06', NULL, 0, NULL, 0, '2025-05-31 11:44:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_ejercicios`
--

CREATE TABLE `tipos_ejercicios` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `color` varchar(7) NOT NULL,
  `icono` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tipos_ejercicios`
--

INSERT INTO `tipos_ejercicios` (`id`, `nombre`, `descripcion`, `color`, `icono`, `created_at`) VALUES
(1, 'Fuerza', 'Ejercicios para desarrollar fuerza muscular', '#FF6B6B', 'fas fa-dumbbell', '2025-05-01 06:39:55'),
(2, 'Cardio', 'Ejercicios para mejorar la resistencia cardiovascular', '#34cbc1', 'fas fa-running', '2025-05-01 06:39:55'),
(3, 'Flexibilidad', 'Ejercicios para mejorar la movilidad y flexibilidad', '#45B7D1', 'fas fa-yoga', '2025-05-01 06:39:55'),
(4, 'Equilibrio', 'Ejercicios para mejorar el equilibrio y la coordinación', '#96CEB4', 'fas fa-balance-scale', '2025-05-01 06:39:55'),
(5, 'Core', 'Ejercicios para fortalecer el core y la zona media', '#796516', 'fas fa-circle-notch', '2025-05-01 06:39:55'),
(6, 'Movilidad', 'Ejercicios para mejorar la movilidad articular', '#D4A5A5', 'fas fa-arrows-alt', '2025-05-01 06:39:55'),
(7, 'Potencia', 'Ejercicios para desarrollar potencia y explosividad', '#FF9F1C', 'fas fa-bolt', '2025-05-01 06:39:55'),
(8, 'Recuperación', 'Ejercicios para facilitar la recuperación y relajación', '#A8D5BA', 'fas fa-spa', '2025-05-01 06:39:55'),
(10, 'Mixto', 'Tipo Mixto', '#007bff', 'fas fa-running', '2025-05-02 09:40:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `rol` enum('admin','entrenador','entrenado') NOT NULL,
  `entrenador_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `password`, `telefono`, `rol`, `entrenador_id`, `created_at`) VALUES
(1, 'Admin', 'Sistema', 'admin@example.com', '$2y$10$QADzjtkYA1VSAUdJWoj6C.JHe8nO5uX7rylmpsjUBNBbQ2qBme4lK', '600123456', 'admin', NULL, '2025-05-01 06:39:40'),
(2, 'Juan', 'Pérez', 'juan@example.com', '$2y$10$QADzjtkYA1VSAUdJWoj6C.JHe8nO5uX7rylmpsjUBNBbQ2qBme4lK', '600234567', 'entrenador', NULL, '2025-05-01 06:39:40'),
(3, 'María', 'García', 'maria@example.com', '$2y$10$QADzjtkYA1VSAUdJWoj6C.JHe8nO5uX7rylmpsjUBNBbQ2qBme4lK', '600345678', 'entrenador', NULL, '2025-05-01 06:39:40'),
(4, 'Carlos', 'López', 'carlos@example.com', '$2y$10$QADzjtkYA1VSAUdJWoj6C.JHe8nO5uX7rylmpsjUBNBbQ2qBme4lK', '600456789', 'entrenado', 2, '2025-05-01 06:39:40'),
(5, 'Ana', 'Martínez', 'ana@example.com', '$2y$10$QADzjtkYA1VSAUdJWoj6C.JHe8nO5uX7rylmpsjUBNBbQ2qBme4lK', '600567890', 'entrenado', 2, '2025-05-01 06:39:40'),
(6, 'Roberto', 'Sánchez', 'roberto@example.com', '$2y$10$QADzjtkYA1VSAUdJWoj6C.JHe8nO5uX7rylmpsjUBNBbQ2qBme4lK', '600678901', 'entrenado', 3, '2025-05-01 06:39:40'),
(8, 'Pedro José', 'Molina García', 'pedro@seoclic.com', '$2y$10$P0ZPpZ2VKq/PptN/5bj8SeZaWqm53/bKQL04tL4Or5AO9n6EMRTmy', '678478872', 'entrenado', 2, '2025-05-31 11:21:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valoraciones_entrenamientos`
--

CREATE TABLE `valoraciones_entrenamientos` (
  `id` int NOT NULL,
  `entrenamiento_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `dia_id` int NOT NULL,
  `sesion_id` int DEFAULT NULL,
  `calidad` int NOT NULL,
  `esfuerzo` int NOT NULL,
  `complejidad` int NOT NULL,
  `duracion` int NOT NULL,
  `comentarios` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `valoraciones_entrenamientos`
--

INSERT INTO `valoraciones_entrenamientos` (`id`, `entrenamiento_id`, `usuario_id`, `dia_id`, `sesion_id`, `calidad`, `esfuerzo`, `complejidad`, `duracion`, `comentarios`, `created_at`) VALUES
(10, 6, 5, 25, NULL, 5, 5, 5, 5, NULL, '2025-05-02 08:40:17'),
(11, 4, 5, 26, NULL, 3, 4, 4, 4, NULL, '2025-05-02 08:40:32'),
(12, 4, 5, 27, NULL, 5, 4, 3, 2, NULL, '2025-05-02 08:41:56'),
(13, 6, 5, 28, NULL, 5, 5, 5, 5, NULL, '2025-05-02 08:42:16'),
(14, 4, 5, 29, NULL, 5, 5, 5, 5, 'geia', '2025-05-02 08:57:11'),
(16, 6, 5, 0, 1, 5, 5, 5, 5, NULL, '2025-05-02 09:20:33'),
(17, 4, 5, 30, NULL, 5, 5, 5, 5, 'muy bueno', '2025-05-02 09:28:10'),
(18, 10, 5, 0, 2, 5, 5, 4, 5, 'comentario', '2025-05-02 09:49:42'),
(19, 10, 4, 44, NULL, 5, 4, 3, 2, 'hecho', '2025-05-02 09:50:23'),
(20, 11, 8, 0, 3, 5, 4, 5, 4, 'asf', '2025-05-31 11:33:26');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_id` (`tipo_id`);

--
-- Indices de la tabla `entrenamientos`
--
ALTER TABLE `entrenamientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `entrenamiento_bloques`
--
ALTER TABLE `entrenamiento_bloques`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entrenamiento_id` (`entrenamiento_id`);

--
-- Indices de la tabla `entrenamiento_ejercicios`
--
ALTER TABLE `entrenamiento_ejercicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entrenamiento_id` (`entrenamiento_id`),
  ADD KEY `ejercicio_id` (`ejercicio_id`),
  ADD KEY `bloque_id` (`bloque_id`);

--
-- Indices de la tabla `programaciones`
--
ALTER TABLE `programaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `programacion_dias`
--
ALTER TABLE `programacion_dias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `semana_id` (`semana_id`),
  ADD KEY `entrenamiento_id` (`entrenamiento_id`);

--
-- Indices de la tabla `programacion_dias_usuarios`
--
ALTER TABLE `programacion_dias_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_usuario_dia` (`usuario_id`,`dia_id`),
  ADD KEY `dia_id` (`dia_id`);

--
-- Indices de la tabla `programacion_semanas`
--
ALTER TABLE `programacion_semanas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `programacion_id` (`programacion_id`);

--
-- Indices de la tabla `programacion_usuarios`
--
ALTER TABLE `programacion_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_programacion_usuario` (`programacion_id`,`usuario_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entrenamiento_id` (`entrenamiento_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `tipos_ejercicios`
--
ALTER TABLE `tipos_ejercicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `entrenador_id` (`entrenador_id`);

--
-- Indices de la tabla `valoraciones_entrenamientos`
--
ALTER TABLE `valoraciones_entrenamientos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_valoracion` (`entrenamiento_id`,`usuario_id`,`dia_id`) USING BTREE,
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT de la tabla `entrenamientos`
--
ALTER TABLE `entrenamientos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `entrenamiento_bloques`
--
ALTER TABLE `entrenamiento_bloques`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `entrenamiento_ejercicios`
--
ALTER TABLE `entrenamiento_ejercicios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de la tabla `programaciones`
--
ALTER TABLE `programaciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `programacion_dias`
--
ALTER TABLE `programacion_dias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `programacion_dias_usuarios`
--
ALTER TABLE `programacion_dias_usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `programacion_semanas`
--
ALTER TABLE `programacion_semanas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `programacion_usuarios`
--
ALTER TABLE `programacion_usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipos_ejercicios`
--
ALTER TABLE `tipos_ejercicios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `valoraciones_entrenamientos`
--
ALTER TABLE `valoraciones_entrenamientos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
