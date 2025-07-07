<?php
require_once 'BaseModel.php';

class EjercicioModel extends BaseModel {
    public function __construct() {
        parent::__construct('ejercicios');
    }
    
    public function getEjerciciosPorTipo($tipo = null) {
        $sql = "SELECT e.*, t.nombre as tipo_nombre, t.color as tipo_color, t.icono as tipo_icono 
                FROM ejercicios e 
                LEFT JOIN tipos_ejercicios t ON e.tipo_id = t.id";
        
        if ($tipo) {
            $sql .= " WHERE t.nombre = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$tipo]);
        } else {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTiposEjercicios() {
        $sql = "SELECT t.nombre FROM tipos_ejercicios t ORDER BY t.nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getTotalEjercicios() {
        $sql = "SELECT COUNT(*) as total FROM ejercicios";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getEjerciciosPopulares($limite = 5) {
        $sql = "SELECT e.*, COUNT(ee.id) as total_uso 
                FROM ejercicios e 
                LEFT JOIN entrenamiento_ejercicios ee ON e.id = ee.ejercicio_id 
                GROUP BY e.id 
                ORDER BY total_uso DESC 
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $query = "SELECT e.*, t.nombre as tipo_nombre, t.color as tipo_color, t.icono as tipo_icono
                  FROM ejercicios e
                  JOIN tipos_ejercicios t ON e.tipo_id = t.id
                  ORDER BY e.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllEjercicios() {
        $sql = "SELECT e.*, t.nombre as tipo_nombre, t.color as tipo_color, t.icono as tipo_icono 
                FROM ejercicios e 
                LEFT JOIN tipos_ejercicios t ON e.tipo_id = t.id 
                ORDER BY e.nombre ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $query = "SELECT e.*, t.nombre as tipo_nombre, t.color as tipo_color, t.icono as tipo_icono
                  FROM ejercicios e
                  JOIN tipos_ejercicios t ON e.tipo_id = t.id
                  WHERE e.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByTipo($tipo_id) {
        $query = "SELECT e.*, t.nombre as tipo_nombre, t.color as tipo_color, t.icono as tipo_icono
                  FROM ejercicios e
                  JOIN tipos_ejercicios t ON e.tipo_id = t.id
                  WHERE e.tipo_id = ?
                  ORDER BY e.nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$tipo_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        try {
            // Validar datos requeridos
            if (empty($data['nombre']) || empty($data['tipo_id'])) {
                throw new Exception("Nombre y tipo son campos requeridos");
            }

            // Verificar si el tipo existe
            $sql = "SELECT id FROM tipos_ejercicios WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data['tipo_id']]);
            if (!$stmt->fetch()) {
                throw new Exception("El tipo de ejercicio seleccionado no existe");
            }

            $query = "INSERT INTO ejercicios (nombre, descripcion, tipo_id, video_url) 
                      VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            
            $result = $stmt->execute([
                trim($data['nombre']),
                trim($data['descripcion'] ?? ''),
                (int)$data['tipo_id'],
                !empty($data['video_url']) ? trim($data['video_url']) : null
            ]);

            if (!$result) {
                throw new Exception("Error al crear el ejercicio: " . implode(", ", $stmt->errorInfo()));
            }

            return true;
        } catch (Exception $e) {
            error_log("Error en EjercicioModel::create: " . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data) {
        $query = "UPDATE ejercicios 
                  SET nombre = ?, descripcion = ?, tipo_id = ?, video_url = ?
                  WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['tipo_id'],
            $data['video_url'] ?? null,
            $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM ejercicios WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function buscar($busqueda = '', $tipo_id = null, $pagina = 1, $por_pagina = 10) {
        $sql = "SELECT e.*, t.nombre as tipo_nombre, t.color as tipo_color, t.icono as tipo_icono
                FROM ejercicios e
                JOIN tipos_ejercicios t ON e.tipo_id = t.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($busqueda)) {
            $sql .= " AND (e.nombre LIKE ? OR e.descripcion LIKE ?)";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
        }
        
        if ($tipo_id) {
            $sql .= " AND e.tipo_id = ?";
            $params[] = $tipo_id;
        }
        
        $sql .= " ORDER BY e.nombre";
        
        // Agregar paginación
        $offset = ($pagina - 1) * $por_pagina;
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $por_pagina;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarBusqueda($busqueda = '', $tipo_id = null) {
        $sql = "SELECT COUNT(*) as total 
                FROM ejercicios e 
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($busqueda)) {
            $sql .= " AND (e.nombre LIKE ? OR e.descripcion LIKE ?)";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
        }
        
        if ($tipo_id) {
            $sql .= " AND e.tipo_id = ?";
            $params[] = $tipo_id;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
} 