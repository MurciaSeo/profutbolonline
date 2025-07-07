<?php
require_once 'BaseController.php';
require_once 'models/UsuarioModel.php';

class AuthController extends BaseController {
    private $usuarioModel;
    
    public function __construct() {
        parent::__construct();
        $this->usuarioModel = new UsuarioModel();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            error_log("Intento de login - Email: " . $email);
            
            $usuario = $this->usuarioModel->authenticate($email, $password);
            
            if ($usuario) {
                error_log("Autenticación exitosa - ID: " . $usuario['id']);
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_role'] = $usuario['rol'];
                $_SESSION['user_name'] = $usuario['nombre'];
                
                $this->redirect('/dashboard');
            } else {
                error_log("Autenticación fallida para email: " . $email);
                $_SESSION['error'] = "Credenciales inválidas";
                $this->render('auth/login');
            }
        } else {
            $this->render('auth/login');
        }
    }
    
    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $apellidos = $_POST['apellidos'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($nombre) || empty($apellidos) || empty($email) || empty($telefono) || empty($password) || empty($confirm_password)) {
                $this->render('auth/registro', ['error' => 'Todos los campos son obligatorios']);
                return;
            }

            if ($password !== $confirm_password) {
                $this->render('auth/registro', ['error' => 'Las contraseñas no coinciden']);
                return;
            }

            if (!preg_match('/^[0-9]{9}$/', $telefono)) {
                $this->render('auth/registro', ['error' => 'El número de teléfono debe tener 9 dígitos']);
                return;
            }

            $usuarioModel = new UsuarioModel();
            
            if ($usuarioModel->findByEmail($email)) {
                $this->render('auth/registro', ['error' => 'El correo electrónico ya está registrado']);
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $usuarioData = [
                'nombre' => $nombre,
                'apellido' => $apellidos,
                'email' => $email,
                'telefono' => $telefono,
                'password' => $hashedPassword,
                'rol' => 'entrenado'
            ];

            if ($usuarioModel->create($usuarioData)) {
                $_SESSION['success'] = 'Registro exitoso. Por favor, inicia sesión.';
                header('Location: /login');
                exit;
            } else {
                $this->render('auth/registro', ['error' => 'Error al registrar el usuario']);
            }
        } else {
            $this->render('auth/registro');
        }
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }
} 