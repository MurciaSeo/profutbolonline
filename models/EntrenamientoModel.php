<?php
require_once 'BaseModel.php';

class EntrenamientoModel extends BaseModel {
    protected $table = 'entrenamientos';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getEntrenamientosPorUsuario($usuario_id) {
        try {
            $sql = "SELECT DISTINCT e.*, 
                    (SELECT COUNT(*) FROM entrenamiento_ejercicios ee WHERE ee.entrenamiento_id = e.id) as total_ejercicios,
                    (SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'id', ee.id,
                            'ejercicio_id', ee.ejercicio_id,
                            'nombre', ej.nombre,
                            'tipo_id', ej.tipo_id,
                            'descripcion', ej.descripcion,
                            'video_url', ej.video_url,
                            'tiempo', ee.tiempo,
                            'repeticiones', ee.repeticiones,
                            'orden', ee.orden
                        )
                    ) FROM entrenamiento_ejercicios ee 
                    JOIN ejercicios ej ON ee.ejercicio_id = ej.id 
                    WHERE ee.entrenamiento_id = e.id) as ejercicios,
                    s.fecha, s.completado
                    FROM {$this->table} e 
                    INNER JOIN sesiones s ON e.id = s.entrenamiento_id
                    WHERE s.usuario_id = ? 
                    ORDER BY s.fecha DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id]);
            $entrenamientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Procesar el JSON de ejercicios para cada entrenamiento
            foreach ($entrenamientos as &$entrenamiento) {
                if ($entrenamiento['ejercicios'] && $entrenamiento['ejercicios'] !== null) {
                    $decoded = json_decode($entrenamiento['ejercicios'], true);
                    $entrenamiento['ejercicios'] = is_array($decoded) ? $decoded : [];
                } else {
                    $entrenamiento['ejercicios'] = [];
                }
            }
            
            return $entrenamientos;
        } catch (PDOException $e) {
            error_log("Error al obtener entrenamientos del usuario: " . $e->getMessage());
            return [];
        }
    }
    
    public function getEntrenamientosPorEntrenador($entrenador_id) {
        $query = "SELECT DISTINCT e.*, 
                  (SELECT COUNT(*) FROM entrenamiento_ejercicios ee WHERE ee.entrenamiento_id = e.id) as total_ejercicios,
                  (SELECT JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'id', ee.id,
                        'ejercicio_id', ee.ejercicio_id,
                        'nombre', ej.nombre,
                        'tipo_id', ej.tipo_id,
                        'descripcion', ej.descripcion,
                        'video_url', ej.video_url,
                        'tiempo', ee.tiempo,
                        'repeticiones', ee.repeticiones,
                        'orden', ee.orden
                    )
                 ) FROM entrenamiento_ejercicios ee 
                 JOIN ejercicios ej ON ee.ejercicio_id = ej.id 
                 WHERE ee.entrenamiento_id = e.id) as ejercicios
                  FROM {$this->table} e 
                  WHERE e.creado_por = ? 
                  ORDER BY e.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$entrenador_id]);
        $entrenamientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Procesar el JSON de ejercicios para cada entrenamiento
        foreach ($entrenamientos as &$entrenamiento) {
            if ($entrenamiento['ejercicios'] && $entrenamiento['ejercicios'] !== null) {
                $decoded = json_decode($entrenamiento['ejercicios'], true);
                $entrenamiento['ejercicios'] = is_array($decoded) ? $decoded : [];
            } else {
                $entrenamiento['ejercicios'] = [];
            }
        }
        
        return $entrenamientos;
    }
    
    public function crear($data) {
        $this->db->beginTransaction();
        
        try {
            // Insertar el entrenamiento
            $query = "INSERT INTO {$this->table} (nombre, notas, creado_por) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['nombre'],
                $data['notas'],
                $_SESSION['user_id']
            ]);
            
            $entrenamiento_id = $this->db->lastInsertId();
            
            // Insertar los ejercicios del entrenamiento
            foreach ($data['ejercicios'] as $orden => $ejercicio) {
                $query = "INSERT INTO entrenamiento_ejercicios 
                         (entrenamiento_id, ejercicio_id, tiempo, repeticiones, descanso, orden) 
                         VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                
                $orden_valor = $orden + 1;
                $tiempo_valor = $ejercicio['tiempo'] ?? 0;
                $repeticiones_valor = $ejercicio['repeticiones'] ?? 0;
                $descanso_valor = $ejercicio['descanso'] ?? 60;
                
                $stmt->execute([
                    $entrenamiento_id,
                    $ejercicio['ejercicio_id'],
                    $tiempo_valor,
                    $repeticiones_valor,
                    $descanso_valor,
                    $orden_valor
                ]);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    public function findById($id) {
        // Obtener información del entrenamiento
        $query = "SELECT e.*, u.nombre as nombre_usuario, u.apellido as apellido_usuario
                  FROM {$this->table} e 
                  JOIN usuarios u ON e.creado_por = u.id
                  WHERE e.id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        $entrenamiento = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($entrenamiento) {
            // Obtener los bloques del entrenamiento
            $query = "SELECT * FROM entrenamiento_bloques 
                     WHERE entrenamiento_id = ? 
                     ORDER BY orden";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $bloques = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Para cada bloque, obtener sus ejercicios
            foreach ($bloques as &$bloque) {
                $query = "SELECT ee.*, ej.nombre as nombre_ejercicio, ej.tipo_id as tipo_ejercicio, 
                                ej.descripcion as descripcion_ejercicio, ej.video_url
                         FROM entrenamiento_ejercicios ee
                         JOIN ejercicios ej ON ee.ejercicio_id = ej.id
                         WHERE ee.bloque_id = ?
                         ORDER BY ee.orden";
                
                $stmt = $this->db->prepare($query);
                $stmt->execute([$bloque['id']]);
                $bloque['ejercicios'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            $entrenamiento['bloques'] = $bloques;
        }
        
        return $entrenamiento;
    }
    
    /**
     * Alias para el método crear() para mantener compatibilidad con el controlador
     * @param array $data Datos del entrenamiento
     * @return bool
     */
    public function asignarEntrenamiento($entrenamiento_id, $usuario_id, $fecha) {
        try {
            // Verificar que el entrenamiento existe
            $entrenamiento = $this->findById($entrenamiento_id);
            if (!$entrenamiento) {
                return false;
            }

            // Crear una nueva sesión
            $sql = "INSERT INTO sesiones (entrenamiento_id, usuario_id, fecha, completado) 
                    VALUES (?, ?, ?, 0)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$entrenamiento_id, $usuario_id, $fecha]);
        } catch (PDOException $e) {
            error_log("Error al asignar entrenamiento: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualiza un entrenamiento existente
     * @param array $data Datos del entrenamiento a actualizar
     * @return bool
     */
    public function actualizar($data) {
        $this->db->beginTransaction();
        
        try {
            // Actualizar el entrenamiento
            $query = "UPDATE {$this->table} SET usuario_id = ?, fecha = ?, notas = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['usuario_id'],
                $data['fecha'],
                $data['notas'],
                $data['id']
            ]);
            
            // Eliminar los ejercicios existentes
            $query = "DELETE FROM entrenamiento_ejercicios WHERE entrenamiento_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$data['id']]);
            
            // Insertar los nuevos ejercicios
            foreach ($data['ejercicios'] as $orden => $ejercicio) {
                $query = "INSERT INTO entrenamiento_ejercicios 
                         (entrenamiento_id, ejercicio_id, tiempo, repeticiones, orden) 
                         VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                
                $orden_valor = $orden + 1;
                $tiempo_valor = $ejercicio['tiempo'] ?? 0;
                $repeticiones_valor = $ejercicio['repeticiones'] ?? 0;
                
                $stmt->execute([
                    $data['id'],
                    $ejercicio['ejercicio_id'],
                    $tiempo_valor,
                    $repeticiones_valor,
                    $orden_valor
                ]);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getTotalEntrenamientos() {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getEntrenamientosRecientes($limite = 5) {
        $query = "SELECT e.*, u.nombre as nombre_usuario, u.apellido as apellido_usuario
                  FROM {$this->table} e
                  JOIN usuarios u ON e.creado_por = u.id
                  ORDER BY e.created_at DESC
                  LIMIT ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$limite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEntrenamientosPorMes() {
        $query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as mes, COUNT(*) as total
                  FROM {$this->table}
                  GROUP BY mes
                  ORDER BY mes DESC
                  LIMIT 12";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Marca un entrenamiento como completado
     * @param int $id ID del entrenamiento
     * @return bool
     */
    public function marcarCompletado($id) {
        $query = "UPDATE {$this->table} SET completado = 1, fecha_completado = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    /**
     * Añade una valoración a un entrenamiento
     * @param int $id ID del entrenamiento
     * @param array $valoracion Datos de la valoración
     * @return bool
     */
    public function añadirValoracion($entrenamiento_id, $valoracion) {
        $query = "UPDATE {$this->table} 
                  SET valoracion_esfuerzo = ?, valoracion_calidad = ?, valoracion_videos = ?, comentarios = ?
                  WHERE id = ?";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $valoracion['esfuerzo'],
            $valoracion['calidad'],
            $valoracion['videos'],
            $valoracion['comentarios'] ?? null,
            $entrenamiento_id
        ]);
    }

    public function getAllEntrenamientos() {
        $query = "SELECT e.*, u.nombre as nombre_usuario, u.apellido as apellido_usuario
                  FROM {$this->table} e
                  JOIN usuarios u ON e.creado_por = u.id
                  ORDER BY e.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEntrenamientosPorProgramacion($programacion_id) {
        $query = "SELECT e.*, u.nombre as nombre_usuario, u.apellido as apellido_usuario
                  FROM {$this->table} e
                  JOIN usuarios u ON e.creado_por = u.id
                  JOIN programacion_dias pd ON pd.entrenamiento_id = e.id
                  WHERE pd.programacion_id = ?
                  ORDER BY e.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$programacion_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerConDetalles($id) {
        $query = "SELECT e.*, u.nombre as nombre_usuario, u.apellido as apellido_usuario,
                  (SELECT JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'id', eb.id,
                        'nombre', eb.nombre,
                        'descripcion', eb.descripcion,
                        'orden', eb.orden,
                        'serie', eb.serie,
                        'ejercicios', (
                            SELECT JSON_ARRAYAGG(
                                JSON_OBJECT(
                                    'id', ee.id,
                                    'ejercicio_id', ee.ejercicio_id,
                                    'nombre', ej.nombre,
                                    'tipo_id', ej.tipo_id,
                                    'descripcion', ej.descripcion,
                                    'video_url', ej.video_url,
                                    'tiempo', ee.tiempo,
                                    'repeticiones', ee.repeticiones,
                                    'tiempo_descanso', ee.tiempo_descanso,
                                    'tipo_configuracion', ee.tipo_configuracion,
                                    'peso_kg', ee.peso_kg,
                                    'repeticiones_por_hacer', ee.repeticiones_por_hacer,
                                    'orden', ee.orden
                                )
                            )
                            FROM entrenamiento_ejercicios ee
                            JOIN ejercicios ej ON ee.ejercicio_id = ej.id
                            WHERE ee.bloque_id = eb.id
                            ORDER BY ee.orden ASC
                        )
                    )
                  ) FROM entrenamiento_bloques eb
                  WHERE eb.entrenamiento_id = e.id
                  ORDER BY eb.orden ASC) as bloques
                  FROM {$this->table} e
                  JOIN usuarios u ON e.creado_por = u.id
                  WHERE e.id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        $entrenamiento = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($entrenamiento && $entrenamiento['bloques'] && $entrenamiento['bloques'] !== null) {
            $decoded = json_decode($entrenamiento['bloques'], true);
            $entrenamiento['bloques'] = is_array($decoded) ? $decoded : [];
        } else {
            $entrenamiento['bloques'] = [];
        }
        
        return $entrenamiento;
    }

    /**
     * Actualiza un entrenamiento con sus bloques y ejercicios
     * @param int $id ID del entrenamiento
     * @param array $data Datos del entrenamiento a actualizar
     * @return bool
     */
    public function actualizarConBloques($id, $data) {
        $this->db->beginTransaction();
        
        try {
            // Actualizar el entrenamiento
            $query = "UPDATE {$this->table} SET nombre = ?, descripcion = ?, tipo = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['nombre'],
                $data['descripcion'] ?? '',
                $data['tipo'],
                $id
            ]);
            
            // Eliminar los ejercicios existentes
            $query = "DELETE FROM entrenamiento_ejercicios WHERE entrenamiento_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            
            // Eliminar los bloques existentes
            $query = "DELETE FROM entrenamiento_bloques WHERE entrenamiento_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            
            // Insertar los nuevos bloques y ejercicios
            foreach ($data['bloques'] as $bloqueIndex => $bloque) {
                $query = "INSERT INTO entrenamiento_bloques 
                         (entrenamiento_id, nombre, descripcion, orden, serie) 
                         VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $id,
                    $bloque['nombre'],
                    $bloque['descripcion'] ?? '',
                    (int)$bloqueIndex + 1,
                    $bloque['serie'] ?? 1
                ]);
                
                $bloque_id = $this->db->lastInsertId();
                
                foreach ($bloque['ejercicios'] as $ejercicioIndex => $ejercicio) {
                    $query = "INSERT INTO entrenamiento_ejercicios 
                             (entrenamiento_id, bloque_id, ejercicio_id, tiempo, repeticiones, tiempo_descanso, 
                              tipo_configuracion, peso_kg, repeticiones_por_hacer, orden) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([
                        $id,
                        $bloque_id,
                        $ejercicio['ejercicio_id'],
                        !empty($ejercicio['tiempo']) ? (int)$ejercicio['tiempo'] : null,
                        !empty($ejercicio['repeticiones']) ? (int)$ejercicio['repeticiones'] : null,
                        !empty($ejercicio['tiempo_descanso']) ? (int)$ejercicio['tiempo_descanso'] : null,
                        $ejercicio['tipo_configuracion'] ?? 'repeticiones',
                        !empty($ejercicio['peso_kg']) ? (float)$ejercicio['peso_kg'] : null,
                        !empty($ejercicio['repeticiones_por_hacer']) ? (int)$ejercicio['repeticiones_por_hacer'] : null,
                        (int)$ejercicioIndex + 1
                    ]);
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getTotalEntrenamientosCompletados() {
        try {
            $sql = "SELECT COUNT(*) as total FROM sesiones WHERE completado = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error al obtener total de entrenamientos completados: " . $e->getMessage());
            return 0;
        }
    }

    public function getTasaCompletitud() {
        try {
            $total = $this->getTotalEntrenamientos();
            $completados = $this->getTotalEntrenamientosCompletados();
            return $total > 0 ? ($completados / $total) * 100 : 0;
        } catch (PDOException $e) {
            error_log("Error al calcular tasa de completitud: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalEntrenamientosCompletadosPorEntrenador($entrenador_id) {
        try {
            $sql = "SELECT COUNT(*) as total 
                   FROM sesiones s
                   INNER JOIN usuarios u ON s.usuario_id = u.id
                   WHERE u.entrenador_id = :entrenador_id 
                   AND s.completado = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':entrenador_id', $entrenador_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error en getTotalEntrenamientosCompletadosPorEntrenador: " . $e->getMessage());
            return 0;
        }
    }

    public function getValoracionPromedioPorEntrenador($entrenador_id) {
        try {
            $sql = "SELECT AVG((s.calidad + s.esfuerzo + s.complejidad + s.duracion) / 4) as promedio
                   FROM sesiones s
                   INNER JOIN usuarios u ON s.usuario_id = u.id
                   WHERE u.entrenador_id = :entrenador_id 
                   AND s.completado = 1
                   AND s.calidad IS NOT NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':entrenador_id', $entrenador_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['promedio'] ? round($result['promedio'], 1) : 0;
        } catch (PDOException $e) {
            error_log("Error en getValoracionPromedioPorEntrenador: " . $e->getMessage());
            return 0;
        }
    }

    public function getEntrenamientosCompletadosPorEntrenador($entrenador_id) {
        try {
            $sql = "SELECT 
                       e.nombre,
                       CONCAT(u.nombre, ' ', u.apellido) as entrenado,
                       s.fecha_completado,
                       ROUND((s.calidad + s.esfuerzo + s.complejidad + s.duracion) / 4, 1) as valoracion
                   FROM sesiones s
                   INNER JOIN entrenamientos e ON s.entrenamiento_id = e.id
                   INNER JOIN usuarios u ON s.usuario_id = u.id
                   WHERE u.entrenador_id = :entrenador_id 
                   AND s.completado = 1
                   ORDER BY s.fecha_completado DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':entrenador_id', $entrenador_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getEntrenamientosCompletadosPorEntrenador: " . $e->getMessage());
            return [];
        }
    }

    public function getProgresoGeneral($usuario_id) {
        try {
            $sql = "SELECT 
                       (SELECT COUNT(*) FROM sesiones WHERE usuario_id = ? AND completado = 1) as completados,
                       (SELECT COUNT(*) FROM sesiones WHERE usuario_id = ?) as total";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id, $usuario_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] > 0 ? 
                round(($result['completados'] / $result['total']) * 100, 1) : 0;
        } catch (PDOException $e) {
            error_log("Error en getProgresoGeneral: " . $e->getMessage());
            return 0;
        }
    }

    public function getProximosEntrenamientos($usuario_id) {
        try {
            $sql = "SELECT 
                       e.id,
                       e.nombre,
                       s.fecha,
                       s.completado
                   FROM sesiones s
                   INNER JOIN entrenamientos e ON s.entrenamiento_id = e.id
                   WHERE s.usuario_id = :usuario_id 
                   AND s.fecha >= CURDATE()
                   ORDER BY s.fecha ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getProximosEntrenamientos: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalEntrenamientosPorUsuario($usuario_id) {
        try {
            $sql = "SELECT COUNT(*) as total FROM sesiones WHERE usuario_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log("Error al obtener total de entrenamientos: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalEntrenamientosCompletadosPorUsuario($usuario_id) {
        try {
            $sql = "SELECT COUNT(*) as total FROM sesiones WHERE usuario_id = ? AND completado = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log("Error al obtener total de entrenamientos completados: " . $e->getMessage());
            return 0;
        }
    }

    public function verificarAccesoUsuario($entrenamiento_id, $usuario_id) {
        try {
            $sql = "SELECT COUNT(*) as total 
                   FROM sesiones 
                   WHERE entrenamiento_id = ? AND usuario_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$entrenamiento_id, $usuario_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'] > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar acceso al entrenamiento: " . $e->getMessage());
            return false;
        }
    }

    public function getEntrenamientosPorCreador($creador_id) {
        try {
            $sql = "SELECT e.*, 
                   (SELECT COUNT(*) FROM entrenamiento_ejercicios ee WHERE ee.entrenamiento_id = e.id) as total_ejercicios
                   FROM {$this->table} e 
                   WHERE e.creado_por = ? 
                   ORDER BY e.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$creador_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener entrenamientos por creador: " . $e->getMessage());
            return [];
        }
    }

    public function getAsignacionesRecientes($limite = 10) {
        try {
            $sql = "SELECT s.*, u.nombre as nombre_usuario, u.apellido as apellido_usuario,
                           e.nombre as nombre_entrenamiento
                    FROM sesiones s
                    JOIN usuarios u ON s.usuario_id = u.id
                    JOIN entrenamientos e ON s.entrenamiento_id = e.id
                    ORDER BY s.fecha DESC
                    LIMIT ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limite]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener asignaciones recientes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Crea un entrenamiento con bloques y ejercicios
     * @param array $data Datos del entrenamiento con bloques
     * @return bool
     */
    public function crearConBloques($data) {
        $this->db->beginTransaction();
        
        try {
            // Insertar el entrenamiento
            $query = "INSERT INTO {$this->table} (nombre, descripcion, tipo, creado_por) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['nombre'],
                $data['descripcion'] ?? '',
                $data['tipo'],
                $_SESSION['user_id']
            ]);
            
            $entrenamiento_id = $this->db->lastInsertId();
            
            // Insertar los bloques y ejercicios
            foreach ($data['bloques'] as $bloqueIndex => $bloque) {
                $query = "INSERT INTO entrenamiento_bloques 
                         (entrenamiento_id, nombre, descripcion, orden, serie) 
                         VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $entrenamiento_id,
                    $bloque['nombre'],
                    $bloque['descripcion'] ?? '',
                    (int)$bloqueIndex + 1,
                    $bloque['serie'] ?? 1
                ]);
                
                $bloque_id = $this->db->lastInsertId();
                
                foreach ($bloque['ejercicios'] as $ejercicioIndex => $ejercicio) {
                    $query = "INSERT INTO entrenamiento_ejercicios 
                             (entrenamiento_id, bloque_id, ejercicio_id, tiempo, repeticiones, tiempo_descanso, 
                              tipo_configuracion, peso_kg, repeticiones_por_hacer, orden) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([
                        $entrenamiento_id,
                        $bloque_id,
                        $ejercicio['ejercicio_id'],
                        !empty($ejercicio['tiempo']) ? (int)$ejercicio['tiempo'] : null,
                        !empty($ejercicio['repeticiones']) ? (int)$ejercicio['repeticiones'] : null,
                        !empty($ejercicio['tiempo_descanso']) ? (int)$ejercicio['tiempo_descanso'] : null,
                        $ejercicio['tipo_configuracion'] ?? 'repeticiones',
                        !empty($ejercicio['peso_kg']) ? (float)$ejercicio['peso_kg'] : null,
                        !empty($ejercicio['repeticiones_por_hacer']) ? (int)$ejercicio['repeticiones_por_hacer'] : null,
                        (int)$ejercicioIndex + 1
                    ]);
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error al crear entrenamiento con bloques: " . $e->getMessage());
            return false;
        }
    }
} 