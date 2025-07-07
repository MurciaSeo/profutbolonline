<?php
require_once 'BaseModel.php';

class TipoEjercicioModel extends BaseModel {
    public function __construct() {
        parent::__construct();
        $this->table = 'tipos_ejercicios';
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (nombre, descripcion, color, icono) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['color'],
            $data['icono']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET nombre = ?, descripcion = ?, color = ?, icono = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['color'],
            $data['icono'],
            $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function isUsed($id) {
        $sql = "SELECT COUNT(*) as count FROM ejercicios WHERE tipo_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
} 