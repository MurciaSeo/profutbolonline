<?php
require_once 'BaseController.php';
require_once 'models/ProgramaCoachingModel.php';
require_once 'models/AccesoBloqueModel.php';
require_once 'models/SuscripcionCoachingModel.php';
require_once 'models/PagoSuscripcionModel.php';

class AdminCoachingController extends BaseController {
    private $programaCoachingModel;
    private $accesoBloqueModel;
    private $suscripcionCoachingModel;
    private $pagoSuscripcionModel;
    
    public function __construct() {
        parent::__construct();
        $this->programaCoachingModel = new ProgramaCoachingModel();
        $this->accesoBloqueModel = new AccesoBloqueModel();
        $this->suscripcionCoachingModel = new SuscripcionCoachingModel();
        $this->pagoSuscripcionModel = new PagoSuscripcionModel();
    }
    
    /**
     * Lista todos los programas de coaching
     */
    public function index() {
        $this->requireAuth();
        $this->requireAdmin();
        
        $programas = $this->programaCoachingModel->getProgramasConEstadisticas();
        
        $this->render('admin/coaching/index', [
            'programas' => $programas
        ]);
    }
    
    /**
     * Muestra el formulario para crear un nuevo programa
     */
    public function crear() {
        $this->requireAuth();
        $this->requireAdmin();
        
        $categorias = [
            'nutricion' => 'Nutrición',
            'psicologia' => 'Psicología',
            'tecnica' => 'Técnica',
            'fisico' => 'Físico',
            'mental' => 'Mental',
            'recuperacion' => 'Recuperación'
        ];
        
        $this->render('admin/coaching/crear', [
            'categorias' => $categorias
        ]);
    }
    
    /**
     * Muestra el formulario para editar un programa
     */
    public function editar($id) {
        $this->requireAuth();
        $this->requireAdmin();
        
        $programa = $this->programaCoachingModel->findById($id);
        
        if (!$programa) {
            $_SESSION['error'] = "Programa no encontrado.";
            $this->redirect('/admin/coaching');
        }
        
        $categorias = [
            'nutricion' => 'Nutrición',
            'psicologia' => 'Psicología',
            'tecnica' => 'Técnica',
            'fisico' => 'Físico',
            'mental' => 'Mental',
            'recuperacion' => 'Recuperación'
        ];
        
        $this->render('admin/coaching/editar', [
            'programa' => $programa,
            'categorias' => $categorias
        ]);
    }
    
    /**
     * Guarda un programa (crear o actualizar)
     */
    public function guardar() {
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/coaching');
        }
        
        $data = [
            'nombre' => $_POST['nombre'],
            'descripcion' => $_POST['descripcion'],
            'categoria' => $_POST['categoria'],
            'duracion_meses' => (int)$_POST['duracion_meses'],
            'precio_mensual' => (float)$_POST['precio_mensual'],
            'moneda' => $_POST['moneda'],
            'imagen_url' => $_POST['imagen_url'] ?? null
        ];
        
        $id = $_POST['id'] ?? null;
        
        try {
            if ($id) {
                // Actualizar programa existente
                $this->programaCoachingModel->actualizarPrograma($id, $data);
                $_SESSION['success'] = "Programa actualizado correctamente.";
            } else {
                // Crear nuevo programa
                $id = $this->programaCoachingModel->crearPrograma($data);
                $_SESSION['success'] = "Programa creado correctamente.";
            }
            
            $this->redirect('/admin/coaching');
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al guardar el programa: " . $e->getMessage();
            $this->redirect('/admin/coaching/crear');
        }
    }
    
    /**
     * Elimina un programa
     */
    public function eliminar($id) {
        $this->requireAuth();
        $this->requireAdmin();
        
        try {
            $this->programaCoachingModel->desactivarPrograma($id);
            $_SESSION['success'] = "Programa eliminado correctamente.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al eliminar el programa: " . $e->getMessage();
        }
        
        $this->redirect('/admin/coaching');
    }
    
    /**
     * Gestiona los bloques de un programa
     */
    public function gestionarBloques($programa_id) {
        $this->requireAuth();
        $this->requireAdmin();
        
        $programa = $this->programaCoachingModel->getProgramaConBloques($programa_id);
        
        if (!$programa) {
            $_SESSION['error'] = "Programa no encontrado.";
            $this->redirect('/admin/coaching');
        }
        
        $tipos_contenido = [
            'texto' => 'Texto',
            'video' => 'Video',
            'audio' => 'Audio',
            'pdf' => 'PDF',
            'interactivo' => 'Interactivo'
        ];
        
        $this->render('admin/coaching/bloques', [
            'programa' => $programa,
            'tipos_contenido' => $tipos_contenido
        ]);
    }
    
    /**
     * Guarda un bloque
     */
    public function guardarBloque() {
        $this->requireAuth();
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/coaching');
        }
        
        $data = [
            'programa_coaching_id' => (int)$_POST['programa_coaching_id'],
            'mes' => (int)$_POST['mes'],
            'titulo' => $_POST['titulo'],
            'descripcion' => $_POST['descripcion'],
            'contenido' => $_POST['contenido'],
            'tipo_contenido' => $_POST['tipo_contenido'],
            'url_contenido' => $_POST['url_contenido'] ?? null,
            'duracion_minutos' => (int)$_POST['duracion_minutos'],
            'orden' => (int)$_POST['orden']
        ];
        
        $id = $_POST['id'] ?? null;
        
        try {
            if ($id) {
                // Actualizar bloque existente
                $this->accesoBloqueModel->actualizarBloque($id, $data);
                $_SESSION['success'] = "Bloque actualizado correctamente.";
            } else {
                // Crear nuevo bloque
                $this->accesoBloqueModel->crearBloque($data);
                $_SESSION['success'] = "Bloque creado correctamente.";
            }
            
            $this->redirect('/admin/coaching/bloques/' . $data['programa_coaching_id']);
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al guardar el bloque: " . $e->getMessage();
            $this->redirect('/admin/coaching/bloques/' . $data['programa_coaching_id']);
        }
    }
    
    /**
     * Elimina un bloque
     */
    public function eliminarBloque($bloque_id) {
        $this->requireAuth();
        $this->requireAdmin();
        
        try {
            $this->accesoBloqueModel->eliminarBloque($bloque_id);
            $_SESSION['success'] = "Bloque eliminado correctamente.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al eliminar el bloque: " . $e->getMessage();
        }
        
        $this->redirect('/admin/coaching');
    }
    
    /**
     * Muestra las suscripciones de coaching
     */
    public function suscripciones() {
        $this->requireAuth();
        $this->requireAdmin();
        
        $suscripciones = $this->suscripcionCoachingModel->getAllSuscripciones();
        
        $this->render('admin/coaching/suscripciones', [
            'suscripciones' => $suscripciones
        ]);
    }
    
    /**
     * Muestra estadísticas de coaching
     */
    public function estadisticas() {
        $this->requireAuth();
        $this->requireAdmin();
        
        $estadisticas = $this->suscripcionCoachingModel->getEstadisticas();
        $programas = $this->programaCoachingModel->getProgramasConEstadisticas();
        
        $this->render('admin/coaching/estadisticas', [
            'estadisticas' => $estadisticas,
            'programas' => $programas
        ]);
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