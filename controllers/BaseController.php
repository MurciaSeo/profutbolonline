<?php
class BaseController {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    protected function render($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . "/../views/{$view}.php";
        if (!file_exists($viewPath)) {
            throw new Exception("La vista {$view} no existe en la ruta: {$viewPath}");
        }
        require_once $viewPath;
    }
    
    protected function redirect($url) {
        if (strpos($url, '/') !== 0) {
            $url = '/' . $url;
        }
        header("Location: {$url}");
        exit();
    }
    
    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }
    
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Debe iniciar sesión para acceder a esta sección.";
            $this->redirect('/login');
        }
    }
    
    protected function requireAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = "No tiene permisos de administrador para acceder a esta sección.";
            $this->redirect('/dashboard');
        }
    }
    
    protected function requireEntrenador() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Debe iniciar sesión para acceder a esta sección.";
            $this->redirect('/login');
        }
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'entrenador') {
            $_SESSION['error'] = "No tiene permisos de entrenador para acceder a esta sección. Su rol actual es: " . ($_SESSION['user_role'] ?? 'no definido');
            $this->redirect('/dashboard');
        }
    }
} 