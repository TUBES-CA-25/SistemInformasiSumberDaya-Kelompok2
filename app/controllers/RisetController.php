<?php
require_once CONTROLLER_PATH . '/Controller.php';

/**
 * RisetController
 */
class RisetController extends Controller {
    
    public function index($params = []) {
        $this->view('fasilitas/riset');
    }
}
?>