<?php
require_once 'BaseController.php';
require_once 'models/SesionModel.php';
require_once 'models/ValoracionModel.php';
require_once 'models/ProgramacionModel.php';

class SesionController extends BaseController {
    private $sesionModel;
    private $valoracionModel;
    private $programacionModel;
    
    public function __construct() {
        parent::__construct();
        $this->sesionModel = new SesionModel();
        $this->valoracionModel = new ValoracionModel();
        $this->programacionModel = new ProgramacionModel();
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
    
    /**
     * Marca una sesión como completada
     */
    public function marcarCompletada() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $programacion_id = $_POST['programacion_id'];
                $usuario_id = $_SESSION['user_id'];
                $dia_id = $_POST['dia_id'];
                $entrenamiento_id = $_POST['entrenamiento_id'];
                
                // Verificar que el usuario tiene acceso a esta sesión
                if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'entrenador') {
                    // Para entrenados, verificar que es su sesión
                    if ($usuario_id != $_SESSION['user_id']) {
                        throw new Exception('No tienes permisos para marcar esta sesión');
                    }
                }
                
                $resultado = $this->programacionModel->marcarSesionCompletada(
                    $programacion_id, 
                    $usuario_id, 
                    $dia_id, 
                    $entrenamiento_id
                );
                
                if ($resultado) {
                    $_SESSION['success'] = 'Sesión marcada como completada';
                } else {
                    $_SESSION['error'] = 'Error al marcar la sesión como completada';
                }
                
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        // Redirigir de vuelta a la página del programa
        $this->redirect('/programaciones/ver/' . $programacion_id);
    }
    
    /**
     * Marca una sesión como no completada
     */
    public function marcarNoCompletada() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $programacion_id = $_POST['programacion_id'];
                $usuario_id = $_SESSION['user_id'];
                $dia_id = $_POST['dia_id'];
                $entrenamiento_id = $_POST['entrenamiento_id'];
                
                // Verificar que el usuario tiene acceso a esta sesión
                if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'entrenador') {
                    // Para entrenados, verificar que es su sesión
                    if ($usuario_id != $_SESSION['user_id']) {
                        throw new Exception('No tienes permisos para marcar esta sesión');
                    }
                }
                
                // Actualizar la sesión como no completada
                $sql = "UPDATE sesiones 
                        SET completado = 0, fecha_completado = NULL 
                        WHERE programacion_id = ? AND usuario_id = ? AND dia_id = ? AND entrenamiento_id = ?";
                $stmt = $this->db->prepare($sql);
                $resultado = $stmt->execute([$programacion_id, $usuario_id, $dia_id, $entrenamiento_id]);
                
                if ($resultado) {
                    $_SESSION['success'] = 'Sesión marcada como no completada';
                } else {
                    $_SESSION['error'] = 'Error al actualizar la sesión';
                }
                
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        // Redirigir de vuelta a la página del programa
        $this->redirect('/programaciones/ver/' . $programacion_id);
    }
} 