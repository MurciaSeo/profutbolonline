<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/ProgramacionUsuarioModel.php';
require_once __DIR__ . '/../models/ProgramacionModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

class ProgramacionUsuarioController extends BaseController {
    private $programacionUsuarioModel;
    private $programacionModel;
    private $usuarioModel;

    public function __construct() {
        parent::__construct();
        $this->programacionUsuarioModel = new ProgramacionUsuarioModel();
        $this->programacionModel = new ProgramacionModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function index() {
        $this->requireAdmin();
        
        $asignaciones = $this->programacionUsuarioModel->getAllAsignaciones();
        $programaciones = $this->programacionModel->getAllProgramaciones();
        $usuarios = $this->usuarioModel->getAllUsuarios();
        
        $this->render('programacion_usuarios/index', [
            'asignaciones' => $asignaciones,
            'programaciones' => $programaciones,
            'usuarios' => $usuarios
        ]);
    }

    public function asignar() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'programacion_id' => $_POST['programacion_id'],
                    'usuario_id' => $_POST['usuario_id'],
                    'fecha_inicio' => $_POST['fecha_inicio'],
                    'fecha_fin' => !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null,
                    'estado' => 'activo'
                ];

                $this->programacionUsuarioModel->asignarPrograma($data);
                $_SESSION['success'] = "Programa asignado exitosamente";
                header('Location: /programacion_usuarios');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: /programacion_usuarios/asignar');
                exit;
            }
        }

        $programas = $this->programacionModel->getAllProgramaciones();
        $usuarios = $this->usuarioModel->getAllUsuarios();
        
        $this->render('programacion_usuarios/asignar', [
            'programas' => $programas,
            'usuarios' => $usuarios
        ]);
    }

    public function verAsignaciones($programacion_id) {
        $this->requireAdmin();
        
        $programacion = $this->programacionModel->findById($programacion_id);
        $usuariosAsignados = $this->programacionUsuarioModel->getUsuariosAsignados($programacion_id);
        
        $this->view('programacion_usuarios/ver_asignaciones', [
            'programacion' => $programacion,
            'usuariosAsignados' => $usuariosAsignados
        ]);
    }

    public function actualizarEstado($id) {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $estado = $_POST['estado'];
                $this->programacionUsuarioModel->actualizarEstado($id, $estado);
                $_SESSION['success'] = "Estado actualizado exitosamente";
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function eliminar($id) {
        $this->requireAdmin();
        
        try {
            $this->programacionUsuarioModel->eliminarAsignacion($id);
            $_SESSION['success'] = "Asignación eliminada exitosamente";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
} 