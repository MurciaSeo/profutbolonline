<?php
require_once 'BaseModel.php';

class SesionModel extends BaseModel {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Obtiene todas las sesiones de un usuario
     */
    public function getSesionesPorUsuario($usuario_id) {
        $sql = "SELECT s.*, e.nombre as entrenamiento_nombre, e.descripcion as entrenamiento_descripcion,
                       u.nombre as creador_nombre, u.apellido as creador_apellido,
                       v.calidad, v.esfuerzo, v.complejidad, v.duracion, v.comentarios
                FROM sesiones s
                JOIN entrenamientos e ON s.entrenamiento_id = e.id
                JOIN usuarios u ON e.creado_por = u.id
                LEFT JOIN valoraciones_entrenamientos v ON s.id = v.sesion_id AND v.usuario_id = s.usuario_id
                WHERE s.usuario_id = ?
                ORDER BY s.fecha DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todas las sesiones asignadas por un entrenador
     */
    public function getSesionesPorEntrenador($entrenador_id) {
        $sql = "SELECT s.*, e.nombre as entrenamiento_nombre, e.descripcion as entrenamiento_descripcion,
                       u.nombre as usuario_nombre, u.apellido as usuario_apellido
                FROM sesiones s
                JOIN entrenamientos e ON s.entrenamiento_id = e.id
                JOIN usuarios u ON s.usuario_id = u.id
                WHERE e.creado_por = ?
                ORDER BY s.fecha DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$entrenador_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una sesión por su ID
     */
    public function getSesionPorId($id) {
        // Primero obtenemos la información básica de la sesión
        $sql = "SELECT s.*, e.id as entrenamiento_id, e.nombre as entrenamiento_nombre, 
                       e.descripcion as entrenamiento_descripcion, e.creado_por,
                       u.nombre as usuario_nombre, u.apellido as usuario_apellido,
                       c.nombre as creador_nombre, c.apellido as creador_apellido
                FROM sesiones s
                JOIN entrenamientos e ON s.entrenamiento_id = e.id
                JOIN usuarios u ON s.usuario_id = u.id
                JOIN usuarios c ON e.creado_por = c.id
                WHERE s.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $sesion = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$sesion) {
            return null;
        }

        // Obtenemos los bloques del entrenamiento
        $sql = "SELECT b.*, e.id as ejercicio_id, e.nombre as ejercicio_nombre, 
                       e.descripcion as ejercicio_descripcion, e.video_url,
                       ee.tiempo, ee.repeticiones, ee.tiempo_descanso, ee.orden as ejercicio_orden,
                       t.nombre as tipo_nombre, t.color as tipo_color, t.icono as tipo_icono
                FROM entrenamiento_bloques b
                LEFT JOIN entrenamiento_ejercicios ee ON b.id = ee.bloque_id
                LEFT JOIN ejercicios e ON ee.ejercicio_id = e.id
                LEFT JOIN tipos_ejercicios t ON e.tipo_id = t.id
                WHERE b.entrenamiento_id = ?
                ORDER BY b.orden, ee.orden";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$sesion['entrenamiento_id']]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Organizamos los bloques y ejercicios
        $bloques = [];
        foreach ($resultados as $row) {
            $bloque_id = $row['id'];
            if (!isset($bloques[$bloque_id])) {
                $bloques[$bloque_id] = [
                    'id' => $bloque_id,
                    'nombre' => $row['nombre'],
                    'descripcion' => $row['descripcion'],
                    'orden' => $row['orden'],
                    'serie' => $row['serie'] ?? 1,
                    'ejercicios' => []
                ];
            }
            
            if ($row['ejercicio_id']) {
                $bloques[$bloque_id]['ejercicios'][] = [
                    'id' => $row['ejercicio_id'],
                    'nombre' => $row['ejercicio_nombre'],
                    'descripcion' => $row['ejercicio_descripcion'],
                    'video_url' => $row['video_url'],
                    'tiempo' => $row['tiempo'],
                    'repeticiones' => $row['repeticiones'],
                    'tiempo_descanso' => $row['tiempo_descanso'],
                    'orden' => $row['ejercicio_orden'],
                    'tipo_nombre' => $row['tipo_nombre'],
                    'tipo_color' => $row['tipo_color'],
                    'tipo_icono' => $row['tipo_icono']
                ];
            }
        }
        
        // Ordenamos los bloques por orden
        usort($bloques, function($a, $b) {
            return $a['orden'] - $b['orden'];
        });
        
        // Añadimos los bloques a la sesión
        $sesion['bloques'] = array_values($bloques);
        
        return $sesion;
    }

    /**
     * Crea una nueva sesión
     */
    public function crear($datos) {
        $sql = "INSERT INTO sesiones (entrenamiento_id, usuario_id, fecha, notas)
                VALUES (?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $datos['entrenamiento_id'],
            $datos['usuario_id'],
            $datos['fecha'],
            $datos['notas'] ?? null
        ]);
    }

    /**
     * Actualiza una sesión existente
     */
    public function actualizar($id, $datos) {
        $sql = "UPDATE sesiones 
                SET entrenamiento_id = ?, fecha = ?, notas = ?
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $datos['entrenamiento_id'],
            $datos['fecha'],
            $datos['notas'] ?? null,
            $id
        ]);
    }

    /**
     * Marca una sesión como completada
     */
    public function marcarCompletada($id) {
        $sql = "UPDATE sesiones 
                SET completado = TRUE,
                    fecha_completado = NOW()
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Elimina una sesión
     */
    public function eliminar($id) {
        $sql = "DELETE FROM sesiones WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Obtiene las sesiones pendientes de un usuario
     */
    public function getSesionesPendientes($usuario_id) {
        $sql = "SELECT s.*, e.nombre as entrenamiento_nombre, e.descripcion as entrenamiento_descripcion
                FROM sesiones s
                JOIN entrenamientos e ON s.entrenamiento_id = e.id
                WHERE s.usuario_id = ? AND s.completado = FALSE
                ORDER BY s.fecha ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene las sesiones completadas de un usuario
     */
    public function getSesionesCompletadas($usuario_id) {
        $sql = "SELECT s.*, e.nombre as entrenamiento_nombre, e.descripcion as entrenamiento_descripcion
                FROM sesiones s
                JOIN entrenamientos e ON s.entrenamiento_id = e.id
                WHERE s.usuario_id = ? AND s.completado = TRUE
                ORDER BY s.fecha_completado DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 