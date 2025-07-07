<?php

require_once __DIR__ . '/BaseModel.php';

class ProgramacionUsuarioModel extends BaseModel {
    
    public function asignarPrograma($data) {
        try {
            $this->db->beginTransaction();
            
            // Insertar en programacion_usuarios
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

            // Obtener todos los días de la programación
            $sql = "SELECT pd.id as dia_id 
                    FROM programacion_dias pd 
                    JOIN programacion_semanas ps ON pd.semana_id = ps.id 
                    WHERE ps.programacion_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data['programacion_id']]);
            $dias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Crear registros en programacion_dias_usuarios para cada día
            $sql = "INSERT INTO programacion_dias_usuarios (usuario_id, dia_id, completado) 
                    VALUES (?, ?, 0)";
            $stmt = $this->db->prepare($sql);

            foreach ($dias as $dia) {
                $stmt->execute([$data['usuario_id'], $dia['dia_id']]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en ProgramacionUsuarioModel::asignarPrograma: " . $e->getMessage());
            throw new Exception("Error al asignar el programa al usuario");
        }
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
                       ROUND((SELECT COUNT(*) FROM programacion_dias_usuarios pdu 
                             INNER JOIN programacion_dias pd ON pdu.dia_id = pd.id
                             INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id
                             WHERE ps.programacion_id = p.id AND pdu.usuario_id = :usuario_id AND pdu.completado = 1) * 100.0 / 
                            (SELECT COUNT(*) FROM programacion_dias pd
                             INNER JOIN programacion_semanas ps ON pd.semana_id = ps.id
                             WHERE ps.programacion_id = p.id), 1) as progreso
                   FROM programacion_usuarios pu
                   INNER JOIN programaciones p ON pu.programacion_id = p.id
                   WHERE pu.usuario_id = :usuario_id 
                   AND pu.fecha_fin >= CURDATE()
                   ORDER BY pu.fecha_inicio ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getProgramasActivosPorUsuario: " . $e->getMessage());
            return [];
        }
    }
} 