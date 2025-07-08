<?php

require_once __DIR__ . '/BaseModel.php';

class ProgramacionUsuarioModel extends BaseModel {
    
    public function asignarPrograma($data) {
        try {
            $this->db->beginTransaction();
            
            // Verificar si ya existe una asignación para este usuario y programa
            $sql = "SELECT id FROM programacion_usuarios WHERE usuario_id = ? AND programacion_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data['usuario_id'], $data['programacion_id']]);
            $asignacion_existente = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($asignacion_existente) {
                // Si ya existe, actualizar la fecha de inicio
                $sql = "UPDATE programacion_usuarios 
                        SET fecha_inicio = ?, estado = ?, fecha_fin = NULL 
                        WHERE usuario_id = ? AND programacion_id = ?";
                $stmt = $this->db->prepare($sql);
                $estado = $data['estado'] ?? 'activo';
                $stmt->execute([$data['fecha_inicio'], $estado, $data['usuario_id'], $data['programacion_id']]);
            } else {
                // Insertar nueva asignación
                $sql = "INSERT INTO programacion_usuarios (programacion_id, usuario_id, fecha_inicio, fecha_fin, estado) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $estado = $data['estado'] ?? 'activo';
                $fecha_fin = $data['fecha_fin'] ?? null;
                
                $stmt->execute([
                    $data['programacion_id'],
                    $data['usuario_id'],
                    $data['fecha_inicio'],
                    $fecha_fin,
                    $estado
                ]);
            }

            // Obtener todos los días de la programación con sus entrenamientos
            $sql = "SELECT pd.id as dia_id, pd.entrenamiento_id, ps.numero_semana, pd.dia_semana
                    FROM programacion_dias pd 
                    JOIN programacion_semanas ps ON pd.semana_id = ps.id 
                    WHERE ps.programacion_id = ? AND pd.entrenamiento_id IS NOT NULL";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data['programacion_id']]);
            $dias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Crear registros en programacion_dias_usuarios para cada día (usando INSERT IGNORE)
            $sql = "INSERT IGNORE INTO programacion_dias_usuarios (usuario_id, dia_id, completado) 
                    VALUES (?, ?, 0)";
            $stmt = $this->db->prepare($sql);

            foreach ($dias as $dia) {
                $stmt->execute([$data['usuario_id'], $dia['dia_id']]);
            }

            // Crear sesiones individuales para cada entrenamiento del programa
            $sql = "INSERT INTO sesiones (entrenamiento_id, usuario_id, fecha, notas, programacion_id, semana_id, dia_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);

            foreach ($dias as $dia) {
                // Calcular la fecha del entrenamiento basada en la semana y día
                $fecha_entrenamiento = $this->calcularFechaEntrenamiento(
                    $data['fecha_inicio'], 
                    $dia['numero_semana'], 
                    $dia['dia_semana']
                );
                
                $notas = "Entrenamiento asignado automáticamente desde programa";
                
                $stmt->execute([
                    $dia['entrenamiento_id'],
                    $data['usuario_id'],
                    $fecha_entrenamiento,
                    $notas,
                    $data['programacion_id'],
                    $dia['numero_semana'],
                    $dia['dia_id']
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en ProgramacionUsuarioModel::asignarPrograma: " . $e->getMessage());
            throw new Exception("Error al asignar el programa al usuario");
        }
    }

    /**
     * Calcula la fecha de un entrenamiento basada en la fecha de inicio del programa,
     * la semana y el día de la semana
     */
    private function calcularFechaEntrenamiento($fecha_inicio, $numero_semana, $dia_semana) {
        // Convertir fecha de inicio a DateTime
        $fecha_inicio_dt = new DateTime($fecha_inicio);
        
        // Calcular días desde el inicio hasta la semana correspondiente
        $dias_hasta_semana = ($numero_semana - 1) * 7;
        
        // Calcular días desde el lunes de esa semana hasta el día específico
        // 1 = Lunes, 2 = Martes, ..., 7 = Domingo
        $dias_desde_lunes = $dia_semana - 1;
        
        // Calcular el lunes de la semana correspondiente
        $lunes_semana = clone $fecha_inicio_dt;
        $lunes_semana->add(new DateInterval("P{$dias_hasta_semana}D"));
        
        // Ajustar al lunes de esa semana
        $dias_hasta_lunes = $lunes_semana->format('N') - 1; // N = 1 (Lunes) a 7 (Domingo)
        $lunes_semana->sub(new DateInterval("P{$dias_hasta_lunes}D"));
        
        // Calcular la fecha final del entrenamiento
        $fecha_entrenamiento = clone $lunes_semana;
        $fecha_entrenamiento->add(new DateInterval("P{$dias_desde_lunes}D"));
        
        return $fecha_entrenamiento->format('Y-m-d');
    }

    public function getProgramasAsignados($usuario_id) {
        try {
            $sql = "SELECT pu.*, p.id as programacion_id, p.nombre as nombre_programa, p.descripcion, p.duracion_semanas, 
                           p.entrenamientos_por_semana, p.nivel, p.objetivo
                    FROM programacion_usuarios pu
                    JOIN programaciones p ON p.id = pu.programacion_id
                    WHERE pu.usuario_id = ?
                    ORDER BY pu.fecha_inicio DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en ProgramacionUsuarioModel::getProgramasAsignados: " . $e->getMessage());
            throw new Exception("Error al obtener los programas asignados");
        }
    }

    public function getUsuariosAsignados($programacion_id) {
        try {
            $sql = "SELECT pu.*, u.nombre, u.apellido, u.email
                    FROM programacion_usuarios pu
                    JOIN usuarios u ON u.id = pu.usuario_id
                    WHERE pu.programacion_id = ?
                    ORDER BY pu.fecha_inicio DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$programacion_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en ProgramacionUsuarioModel::getUsuariosAsignados: " . $e->getMessage());
            throw new Exception("Error al obtener los usuarios asignados");
        }
    }

    public function actualizarEstado($id, $estado) {
        try {
            $sql = "UPDATE programacion_usuarios 
                    SET estado = ?, 
                        fecha_fin = CASE 
                            WHEN ? IN ('completado', 'cancelado') THEN CURRENT_DATE 
                            ELSE fecha_fin 
                        END
                    WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$estado, $estado, $id]);
        } catch (Exception $e) {
            error_log("Error en ProgramacionUsuarioModel::actualizarEstado: " . $e->getMessage());
            throw new Exception("Error al actualizar el estado de la asignación");
        }
    }

    public function eliminarAsignacion($id) {
        try {
            $sql = "DELETE FROM programacion_usuarios WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            error_log("Error en ProgramacionUsuarioModel::eliminarAsignacion: " . $e->getMessage());
            throw new Exception("Error al eliminar la asignación");
        }
    }

    public function getAllAsignaciones() {
        try {
            $sql = "SELECT pu.*, 
                           p.nombre as nombre_programa,
                           CONCAT(u.nombre, ' ', u.apellido) as nombre_usuario
                    FROM programacion_usuarios pu
                    JOIN programaciones p ON p.id = pu.programacion_id
                    JOIN usuarios u ON u.id = pu.usuario_id
                    ORDER BY pu.fecha_inicio DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en ProgramacionUsuarioModel::getAllAsignaciones: " . $e->getMessage());
            throw new Exception("Error al obtener las asignaciones");
        }
    }

    public function verificarAccesoPrograma($usuario_id, $programa_id) {
        try {
            $sql = "SELECT pu.* 
                    FROM programacion_usuarios pu 
                    WHERE pu.usuario_id = ? 
                    AND pu.programacion_id = ? 
                    AND DATE(pu.fecha_inicio) <= CURDATE()
                    AND (pu.fecha_fin IS NULL OR DATE(pu.fecha_fin) >= CURDATE())";

            //echo $sql;die;
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id, $programa_id]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Verificando acceso - Usuario: $usuario_id, Programa: $programa_id, Resultado: " . ($result ? 'true' : 'false'));
            return $result ? true : false;
        } catch (Exception $e) {
            error_log("Error en verificarAccesoPrograma: " . $e->getMessage());
            return false;
        }
    }

    public function verificarAccesoDia($dia_id, $usuario_id) {
        $sql = "SELECT 1 FROM programacion_usuarios pu
                JOIN programacion_semanas ps ON ps.programacion_id = pu.programacion_id
                JOIN programacion_dias pd ON pd.semana_id = ps.id
                WHERE pu.usuario_id = ? AND pd.id = ? 
                AND pu.estado = 'activo'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $dia_id]);
        return $stmt->rowCount() > 0;
    }

    public function getAsignacionPrograma($usuario_id, $programa_id) {
        try {
            $sql = "SELECT pu.*, p.nombre as nombre_programa, p.descripcion, p.nivel, p.objetivo
                    FROM programacion_usuarios pu
                    JOIN programaciones p ON p.id = pu.programacion_id
                    WHERE pu.usuario_id = ? AND pu.programacion_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id, $programa_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getAsignacionPrograma: " . $e->getMessage());
            return null;
        }
    }

    public function getProgramasAsignadosPorEntrenador($entrenador_id) {
        try {
            $sql = "SELECT DISTINCT p.*, u.nombre as entrenado_nombre, u.apellido as entrenado_apellido,
                           pu.fecha_inicio, pu.fecha_fin, pu.estado
                    FROM programaciones p
                    INNER JOIN programacion_usuarios pu ON p.id = pu.programacion_id
                    INNER JOIN usuarios u ON pu.usuario_id = u.id
                    WHERE u.entrenador_id = ?
                    ORDER BY pu.fecha_inicio DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$entrenador_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getProgramasAsignadosPorEntrenador: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalProgramasActivosPorEntrenador($entrenador_id) {
        try {
            $sql = "SELECT COUNT(DISTINCT pu.programacion_id) as total 
                   FROM programacion_usuarios pu
                   INNER JOIN usuarios u ON pu.usuario_id = u.id
                   WHERE u.entrenador_id = :entrenador_id 
                   AND pu.fecha_fin >= CURDATE()";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':entrenador_id', $entrenador_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error en getTotalProgramasActivosPorEntrenador: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalProgramasActivosPorUsuario($usuario_id) {
        try {
            $sql = "SELECT COUNT(*) as total 
                   FROM programacion_usuarios 
                   WHERE usuario_id = :usuario_id 
                   AND fecha_fin >= CURDATE()";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error en getTotalProgramasActivosPorUsuario: " . $e->getMessage());
            return 0;
        }
    }

    public function getProgramasActivosPorUsuario($usuario_id) {
        try {
            $sql = "SELECT 
                       p.id,
                       p.nombre,
                       pu.fecha_inicio,
                       pu.fecha_fin,
                       CASE 
                           WHEN pu.fecha_fin < CURDATE() THEN 'completado'
                           WHEN pu.fecha_inicio <= CURDATE() THEN 'activo'
                           ELSE 'pendiente'
                       END as estado,
                       COALESCE(
                           ROUND(
                               (SELECT COUNT(*) FROM programacion_dias_usuarios pdu 
                                INNER JOIN programacion_dias pd ON pdu.dia_id = pd.id
                                INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id
                                WHERE ps.programacion_id = p.id AND pdu.usuario_id = ? AND pdu.completado = 1) * 100.0 / 
                               NULLIF((SELECT COUNT(*) FROM programacion_dias pd
                                      INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id
                                      WHERE ps.programacion_id = p.id), 0)
                           , 1)
                       , 0) as progreso
                   FROM programacion_usuarios pu
                   INNER JOIN programaciones p ON pu.programacion_id = p.id
                   WHERE pu.usuario_id = ? 
                   AND pu.fecha_fin >= CURDATE()
                   ORDER BY pu.fecha_inicio ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id, $usuario_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getProgramasActivosPorUsuario: " . $e->getMessage());
            return [];
        }
    }
} 