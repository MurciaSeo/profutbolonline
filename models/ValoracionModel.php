<?php
require_once 'BaseModel.php';

class ValoracionModel extends BaseModel {
    public function __construct() {
        parent::__construct();
    }

    public function guardarValoracion($entrenamiento_id, $usuario_id, $calidad, $esfuerzo, $complejidad, $duracion, $comentarios = null, $id_referencia = null, $es_sesion = false) {
        try {
            $this->db->beginTransaction();
            
            // Validar y convertir valores a enteros entre 1 y 5
            $calidad = max(1, min(5, (int)$calidad));
            $esfuerzo = max(1, min(5, (int)$esfuerzo));
            $complejidad = max(1, min(5, (int)$complejidad));
            $duracion = max(1, min(5, (int)$duracion));
            
            // Preparar la consulta según si es una sesión o un día de programa
            if ($es_sesion) {
                $sql = "INSERT INTO valoraciones_entrenamientos 
                        (entrenamiento_id, usuario_id, sesion_id, dia_id, calidad, esfuerzo, complejidad, duracion, comentarios) 
                        VALUES (?, ?, ?, 0, ?, ?, ?, ?, ?)";
            } else {
                $sql = "INSERT INTO valoraciones_entrenamientos 
                        (entrenamiento_id, usuario_id, sesion_id, dia_id, calidad, esfuerzo, complejidad, duracion, comentarios) 
                        VALUES (?, ?, 0, ?, ?, ?, ?, ?, ?)";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $entrenamiento_id,
                $usuario_id,
                $id_referencia, // será sesion_id o dia_id según el caso
                $calidad,
                $esfuerzo,
                $complejidad,
                $duracion,
                $comentarios
            ]);
            
            // Si es una sesión, marcarla como valorada
            if ($es_sesion) {
                $sql = "UPDATE sesiones SET valorado = TRUE WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id_referencia]);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error al guardar valoración: " . $e->getMessage());
            return false;
        }
    }

    public function getValoracion($entrenamiento_id, $usuario_id, $id_referencia = null, $es_sesion = false) {
        try {
            $sql = "SELECT * FROM valoraciones_entrenamientos 
                    WHERE entrenamiento_id = ? AND usuario_id = ?";
            $params = [$entrenamiento_id, $usuario_id];
            
            if ($id_referencia !== null) {
                if ($es_sesion) {
                    $sql .= " AND sesion_id = ?";
                } else {
                    $sql .= " AND dia_id = ?";
                }
                $params[] = $id_referencia;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener valoración: " . $e->getMessage());
            return false;
        }
    }
} 