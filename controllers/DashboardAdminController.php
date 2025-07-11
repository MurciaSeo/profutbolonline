<?php
require_once 'BaseController.php';
require_once 'models/PagoModel.php';
require_once 'models/ProgramaCoachingModel.php';
require_once 'models/SuscripcionCoachingModel.php';
require_once 'models/PagoSuscripcionModel.php';
require_once 'models/ProgramacionModel.php';
require_once 'models/UsuarioModel.php';

class DashboardAdminController extends BaseController {
    private $pagoModel;
    private $programaCoachingModel;
    private $suscripcionCoachingModel;
    private $pagoSuscripcionModel;
    private $programacionModel;
    private $usuarioModel;
    
    public function __construct() {
        parent::__construct();
        $this->pagoModel = new PagoModel();
        $this->programaCoachingModel = new ProgramaCoachingModel();
        $this->suscripcionCoachingModel = new SuscripcionCoachingModel();
        $this->pagoSuscripcionModel = new PagoSuscripcionModel();
        $this->programacionModel = new ProgramacionModel();
        $this->usuarioModel = new UsuarioModel();
    }
    
    /**
     * Dashboard principal de administración con estadísticas unificadas
     */
    public function index() {
        $this->requireAuth();
        $this->requireAdmin();
        
        // Estadísticas de programas de entrenamiento
        $estadisticasEntrenamiento = $this->pagoModel->getEstadisticasVentas();
        
        // Estadísticas de coaching
        $estadisticasCoaching = $this->suscripcionCoachingModel->getEstadisticas();
        $estadisticasPagosCoaching = $this->pagoSuscripcionModel->getEstadisticasPagos();
        
        // Programas más populares
        $programasEntrenamiento = $this->programacionModel->getAllProgramaciones();
        $programasCoaching = $this->programaCoachingModel->getProgramasConEstadisticas();
        
        // Usuarios activos
        $usuariosActivos = $this->usuarioModel->getUsuariosActivos();
        
        // Resumen general
        $resumenGeneral = [
            'total_ingresos' => ($estadisticasEntrenamiento['total_recaudado'] ?? 0) + ($estadisticasPagosCoaching['ingresos_totales'] ?? 0),
            'total_ventas' => ($estadisticasEntrenamiento['total_ventas'] ?? 0) + ($estadisticasCoaching['total_suscripciones'] ?? 0),
            'usuarios_activos' => count($usuariosActivos),
            'programas_activos' => count($programasEntrenamiento) + count($programasCoaching)
        ];
        
        // Datos para gráficos
        $datosGraficos = $this->obtenerDatosGraficos();
        
        $this->render('admin/dashboard', [
            'estadisticasEntrenamiento' => $estadisticasEntrenamiento,
            'estadisticasCoaching' => $estadisticasCoaching,
            'estadisticasPagosCoaching' => $estadisticasPagosCoaching,
            'programasEntrenamiento' => $programasEntrenamiento,
            'programasCoaching' => $programasCoaching,
            'usuariosActivos' => $usuariosActivos,
            'resumenGeneral' => $resumenGeneral,
            'datosGraficos' => $datosGraficos
        ]);
    }
    
    /**
     * Obtiene datos para los gráficos
     */
    private function obtenerDatosGraficos() {
        // Ventas por mes (últimos 6 meses)
        $ventasPorMes = [];
        $suscripcionesPorMes = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $mes = date('Y-m', strtotime("-$i months"));
            $ventasPorMes[] = [
                'mes' => $mes,
                'ventas' => $this->pagoModel->getVentasPorMes($mes),
                'suscripciones' => $this->suscripcionCoachingModel->getSuscripcionesPorMes($mes)
            ];
        }
        
        // Top programas
        $topProgramasEntrenamiento = $this->pagoModel->getTopProgramas(5);
        $topProgramasCoaching = $this->programaCoachingModel->getTopProgramas(5);
        
        return [
            'ventasPorMes' => $ventasPorMes,
            'topProgramasEntrenamiento' => $topProgramasEntrenamiento,
            'topProgramasCoaching' => $topProgramasCoaching
        ];
    }
    
    /**
     * Requiere permisos de administrador
     */
    protected function requireAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = "Acceso denegado. Se requieren permisos de administrador.";
            $this->redirect('/dashboard');
        }
    }
} 