<?php

require_once 'controllers/BaseController.php';
require_once 'models/ProgramacionUsuarioModel.php';
require_once 'models/ProgramacionModel.php';
require_once 'models/ValoracionModel.php';
require_once 'models/EntrenamientoModel.php';

class SeguimientoProgramaController extends BaseController {
    private $programacionUsuarioModel;
    private $programacionModel;

    public function __construct() {
        parent::__construct();
        $this->programacionUsuarioModel = new ProgramacionUsuarioModel();
        $this->programacionModel = new ProgramacionModel();
    }

    public function index() {
        $this->requireAuth();
        
        $usuario_id = $_SESSION['user_id'];
        $programas = $this->programacionUsuarioModel->getProgramasAsignados($usuario_id);
        
        $this->render('seguimiento_programa/index', [
            'programas' => $programas
        ]);
    }

    public function ver($id) {
        $this->requireAuth();
        
        $programacionUsuarioModel = new ProgramacionUsuarioModel();
        $programacionModel = new ProgramacionModel();
        $valoracionModel = new ValoracionModel();
        $entrenamientoModel = new EntrenamientoModel();
        
        // Obtener los detalles de la asignación del programa
        $asignacion = $programacionUsuarioModel->getAsignacionPrograma($_SESSION['user_id'], $id);
        
        if (!$asignacion) {
            $_SESSION['error'] = "No tiene acceso a este programa.";
            $this->redirect('/seguimiento-programa');
            return;
        }
        
        // Verificar si el programa está dentro del período válido
        if (!$programacionUsuarioModel->verificarAccesoPrograma($_SESSION['user_id'], $id)) {
            $hoy = strtotime(date('Y-m-d'));
            $fecha_inicio = strtotime($asignacion['fecha_inicio']);
            $fecha_fin = $asignacion['fecha_fin'] ? strtotime($asignacion['fecha_fin']) : null;
            
            if ($hoy < $fecha_inicio) {
                $_SESSION['error'] = "Este programa comenzará el " . date('d/m/Y', $fecha_inicio);
            } elseif ($fecha_fin && $hoy > $fecha_fin) {
                $_SESSION['error'] = "Este programa finalizó el " . date('d/m/Y', $fecha_fin);
            } else {
                $_SESSION['error'] = "No tiene acceso a este programa.";
            }
            $this->redirect('/seguimiento-programa');
            return;
        }
        
        // Obtener el programa con todos los detalles
        $programa = $programacionModel->getProgramaConDetallesUsuario($id, $_SESSION['user_id']);
        
        // Determinar el día actual basado en el progreso
        $diaActual = null;
        $semanasCompletadas = true;
        
        foreach ($programa['semanas'] as &$semana) {
            if (!$semanasCompletadas) {
                // Si una semana anterior no está completa, marcar todos los días como bloqueados
                foreach ($semana['dias'] as &$dia) {
                    $dia['bloqueado'] = true;
                }
                continue;
            }
            
            foreach ($semana['dias'] as &$dia) {
                if ($dia['completado']) {
                    // Obtener la valoración si el día tiene un entrenamiento asignado
                    if (!empty($dia['entrenamiento_id'])) {
                        // Obtener detalles completos del entrenamiento
                        $dia['entrenamiento'] = $entrenamientoModel->obtenerConDetalles($dia['entrenamiento_id']);
                        
                        $valoracion = $valoracionModel->getValoracion($dia['entrenamiento_id'], $_SESSION['user_id'], $dia['id']);
                        if ($valoracion) {
                            $dia['valoracion'] = round(($valoracion['calidad'] + $valoracion['esfuerzo'] + $valoracion['complejidad'] + $valoracion['duracion']) / 4);
                        }
                    }
                    continue;
                }
                
                if ($diaActual === null) {
                    $diaActual = $dia;
                    if (!empty($dia['entrenamiento_id'])) {
                        $dia['entrenamiento'] = $entrenamientoModel->obtenerConDetalles($dia['entrenamiento_id']);
                    }
                    $dia['es_actual'] = true;
                    $semanasCompletadas = false;
                } else {
                    $dia['bloqueado'] = true;
                }
            }
        }
        
        $this->render('seguimiento_programa/ver', [
            'programa' => $programa,
            'diaActual' => $diaActual,
            'asignacion' => $asignacion
        ]);
    }

    public function marcarCompletado($dia_id) {
        $this->requireAuth();
        
        $programacionModel = new ProgramacionModel();
        $usuario_id = $_SESSION['user_id'];
        
        $dia_id = $programacionModel->marcarDiaCompletado($dia_id, $usuario_id);
        
        if ($dia_id) {
            // Redirigir a la encuesta de valoración
            $this->redirect("/seguimiento-programa/valoracion/$dia_id");
        } else {
            $_SESSION['error'] = 'Error al marcar el día como completado.';
            $this->redirect('/dashboard');
        }
    }

    public function valoracion($dia_id) {
        $this->requireAuth();
        
        $programacionModel = new ProgramacionModel();
        $dia = $programacionModel->getDiaProgramacion($dia_id);
        
        if (!$dia) {
            $_SESSION['error'] = "Día de entrenamiento no encontrado.";
            $this->redirect('/dashboard');
        }
        
        $this->render('seguimiento_programa/valoracion', [
            'entrenamiento_id' => $dia['entrenamiento_id'],
            'dia_id' => $dia_id
        ]);
    }

    public function guardarValoracion() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dashboard');
        }
        
        $entrenamiento_id = $_POST['entrenamiento_id'] ?? null;
        $dia_id = $_POST['dia_id'] ?? null;
        $calidad = $_POST['calidad'] ?? null;
        $esfuerzo = $_POST['esfuerzo'] ?? null;
        $complejidad = $_POST['complejidad'] ?? null;
        $duracion = $_POST['duracion'] ?? null;
        $comentarios = !empty($_POST['comentarios']) ? trim($_POST['comentarios']) : null;
        
        if (!$entrenamiento_id || !$dia_id || !$calidad || !$esfuerzo || !$complejidad || !$duracion) {
            $_SESSION['error'] = 'Por favor, complete todos los campos requeridos.';
            error_log("Error en valoración - Campos faltantes: " . 
                     "entrenamiento_id=" . ($entrenamiento_id ?? 'null') . 
                     ", dia_id=" . ($dia_id ?? 'null') . 
                     ", calidad=" . ($calidad ?? 'null') . 
                     ", esfuerzo=" . ($esfuerzo ?? 'null') . 
                     ", complejidad=" . ($complejidad ?? 'null') . 
                     ", duracion=" . ($duracion ?? 'null'));
            $this->redirect("/seguimiento-programa/valoracion/$dia_id");
            return;
        }
        
        $valoracionModel = new ValoracionModel();
        $usuario_id = $_SESSION['user_id'];
        
        try {
            if ($valoracionModel->guardarValoracion(
                $entrenamiento_id, 
                $usuario_id, 
                $calidad, 
                $esfuerzo, 
                $complejidad, 
                $duracion, 
                $comentarios,
                $dia_id,
                false // indicar que NO es una sesión
            )) {
                $_SESSION['success'] = '¡Gracias por tu valoración!';
                $this->redirect('/dashboard');
        } else {
                throw new Exception('Error al guardar la valoración en la base de datos');
            }
        } catch (Exception $e) {
            error_log("Error al guardar valoración: " . $e->getMessage() . 
                     " - Datos: entrenamiento_id=$entrenamiento_id, usuario_id=$usuario_id, dia_id=$dia_id");
            $_SESSION['error'] = 'Hubo un error al guardar tu valoración. Por favor, inténtalo de nuevo.';
            $this->redirect("/seguimiento-programa/valoracion/$dia_id");
        }
    }
} 