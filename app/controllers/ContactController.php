<?php
require_once CONTROLLER_PATH . '/Controller.php';

/**
 * ContactController
 * Menangani halaman kontak
 */
class ContactController extends Controller {
    
    /**
     * Menampilkan halaman kontak
     */
    public function index($params = []) {
        $this->view('contact/index');
    }
}
?>