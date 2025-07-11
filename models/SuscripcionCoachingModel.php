<?php
require_once 'BaseModel.php';

class SuscripcionCoachingModel extends BaseModel {
    protected $table = 'suscripciones_coaching';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Crea una nueva suscripción
     */
    public function crearSuscripcion($data) {
        $sql = "INSERT INTO {$this->table} (usuario_id, programa_coaching_id, stripe_subscription_id, fecha_inicio, mes_actual) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['usuario_id'],
            $data['programa_coaching_id'],
            $data['stripe_subscription_id'] ?? null,
            $data['fecha_inicio'],
            $data['mes_actual'] ?? 1
        ]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Obtiene las suscripciones activas de un usuario
     */
    public function getSuscripcionesActivas($usuario_id) {
        $sql = "SELECT sc.*, pc.nombre as programa_nombre, pc.descripcion as programa_descripcion, 
                       pc.categoria, pc.duracion_meses, pc.precio_mensual, pc.moneda
                FROM {$this->table} sc
                JOIN programas_coaching pc ON sc.programa_coaching_id = pc.id
                WHERE sc.usuario_id = ? AND sc.estado = 'activa'
                ORDER BY sc.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene todas las suscripciones de un usuario (activas y canceladas)
     */
    public function getSuscripcionesPorUsuario($usuario_id) {
        $sql = "SELECT sc.*, pc.nombre as programa_nombre, pc.descripcion as programa_descripcion, 
                       pc.categoria, pc.duracion_meses, pc.precio_mensual, pc.moneda
                FROM {$this->table} sc
                JOIN programas_coaching pc ON sc.programa_coaching_id = pc.id
                WHERE sc.usuario_id = ?
                ORDER BY sc.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene una suscripción específica con detalles del programa
     */
    public function getSuscripcionConDetalles($suscripcion_id, $usuario_id = null) {
        $sql = "SELECT sc.*, pc.nombre as programa_nombre, pc.descripcion as programa_descripcion, 
                       pc.categoria, pc.duracion_meses, pc.precio_mensual, pc.moneda,
                       u.nombre as usuario_nombre, u.apellido as usuario_apellido
                FROM {$this->table} sc
                JOIN programas_coaching pc ON sc.programa_coaching_id = pc.id
                JOIN usuarios u ON sc.usuario_id = u.id
                WHERE sc.id = ?";
        
        $params = [$suscripcion_id];
        if ($usuario_id) {
            $sql .= " AND sc.usuario_id = ?";
            $params[] = $usuario_id;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Actualiza el estado de una suscripción
     */
    public function actualizarEstado($suscripcion_id, $estado, $fecha_fin = null) {
        $sql = "UPDATE {$this->table} SET estado = ?, fecha_fin = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$estado, $fecha_fin, $suscripcion_id]);
    }
    
    /**
     * Actualiza el mes actual de la suscripción
     */
    public function actualizarMesActual($suscripcion_id, $mes_actual) {
        $sql = "UPDATE {$this->table} SET mes_actual = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$mes_actual, $suscripcion_id]);
    }
    
    /**
     * Actualiza las fechas de pago
     */
    public function actualizarFechasPago($suscripcion_id, $ultimo_pago, $proximo_pago) {
        $sql = "UPDATE {$this->table} SET ultimo_pago = ?, proximo_pago = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$ultimo_pago, $proximo_pago, $suscripcion_id]);
    }
    
    /**
     * Verifica si un usuario ya tiene una suscripción activa a un programa
     */
    public function usuarioTieneSuscripcionActiva($usuario_id, $programa_coaching_id) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE usuario_id = ? AND programa_coaching_id = ? AND estado = 'activa'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $programa_coaching_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }
    
    /**
     * Obtiene la suscripción activa de un usuario a un programa específico
     */
    public function getSuscripcionActiva($usuario_id, $programa_coaching_id) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE usuario_id = ? AND programa_coaching_id = ? AND estado = 'activa'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $programa_coaching_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene todas las suscripciones (para administradores)
     */
    public function getAllSuscripciones() {
        $sql = "SELECT sc.*, pc.nombre as nombre_programa, pc.categoria,
                       u.nombre as nombre_usuario, u.apellido as apellido_usuario, u.email as email_usuario
                FROM {$this->table} sc
                JOIN programas_coaching pc ON sc.programa_coaching_id = pc.id
                JOIN usuarios u ON sc.usuario_id = u.id
                ORDER BY sc.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene estadísticas de suscripciones
     */
    public function getEstadisticas() {
        $sql = "SELECT 
                    COUNT(*) as total_suscripciones,
                    COUNT(CASE WHEN estado = 'activa' THEN 1 END) as suscripciones_activas,
                    COUNT(CASE WHEN estado = 'cancelada' THEN 1 END) as suscripciones_canceladas,
                    COUNT(CASE WHEN estado = 'pausada' THEN 1 END) as suscripciones_pausadas,
                    AVG(mes_actual) as mes_promedio
                FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene suscripciones que necesitan renovación
     */
    public function getSuscripcionesParaRenovar() {
        $sql = "SELECT sc.*, pc.nombre as programa_nombre, pc.precio_mensual
                FROM {$this->table} sc
                JOIN programas_coaching pc ON sc.programa_coaching_id = pc.id
                WHERE sc.estado = 'activa' 
                AND sc.proximo_pago <= DATE_ADD(NOW(), INTERVAL 7 DAY)
                ORDER BY sc.proximo_pago ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene suscripciones por mes
     */
    public function getSuscripcionesPorMes($mes) {
        $sql = "SELECT COUNT(*) as suscripciones
                FROM {$this->table} 
                WHERE estado = 'activa' 
                AND DATE_FORMAT(created_at, '%Y-%m') = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$mes]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['suscripciones'] ?? 0;
    }
} 