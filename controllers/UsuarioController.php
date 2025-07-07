<?php
require_once 'BaseController.php';
require_once 'models/UsuarioModel.php';
require_once 'models/EntrenamientoModel.php';
require_once 'models/ProgramacionUsuarioModel.php';
require_once 'models/ProgramacionModel.php';

class UsuarioController extends BaseController {
    private $usuarioModel;
    private $entrenamientoModel;
    private $programacionUsuarioModel;
    private $programacionModel;
    
    public function __construct() {
        parent::__construct();
        $this->usuarioModel = new UsuarioModel();
        $this->entrenamientoModel = new EntrenamientoModel();
        $this->programacionUsuarioModel = new ProgramacionUsuarioModel();
        $this->programacionModel = new ProgramacionModel();
    }
    
    public function index() {
        $this->requireAuth();
        
        if ($_SESSION['user_role'] !== 'admin') {
            $this->redirect('/dashboard');
        }
        
        $usuarios = $this->usuarioModel->findAll();
        $this->render('usuarios/index', [
            'usuarios' => $usuarios
        ]);
    }
    
    public function crear() {
        $this->requireAuth();
        
        if ($_SESSION['user_role'] !== 'admin') {
            $this->redirect('/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'email' => $_POST['email'],
                'telefono' => $_POST['telefono'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'rol' => $_POST['rol']
            ];
            
            if ($this->usuarioModel->create($data)) {
                $_SESSION['success'] = "Usuario creado correctamente.";
                $this->redirect('/usuarios');
            } else {
                $_SESSION['error'] = "Error al crear el usuario";
                $this->render('usuarios/crear', ['error' => $error]);
            }
        } else {
            $this->render('usuarios/crear');
        }
    }
    
    public function editar($id) {
        $this->requireAuth();
        
        if ($_SESSION['user_role'] !== 'admin') {
            $this->redirect('/dashboard');
        }
        
        $usuario = $this->usuarioModel->findById($id);
        
        if (!$usuario) {
            $this->redirect('/usuarios');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $id,
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'email' => $_POST['email'],
                'rol' => $_POST['rol']
            ];
            
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
            
            if ($this->usuarioModel->update($data)) {
                $this->redirect('/usuarios');
            } else {
                $error = "Error al actualizar el usuario";
                $this->render('usuarios/editar', [
                    'error' => $error,
                    'usuario' => $usuario
                ]);
            }
        } else {
            $this->render('usuarios/editar', [
                'usuario' => $usuario
            ]);
        }
    }
    
    public function eliminar($id) {
        $this->requireAuth();
        
        if ($_SESSION['user_role'] !== 'admin') {
            $this->redirect('/dashboard');
        }
        
        if ($this->usuarioModel->delete($id)) {
            $this->redirect('/usuarios');
        } else {
            $error = "Error al eliminar el usuario";
            $this->redirect('/usuarios');
        }
    }
    
    public function asignarEntrenador() {
        $this->requireAuth();
        
        if ($_SESSION['user_role'] !== 'admin') {
            $this->redirect('/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entrenado_id = $_POST['entrenado_id'];
            $entrenador_id = $_POST['entrenador_id'];
            
            if ($this->usuarioModel->asignarEntrenador($entrenado_id, $entrenador_id)) {
                $this->redirect('/usuarios');
            } else {
                $error = "Error al asignar el entrenador";
                $this->redirect('/usuarios');
            }
        }
    }

    public function entrenados() {
        $this->requireAuth();
        
        if ($_SESSION['user_role'] !== 'entrenador') {
            $this->redirect('/dashboard');
        }
        
        $entrenador_id = $_SESSION['user_id'];
        $entrenados = $this->usuarioModel->getEntrenadosPorEntrenador($entrenador_id);
        
        // Obtener estadísticas de cada entrenado
        $estadisticas = [];
        foreach ($entrenados as $entrenado) {
            $estadisticas[$entrenado['id']] = [
                'total_entrenamientos' => $this->entrenamientoModel->getTotalEntrenamientosPorUsuario($entrenado['id']),
                'entrenamientos_completados' => $this->entrenamientoModel->getTotalEntrenamientosCompletadosPorUsuario($entrenado['id']),
                'ultima_actividad' => $this->usuarioModel->getUltimaActividad($entrenado['id'])
            ];
        }
        
        $this->render('usuarios/entrenados', [
            'entrenados' => $entrenados,
            'estadisticas' => $estadisticas
        ]);
    }

    public function perfil($id) {
        $this->requireAuth();
        
        // Verificar que el usuario tiene permiso para ver este perfil
        if ($_SESSION['user_role'] === 'entrenador') {
            // Si es entrenador, solo puede ver perfiles de sus entrenados
            $entrenados = $this->usuarioModel->getEntrenadosPorEntrenador($_SESSION['user_id']);
            $puede_ver = false;
            foreach ($entrenados as $entrenado) {
                if ($entrenado['id'] == $id) {
                    $puede_ver = true;
                    break;
                }
            }
            if (!$puede_ver) {
                $this->redirect('/dashboard');
            }
        } elseif ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_id'] != $id) {
            // Si no es admin ni el propio usuario, redirigir al dashboard
            $this->redirect('/dashboard');
        }
        
        $usuario = $this->usuarioModel->findById($id);
        if (!$usuario) {
            $this->redirect('/dashboard');
        }
        
        // Obtener estadísticas del usuario
        $estadisticas = [
            'total_entrenamientos' => $this->entrenamientoModel->getTotalEntrenamientosPorUsuario($id),
            'entrenamientos_completados' => $this->entrenamientoModel->getTotalEntrenamientosCompletadosPorUsuario($id),
            'ultima_actividad' => $this->usuarioModel->getUltimaActividad($id)
        ];

        // Obtener entrenamientos asignados
        $entrenamientos = $this->entrenamientoModel->getEntrenamientosPorUsuario($id);

        // Obtener programas asignados
        $programaciones = $this->programacionUsuarioModel->getProgramasAsignados($id);
        foreach ($programaciones as &$programacion) {
            $detalles = $this->programacionModel->getProgramaConDetallesUsuario($programacion['programacion_id'], $id);
            $programacion['total_dias'] = $detalles['total_dias'];
            $programacion['dias_completados'] = $detalles['dias_completados'];
            $programacion['progreso'] = $detalles['total_dias'] > 0 ? 
                round(($detalles['dias_completados'] / $detalles['total_dias']) * 100) : 0;
        }

        // Si es entrenador, obtener sus entrenados
        if ($usuario['rol'] === 'entrenador') {
            $entrenados = $this->usuarioModel->getEntrenadosPorEntrenador($id);
            $estadisticas['total_entrenados'] = count($entrenados);
        }
        
        $this->render('usuarios/perfil', [
            'usuario' => $usuario,
            'estadisticas' => $estadisticas,
            'entrenamientos' => $entrenamientos,
            'programaciones' => $programaciones,
            'entrenados' => $entrenados ?? []
        ]);
    }
} 