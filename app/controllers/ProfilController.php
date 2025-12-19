<?php
require_once CONTROLLER_PATH . '/Controller.php';

/**
 * ProfilController
 */
class ProfilController extends Controller {
    
    public function index($params = []) {
        $this->view('sumberdaya/profil');
    }
}
?>