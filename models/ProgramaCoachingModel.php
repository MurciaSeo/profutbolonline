<?php
require_once 'BaseModel.php';

class ProgramaCoachingModel extends BaseModel {
    protected $table = 'programas_coaching';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Obtiene todos los programas de coaching activos
     */
    public function getProgramasActivos() {
        $sql = "SELECT * FROM {$this->table} WHERE activo = 1 ORDER BY categoria, nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene un programa por ID con sus bloques
     */
    public function getProgramaConBloques($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $programa = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($programa) {
            // Obtener los bloques del programa
            $sql = "SELECT * FROM coaching_bloques WHERE programa_coaching_id = ? ORDER BY mes, orden";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $programa['bloques'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $programa;
    }
    
    /**
     * Obtiene un programa por ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene programas por categoría
     */
    public function getProgramasPorCategoria($categoria) {
        $sql = "SELECT * FROM {$this->table} WHERE categoria = ? AND activo = 1 ORDER BY nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoria]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene las categorías disponibles
     */
    public function getCategorias() {
        $sql = "SELECT DISTINCT categoria FROM {$this->table} WHERE activo = 1 ORDER BY categoria";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Crea un nuevo programa de coaching
     */
    public function crearPrograma($data) {
        $sql = "INSERT INTO {$this->table} (nombre, descripcion, categoria, duracion_meses, precio_mensual, moneda, imagen_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['categoria'],
            $data['duracion_meses'],
            $data['precio_mensual'],
            $data['moneda'],
            $data['imagen_url'] ?? null
        ]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Actualiza un programa de coaching
     */
    public function actualizarPrograma($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                nombre = ?, descripcion = ?, categoria = ?, duracion_meses = ?, 
                precio_mensual = ?, moneda = ?, imagen_url = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['categoria'],
            $data['duracion_meses'],
            $data['precio_mensual'],
            $data['moneda'],
            $data['imagen_url'] ?? null,
            $id
        ]);
    }
    
    /**
     * Desactiva un programa (borrado lógico)
     */
    public function desactivarPrograma($id) {
        $sql = "UPDATE {$this->table} SET activo = 0, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Obtiene estadísticas de programas
     */
    public function getEstadisticas() {
        $sql = "SELECT 
                    COUNT(*) as total_programas,
                    COUNT(CASE WHEN activo = 1 THEN 1 END) as programas_activos,
                    COUNT(CASE WHEN activo = 0 THEN 1 END) as programas_inactivos,
                    AVG(precio_mensual) as precio_promedio,
                    SUM(duracion_meses) as total_meses
                FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene programas con estadísticas de suscripciones
     */
    public function getProgramasConEstadisticas() {
        $sql = "SELECT pc.*, 
                       COUNT(DISTINCT sc.id) as total_suscripciones,
                       COUNT(DISTINCT CASE WHEN sc.estado = 'activa' THEN sc.id END) as suscripciones_activas
                FROM {$this->table} pc
                LEFT JOIN suscripciones_coaching sc ON pc.id = sc.programa_coaching_id
                WHERE pc.activo = 1
                GROUP BY pc.id
                ORDER BY pc.categoria, pc.nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene top programas por suscripciones
     */
    public function getTopProgramas($limite = 5) {
        $sql = "SELECT pc.nombre, pc.descripcion, pc.categoria,
                       COUNT(sc.id) as suscripciones,
                       SUM(ps.monto) as ingresos
                FROM {$this->table} pc
                LEFT JOIN suscripciones_coaching sc ON pc.id = sc.programa_coaching_id
                LEFT JOIN pagos_suscripcion ps ON sc.id = ps.suscripcion_id AND ps.estado = 'completado'
                WHERE pc.activo = 1
                GROUP BY pc.id
                ORDER BY suscripciones DESC, ingresos DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 