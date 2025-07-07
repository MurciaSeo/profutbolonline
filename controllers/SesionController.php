<?php
require_once 'BaseController.php';
require_once 'models/SesionModel.php';
require_once 'models/ValoracionModel.php';

class SesionController extends BaseController {
    private $sesionModel;
    private $valoracionModel;
    
    public function __construct() {
        parent::__construct();
        $this->sesionModel = new SesionModel();
        $this->valoracionModel = new ValoracionModel();
    }
    
    public function completar($id) {
        $this->requireAuth();
        
        $sesion = $this->sesionModel->getSesionPorId($id);
        
        if (!$sesion || $sesion['usuario_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "No tienes permiso para completar esta sesión";
            $this->redirect('/dashboard');
            return;
        }
        
        if ($sesion['completado']) {
            $_SESSION['error'] = "Esta sesión ya está completada";
            $this->redirect('/dashboard');
            return;
        }
        
        if ($this->sesionModel->marcarCompletada($id)) {
            $_SESSION['success'] = "¡Sesión completada con éxito!";
            $this->redirect('/sesiones/valorar/' . $id);
        } else {
            $_SESSION['error'] = "Error al marcar la sesión como completada";
            $this->redirect('/dashboard');
        }
    }
    
    public function valorar($id) {
        $this->requireAuth();
        
        $sesion = $this->sesionModel->getSesionPorId($id);
        
        if (!$sesion || $sesion['usuario_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "No tienes permiso para valorar esta sesión";
            $this->redirect('/dashboard');
            return;
        }
        
        if (!$sesion['completado']) {
            $_SESSION['error'] = "Debes completar la sesión antes de valorarla";
            $this->redirect('/dashboard');
            return;
        }
        
        $this->render('sesiones/valorar', [
            'sesion' => $sesion
        ]);
    }
    
    public function guardarValoracion() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dashboard');
            return;
        }
        
        $sesion_id = $_POST['sesion_id'] ?? null;
        $entrenamiento_id = $_POST['entrenamiento_id'] ?? null;
        $calidad = $_POST['calidad'] ?? null;
        $esfuerzo = $_POST['esfuerzo'] ?? null;
        $complejidad = $_POST['complejidad'] ?? null;
        $duracion = $_POST['duracion'] ?? null;
        $comentarios = !empty($_POST['comentarios']) ? trim($_POST['comentarios']) : null;
        
        if (!$sesion_id || !$entrenamiento_id || !$calidad || !$esfuerzo || !$complejidad || !$duracion) {
            $_SESSION['error'] = "Por favor, complete todos los campos requeridos";
            $this->redirect('/sesiones/valorar/' . $sesion_id);
            return;
        }
        
        $sesion = $this->sesionModel->getSesionPorId($sesion_id);
        
        if (!$sesion || $sesion['usuario_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "No tienes permiso para valorar esta sesión";
            $this->redirect('/dashboard');
            return;
        }
        
        if ($this->valoracionModel->guardarValoracion(
            $entrenamiento_id,
            $_SESSION['user_id'],
            $calidad,
            $esfuerzo,
            $complejidad,
            $duracion,
            $comentarios,
            $sesion_id,
            true // indicar que es una sesión
        )) {
            $_SESSION['success'] = "¡Gracias por tu valoración!";
            $this->redirect('/dashboard');
        } else {
            $_SESSION['error'] = "Error al guardar la valoración";
            $this->redirect('/sesiones/valorar/' . $sesion_id);
        }
    }
    
    public function ver($id) {
        $this->requireAuth();
        
        $sesion = $this->sesionModel->getSesionPorId($id);
        
        if (!$sesion || $sesion['usuario_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "No tienes permiso para ver esta sesión";
            $this->redirect('/dashboard');
            return;
        }
        
        $this->render('sesiones/ver', [
            'sesion' => $sesion
        ]);
    }
} 