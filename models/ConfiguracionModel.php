<?php

require_once 'BaseModel.php';

class ConfiguracionModel extends BaseModel {
    public function __construct() {
        parent::__construct('configuracion');
    }
    
    public function getConfiguracion($clave) {
        $sql = "SELECT valor FROM configuracion WHERE clave = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$clave]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $resultado ? $resultado['valor'] : null;
    }
    
    public function actualizarConfiguracion($clave, $valor) {
        $sql = "INSERT INTO configuracion (clave, valor) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE valor = ?";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$clave, $valor, $valor]);
    }
    
    public function getAllConfiguracion() {
        $sql = "SELECT clave, valor FROM configuracion";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        $config = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $config[$row['clave']] = $row['valor'];
        }
        
        return $config;
    }
    
    public function getConfiguracionSeguridad() {
        $claves = [
            'intentos_login',
            'tiempo_bloqueo',
            'longitud_minima_password',
            'requerir_mayuscula',
            'requerir_numero',
            'requerir_caracter_especial'
        ];
        
        $sql = "SELECT clave, valor FROM configuracion WHERE clave IN (" . str_repeat('?,', count($claves) - 1) . "?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($claves);
        
        $config = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $config[$row['clave']] = $row['valor'];
        }
        
        // Establecer valores por defecto si no existen
        $defaults = [
            'intentos_login' => 3,
            'tiempo_bloqueo' => 30,
            'longitud_minima_password' => 8,
            'requerir_mayuscula' => 1,
            'requerir_numero' => 1,
            'requerir_caracter_especial' => 0
        ];
        
        foreach ($defaults as $clave => $valor) {
            if (!isset($config[$clave])) {
                $config[$clave] = $valor;
                $this->actualizarConfiguracion($clave, $valor);
            }
        }
        
        return $config;
    }
    
    public function getConfiguracionGeneral() {
        $claves = [
            'site_name',
            'site_description',
            'contact_email',
            'items_per_page',
            'max_entrenamientos',
            'max_ejercicios',
            'dias_vencimiento'
        ];
        
        $sql = "SELECT clave, valor FROM configuracion WHERE clave IN (" . str_repeat('?,', count($claves) - 1) . "?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($claves);
        
        $config = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $config[$row['clave']] = $row['valor'];
        }
        
        // Establecer valores por defecto si no existen
        $defaults = [
            'site_name' => 'Sistema de Entrenamiento',
            'site_description' => 'Plataforma de gestión de entrenamientos funcionales',
            'contact_email' => 'admin@example.com',
            'items_per_page' => 10,
            'max_entrenamientos' => 10,
            'max_ejercicios' => 20,
            'dias_vencimiento' => 30
        ];
        
        foreach ($defaults as $clave => $valor) {
            if (!isset($config[$clave])) {
                $config[$clave] = $valor;
                $this->actualizarConfiguracion($clave, $valor);
            }
        }
        
        return $config;
    }
} 