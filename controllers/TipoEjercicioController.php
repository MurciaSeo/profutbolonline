<?php
require_once 'controllers/BaseController.php';
require_once 'models/TipoEjercicioModel.php';

class TipoEjercicioController extends BaseController {
    private $model;

    public function __construct() {
        parent::__construct();
        $this->model = new TipoEjercicioModel();
    }

    public function index() {
        $tipos = $this->model->getAll();
        $this->render('tipos_ejercicios/index', [
            'tipos' => $tipos
        ]);
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'color' => $_POST['color'],
                'icono' => $_POST['icono']
            ];

            if ($this->model->create($data)) {
                $_SESSION['success'] = 'Tipo de ejercicio creado exitosamente';
                $this->redirect('/tipos-ejercicios');
            } else {
                $_SESSION['error'] = 'Error al crear el tipo de ejercicio';
            }
        }
        $this->render('tipos_ejercicios/crear');
    }

    public function editar($id) {
        $tipo = $this->model->findById($id);
        if (!$tipo) {
            $_SESSION['error'] = 'Tipo de ejercicio no encontrado';
            $this->redirect('/tipos-ejercicios');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'color' => $_POST['color'],
                'icono' => $_POST['icono']
            ];

            if ($this->model->update($id, $data)) {
                $_SESSION['success'] = 'Tipo de ejercicio actualizado exitosamente';
                $this->redirect('/tipos-ejercicios');
            } else {
                $_SESSION['error'] = 'Error al actualizar el tipo de ejercicio';
            }
        }
        $this->render('tipos_ejercicios/editar', [
            'tipo' => $tipo
        ]);
    }

    public function eliminar($id) {
        if ($this->model->isUsed($id)) {
            $_SESSION['error'] = 'No se puede eliminar este tipo de ejercicio porque está siendo utilizado';
        } else {
            if ($this->model->delete($id)) {
                $_SESSION['success'] = 'Tipo de ejercicio eliminado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al eliminar el tipo de ejercicio';
            }
        }
        $this->redirect('/tipos-ejercicios');
    }
} 