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
            $this->redirect('/login');
        }
    }
} 