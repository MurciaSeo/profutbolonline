<?php
require_once 'BaseController.php';
require_once 'models/EntrenamientoModel.php';
require_once 'models/UsuarioModel.php';
require_once 'models/EjercicioModel.php';
require_once 'models/ProgramacionUsuarioModel.php';
require_once 'models/ProgramacionModel.php';
require_once 'models/SesionModel.php';

class DashboardController extends BaseController {
    private $entrenamientoModel;
    private $usuarioModel;
    private $ejercicioModel;
    private $programacionUsuarioModel;
    private $programacionModel;
    private $sesionModel;
    
    public function __construct() {
        parent::__construct();
        $this->entrenamientoModel = new EntrenamientoModel();
        $this->usuarioModel = new UsuarioModel();
        $this->ejercicioModel = new EjercicioModel();
        $this->programacionUsuarioModel = new ProgramacionUsuarioModel();
        $this->programacionModel = new ProgramacionModel();
        $this->sesionModel = new SesionModel();
    }
    
    public function index() {
        // Verificar autenticación
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['user_id'];
        $rol = $_SESSION['user_role'];

        // Datos comunes para todos los roles
        $data = [
            'rol' => $rol,
            'usuario' => $this->usuarioModel->findById($usuario_id)
        ];

        if ($rol === 'admin') {
            // Métricas para administradores
            $data['total_usuarios'] = $this->usuarioModel->getTotalUsuarios();
            $data['usuarios_nuevos_mes'] = $this->usuarioModel->getUsuariosNuevosMes();
            $data['entrenamientos_completados'] = $this->entrenamientoModel->getTotalEntrenamientosCompletados();
            $data['tasa_completitud'] = $this->entrenamientoModel->getTasaCompletitud();
            $data['usuarios_activos_mes'] = $this->usuarioModel->getUsuariosActivosMes();

            // Datos para gráficos
            $data['entrenamientos_por_mes'] = $this->entrenamientoModel->getEntrenamientosPorMes();
            $data['distribucion_usuarios'] = $this->usuarioModel->getDistribucionUsuarios();

            // Tablas de datos
            $data['ultimos_usuarios'] = $this->usuarioModel->getUltimosUsuarios(5);
            $data['entrenamientos_recientes'] = $this->entrenamientoModel->getEntrenamientosRecientes(5);
        } 
        elseif ($rol === 'entrenador') {
            // Métricas para entrenadores
            $data['total_entrenados'] = $this->usuarioModel->getTotalEntrenados($usuario_id);
            $data['entrenamientos_completados'] = $this->entrenamientoModel->getTotalEntrenamientosCompletadosPorEntrenador($usuario_id);
            $data['programas_activos'] = $this->programacionUsuarioModel->getTotalProgramasActivosPorEntrenador($usuario_id);
            $data['valoracion_promedio'] = $this->entrenamientoModel->getValoracionPromedioPorEntrenador($usuario_id);

            // Lista de entrenados
            $data['entrenados'] = $this->usuarioModel->getEntrenadosPorEntrenador($usuario_id);
            foreach ($data['entrenados'] as &$entrenado) {
                $entrenado['completados'] = $this->entrenamientoModel->getTotalEntrenamientosCompletadosPorUsuario($entrenado['id']);
                $entrenado['total'] = $this->entrenamientoModel->getTotalEntrenamientosPorUsuario($entrenado['id']);
                $entrenado['ultima_actividad'] = $this->usuarioModel->getUltimaActividad($entrenado['id']);
            }

            // Entrenamientos completados por entrenados
            $data['entrenamientos_completados'] = $this->entrenamientoModel->getEntrenamientosCompletadosPorEntrenador($usuario_id);
        } 
        else {
            // Métricas para entrenados
            $data['total_entrenamientos'] = $this->entrenamientoModel->getTotalEntrenamientosPorUsuario($usuario_id);
            $data['entrenamientos_completados'] = $this->entrenamientoModel->getTotalEntrenamientosCompletadosPorUsuario($usuario_id);
            $data['programas_activos'] = $this->programacionUsuarioModel->getTotalProgramasActivosPorUsuario($usuario_id);
            $data['progreso_general'] = $this->entrenamientoModel->getProgresoGeneral($usuario_id);

            // Próximos entrenamientos
            $data['proximos_entrenamientos'] = $this->entrenamientoModel->getProximosEntrenamientos($usuario_id);

            // Programas activos
            $data['programas_activos'] = $this->programacionUsuarioModel->getProgramasActivosPorUsuario($usuario_id);
        }

        // Renderizar vista
        $this->render('dashboard/index', $data);
    }
} 