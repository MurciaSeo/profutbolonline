<?php

require_once 'models/ConfiguracionModel.php';

class ConfiguracionController {
    private $configModel;
    
    public function __construct() {
        $this->configModel = new ConfiguracionModel();
    }
    
    public function index() {
        $this->requireAdmin();
        
        // Obtener la configuración actual
        $configuracion = $this->configModel->getAllConfiguracion();
        
        // Renderizar la vista
        require_once 'views/admin/configuracion/index.php';
    }
    
    public function guardar() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Actualizar configuración general
                $this->configModel->actualizarConfiguracion('site_name', $_POST['site_name']);
                $this->configModel->actualizarConfiguracion('site_description', $_POST['site_description']);
                $this->configModel->actualizarConfiguracion('contact_email', $_POST['contact_email']);
                $this->configModel->actualizarConfiguracion('items_per_page', $_POST['items_per_page']);
                
                $_SESSION['success'] = 'Configuración actualizada correctamente';
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error al actualizar la configuración: ' . $e->getMessage();
            }
            
            header('Location: /admin/configuracion');
            exit;
        }
    }
    
    public function guardarSeguridad() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Actualizar configuración de seguridad
                $this->configModel->actualizarConfiguracion('max_login_attempts', $_POST['max_login_attempts']);
                $this->configModel->actualizarConfiguracion('password_min_length', $_POST['password_min_length']);
                $this->configModel->actualizarConfiguracion('require_special_chars', $_POST['require_special_chars'] ?? '0');
                $this->configModel->actualizarConfiguracion('session_timeout', $_POST['session_timeout']);
                
                $_SESSION['success'] = 'Configuración de seguridad actualizada correctamente';
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error al actualizar la configuración de seguridad: ' . $e->getMessage();
            }
            
            header('Location: /admin/configuracion');
            exit;
        }
    }
    
    public function backup() {
        $this->requireAdmin();
        
        try {
            $backupDir = __DIR__ . '/../backups/';
            if (!file_exists($backupDir)) {
                if (!mkdir($backupDir, 0777, true)) {
                    throw new Exception('No se pudo crear el directorio de backups');
                }
            }
            
            $filename = $backupDir . 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            
            // Obtener la configuración de la base de datos
            require_once __DIR__ . '/../config/database.php';
            $db = Database::getInstance();
            $connection = $db->getConnection();
            $dbname = $connection->query('SELECT DATABASE()')->fetchColumn();
            
            // Construir el comando mysqldump
            $command = sprintf(
                'mysqldump -h%s -u%s -p%s %s > %s',
                'localhost',
                'root',
                '',
                $dbname,
                $filename
            );
            
            // Ejecutar el comando
            exec($command, $output, $return);
            
            if ($return === 0) {
                $_SESSION['success'] = 'Backup creado correctamente en: ' . basename($filename);
            } else {
                throw new Exception('Error al ejecutar mysqldump. Código de retorno: ' . $return);
            }
        } catch (Exception $e) {
            error_log('Error en backup: ' . $e->getMessage());
            $_SESSION['error'] = 'Error al crear el backup: ' . $e->getMessage();
        }
        
        header('Location: /admin/configuracion');
        exit;
    }
    
    public function limpiarCache() {
        $this->requireAdmin();
        
        try {
            $cacheDir = 'cache/';
            if (file_exists($cacheDir)) {
                $files = glob($cacheDir . '*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                $_SESSION['success'] = 'Cache limpiado correctamente';
            } else {
                $_SESSION['info'] = 'No hay archivos de cache para limpiar';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al limpiar el cache: ' . $e->getMessage();
        }
        
        header('Location: /admin/configuracion');
        exit;
    }
    
    private function requireAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = 'Acceso denegado. Se requieren privilegios de administrador.';
            header('Location: /dashboard');
            exit;
        }
    }
} 