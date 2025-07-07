<?php
require_once 'BaseController.php';
require_once 'models/EjercicioModel.php';
require_once 'models/TipoEjercicioModel.php';

class EjercicioController extends BaseController {
    private $ejercicioModel;
    private $tipoModel;
    
    public function __construct() {
        parent::__construct();
        $this->ejercicioModel = new EjercicioModel();
        $this->tipoModel = new TipoEjercicioModel();
    }
    
    public function index() {
        $this->requireAuth();
        
        $busqueda = $_GET['busqueda'] ?? '';
        $tipo_id = $_GET['tipo_id'] ?? null;
        $pagina = $_GET['pagina'] ?? 1;
        $por_pagina = 10;
        
        $ejercicios = $this->ejercicioModel->buscar($busqueda, $tipo_id, $pagina, $por_pagina);
        $total = $this->ejercicioModel->contarBusqueda($busqueda, $tipo_id);
        $tipos = $this->tipoModel->getAll();
        
        $this->render('ejercicios/index', [
            'ejercicios' => $ejercicios,
            'tipos' => $tipos,
            'tipo_seleccionado' => $tipo_id,
            'busqueda' => $busqueda,
            'pagina_actual' => $pagina,
            'paginas' => ceil($total / $por_pagina)
        ]);
    }
    
    public function crear() {
        $this->requireAuth();
        
        // Permitir acceso a entrenadores y administradores
        if (!isset($_SESSION['user_role']) || 
            ($_SESSION['user_role'] !== 'entrenador' && $_SESSION['user_role'] !== 'admin')) {
            $_SESSION['error'] = "No tiene permisos para acceder a esta sección. Su rol actual es: " . ($_SESSION['user_role'] ?? 'no definido');
            $this->redirect('/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar y limpiar datos
                $data = [
                    'nombre' => trim($_POST['nombre'] ?? ''),
                    'descripcion' => trim($_POST['descripcion'] ?? ''),
                    'tipo_id' => (int)($_POST['tipo_id'] ?? 0),
                    'video_url' => !empty($_POST['video_url']) ? trim($_POST['video_url']) : null
                ];
                
                // Validaciones básicas
                if (empty($data['nombre'])) {
                    throw new Exception("El nombre del ejercicio es requerido");
                }
                
                if (empty($data['tipo_id'])) {
                    throw new Exception("Debe seleccionar un tipo de ejercicio");
                }
                
                // Intentar crear el ejercicio
                if ($this->ejercicioModel->create($data)) {
                    $_SESSION['success'] = "Ejercicio creado correctamente.";
                    $this->redirect('/ejercicios');
                } else {
                    throw new Exception("Error al crear el ejercicio en la base de datos");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                // Mantener los datos ingresados para no perderlos
                $_SESSION['form_data'] = $_POST;
            }
        }
        
        // Obtener tipos de ejercicios
        $tipos = $this->tipoModel->getAll();
        if (empty($tipos)) {
            $_SESSION['error'] = "No hay tipos de ejercicios disponibles. Por favor, contacte al administrador.";
        }
        
        // Recuperar datos del formulario si hubo error
        $form_data = $_SESSION['form_data'] ?? [];
        unset($_SESSION['form_data']);
        
        $this->render('ejercicios/crear', [
            'tipos' => $tipos,
            'form_data' => $form_data
        ]);
    }
    
    public function editar($id) {
        $this->requireAuth();
        
        // Permitir acceso a entrenadores y administradores
        if (!isset($_SESSION['user_role']) || 
            ($_SESSION['user_role'] !== 'entrenador' && $_SESSION['user_role'] !== 'admin')) {
            $_SESSION['error'] = "No tiene permisos para acceder a esta sección. Su rol actual es: " . ($_SESSION['user_role'] ?? 'no definido');
            $this->redirect('/dashboard');
        }
        
        $ejercicio = $this->ejercicioModel->findById($id);
        if (!$ejercicio) {
            $_SESSION['error'] = "Ejercicio no encontrado.";
            $this->redirect('/ejercicios');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'tipo_id' => $_POST['tipo_id'],
                'video_url' => $_POST['video_url'] ?? null
            ];
            
            if ($this->ejercicioModel->update($id, $data)) {
                $_SESSION['success'] = "Ejercicio actualizado correctamente.";
                $this->redirect('/ejercicios');
            } else {
                $_SESSION['error'] = "Error al actualizar el ejercicio.";
            }
        }
        
        $tipos = $this->tipoModel->getAll();
        $this->render('ejercicios/editar', [
            'ejercicio' => $ejercicio,
            'tipos' => $tipos
        ]);
    }
    
    public function eliminar($id) {
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($this->ejercicioModel->delete($id)) {
            $_SESSION['success'] = 'Ejercicio eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el ejercicio';
        }
        
        $this->redirect('/ejercicios');
    }
} 