-- Vaciar tablas de coaching y datos de prueba relacionados
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE accesos_bloques;
TRUNCATE TABLE pagos_suscripcion;
TRUNCATE TABLE suscripciones_coaching;
TRUNCATE TABLE coaching_bloques;
TRUNCATE TABLE programas_coaching;
TRUNCATE TABLE programas_precios;
TRUNCATE TABLE pagos;
TRUNCATE TABLE programacion_usuarios;
TRUNCATE TABLE programaciones;
SET FOREIGN_KEY_CHECKS=1; 