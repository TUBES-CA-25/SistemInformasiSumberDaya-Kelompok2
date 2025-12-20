<?php
require_once CONTROLLER_PATH . '/Controller.php';

/**
 * HomeController
 * Menangani halaman utama/beranda
 */
class HomeController extends Controller {
    
    /**
     * Menampilkan halaman beranda
     */
    public function index($params = []) {
        $this->view('home/index');
    }
}
?>