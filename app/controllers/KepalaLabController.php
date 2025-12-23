<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/ManajemenModel.php';

/**
 * KepalaLabController
 */
class KepalaLabController extends Controller {
    private $model;
    
    public function __construct() {
        $this->model = new ManajemenModel();
    }
    
    public function index($params = []) {
        // Kembalikan tampilan ke versi awal (legacy view)
        // View ini menggunakan sumberdaya.css dan struktur lama yang diharapkan pengguna
        $this->view('sumberdaya/kepala');
    }
    
    /**
     * API endpoint untuk mendapatkan semua manajemen lab
     */
    public function apiIndex($params = []) {
        try {
            $data = $this->model->getAll();
            $this->response([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (Exception $e) {
            $this->response([
                'status' => 'error',
                'message' => 'Gagal mengambil data manajemen'
            ], 500);
        }
    }
}
?>