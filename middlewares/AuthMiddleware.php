<?php

class AuthMiddleware {
    
    /**
     * Verifica si el usuario está autenticado
     */
    public static function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Debe iniciar sesión para acceder a esta sección.";
            header("Location: /login");
            exit();
        }
    }
    
    /**
     * Verifica si el usuario tiene uno de los roles requeridos
     */
    public static function requireRole($roles) {
        self::requireAuth();
        
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        $userRole = $_SESSION['user_role'] ?? null;
        
        if (!in_array($userRole, $roles)) {
            $_SESSION['error'] = "No tiene permisos para acceder a esta sección.";
            header("Location: /dashboard");
            exit();
        }
    }
    
    /**
     * Verifica si el usuario es administrador
     */
    public static function requireAdmin() {
        self::requireRole(['admin']);
    }
    
    /**
     * Verifica si el usuario es entrenador o administrador
     */
    public static function requireEntrenador() {
        self::requireRole(['entrenador', 'admin']);
    }
    
    /**
     * Verifica si el usuario puede acceder a un recurso específico
     */
    public static function requireOwnership($resource, $userId, $field = 'user_id') {
        self::requireAuth();
        
        $currentUserId = $_SESSION['user_id'];
        $userRole = $_SESSION['user_role'];
        
        // Los administradores pueden acceder a todo
        if ($userRole === 'admin') {
            return true;
        }
        
        // Los entrenadores pueden acceder a recursos de sus entrenados
        if ($userRole === 'entrenador') {
            if (self::isTrainerOfUser($currentUserId, $userId)) {
                return true;
            }
        }
        
        // El usuario solo puede acceder a sus propios recursos
        if ($currentUserId != $userId) {
            $_SESSION['error'] = "No tiene permisos para acceder a este recurso.";
            header("Location: /dashboard");
            exit();
        }
        
        return true;
    }
    
    /**
     * Verifica si un entrenador está asignado a un usuario
     */
    private static function isTrainerOfUser($trainerId, $userId) {
        require_once 'models/UsuarioModel.php';
        $usuarioModel = new UsuarioModel();
        
        $usuario = $usuarioModel->findById($userId);
        return $usuario && $usuario['entrenador_id'] == $trainerId;
    }
    
    /**
     * Obtiene el rol del usuario actual
     */
    public static function getCurrentUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
    
    /**
     * Obtiene el ID del usuario actual
     */
    public static function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Verifica si el usuario actual es administrador
     */
    public static function isAdmin() {
        return self::getCurrentUserRole() === 'admin';
    }
    
    /**
     * Verifica si el usuario actual es entrenador
     */
    public static function isEntrenador() {
        return self::getCurrentUserRole() === 'entrenador';
    }
    
    /**
     * Verifica si el usuario actual es entrenado
     */
    public static function isEntrenado() {
        return self::getCurrentUserRole() === 'entrenado';
    }
    
    /**
     * Redirect con mensaje de error
     */
    public static function redirectWithError($message, $path = '/dashboard') {
        $_SESSION['error'] = $message;
        header("Location: $path");
        exit();
    }
    
    /**
     * Redirect con mensaje de éxito
     */
    public static function redirectWithSuccess($message, $path = '/dashboard') {
        $_SESSION['success'] = $message;
        header("Location: $path");
        exit();
    }
}