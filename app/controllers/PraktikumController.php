<?php
require_once CONTROLLER_PATH . '/Controller.php';

/**
 * PraktikumController
 */
class PraktikumController extends Controller {
    
    public function index($params = []) {
        $this->view('praktikum/index');
    }
}
?>