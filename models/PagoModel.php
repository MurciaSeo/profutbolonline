<?php
require_once 'BaseModel.php';

class PagoModel extends BaseModel {
    protected $table = 'pagos';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Crea un nuevo registro de pago
     */
    public function crearPago($data) {
        $sql = "INSERT INTO pagos (usuario_id, programacion_id, stripe_payment_intent_id, monto, moneda, estado) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['usuario_id'],
            $data['programacion_id'],
            $data['stripe_payment_intent_id'],
            $data['monto'],
            $data['moneda'],
            $data['estado']
        ]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Actualiza el estado de un pago
     */
    public function actualizarEstado($payment_intent_id, $estado, $fecha_pago = null) {
        $sql = "UPDATE pagos SET estado = ?, fecha_pago = ? WHERE stripe_payment_intent_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$estado, $fecha_pago, $payment_intent_id]);
    }
    
    /**
     * Obtiene un pago por su ID de Stripe
     */
    public function getPagoPorStripeId($stripe_payment_intent_id) {
        $sql = "SELECT p.*, pr.nombre as programa_nombre, u.nombre as usuario_nombre, u.apellido as usuario_apellido
                FROM pagos p
                JOIN programaciones pr ON p.programacion_id = pr.id
                JOIN usuarios u ON p.usuario_id = u.id
                WHERE p.stripe_payment_intent_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$stripe_payment_intent_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene todas las compras de un usuario (programas de entrenamiento)
     */
    public function getComprasPorUsuario($usuario_id) {
        $sql = "SELECT p.*, pr.nombre as programa_nombre, pr.descripcion as programa_descripcion,
                       pr.duracion_semanas, pr.entrenamientos_por_semana, pr.nivel, pr.objetivo,
                       pp.precio, pp.moneda
                FROM pagos p
                JOIN programaciones pr ON p.programacion_id = pr.id
                LEFT JOIN programas_precios pp ON pr.id = pp.programacion_id
                WHERE p.usuario_id = ? AND p.estado = 'completado'
                ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene todos los pagos de un usuario
     */
    public function getPagosPorUsuario($usuario_id) {
        $sql = "SELECT p.*, pr.nombre as programa_nombre, pr.descripcion as programa_descripcion
                FROM pagos p
                JOIN programaciones pr ON p.programacion_id = pr.id
                WHERE p.usuario_id = ?
                ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene todos los pagos (para administradores)
     */
    public function getAllPagos() {
        $sql = "SELECT p.*, pr.nombre as programa_nombre, pr.descripcion as programa_descripcion,
                       u.nombre as usuario_nombre, u.apellido as usuario_apellido
                FROM pagos p
                JOIN programaciones pr ON p.programacion_id = pr.id
                JOIN usuarios u ON p.usuario_id = u.id
                ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verifica si un usuario ya pagó por un programa específico
     */
    public function usuarioYaPago($usuario_id, $programacion_id) {
        $sql = "SELECT COUNT(*) as total FROM pagos 
                WHERE usuario_id = ? AND programacion_id = ? AND estado = 'completado'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $programacion_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
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
                    SUM(CASE WHEN estado = 'completado' THEN monto ELSE 0 END) as total_recaudado
                FROM pagos";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene estadísticas de ventas (para administradores)
     */
    public function getEstadisticasVentas() {
        $sql = "SELECT 
                    COUNT(*) as total_ventas,
                    COUNT(CASE WHEN estado = 'completado' THEN 1 END) as ventas_completadas,
                    SUM(CASE WHEN estado = 'completado' THEN monto ELSE 0 END) as total_recaudado,
                    AVG(CASE WHEN estado = 'completado' THEN monto ELSE NULL END) as ticket_promedio,
                    COUNT(DISTINCT usuario_id) as clientes_unicos,
                    COUNT(DISTINCT programacion_id) as programas_vendidos
                FROM pagos";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene ventas por mes
     */
    public function getVentasPorMes($mes) {
        $sql = "SELECT COUNT(*) as ventas, SUM(monto) as ingresos
                FROM pagos 
                WHERE estado = 'completado' 
                AND DATE_FORMAT(created_at, '%Y-%m') = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$mes]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['ventas'] ?? 0;
    }
    
    /**
     * Obtiene top programas por ventas
     */
    public function getTopProgramas($limite = 5) {
        $sql = "SELECT pr.nombre, pr.descripcion,
                       COUNT(p.id) as ventas,
                       SUM(p.monto) as ingresos
                FROM pagos p
                JOIN programaciones pr ON p.programacion_id = pr.id
                WHERE p.estado = 'completado'
                GROUP BY pr.id
                ORDER BY ventas DESC, ingresos DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 