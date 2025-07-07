<?php
require_once 'BaseController.php';
require_once 'models/UsuarioModel.php';
require_once 'models/SesionModel.php';
require_once 'models/EntrenamientoModel.php';

class AdminController extends BaseController {
    private $usuarioModel;
    
    public function __construct() {
        parent::__construct();
        $this->usuarioModel = new UsuarioModel();
    }
    
    public function index() {
        $this->requireAuth();
        $this->requireAdmin();
        
        $usuarios = $this->usuarioModel->findAll();
        $this->render('usuarios/index', ['usuarios' => $usuarios]);
    }
    
    public function usuarios() {
        $this->requireAuth();
        $this->requireAdmin();
        
        $usuarios = $this->usuarioModel->findAll();
        $entrenadores = $this->usuarioModel->getUsuariosPorRol('entrenador');
        
        $this->render('usuarios/index', [
            'usuarios' => $usuarios,
            'entrenadores' => $entrenadores
        ]);
    }
    
    public function crearUsuario() {
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $password = $_POST['password'] ?? '';
            $rol = $_POST['rol'] ?? 'entrenado';
            
            // Validar campos requeridos
            if (empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($rol)) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                $this->redirect('/usuarios/crear');
            }
            
            // Validar formato de email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "El formato del email no es válido.";
                $this->redirect('/usuarios/crear');
            }
            
            // Verificar si el email ya existe
            $usuarioExistente = $this->usuarioModel->findByEmail($email);
            if ($usuarioExistente) {
                $_SESSION['error'] = "Ya existe un usuario con ese email.";
                $this->redirect('/usuarios/crear');
            }
            
            // Validar longitud mínima de contraseña
            if (strlen($password) < 8) {
                $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres.";
                $this->redirect('/usuarios/crear');
            }
            
            $data = [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => $email,
                'telefono' => $telefono,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'rol' => $rol
            ];
            
            if ($this->usuarioModel->create($data)) {
                $_SESSION['success'] = "Usuario creado correctamente.";
                $this->redirect('/usuarios');
            } else {
                $_SESSION['error'] = "Error al crear el usuario.";
                $this->redirect('/usuarios/crear');
            }
        } else {
            $this->render('usuarios/crear');
        }
    }
    
    public function editarUsuario($id) {
        $this->requireAuth();
        $this->requireAdmin();
        
        $usuario = $this->usuarioModel->findById($id);
        
        if (!$usuario) {
            $_SESSION['error'] = "Usuario no encontrado.";
            $this->redirect('/usuarios');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $rol = $_POST['rol'] ?? 'entrenado';
            
            // Validar campos requeridos
            if (empty($nombre) || empty($apellido) || empty($email) || empty($rol)) {
                $_SESSION['error'] = "Los campos nombre, apellido, email y rol son obligatorios.";
                $this->redirect('/usuarios/editar/' . $id);
            }
            
            // Validar formato de email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "El formato del email no es válido.";
                $this->redirect('/usuarios/editar/' . $id);
            }
            
            // Verificar si el email ya existe en otro usuario
            $usuarioExistente = $this->usuarioModel->findByEmail($email);
            if ($usuarioExistente && $usuarioExistente['id'] != $id) {
                $_SESSION['error'] = "Ya existe otro usuario con ese email.";
                $this->redirect('/usuarios/editar/' . $id);
            }
            
            $data = [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => $email,
                'telefono' => $usuario['telefono'],
                'rol' => $rol,
                'entrenador_id' => $usuario['entrenador_id']
            ];
            
            // Solo actualizar la contraseña si se proporciona una nueva
            if (!empty($password)) {
                // Validar longitud mínima de contraseña
                if (strlen($password) < 8) {
                    $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres.";
                    $this->redirect('/usuarios/editar/' . $id);
                }
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            
            if ($this->usuarioModel->update($id, $data)) {
                $_SESSION['success'] = "Usuario actualizado correctamente.";
                $this->redirect('/usuarios');
            } else {
                $_SESSION['error'] = "Error al actualizar el usuario.";
                $this->redirect('/usuarios/editar/' . $id);
            }
        } else {
            $this->render('usuarios/editar', ['usuario' => $usuario]);
        }
    }
    
    public function eliminarUsuario($id) {
        $this->requireAuth();
        $this->requireAdmin();
        
        // Verificar si el usuario existe
        $usuario = $this->usuarioModel->findById($id);
        if (!$usuario) {
            $_SESSION['error'] = "Usuario no encontrado.";
            $this->redirect('/usuarios');
        }
        
        // Verificar si es el último administrador
        if ($usuario['rol'] === 'admin') {
            $adminCount = $this->usuarioModel->getTotalUsuariosPorRol('admin');
            if ($adminCount <= 1) {
                $_SESSION['error'] = "No se puede eliminar el último administrador.";
                $this->redirect('/usuarios');
            }
        }
        
        // Verificar dependencias antes de eliminar
        // Aquí se podrían añadir más verificaciones según las relaciones en la base de datos
        
        if ($this->usuarioModel->delete($id)) {
            $_SESSION['success'] = "Usuario eliminado correctamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar el usuario.";
        }
        
        $this->redirect('/usuarios');
    }
    
    public function asignarEntrenador() {
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $entrenado_id = $_POST['entrenado_id'] ?? null;
            $entrenador_id = $_POST['entrenador_id'] ?? null;
            
            if (!$entrenado_id || !$entrenador_id) {
                $_SESSION['error'] = "Por favor, seleccione tanto el entrenado como el entrenador.";
                $this->redirect('/usuarios');
            }
            
            if ($entrenado_id === $entrenador_id) {
                $_SESSION['error'] = "Un usuario no puede ser asignado como su propio entrenador.";
                $this->redirect('/usuarios');
            }
            
            if ($this->usuarioModel->asignarEntrenador($entrenado_id, $entrenador_id)) {
                $_SESSION['success'] = "Entrenador asignado correctamente.";
            } else {
                $_SESSION['error'] = "Error al asignar el entrenador. Verifique que los usuarios seleccionados tengan los roles correctos.";
            }
            
            $this->redirect('/usuarios');
        }
        
        // Obtener lista de entrenados y entrenadores
        $entrenados = $this->usuarioModel->getUsuariosPorRol('entrenado');
        $entrenadores = $this->usuarioModel->getUsuariosPorRol('entrenador');
        
        if (empty($entrenados) || empty($entrenadores)) {
            $_SESSION['error'] = "No hay usuarios disponibles para realizar la asignación. Asegúrese de que existan usuarios con roles de entrenado y entrenador.";
            $this->redirect('/usuarios');
        }
        
        $this->render('usuarios/asignar_entrenador', [
            'entrenados' => $entrenados,
            'entrenadores' => $entrenadores
        ]);
    }
    
    public function gestionarRoles() {
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = $_POST['usuario_id'] ?? 0;
            $nuevo_rol = $_POST['nuevo_rol'] ?? '';
            
            // Verificar si el usuario existe
            $usuario = $this->usuarioModel->findById($usuario_id);
            if (!$usuario) {
                $_SESSION['error'] = "Usuario no encontrado.";
                $this->redirect('/usuarios');
            }
            
            // Verificar si es el último administrador y se está cambiando a otro rol
            if ($usuario['rol'] === 'admin' && $nuevo_rol !== 'admin') {
                $adminCount = $this->usuarioModel->getTotalUsuariosPorRol('admin');
                if ($adminCount <= 1) {
                    $_SESSION['error'] = "No se puede cambiar el rol del último administrador.";
                    $this->redirect('/usuarios');
                }
            }
            
            if ($this->usuarioModel->actualizarRol($usuario_id, $nuevo_rol)) {
                $_SESSION['success'] = "Rol actualizado correctamente.";
            } else {
                $_SESSION['error'] = "Error al actualizar el rol.";
            }
            
            $this->redirect('/usuarios');
        } else {
            $usuarios = $this->usuarioModel->findAll();
            $this->render('usuarios/index', ['usuarios' => $usuarios]);
        }
    }
    
    public function sesiones() {
        $this->requireAuth();
        $this->requireAdmin();
        
        $sesionModel = new SesionModel();
        $sesiones = $sesionModel->getSesionesPorEntrenador($_SESSION['user_id']);
        
        $this->render('admin/sesiones/index', ['sesiones' => $sesiones]);
    }
    
    public function asignarSesion() {
        $this->requireAuth();
        $this->requireAdmin();
        
        $usuarioModel = new UsuarioModel();
        $entrenamientoModel = new EntrenamientoModel();
        
        $usuarios = $usuarioModel->getUsuariosPorRol('entrenado');
        $entrenamientos = $entrenamientoModel->getEntrenamientosPorCreador($_SESSION['user_id']);
        
        $this->render('admin/sesiones/asignar', [
            'usuarios' => $usuarios,
            'entrenamientos' => $entrenamientos
        ]);
    }
    
    public function guardarSesion() {
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/sesiones');
        }
        
        $usuario_id = $_POST['usuario_id'] ?? null;
        $entrenamiento_id = $_POST['entrenamiento_id'] ?? null;
        $fecha = $_POST['fecha'] ?? null;
        $notas = $_POST['notas'] ?? null;
        
        if (!$usuario_id || !$entrenamiento_id || !$fecha) {
            $_SESSION['error'] = "Por favor, complete todos los campos requeridos.";
            $this->redirect('/admin/sesiones/asignar');
        }
        
        $sesionModel = new SesionModel();
        $datos = [
            'usuario_id' => $usuario_id,
            'entrenamiento_id' => $entrenamiento_id,
            'fecha' => $fecha,
            'notas' => $notas
        ];
        
        if ($sesionModel->crear($datos)) {
            $_SESSION['success'] = "Sesión asignada correctamente.";
            $this->redirect('/admin/sesiones');
        } else {
            $_SESSION['error'] = "Error al asignar la sesión.";
            $this->redirect('/admin/sesiones/asignar');
        }
    }
    
    public function eliminarSesion($id) {
        $this->requireAuth();
        $this->requireAdmin();
        
        $sesionModel = new SesionModel();
        
        if ($sesionModel->eliminar($id)) {
            $_SESSION['success'] = "Sesión eliminada correctamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar la sesión.";
        }
        
        $this->redirect('/admin/sesiones');
    }
    
    public function editarSesion($id) {
        $this->requireAuth();
        $this->requireAdmin();
        
        $sesionModel = new SesionModel();
        $usuarioModel = new UsuarioModel();
        $entrenamientoModel = new EntrenamientoModel();
        
        $sesion = $sesionModel->getSesionPorId($id);
        
        if (!$sesion) {
            $_SESSION['error'] = "Sesión no encontrada.";
            $this->redirect('/admin/sesiones');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'entrenamiento_id' => $_POST['entrenamiento_id'],
                'fecha' => $_POST['fecha'],
                'notas' => $_POST['notas'] ?? null
            ];
            
            if ($sesionModel->actualizar($id, $datos)) {
                $_SESSION['success'] = "Sesión actualizada correctamente.";
                $this->redirect('/admin/sesiones');
            } else {
                $_SESSION['error'] = "Error al actualizar la sesión.";
            }
        }
        
        $usuarios = $usuarioModel->getUsuariosPorRol('entrenado');
        $entrenamientos = $entrenamientoModel->getEntrenamientosPorCreador($_SESSION['user_id']);
        
        $this->render('admin/sesiones/editar', [
            'sesion' => $sesion,
            'usuarios' => $usuarios,
            'entrenamientos' => $entrenamientos
        ]);
    }
    
    protected function requireAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = "No tiene permisos para acceder a esta sección.";
            $this->redirect('/dashboard');
        }
    }
} 