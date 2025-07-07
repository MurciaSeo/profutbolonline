<?php

require_once __DIR__ . '/../config/database.php';

class BaseModel {
    protected $db;
    protected $table;
    
    public function __construct($table = null) {
        $this->db = Database::getInstance()->getConnection();
        if ($table) {
            $this->table = $table;
        }
    }
    
    public function setTable($table) {
        $this->table = $table;
        return $this;
    }
    
    public function getDb() {
        return $this->db;
    }
    
    public function findAll() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        
        $query = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute(array_values($data));
    }
    
    public function update($id, $data) {
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $query = "UPDATE {$this->table} SET $set WHERE id = ?";
        
        $stmt = $this->db->prepare($query);
        $values = array_values($data);
        $values[] = $id;
        
        return $stmt->execute($values);
    }
    
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
    
    public function actualizarConfiguracion($clave, $valor) {
        // Verificar si la clave existe
        $query = "SELECT id FROM configuracion WHERE clave = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$clave]);
        
        if ($stmt->rowCount() > 0) {
            // Actualizar el valor existente
            $query = "UPDATE configuracion SET valor = ? WHERE clave = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$valor, $clave]);
        } else {
            // Insertar nueva configuración
            $query = "INSERT INTO configuracion (clave, valor) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$clave, $valor]);
        }
    }
} 