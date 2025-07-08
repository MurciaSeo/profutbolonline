<?php
require_once 'BaseModel.php';

class ProgramacionModel extends BaseModel {
    protected $table = 'programaciones';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Obtiene todas las programaciones
     */
    public function getAllProgramaciones() {
        $sql = "SELECT p.*, 
                COUNT(DISTINCT pu.id) as total_usuarios,
                COUNT(DISTINCT ps.id) as total_semanas
                FROM programaciones p
                LEFT JOIN programacion_usuarios pu ON p.id = pu.programacion_id
                LEFT JOIN programacion_semanas ps ON p.id = ps.programacion_id
                GROUP BY p.id
                ORDER BY p.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene una programación por su ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM programaciones WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crea una nueva programación
     */
    public function crear($data) {
        $query = "INSERT INTO programaciones (nombre, descripcion, duracion_semanas, entrenamientos_por_semana, nivel, objetivo) 
                 VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['duracion_semanas'],
            $data['entrenamientos_por_semana'],
            $data['nivel'],
            $data['objetivo']
        ]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Actualiza una programación existente
     */
    public function actualizar($id, $data) {
        $this->db->beginTransaction();
        
        try {
            // Actualizar programación
            $sql = "UPDATE programaciones SET 
                    nombre = ?, 
                    descripcion = ?, 
                    duracion_semanas = ?, 
                    entrenamientos_por_semana = ?,
                    nivel = ?,
                    objetivo = ?
                    WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['nombre'],
                $data['descripcion'],
                $data['duracion_semanas'],
                $data['entrenamientos_por_semana'],
                $data['nivel'],
                $data['objetivo'],
                $id
            ]);
            
            // Obtener semanas existentes
            $sql = "SELECT id, numero_semana FROM programacion_semanas WHERE programacion_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $semanasExistentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Procesar cada semana
            if (!empty($data['semanas'])) {
                foreach ($data['semanas'] as $semana) {
                    $semanaExistente = array_filter($semanasExistentes, function($s) use ($semana) {
                        return $s['numero_semana'] == $semana['numero_semana'];
                    });
                    
                    $semanaExistente = reset($semanaExistente);
                    
                    if ($semanaExistente) {
                        $semana_id = $semanaExistente['id'];
                    } else {
                        // Crear nueva semana si no existe
                    $sql = "INSERT INTO programacion_semanas (programacion_id, numero_semana) VALUES (?, ?)";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute([$id, $semana['numero_semana']]);
                        $semana_id = $this->db->lastInsertId();
                    }
                    
                    // Obtener días existentes para esta semana
                    $sql = "SELECT id, dia_semana FROM programacion_dias WHERE semana_id = ?";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([$semana_id]);
                    $diasExistentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Procesar cada día
                    if (!empty($semana['dias'])) {
                        foreach ($semana['dias'] as $dia) {
                            $diaExistente = array_filter($diasExistentes, function($d) use ($dia) {
                                return $d['dia_semana'] == $dia['dia_semana'];
                            });
                            
                            $diaExistente = reset($diaExistente);
                            
                            if ($diaExistente) {
                                // Actualizar día existente
                                $sql = "UPDATE programacion_dias SET 
                                        entrenamiento_id = ?,
                                        orden = ?
                                        WHERE id = ?";
                                $stmt = $this->db->prepare($sql);
                                $stmt->execute([
                                    $dia['entrenamiento_id'],
                                    $dia['orden'],
                                    $diaExistente['id']
                                ]);
                            } else {
                                // Crear nuevo día
                                $sql = "INSERT INTO programacion_dias (semana_id, dia_semana, entrenamiento_id, orden) 
                                        VALUES (?, ?, ?, ?)";
                            $stmt = $this->db->prepare($sql);
                                $stmt->execute([
                                    $semana_id,
                                    $dia['dia_semana'],
                                    $dia['entrenamiento_id'],
                                    $dia['orden']
                                ]);
                            }
                        }
                    }
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    /**
     * Elimina una programación y todos sus registros relacionados
     */
    public function eliminar($id) {
        try {
            $this->db->beginTransaction();
            
            // Eliminar la programación (esto eliminará automáticamente las semanas y días por las restricciones ON DELETE CASCADE)
            $sql = "DELETE FROM programaciones WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    /*  *
     ** Obtiene los detalles completos de una programación
     */
    public function getProgramacionCompleta($id) {
        $sql = "SELECT p.*, 
                       ps.numero_semana,
                       pd.dia_semana,
                       pd.orden,
                       e.id as entrenamiento_id,
                       e.nombre as entrenamiento_nombre,
                       e.descripcion as entrenamiento_descripcion,
                       p.duracion_semanas as entrenamiento_duracion,
                       p.nivel as entrenamiento_nivel,
                       p.objetivo as entrenamiento_objetivo
                FROM programaciones p
                LEFT JOIN programacion_semanas ps ON p.id = ps.programacion_id
                LEFT JOIN programacion_dias pd ON ps.id = pd.semana_id
                LEFT JOIN entrenamientos e ON pd.entrenamiento_id = e.id
                WHERE p.id = ?
                ORDER BY ps.numero_semana, pd.dia_semana, pd.orden";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $programacion = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Obtener las semanas
        $sql = "SELECT * FROM programacion_semanas WHERE programacion_id = ? ORDER BY numero_semana";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $semanas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener los días de cada semana
        foreach ($semanas as &$semana) {
            $sql = "SELECT pd.*, e.nombre as entrenamiento_nombre, e.descripcion as entrenamiento_descripcion,
                           e.id as entrenamiento_id, ps.numero_semana
                    FROM programacion_dias pd 
                    LEFT JOIN entrenamientos e ON pd.entrenamiento_id = e.id 
                    INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id
                    WHERE pd.semana_id = ? 
                    ORDER BY pd.dia_semana";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$semana['id']]);
            $semana['dias'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        $programacion['semanas'] = $semanas;
        return $programacion;
    }
    
    /**
     * Obtiene los entrenamientos disponibles para asignar a una programación
     */
    public function getEntrenamientosDisponibles() {
        $sql = "SELECT e.id, e.nombre, e.descripcion, 
                u.nombre as usuario_nombre, u.apellido as usuario_apellido,
                (SELECT COUNT(*) FROM entrenamiento_ejercicios ee WHERE ee.entrenamiento_id = e.id) as total_ejercicios
                FROM entrenamientos e
                JOIN usuarios u ON e.creado_por = u.id
                ORDER BY e.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProgramaConDetalles($id) {
        // Obtener la programación base
        $sql = "SELECT p.* FROM programaciones p WHERE p.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $programa = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$programa) {
            return null;
        }
        
        // Obtener las semanas
        $sql = "SELECT * FROM programacion_semanas WHERE programacion_id = ? ORDER BY numero_semana";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $semanas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener los días de cada semana con sus entrenamientos
        foreach ($semanas as &$semana) {
            $sql = "SELECT pd.*, e.nombre as entrenamiento_nombre, e.descripcion as entrenamiento_descripcion,
                           e.id as entrenamiento_id, ps.numero_semana
                    FROM programacion_dias pd 
                    LEFT JOIN entrenamientos e ON pd.entrenamiento_id = e.id 
                    INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id
                    WHERE pd.semana_id = ? 
                    ORDER BY pd.dia_semana";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$semana['id']]);
            $dias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Para cada día, obtener los detalles del entrenamiento
            foreach ($dias as &$dia) {
                if ($dia['entrenamiento_id']) {
                    // Obtener los bloques del entrenamiento
                    $sql = "SELECT b.* FROM entrenamiento_bloques b 
                            WHERE b.entrenamiento_id = ? 
                            ORDER BY b.orden";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([$dia['entrenamiento_id']]);
                    $bloques = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Para cada bloque, obtener los ejercicios
                    foreach ($bloques as &$bloque) {
                        $sql = "SELECT ee.*, e.nombre, e.descripcion, e.video_url 
                                FROM entrenamiento_ejercicios ee
                                JOIN ejercicios e ON ee.ejercicio_id = e.id
                                WHERE ee.bloque_id = ?
                                ORDER BY ee.orden";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute([$bloque['id']]);
                        $bloque['ejercicios'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                    
                    $dia['entrenamiento'] = [
                        'id' => $dia['entrenamiento_id'],
                        'nombre' => $dia['entrenamiento_nombre'],
                        'descripcion' => $dia['entrenamiento_descripcion'],
                        'bloques' => $bloques
                    ];
                }
                
                // Obtener el estado de las sesiones específicas para cada usuario asignado
                $sql = "SELECT s.usuario_id, s.completado, s.fecha_completado, s.valorado, u.nombre, u.apellido
                        FROM sesiones s
                        JOIN usuarios u ON s.usuario_id = u.id
                        WHERE s.programacion_id = ? AND s.dia_id = ? AND s.entrenamiento_id = ?
                        ORDER BY u.nombre, u.apellido";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id, $dia['id'], $dia['entrenamiento_id']]);
                $estados_sesiones = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $dia['estados_sesiones'] = $estados_sesiones;
                
                // Calcular estadísticas del estado basado en sesiones
                $total_sesiones = count($estados_sesiones);
                $sesiones_completadas = count(array_filter($estados_sesiones, function($s) {
                    return $s['completado'] == 1;
                }));
                
                $dia['estadisticas'] = [
                    'total_sesiones' => $total_sesiones,
                    'sesiones_completadas' => $sesiones_completadas,
                    'porcentaje_completado' => $total_sesiones > 0 ? round(($sesiones_completadas / $total_sesiones) * 100) : 0
                ];
            }
            
            $semana['dias'] = $dias;
        }
        
        $programa['semanas'] = $semanas;
        return $programa;
    }

    public function getProgramaConDetallesUsuario($id, $usuario_id) {
        // Obtener la programación base
        $sql = "SELECT p.* FROM programaciones p WHERE p.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $programa = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$programa) {
            return null;
        }
        
        // Obtener el total de días y días completados
        $sql = "SELECT 
                    COUNT(*) as total_dias,
                    SUM(CASE WHEN pdu.completado = TRUE THEN 1 ELSE 0 END) as dias_completados
                FROM programacion_dias pd 
                INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id 
                LEFT JOIN programacion_dias_usuarios pdu ON pd.id = pdu.dia_id AND pdu.usuario_id = ?
                WHERE ps.programacion_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $id]);
        $progreso = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $programa['total_dias'] = $progreso['total_dias'];
        $programa['dias_completados'] = $progreso['dias_completados'];
        $programa['progreso'] = $progreso['total_dias'] > 0 ? 
            round(($progreso['dias_completados'] / $progreso['total_dias']) * 100) : 0;
        
        // Obtener las semanas
        $sql = "SELECT * FROM programacion_semanas WHERE programacion_id = ? ORDER BY numero_semana";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $semanas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener los días de cada semana con sus entrenamientos
        foreach ($semanas as &$semana) {
            $sql = "SELECT pd.*, e.nombre as entrenamiento_nombre, e.descripcion as entrenamiento_descripcion,
                           e.id as entrenamiento_id, ps.numero_semana
                    FROM programacion_dias pd 
                    LEFT JOIN entrenamientos e ON pd.entrenamiento_id = e.id 
                    INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id
                    WHERE pd.semana_id = ? 
                    ORDER BY pd.dia_semana";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$semana['id']]);
            $dias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Para cada día, obtener los detalles del entrenamiento
            foreach ($dias as &$dia) {
                if ($dia['entrenamiento_id']) {
                    // Obtener los bloques del entrenamiento
                    $sql = "SELECT b.* FROM entrenamiento_bloques b 
                            WHERE b.entrenamiento_id = ? 
                            ORDER BY b.orden";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([$dia['entrenamiento_id']]);
                    $bloques = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Para cada bloque, obtener los ejercicios
                    foreach ($bloques as &$bloque) {
                        $sql = "SELECT ee.*, e.nombre, e.descripcion, e.video_url 
                                FROM entrenamiento_ejercicios ee
                                JOIN ejercicios e ON ee.ejercicio_id = e.id
                                WHERE ee.bloque_id = ?
                                ORDER BY ee.orden";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute([$bloque['id']]);
                        $bloque['ejercicios'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                    
                    $dia['entrenamiento'] = [
                        'id' => $dia['entrenamiento_id'],
                        'nombre' => $dia['entrenamiento_nombre'],
                        'descripcion' => $dia['entrenamiento_descripcion'],
                        'bloques' => $bloques
                    ];
                }
                
                // Verificar si el día está completado usando programacion_dias_usuarios
                $sql = "SELECT completado 
                        FROM programacion_dias_usuarios 
                        WHERE dia_id = ? AND usuario_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$dia['id'], $usuario_id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $dia['completado'] = $result ? $result['completado'] : false;
            }
            
            $semana['dias'] = $dias;
        }
        
        $programa['semanas'] = $semanas;
        return $programa;
    }

    public function actualizarProgresoPrograma($programacion_id, $usuario_id) {
        try {
            // Obtener el total de días del programa
            $sql = "SELECT COUNT(*) as total_dias 
                    FROM programacion_dias pd 
                    INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id 
                    WHERE ps.programacion_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$programacion_id]);
            $total_dias = $stmt->fetch(PDO::FETCH_ASSOC)['total_dias'];

            // Obtener los días completados
            $sql = "SELECT COUNT(*) as dias_completados 
                    FROM programacion_dias_usuarios pdu 
                    INNER JOIN programacion_dias pd ON pdu.dia_id = pd.id 
                    INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id 
                    WHERE ps.programacion_id = ? AND pdu.usuario_id = ? AND pdu.completado = TRUE";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$programacion_id, $usuario_id]);
            $dias_completados = $stmt->fetch(PDO::FETCH_ASSOC)['dias_completados'];

            // Calcular el progreso
            $progreso = $total_dias > 0 ? round(($dias_completados / $total_dias) * 100) : 0;

            // Actualizar el progreso en la tabla programacion_usuarios
            $sql = "UPDATE programacion_usuarios 
                    SET progreso = ?, 
                        dias_completados = ?, 
                        total_dias = ? 
                    WHERE programacion_id = ? AND usuario_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$progreso, $dias_completados, $total_dias, $programacion_id, $usuario_id]);
        } catch (PDOException $e) {
            error_log("Error en actualizarProgresoPrograma: " . $e->getMessage());
            return false;
        }
    }

    public function marcarSesionCompletada($programacion_id, $usuario_id, $dia_id, $entrenamiento_id) {
        try {
            $this->db->beginTransaction();

            // Verificar si existe la sesión
            $sql = "SELECT id FROM sesiones 
                    WHERE programacion_id = ? AND usuario_id = ? AND dia_id = ? AND entrenamiento_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$programacion_id, $usuario_id, $dia_id, $entrenamiento_id]);
            $sesion = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($sesion) {
                // Actualizar la sesión existente
                $sql = "UPDATE sesiones 
                        SET completado = 1, fecha_completado = NOW() 
                        WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                $success = $stmt->execute([$sesion['id']]);
            } else {
                // Crear nueva sesión si no existe
                $sql = "INSERT INTO sesiones (programacion_id, usuario_id, dia_id, entrenamiento_id, completado, fecha_completado) 
                        VALUES (?, ?, ?, ?, 1, NOW())";
                $stmt = $this->db->prepare($sql);
                $success = $stmt->execute([$programacion_id, $usuario_id, $dia_id, $entrenamiento_id]);
            }

            if ($success) {
                // Actualizar el progreso del programa
                $this->actualizarProgresoPrograma($programacion_id, $usuario_id);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en ProgramacionModel::marcarSesionCompletada: " . $e->getMessage());
            throw new Exception("Error al marcar la sesión como completada");
        }
    }

    public function marcarDiaCompletado($dia_id, $usuario_id) {
        try {
            $this->db->beginTransaction();

            // Verificar si ya existe un registro
            $sql = "SELECT id FROM programacion_dias_usuarios 
                    WHERE dia_id = ? AND usuario_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$dia_id, $usuario_id]);
            $existe = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existe) {
                // Actualizar el registro existente
                $sql = "UPDATE programacion_dias_usuarios 
                        SET completado = TRUE, fecha_completado = CURRENT_TIMESTAMP 
                        WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                $success = $stmt->execute([$existe['id']]);
            } else {
                // Insertar nuevo registro
                $sql = "INSERT INTO programacion_dias_usuarios (dia_id, usuario_id, completado, fecha_completado) 
                        VALUES (?, ?, TRUE, CURRENT_TIMESTAMP)";
                $stmt = $this->db->prepare($sql);
                $success = $stmt->execute([$dia_id, $usuario_id]);
            }

            if ($success) {
                // Obtener el programacion_id
                $sql = "SELECT ps.programacion_id 
                        FROM programacion_dias pd 
                        INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id 
                        WHERE pd.id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$dia_id]);
                $programacion_id = $stmt->fetch(PDO::FETCH_ASSOC)['programacion_id'];
                
                // Actualizar el progreso del programa
                $this->actualizarProgresoPrograma($programacion_id, $usuario_id);
                
                // Verificar si todos los días están completados
                $sql = "SELECT 
                        COUNT(*) as total_dias,
                        SUM(CASE WHEN pdu.completado = TRUE THEN 1 ELSE 0 END) as dias_completados
                        FROM programacion_dias pd 
                        INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id 
                        LEFT JOIN programacion_dias_usuarios pdu ON pd.id = pdu.dia_id AND pdu.usuario_id = ?
                        WHERE ps.programacion_id = (
                            SELECT ps.programacion_id 
                            FROM programacion_dias pd 
                            INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id 
                            WHERE pd.id = ?
                        )";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$usuario_id, $dia_id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result['total_dias'] == $result['dias_completados']) {
                    // Actualizar el estado del programa a completado
                    $sql = "UPDATE programacion_usuarios 
                            SET estado = 'completado', fecha_fin = CURRENT_DATE 
                            WHERE programacion_id = (
                                SELECT ps.programacion_id 
                                FROM programacion_dias pd 
                                INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id 
                                WHERE pd.id = ?
                            ) AND usuario_id = ?";
                    
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([$dia_id, $usuario_id]);
                }
            }

            $this->db->commit();
            return $dia_id; // Devolvemos el ID del día para la redirección
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error al marcar día como completado: " . $e->getMessage());
            return false;
        }
    }

    public function getProgramaIdPorDia($dia_id) {
        $sql = "SELECT ps.programacion_id 
                FROM programacion_dias pd 
                INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id 
                WHERE pd.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dia_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return $row['programacion_id'];
        }
        return null;
    }

    public function crearSemana($data) {
        $query = "INSERT INTO programacion_semanas (programacion_id, numero_semana) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$data['programacion_id'], $data['numero_semana']]);
        return $this->db->lastInsertId();
    }

    public function crearDia($data) {
        $sql = "INSERT INTO programacion_dias (semana_id, dia_semana, entrenamiento_id, orden) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['semana_id'],
            $data['dia_semana'],
            $data['entrenamiento_id'],
            $data['orden']
        ]);
        return $this->db->lastInsertId();
    }

    public function getSemanaId($programacion_id, $numero_semana) {
        $query = "SELECT id FROM programacion_semanas WHERE programacion_id = ? AND numero_semana = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$programacion_id, $numero_semana]);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['id'];
    }

    public function getSemanasPorProgramacion($programacion_id) {
        $query = "SELECT * FROM programacion_semanas WHERE programacion_id = ? ORDER BY numero_semana";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$programacion_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDiasPorProgramacion($programacion_id) {
        $query = "SELECT pd.*, ps.numero_semana 
                 FROM programacion_dias pd 
                 JOIN programacion_semanas ps ON pd.semana_id = ps.id 
                 WHERE ps.programacion_id = ? 
                 ORDER BY ps.numero_semana, pd.orden";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$programacion_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarDiasPorSemana($semana_id) {
        $sql = "DELETE FROM programacion_dias WHERE semana_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$semana_id]);
    }

    /**
     * Actualiza un día específico de la programación
     */
    public function actualizarDia($dia_id, $data) {
        $sql = "UPDATE programacion_dias 
                SET dia_semana = ?, 
                    entrenamiento_id = ?,
                    orden = ?
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['dia_semana'],
            $data['entrenamiento_id'],
            $data['orden'],
            $dia_id
        ]);
    }

    /**
     * Agrega entrenamientos a una semana específica de la programación
     */
    public function agregarEntrenamientosSemana($programacion_id, $numero_semana, $entrenamientos) {
        try {
            $this->db->beginTransaction();
            
            // Obtener el ID de la semana
            $semana_id = $this->getSemanaId($programacion_id, $numero_semana);
            
            if (!$semana_id) {
                // Si la semana no existe, crearla
                $sql = "INSERT INTO programacion_semanas (programacion_id, numero_semana) VALUES (?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$programacion_id, $numero_semana]);
                $semana_id = $this->db->lastInsertId();
            }
            
            // Eliminar entrenamientos existentes para esta semana
            $sql = "DELETE FROM programacion_dias WHERE semana_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$semana_id]);
            
            // Agregar los nuevos entrenamientos
            foreach ($entrenamientos as $dia => $entrenamiento_id) {
                $sql = "INSERT INTO programacion_dias (semana_id, dia_semana, entrenamiento_id) VALUES (?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$semana_id, $dia, $entrenamiento_id]);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getDiaProgramacion($dia_id) {
        $sql = "SELECT pd.*, e.id as entrenamiento_id, e.nombre as entrenamiento_nombre, 
                       e.descripcion as entrenamiento_descripcion, ps.numero_semana
                FROM programacion_dias pd
                LEFT JOIN entrenamientos e ON pd.entrenamiento_id = e.id
                INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id
                WHERE pd.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dia_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProgramasAsignadosPorEntrenador($entrenador_id) {
        try {
            $sql = "SELECT p.*, u.nombre as entrenado_nombre, u.apellido as entrenado_apellido,
                           pu.progreso, pu.estado
                    FROM programaciones p
                    INNER JOIN programaciones_usuarios pu ON p.id = pu.programacion_id
                    INNER JOIN usuarios u ON pu.usuario_id = u.id
                    INNER JOIN relaciones_entrenador_entrenado r ON u.id = r.entrenado_id
                    WHERE r.entrenador_id = ?
                    ORDER BY p.fecha_inicio DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$entrenador_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener programas asignados: " . $e->getMessage());
            return [];
        }
    }
} 