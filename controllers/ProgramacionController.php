<?php
require_once 'BaseController.php';
require_once 'models/ProgramacionModel.php';
require_once 'models/EntrenamientoModel.php';

class ProgramacionController extends BaseController {
    private $programacionModel;
    private $entrenamientoModel;
    
    public function __construct() {
        parent::__construct();
        $this->programacionModel = new ProgramacionModel();
        $this->entrenamientoModel = new EntrenamientoModel();
    }
    
    /**
     * Muestra la lista de programaciones
     */
    public function index() {
        $this->requireAuth();
        $this->requireAdmin();
        
        $programaciones = $this->programacionModel->getAllProgramaciones();
        $this->render('programaciones/index', ['programaciones' => $programaciones]);
    }
    
    /**
     * Muestra el formulario para crear una nueva programación
     */
    public function crear() {
        $this->requireAuth();
        $this->requireAdmin();
        
        $entrenamientos = $this->programacionModel->getEntrenamientosDisponibles();
        $this->render('programaciones/crear', ['entrenamientos' => $entrenamientos]);
    }
    
    /**
     * Procesa la creación de una nueva programación
     */
    public function guardar() {
        $this->requireAuth();
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->db->beginTransaction();
                
                $data = [
                    'nombre' => $_POST['nombre'],
                    'descripcion' => $_POST['descripcion'],
                    'duracion_semanas' => (int)$_POST['duracion_semanas'],
                    'entrenamientos_por_semana' => (int)$_POST['entrenamientos_por_semana'],
                    'nivel' => $_POST['nivel'],
                    'objetivo' => $_POST['objetivo']
                ];

                $programacion_id = $this->programacionModel->crear($data);
                
                if ($programacion_id) {
                    // Crear semanas
                    for ($i = 1; $i <= $data['duracion_semanas']; $i++) {
                        $semana_id = $this->programacionModel->crearSemana([
                            'programacion_id' => $programacion_id,
                            'numero_semana' => $i
                        ]);
                        
                        if ($semana_id) {
                            // Crear días para cada semana
                            for ($j = 1; $j <= $data['entrenamientos_por_semana']; $j++) {
                                $this->programacionModel->crearDia([
                                    'semana_id' => $semana_id,
                                    'dia_semana' => $j,
                                    'orden' => $j
                                ]);
                            }
                        }
                    }
                    
                    $this->db->commit();
                    $_SESSION['success'] = 'Programación creada exitosamente';
                    $this->redirect('/programaciones');
                } else {
                    throw new Exception('Error al crear la programación');
                }
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = $e->getMessage();
                $this->redirect('/programaciones/crear');
            }
        }
    }
    
    /**
     * Muestra el formulario para editar una programación
     */
    public function editar($id) {
        $this->requireAdmin();
        
        $programacion = $this->programacionModel->findById($id);
        
        if (!$programacion) {
            $_SESSION['error'] = "Programa no encontrado";
            $this->redirect('/programaciones');
        }
        
        // Obtener los entrenamientos disponibles
        $entrenamientos = $this->programacionModel->getEntrenamientosDisponibles();
        
        // Obtener la programación completa con semanas y días
        $programacionCompleta = $this->programacionModel->getProgramacionCompleta($id);
        
        // Combinar la información
        $programacion = array_merge($programacion, [
            'semanas' => $programacionCompleta['semanas'] ?? [],
            'dias' => isset($programacionCompleta['dias']) ? array_column($programacionCompleta['dias'], null, 'id') : []
        ]);
        
        $this->render('programaciones/editar', [
            'programacion' => $programacion,
            'entrenamientos' => $entrenamientos
        ]);
    }
    
    /**
     * Procesa la actualización de una programación
     */
    public function actualizar($id) {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Obtener la programación actual para mantener los valores de duración y entrenamientos por semana
                $programacion = $this->programacionModel->findById($id);

                // Preparar los datos de las semanas
                $semanasData = [];
                if (isset($_POST['semanas']) && is_array($_POST['semanas'])) {
                    foreach ($_POST['semanas'] as $semanaIndex => $semana) {
                        $diasData = [];
                        foreach ($semana['dias'] as $diaId => $dia) {
                            $diasData[] = [
                                'dia_semana' => $dia['dia_semana'],
                                'entrenamiento_id' => $dia['entrenamiento_id'],
                                'orden' => $dia['orden']
                            ];
                        }
                        $semanasData[] = [
                            'numero_semana' => $semanaIndex + 1,
                            'dias' => $diasData
                        ];
                    }
                }

                // Actualizar la información básica del programa
                $this->programacionModel->actualizar($id, [
                    'nombre' => $_POST['nombre'],
                    'descripcion' => $_POST['descripcion'],
                    'nivel' => $_POST['nivel'],
                    'objetivo' => $_POST['objetivo'],
                    'duracion_semanas' => $programacion['duracion_semanas'],
                    'entrenamientos_por_semana' => $programacion['entrenamientos_por_semana'],
                    'semanas' => $semanasData
                ]);

                $_SESSION['success'] = "Programa actualizado correctamente";
                $this->redirect('/programaciones');
            } catch (Exception $e) {
                $_SESSION['error'] = "Error al actualizar el programa: " . $e->getMessage();
                $this->redirect('/programaciones/editar/' . $id);
            }
        }
        
        $this->redirect('/programaciones');
    }
    
    /**
     * Elimina una programación
     */
    public function eliminar($id) {
        $this->requireAuth();
        $this->requireAdmin();
        
        try {
            $this->programacionModel->eliminar($id);
            $_SESSION['success'] = 'Programación eliminada exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar la programación: ' . $e->getMessage();
        }
        
        $this->redirect('/programaciones/');
    }
    
    /**
     * Muestra los detalles de una programación
     */
    public function ver($id) {
        $this->requireAuth();
        
        $programacion = $this->programacionModel->getProgramaConDetalles($id);
        
        if (!$programacion) {
            $_SESSION['error'] = "Programa no encontrado";
            $this->redirect('/programaciones');
        }
        
        $this->render('programaciones/ver', [
            'programacion' => $programacion
        ]);
    }
    
    /**
     * Marca un día de la programación como completado
     */
    public function marcarDiaCompletado($dia_id) {
        $this->requireAuth();
        
        try {
            // Obtener el ID de la programación basado en el día
            $programacion_id = $this->programacionModel->getProgramaIdPorDia($dia_id);
            
            if (!$programacion_id) {
                throw new Exception('Día no encontrado');
            }
            
            // Marcar el día como completado
            $this->programacionModel->marcarDiaCompletado($dia_id);
            
            $_SESSION['success'] = 'Día marcado como completado exitosamente';
            $this->redirect('/programaciones/ver/' . $programacion_id);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al marcar el día como completado: ' . $e->getMessage();
            $this->redirect('/dashboard');
        }
    }
    
    protected function requireAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/dashboard');
        }
    }
} 