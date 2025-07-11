<?php
require_once 'BaseModel.php';

class PagoSuscripcionModel extends BaseModel {
    protected $table = 'pagos_suscripcion';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Crea un nuevo pago de suscripción
     */
    public function crearPago($data) {
        $sql = "INSERT INTO {$this->table} (suscripcion_id, stripe_payment_intent_id, mes, monto, moneda, estado, fecha_pago) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['suscripcion_id'],
            $data['stripe_payment_intent_id'],
            $data['mes'],
            $data['monto'],
            $data['moneda'],
            $data['estado'],
            $data['fecha_pago'] ?? null
        ]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Actualiza el estado de un pago
     */
    public function actualizarEstadoPago($pago_id, $estado, $fecha_pago = null) {
        $sql = "UPDATE {$this->table} SET estado = ?, fecha_pago = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$estado, $fecha_pago, $pago_id]);
    }
    
    /**
     * Obtiene los pagos de una suscripción
     */
    public function getPagosPorSuscripcion($suscripcion_id) {
        $sql = "SELECT * FROM {$this->table} WHERE suscripcion_id = ? ORDER BY mes ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$suscripcion_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene un pago por ID de Stripe
     */
    public function getPagoPorStripeId($stripe_payment_intent_id) {
        $sql = "SELECT * FROM {$this->table} WHERE stripe_payment_intent_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$stripe_payment_intent_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene estadísticas de pagos
     */
    public function getEstadisticasPagos() {
        $sql = "SELECT 
                    COUNT(*) as total_pagos,
                    COUNT(CASE WHEN estado = 'completado' THEN 1 END) as pagos_completados,
                    COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as pagos_pendientes,
                    COUNT(CASE WHEN estado = 'fallido' THEN 1 END) as pagos_fallidos,
                    SUM(CASE WHEN estado = 'completado' THEN monto ELSE 0 END) as ingresos_totales,
                    AVG(CASE WHEN estado = 'completado' THEN monto ELSE NULL END) as promedio_pago
                FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene pagos por mes
     */
    public function getPagosPorMes($anio, $mes) {
        $sql = "SELECT ps.*, sc.usuario_id, pc.nombre as programa_nombre
                FROM {$this->table} ps
                JOIN suscripciones_coaching sc ON ps.suscripcion_id = sc.id
                JOIN programas_coaching pc ON sc.programa_coaching_id = pc.id
                WHERE YEAR(ps.created_at) = ? AND MONTH(ps.created_at) = ?
                ORDER BY ps.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$anio, $mes]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene pagos recientes
     */
    public function getPagosRecientes($limite = 10) {
        $sql = "SELECT ps.*, sc.usuario_id, pc.nombre as programa_nombre,
                       u.nombre as usuario_nombre, u.apellido as usuario_apellido
                FROM {$this->table} ps
                JOIN suscripciones_coaching sc ON ps.suscripcion_id = sc.id
                JOIN programas_coaching pc ON sc.programa_coaching_id = pc.id
                JOIN usuarios u ON sc.usuario_id = u.id
                ORDER BY ps.created_at DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 