<?php
require_once 'BaseModel.php';

class AccesoBloqueModel extends BaseModel {
    protected $table = 'accesos_bloques';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Crea un acceso a un bloque
     */
    public function crearAcceso($data) {
        $sql = "INSERT INTO {$this->table} (usuario_id, bloque_id, suscripcion_id, desbloqueado, fecha_desbloqueo) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['usuario_id'],
            $data['bloque_id'],
            $data['suscripcion_id'],
            $data['desbloqueado'] ?? 0,
            $data['fecha_desbloqueo'] ?? null
        ]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Desbloquea un bloque para un usuario
     */
    public function desbloquearBloque($usuario_id, $bloque_id, $suscripcion_id) {
        $sql = "UPDATE {$this->table} SET desbloqueado = 1, fecha_desbloqueo = NOW() 
                WHERE usuario_id = ? AND bloque_id = ? AND suscripcion_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuario_id, $bloque_id, $suscripcion_id]);
    }
    
    /**
     * Marca un bloque como completado
     */
    public function completarBloque($usuario_id, $bloque_id, $suscripcion_id, $progreso = 100) {
        $sql = "UPDATE {$this->table} SET completado = 1, fecha_completado = NOW(), progreso = ? 
                WHERE usuario_id = ? AND bloque_id = ? AND suscripcion_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$progreso, $usuario_id, $bloque_id, $suscripcion_id]);
    }
    
    /**
     * Actualiza el progreso de un bloque
     */
    public function actualizarProgreso($usuario_id, $bloque_id, $suscripcion_id, $progreso) {
        $sql = "UPDATE {$this->table} SET progreso = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE usuario_id = ? AND bloque_id = ? AND suscripcion_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$progreso, $usuario_id, $bloque_id, $suscripcion_id]);
    }
    
    /**
     * Obtiene los bloques desbloqueados de un usuario
     */
    public function getBloquesDesbloqueados($usuario_id, $suscripcion_id) {
        $sql = "SELECT ab.*, cb.titulo, cb.descripcion, cb.contenido, cb.tipo_contenido, 
                       cb.url_contenido, cb.duracion_minutos, cb.mes
                FROM {$this->table} ab
                JOIN coaching_bloques cb ON ab.bloque_id = cb.id
                WHERE ab.usuario_id = ? AND ab.suscripcion_id = ? AND ab.desbloqueado = 1
                ORDER BY cb.mes, cb.orden";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $suscripcion_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene el progreso de un usuario en un programa
     */
    public function getProgresoUsuario($usuario_id, $suscripcion_id) {
        $sql = "SELECT 
                    COUNT(*) as total_bloques,
                    COUNT(CASE WHEN ab.desbloqueado = 1 THEN 1 END) as bloques_desbloqueados,
                    COUNT(CASE WHEN ab.completado = 1 THEN 1 END) as bloques_completados,
                    AVG(ab.progreso) as progreso_promedio
                FROM {$this->table} ab
                JOIN coaching_bloques cb ON ab.bloque_id = cb.id
                WHERE ab.usuario_id = ? AND ab.suscripcion_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $suscripcion_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verifica si un usuario tiene acceso a un bloque específico
     */
    public function usuarioTieneAcceso($usuario_id, $bloque_id, $suscripcion_id) {
        $sql = "SELECT desbloqueado, completado, progreso FROM {$this->table} 
                WHERE usuario_id = ? AND bloque_id = ? AND suscripcion_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $bloque_id, $suscripcion_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene el estado de todos los bloques de un programa para un usuario
     */
    public function getEstadoBloques($usuario_id, $programa_coaching_id, $suscripcion_id) {
        $sql = "SELECT cb.*, 
                       COALESCE(ab.desbloqueado, 0) as desbloqueado,
                       COALESCE(ab.completado, 0) as completado,
                       COALESCE(ab.progreso, 0) as progreso,
                       ab.fecha_desbloqueo,
                       ab.fecha_completado
                FROM coaching_bloques cb
                LEFT JOIN {$this->table} ab ON cb.id = ab.bloque_id 
                    AND ab.usuario_id = ? AND ab.suscripcion_id = ?
                WHERE cb.programa_coaching_id = ?
                ORDER BY cb.mes, cb.orden";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $suscripcion_id, $programa_coaching_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crea accesos para todos los bloques de un programa
     */
    public function crearAccesosParaPrograma($usuario_id, $programa_coaching_id, $suscripcion_id) {
        // Obtener todos los bloques del programa
        $sql = "SELECT id FROM coaching_bloques WHERE programa_coaching_id = ? ORDER BY mes, orden";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$programa_coaching_id]);
        $bloques = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Crear accesos para cada bloque
        foreach ($bloques as $bloque_id) {
            $this->crearAcceso([
                'usuario_id' => $usuario_id,
                'bloque_id' => $bloque_id,
                'suscripcion_id' => $suscripcion_id,
                'desbloqueado' => 0
            ]);
        }
        
        return count($bloques);
    }
    
    /**
     * Desbloquea todos los bloques de un mes específico
     */
    public function desbloquearBloquesDelMes($usuario_id, $programa_coaching_id, $suscripcion_id, $mes) {
        $sql = "UPDATE {$this->table} ab
                JOIN coaching_bloques cb ON ab.bloque_id = cb.id
                SET ab.desbloqueado = 1, ab.fecha_desbloqueo = NOW()
                WHERE ab.usuario_id = ? 
                AND cb.programa_coaching_id = ? 
                AND ab.suscripcion_id = ? 
                AND cb.mes = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuario_id, $programa_coaching_id, $suscripcion_id, $mes]);
    }
    
    /**
     * Obtiene estadísticas de accesos (para administradores)
     */
    public function getEstadisticasAccesos() {
        $sql = "SELECT 
                    COUNT(*) as total_accesos,
                    COUNT(CASE WHEN desbloqueado = 1 THEN 1 END) as bloques_desbloqueados,
                    COUNT(CASE WHEN completado = 1 THEN 1 END) as bloques_completados,
                    AVG(progreso) as progreso_promedio
                FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene todos los accesos de una suscripción
     */
    public function getAccesosPorSuscripcion($suscripcion_id) {
        $sql = "SELECT ab.*, cb.titulo, cb.descripcion, cb.contenido, cb.tipo_contenido, 
                       cb.url_contenido, cb.duracion_minutos, cb.mes, cb.orden
                FROM {$this->table} ab
                JOIN coaching_bloques cb ON ab.bloque_id = cb.id
                WHERE ab.suscripcion_id = ?
                ORDER BY cb.mes, cb.orden";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$suscripcion_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene un bloque con información de acceso para un usuario
     */
    public function getBloqueConAcceso($bloque_id, $usuario_id) {
        $sql = "SELECT cb.*, ab.desbloqueado, ab.completado, ab.progreso, 
                       ab.fecha_desbloqueo, ab.fecha_completado, ab.suscripcion_id
                FROM coaching_bloques cb
                LEFT JOIN {$this->table} ab ON cb.id = ab.bloque_id AND ab.usuario_id = ?
                WHERE cb.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $bloque_id]);
        $bloque = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($bloque) {
            $bloque['acceso'] = $bloque['desbloqueado'] == 1;
        }
        
        return $bloque;
    }
    
    /**
     * Marca un bloque como completado para un usuario
     */
    public function marcarCompletado($bloque_id, $usuario_id) {
        $sql = "UPDATE {$this->table} SET completado = 1, fecha_completado = NOW(), progreso = 100 
                WHERE bloque_id = ? AND usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$bloque_id, $usuario_id]);
    }
    
    /**
     * Crea un nuevo bloque de coaching
     */
    public function crearBloque($data) {
        $sql = "INSERT INTO coaching_bloques (programa_coaching_id, mes, titulo, descripcion, contenido, 
                tipo_contenido, url_contenido, duracion_minutos, orden) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['programa_coaching_id'],
            $data['mes'],
            $data['titulo'],
            $data['descripcion'],
            $data['contenido'],
            $data['tipo_contenido'],
            $data['url_contenido'],
            $data['duracion_minutos'],
            $data['orden']
        ]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Actualiza un bloque de coaching existente
     */
    public function actualizarBloque($id, $data) {
        $sql = "UPDATE coaching_bloques SET 
                programa_coaching_id = ?, mes = ?, titulo = ?, descripcion = ?, contenido = ?, 
                tipo_contenido = ?, url_contenido = ?, duracion_minutos = ?, orden = ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['programa_coaching_id'],
            $data['mes'],
            $data['titulo'],
            $data['descripcion'],
            $data['contenido'],
            $data['tipo_contenido'],
            $data['url_contenido'],
            $data['duracion_minutos'],
            $data['orden'],
            $id
        ]);
    }
    
    /**
     * Elimina un bloque de coaching
     */
    public function eliminarBloque($id) {
        // Primero eliminar los accesos asociados
        $sql = "DELETE FROM {$this->table} WHERE bloque_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        // Luego eliminar el bloque
        $sql = "DELETE FROM coaching_bloques WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Obtiene un bloque por ID
     */
    public function getBloqueById($id) {
        $sql = "SELECT * FROM coaching_bloques WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene todos los bloques de un programa
     */
    public function getBloquesPorPrograma($programa_coaching_id) {
        $sql = "SELECT * FROM coaching_bloques WHERE programa_coaching_id = ? ORDER BY mes, orden";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$programa_coaching_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 