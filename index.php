<?php
session_start();
require_once 'config/database.php';

// Definir la ruta base
define('BASE_PATH', __DIR__);

// Obtener la URL solicitada
$request = $_SERVER['REQUEST_URI'];

// Eliminar la barra inicial si existe
$request = ltrim($request, '/');

// Eliminar los parámetros de consulta para el enrutamiento
$request = strtok($request, '?');

// Enrutamiento básico
switch ($request) {
    case '':
    case '/':
        require __DIR__ . '/controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
        
    case 'login':
        require __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;
        
    case 'registro':
        require __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->registro();
        break;
        
    case 'dashboard':
        require __DIR__ . '/controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;
        
    case 'ejercicios':
        require __DIR__ . '/controllers/EjercicioController.php';
        $controller = new EjercicioController();
        $controller->index();
        break;
        
    case 'ejercicios/crear':
        require __DIR__ . '/controllers/EjercicioController.php';
        $controller = new EjercicioController();
        $controller->crear();
        break;
        
    case (preg_match('/^ejercicios\/editar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/EjercicioController.php';
        $controller = new EjercicioController();
        $controller->editar($matches[1]);
        break;
        
    case (preg_match('/^ejercicios\/eliminar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/EjercicioController.php';
        $controller = new EjercicioController();
        $controller->eliminar($matches[1]);
        break;
        
    case 'tipos-ejercicios':
        require __DIR__ . '/controllers/TipoEjercicioController.php';
        $controller = new TipoEjercicioController();
        $controller->index();
        break;
        
    case 'tipos-ejercicios/crear':
        require __DIR__ . '/controllers/TipoEjercicioController.php';
        $controller = new TipoEjercicioController();
        $controller->crear();
        break;
        
    case (preg_match('/^tipos-ejercicios\/editar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/TipoEjercicioController.php';
        $controller = new TipoEjercicioController();
        $controller->editar($matches[1]);
        break;
        
    case (preg_match('/^tipos-ejercicios\/eliminar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/TipoEjercicioController.php';
        $controller = new TipoEjercicioController();
        $controller->eliminar($matches[1]);
        break;
        
    case 'entrenamientos':
        require __DIR__ . '/controllers/EntrenamientoController.php';
        $controller = new EntrenamientoController();
        $controller->index();
        break;
        
    case 'entrenamientos/crear':
        require __DIR__ . '/controllers/EntrenamientoController.php';
        $controller = new EntrenamientoController();
        $controller->crear();
        break;
        
    case (preg_match('/^entrenamientos\/ver\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/EntrenamientoController.php';
        $controller = new EntrenamientoController();
        $controller->ver($matches[1]);
        break;
        
    case (preg_match('/^entrenamientos\/editar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/EntrenamientoController.php';
        $controller = new EntrenamientoController();
        $controller->editar($matches[1]);
        break;
        
    case (preg_match('/^entrenamientos\/eliminar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/EntrenamientoController.php';
        $controller = new EntrenamientoController();
        $controller->eliminar($matches[1]);
        break;

    case (preg_match('/^entrenamientos\/completar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/EntrenamientoController.php';
        $controller = new EntrenamientoController();
        $controller->completar($matches[1]);
        break;
        
    case (preg_match('/^entrenamientos\/valorar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/EntrenamientoController.php';
        $controller = new EntrenamientoController();
        $controller->valorar($matches[1]);
        break;

    case (preg_match('/^entrenamientos\/descargarPDF\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/EntrenamientoController.php';
        $controller = new EntrenamientoController();
        $controller->descargarPDF($matches[1]);
        break;

    case (preg_match('/^entrenamientos\/asignar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/EntrenamientoController.php';
        $controller = new EntrenamientoController();
        $controller->asignar($matches[1]);
        break;

    // Rutas de gestión de usuarios
    case 'usuarios':
        require __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->usuarios();
        break;

    case 'usuarios/crear':
        require __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->crearUsuario();
        break;

    case (preg_match('/^usuarios\/editar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->editarUsuario($matches[1]);
        break;

    case (preg_match('/^usuarios\/eliminar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->eliminarUsuario($matches[1]);
        break;

    case 'usuarios/asignar-entrenador':
        require __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->asignarEntrenador();
        break;

    case 'usuarios/entrenados':
        require __DIR__ . '/controllers/UsuarioController.php';
        $controller = new UsuarioController();
        $controller->entrenados();
        break;

    case (preg_match('/^usuarios\/perfil\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/UsuarioController.php';
        $controller = new UsuarioController();
        $controller->perfil($matches[1]);
        break;

    case 'usuarios/roles':
        require __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->gestionarRoles();
        break;
        
    // Rutas de configuración
    case 'admin/configuracion':
        require __DIR__ . '/controllers/ConfiguracionController.php';
        $controller = new ConfiguracionController();
        $controller->index();
        break;
        
    case 'admin/configuracion/seguridad':
        require __DIR__ . '/controllers/ConfiguracionController.php';
        $controller = new ConfiguracionController();
        $controller->guardarSeguridad();
        break;
        
    case 'admin/configuracion/guardar':
        require __DIR__ . '/controllers/ConfiguracionController.php';
        $controller = new ConfiguracionController();
        $controller->guardar();
        break;
        
    case 'admin/configuracion/backup':
        require __DIR__ . '/controllers/ConfiguracionController.php';
        $controller = new ConfiguracionController();
        $controller->backup();
        break;
        
    case 'admin/configuracion/limpiar-cache':
        require __DIR__ . '/controllers/ConfiguracionController.php';
        $controller = new ConfiguracionController();
        $controller->limpiarCache();
        break;
        
    // Rutas de programaciones
    case 'programaciones':
        require __DIR__ . '/controllers/ProgramacionController.php';
        $controller = new ProgramacionController();
        $controller->index();
        break;
        
    case 'programaciones/crear':
        require __DIR__ . '/controllers/ProgramacionController.php';
        $controller = new ProgramacionController();
        $controller->crear();
        break;
        
    case 'programaciones/guardar':
        require __DIR__ . '/controllers/ProgramacionController.php';
        $controller = new ProgramacionController();
        $controller->guardar();
        break;
        
    case (preg_match('/^programaciones\/actualizar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/ProgramacionController.php';
        $controller = new ProgramacionController();
        $controller->actualizar($matches[1]);
        break;
        
    case (preg_match('/^programaciones\/ver\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/ProgramacionController.php';
        $controller = new ProgramacionController();
        $controller->ver($matches[1]);
        break;
        
    case (preg_match('/^programaciones\/editar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/ProgramacionController.php';
        $controller = new ProgramacionController();
        $controller->editar($matches[1]);
        break;
        
    case (preg_match('/^programaciones\/eliminar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/ProgramacionController.php';
        $controller = new ProgramacionController();
        $controller->eliminar($matches[1]);
        break;

    // Rutas de programación de usuarios
    case 'programacion_usuarios':
        require __DIR__ . '/controllers/ProgramacionUsuarioController.php';
        $controller = new ProgramacionUsuarioController();
        $controller->index();
        break;

    case 'programacion_usuarios/asignar':
        require __DIR__ . '/controllers/ProgramacionUsuarioController.php';
        $controller = new ProgramacionUsuarioController();
        $controller->asignar();
        break;

    case (preg_match('/^programacion_usuarios\/ver\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/ProgramacionUsuarioController.php';
        $controller = new ProgramacionUsuarioController();
        $controller->verAsignaciones($matches[1]);
        break;

    case (preg_match('/^programacion_usuarios\/estado\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/ProgramacionUsuarioController.php';
        $controller = new ProgramacionUsuarioController();
        $controller->actualizarEstado($matches[1]);
        break;

    case (preg_match('/^programacion_usuarios\/eliminar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/ProgramacionUsuarioController.php';
        $controller = new ProgramacionUsuarioController();
        $controller->eliminar($matches[1]);
        break;
        
    // Rutas de seguimiento de programa
    case 'seguimiento-programa':
        require __DIR__ . '/controllers/SeguimientoProgramaController.php';
        $controller = new SeguimientoProgramaController();
        $controller->index();
        break;
        
    case (preg_match('/^seguimiento-programa\/ver\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/SeguimientoProgramaController.php';
        $controller = new SeguimientoProgramaController();
        $controller->ver($matches[1]);
        break;
        
    case (preg_match('/^seguimiento-programa\/marcar-completado\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/SeguimientoProgramaController.php';
        $controller = new SeguimientoProgramaController();
        $controller->marcarCompletado($matches[1]);
        break;
        
    case 'seguimiento-programa/guardar-valoracion':
        require __DIR__ . '/controllers/SeguimientoProgramaController.php';
        $controller = new SeguimientoProgramaController();
        $controller->guardarValoracion();
        break;
        
    // Rutas de sesiones
    case 'sesiones':
        require __DIR__ . '/controllers/SesionController.php';
        $controller = new SesionController();
        $controller->index();
        break;
        
    case (preg_match('/^sesiones\/ver\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/SesionController.php';
        $controller = new SesionController();
        $controller->ver($matches[1]);
        break;
        
    case (preg_match('/^sesiones\/completar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/SesionController.php';
        $controller = new SesionController();
        $controller->completar($matches[1]);
        break;
        
    case (preg_match('/^sesiones\/valorar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/SesionController.php';
        $controller = new SesionController();
        $controller->valorar($matches[1]);
        break;
        
    case 'sesiones/guardar-valoracion':
        require __DIR__ . '/controllers/SesionController.php';
        $controller = new SesionController();
        $controller->guardarValoracion();
        break;

    case 'logout':
        require __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    // Rutas de administración de sesiones
    case 'admin/sesiones':
        require __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->sesiones();
        break;

    case 'admin/sesiones/asignar':
        require __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->asignarSesion();
        break;

    case 'admin/sesiones/guardar':
        require __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->guardarSesion();
        break;

    case (preg_match('/^admin\/sesiones\/eliminar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->eliminarSesion($matches[1]);
        break;

    case (preg_match('/^admin\/sesiones\/editar\/(\d+)$/', $request, $matches) ? true : false):
        require __DIR__ . '/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->editarSesion($matches[1]);
        break;
        
    default:
        // Página no encontrada
        header("HTTP/1.0 404 Not Found");
        require __DIR__ . '/views/errors/404.php';
        break;
} 