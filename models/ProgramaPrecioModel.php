<?php
require_once 'BaseModel.php';

class ProgramaPrecioModel extends BaseModel {
    protected $table = 'programas_precios';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Obtiene el precio de un programa
     */
    public function getPrecioPorPrograma($programacion_id) {
        $sql = "SELECT * FROM programas_precios WHERE programacion_id = ? AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$programacion_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene todos los precios de programas
     */
    public function getAllPrecios() {
        $sql = "SELECT pp.*, p.nombre as programa_nombre, p.descripcion as programa_descripcion
                FROM programas_precios pp
                JOIN programaciones p ON pp.programacion_id = p.id
                WHERE pp.activo = 1
                ORDER BY p.nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crea o actualiza el precio de un programa
     */
    public function guardarPrecio($data) {
        // Verificar si ya existe un precio para este programa
        $precio_existente = $this->getPrecioPorPrograma($data['programacion_id']);
        
        if ($precio_existente) {
            // Actualizar precio existente
            $sql = "UPDATE programas_precios SET precio = ?, moneda = ?, activo = ? WHERE programacion_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['precio'],
                $data['moneda'],
                $data['activo'],
                $data['programacion_id']
            ]);
        } else {
            // Crear nuevo precio
            $sql = "INSERT INTO programas_precios (programacion_id, precio, moneda, activo) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['programacion_id'],
                $data['precio'],
                $data['moneda'],
                $data['activo']
            ]);
        }
    }
    
    /**
     * Desactiva el precio de un programa
     */
    public function desactivarPrecio($programacion_id) {
        $sql = "UPDATE programas_precios SET activo = 0 WHERE programacion_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$programacion_id]);
    }
    
    /**
     * Obtiene programas con precios para mostrar en la tienda
     */
    public function getProgramasConPrecios() {
        $sql = "SELECT p.*, pp.precio, pp.moneda, pp.activo
                FROM programaciones p
                LEFT JOIN programas_precios pp ON p.id = pp.programacion_id AND pp.activo = 1
                WHERE pp.id IS NOT NULL
                ORDER BY p.nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 