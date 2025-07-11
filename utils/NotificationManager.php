<?php

class NotificationManager {
    
    /**
     * Añade una notificación de éxito
     */
    public static function success($message, $title = '¡Éxito!') {
        self::addNotification('success', $message, $title);
    }
    
    /**
     * Añade una notificación de error
     */
    public static function error($message, $title = 'Error') {
        self::addNotification('error', $message, $title);
    }
    
    /**
     * Añade una notificación de advertencia
     */
    public static function warning($message, $title = 'Advertencia') {
        self::addNotification('warning', $message, $title);
    }
    
    /**
     * Añade una notificación de información
     */
    public static function info($message, $title = 'Información') {
        self::addNotification('info', $message, $title);
    }
    
    /**
     * Añade una notificación personalizada
     */
    private static function addNotification($type, $message, $title) {
        if (!isset($_SESSION['notifications'])) {
            $_SESSION['notifications'] = [];
        }
        
        $_SESSION['notifications'][] = [
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'timestamp' => time()
        ];
    }
    
    /**
     * Obtiene todas las notificaciones y las elimina de la sesión
     */
    public static function getNotifications() {
        $notifications = $_SESSION['notifications'] ?? [];
        unset($_SESSION['notifications']);
        return $notifications;
    }
    
    /**
     * Verifica si hay notificaciones pendientes
     */
    public static function hasNotifications() {
        return !empty($_SESSION['notifications']);
    }
    
    /**
     * Renderiza las notificaciones en HTML
     */
    public static function renderNotifications() {
        $notifications = self::getNotifications();
        
        if (empty($notifications)) {
            return '';
        }
        
        $html = '<div class="notification-container">';
        
        foreach ($notifications as $notification) {
            $typeClass = self::getBootstrapClass($notification['type']);
            $icon = self::getIcon($notification['type']);
            
            $html .= sprintf(
                '<div class="alert alert-%s alert-dismissible fade show notification-item" role="alert">
                    <i class="%s me-2"></i>
                    <strong>%s</strong> %s
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>',
                $typeClass,
                $icon,
                htmlspecialchars($notification['title']),
                htmlspecialchars($notification['message'])
            );
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Renderiza notificaciones con SweetAlert2
     */
    public static function renderSweetAlertNotifications() {
        $notifications = self::getNotifications();
        
        if (empty($notifications)) {
            return '';
        }
        
        $html = '<script>';
        
        foreach ($notifications as $notification) {
            $type = $notification['type'];
            $title = addslashes($notification['title']);
            $message = addslashes($notification['message']);
            
            $html .= sprintf(
                "Swal.fire({
                    icon: '%s',
                    title: '%s',
                    text: '%s',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });",
                $type === 'error' ? 'error' : ($type === 'warning' ? 'warning' : ($type === 'info' ? 'info' : 'success')),
                $title,
                $message
            );
        }
        
        $html .= '</script>';
        
        return $html;
    }
    
    /**
     * Obtiene la clase de Bootstrap para el tipo de notificación
     */
    private static function getBootstrapClass($type) {
        switch ($type) {
            case 'success':
                return 'success';
            case 'error':
                return 'danger';
            case 'warning':
                return 'warning';
            case 'info':
                return 'info';
            default:
                return 'primary';
        }
    }
    
    /**
     * Obtiene el icono para el tipo de notificación
     */
    private static function getIcon($type) {
        switch ($type) {
            case 'success':
                return 'fas fa-check-circle';
            case 'error':
                return 'fas fa-exclamation-circle';
            case 'warning':
                return 'fas fa-exclamation-triangle';
            case 'info':
                return 'fas fa-info-circle';
            default:
                return 'fas fa-bell';
        }
    }
    
    /**
     * Convierte notificaciones legacy a formato nuevo
     */
    public static function convertLegacyNotifications() {
        // Convertir notificaciones de éxito legacy
        if (isset($_SESSION['success'])) {
            self::success($_SESSION['success']);
            unset($_SESSION['success']);
        }
        
        // Convertir notificaciones de error legacy
        if (isset($_SESSION['error'])) {
            self::error($_SESSION['error']);
            unset($_SESSION['error']);
        }
        
        // Convertir notificaciones de info legacy
        if (isset($_SESSION['info'])) {
            self::info($_SESSION['info']);
            unset($_SESSION['info']);
        }
        
        // Convertir notificaciones de warning legacy
        if (isset($_SESSION['warning'])) {
            self::warning($_SESSION['warning']);
            unset($_SESSION['warning']);
        }
    }
    
    /**
     * Métodos de conveniencia para diferentes contextos
     */
    
    public static function userCreated($userName) {
        self::success("Usuario '$userName' creado correctamente.", '¡Usuario Creado!');
    }
    
    public static function userUpdated($userName) {
        self::success("Usuario '$userName' actualizado correctamente.", '¡Usuario Actualizado!');
    }
    
    public static function userDeleted($userName) {
        self::success("Usuario '$userName' eliminado correctamente.", '¡Usuario Eliminado!');
    }
    
    public static function entrenamientoCreated($entrenamientoName) {
        self::success("Entrenamiento '$entrenamientoName' creado correctamente.", '¡Entrenamiento Creado!');
    }
    
    public static function entrenamientoCompleted($entrenamientoName) {
        self::success("¡Felicidades! Has completado el entrenamiento '$entrenamientoName'.", '¡Entrenamiento Completado!');
    }
    
    public static function entrenamientoAssigned($entrenamientoName, $userName) {
        self::success("Entrenamiento '$entrenamientoName' asignado a '$userName'.", '¡Entrenamiento Asignado!');
    }
    
    public static function valoracionSaved() {
        self::success("¡Gracias por tu valoración! Tu feedback nos ayuda a mejorar.", '¡Valoración Guardada!');
    }
    
    public static function accessDenied() {
        self::error("No tienes permisos para realizar esta acción.", 'Acceso Denegado');
    }
    
    public static function notFound($resource) {
        self::error("$resource no encontrado.", 'No Encontrado');
    }
    
    public static function invalidData() {
        self::error("Los datos proporcionados no son válidos. Por favor, revisa la información.", 'Datos Inválidos');
    }
    
    public static function systemError() {
        self::error("Ha ocurrido un error interno. Por favor, inténtalo más tarde.", 'Error del Sistema');
    }
    
    public static function maintenanceMode() {
        self::warning("El sistema está en mantenimiento. Algunas funciones pueden no estar disponibles.", 'Mantenimiento');
    }
    
    public static function sessionExpired() {
        self::warning("Tu sesión ha expirado. Por favor, inicia sesión nuevamente.", 'Sesión Expirada');
    }
}