<?php
require_once 'BaseController.php';

class HomeController extends BaseController {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        } else {
            // Mostrar página web corporativa
            $this->render('home/corporativa', []);
        }
    }
    
    public function terminosCondiciones() {
        $this->render('home/terminos-condiciones', []);
    }
    
    public function politicaPrivacidad() {
        $this->render('home/politica-privacidad', []);
    }
} 