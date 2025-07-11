<?php
require_once 'BaseModel.php';

class UsuarioModel extends BaseModel {
    protected $table = 'usuarios';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function findByEmail($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function authenticate($email, $password) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                error_log("Hash almacenado en BD: " . $user['password']);
                error_log("Hash generado para password: " . password_hash($password, PASSWORD_DEFAULT));
                error_log("Resultado de password_verify: " . (password_verify($password, $user['password']) ? "true" : "false"));
                
                if (password_verify($password, $user['password'])) {
                    error_log("Autenticación exitosa para usuario: " . $user['id']);
                    return $user;
                }
            }
            
            error_log("Autenticación fallida - Verificación de contraseña incorrecta para email: " . $email);
            return false;
        } catch (PDOException $e) {
            error_log("Error en autenticación: " . $e->getMessage());
            return false;
        }
    }
    
    public function getEntrenados($entrenador_id) {
        $query = "SELECT * FROM {$this->table} WHERE entrenador_id = ? AND rol = 'entrenado'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$entrenador_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByRol($rol) {
        $query = "SELECT * FROM {$this->table} WHERE rol = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$rol]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsuariosPorRol($rol) {
        return $this->findByRol($rol);
    }

    public function asignarEntrenador($entrenado_id, $entrenador_id) {
        // Verificar que el entrenado existe y tiene rol de entrenado
        $entrenado = $this->findById($entrenado_id);
        if (!$entrenado || $entrenado['rol'] !== 'entrenado') {
            return false;
        }

        // Verificar que el entrenador existe y tiene rol de entrenador
        $entrenador = $this->findById($entrenador_id);
        if (!$entrenador || $entrenador['rol'] !== 'entrenador') {
            return false;
        }

        // Verificar que no se está asignando a sí mismo
        if ($entrenado_id === $entrenador_id) {
            return false;
        }

        $sql = "UPDATE usuarios SET entrenador_id = ? WHERE id = ? AND rol = 'entrenado'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$entrenador_id, $entrenado_id]);
    }

    public function actualizarRol($usuario_id, $nuevo_rol) {
        $query = "UPDATE {$this->table} SET rol = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nuevo_rol, $usuario_id]);
    }

    public function getTotalUsuarios() {
        try {
            $sql = "SELECT COUNT(*) as total FROM usuarios";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error en getTotalUsuarios: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalUsuariosPorRol($rol) {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE rol = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$rol]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getUsuariosRecientes($limite = 5) {
        $sql = "SELECT id, nombre, email, rol, created_at 
                FROM usuarios 
                ORDER BY created_at DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO usuarios (nombre, apellido, email, telefono, password, rol, entrenador_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $entrenador_id = $data['entrenador_id'] ?? null;
        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['email'],
            $data['telefono'],
            $data['password'],
            $data['rol'],
            $entrenador_id
        ]);
    }

    public function update($id, $data) {
        // Verificar si se está actualizando la contraseña
        if (isset($data['password'])) {
            $sql = "UPDATE usuarios 
                    SET nombre = ?, 
                        apellido = ?, 
                        email = ?, 
                        telefono = ?, 
                        password = ?,
                        rol = ?, 
                        entrenador_id = ? 
                    WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            $entrenador_id = $data['entrenador_id'] ?? null;
            return $stmt->execute([
                $data['nombre'],
                $data['apellido'],
                $data['email'],
                $data['telefono'],
                $data['password'],
                $data['rol'],
                $entrenador_id,
                $id
            ]);
        } else {
            // Si no se está actualizando la contraseña, no la incluimos en la consulta
            $sql = "UPDATE usuarios 
                    SET nombre = ?, 
                        apellido = ?, 
                        email = ?, 
                        telefono = ?, 
                        rol = ?, 
                        entrenador_id = ? 
                    WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            $entrenador_id = $data['entrenador_id'] ?? null;
            return $stmt->execute([
                $data['nombre'],
                $data['apellido'],
                $data['email'],
                $data['telefono'],
                $data['rol'],
                $entrenador_id,
                $id
            ]);
        }
    }

    public function findAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY nombre, apellido";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsuarios() {
        $sql = "SELECT u.*, 
                (SELECT COUNT(*) FROM programacion_dias_usuarios pdu WHERE pdu.usuario_id = u.id AND pdu.completado = 1) as entrenamientos_completados,
                (SELECT COUNT(*) FROM valoraciones_entrenamientos ve WHERE ve.usuario_id = u.id) as entrenamientos_valorados,
                (SELECT AVG((ve.calidad + ve.esfuerzo + ve.complejidad + ve.duracion) / 4) 
                 FROM valoraciones_entrenamientos ve 
                 WHERE ve.usuario_id = u.id) as promedio_valoracion
                FROM usuarios u
                WHERE u.rol = 'entrenado'
                ORDER BY u.nombre, u.apellido";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsuariosActivosUltimoMes() {
        $sql = "SELECT COUNT(DISTINCT u.id) as total 
                FROM usuarios u 
                INNER JOIN sesiones s ON u.id = s.usuario_id 
                WHERE s.fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getEntrenadosPorEntrenador($entrenador_id) {
        try {
            $sql = "SELECT * FROM usuarios 
                    WHERE entrenador_id = ? AND rol = 'entrenado'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$entrenador_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener entrenados: " . $e->getMessage());
            return [];
        }
    }

    public function getUltimaActividad($usuario_id) {
        try {
            $sql = "SELECT MAX(fecha) as ultima_actividad FROM (
                        SELECT fecha_completado as fecha FROM sesiones WHERE usuario_id = ?
                        UNION
                        SELECT fecha_completado as fecha FROM dias_programacion WHERE usuario_id = ?
                    ) as actividades";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id, $usuario_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['ultima_actividad'] ?? date('Y-m-d H:i:s');
        } catch (PDOException $e) {
            error_log("Error al obtener última actividad: " . $e->getMessage());
            return date('Y-m-d H:i:s');
        }
    }

    public function getUsuariosNuevosMes() {
        try {
            $sql = "SELECT COUNT(*) as total FROM usuarios 
                   WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error en getUsuariosNuevosMes: " . $e->getMessage());
            return 0;
        }
    }

    public function getUsuariosActivosMes() {
        try {
            $sql = "SELECT COUNT(DISTINCT u.id) as total 
                   FROM usuarios u 
                   INNER JOIN sesiones s ON u.id = s.usuario_id 
                   WHERE s.fecha >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error en getUsuariosActivosMes: " . $e->getMessage());
            return 0;
        }
    }

    public function getDistribucionUsuarios() {
        try {
            $sql = "SELECT rol, COUNT(*) as total 
                   FROM usuarios 
                   GROUP BY rol";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getDistribucionUsuarios: " . $e->getMessage());
            return [];
        }
    }

    public function getUltimosUsuarios($limite = 5) {
        try {
            $sql = "SELECT id, nombre, apellido, email, rol, created_at 
                   FROM usuarios 
                   ORDER BY created_at DESC 
                   LIMIT :limite";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getUltimosUsuarios: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalEntrenados($entrenador_id) {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE entrenador_id = ? AND rol = 'entrenado'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$entrenador_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    /**
     * Obtiene usuarios activos (que han tenido actividad reciente)
     */
    public function getUsuariosActivos() {
        $sql = "SELECT u.*, 
                       (SELECT COUNT(*) FROM programacion_dias_usuarios pdu 
                        WHERE pdu.usuario_id = u.id AND pdu.completado = 1 
                        AND pdu.fecha_completado >= DATE_SUB(NOW(), INTERVAL 30 DAY)) as actividad_reciente
                FROM usuarios u
                WHERE u.rol = 'entrenado'
                HAVING actividad_reciente > 0
                ORDER BY actividad_reciente DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 