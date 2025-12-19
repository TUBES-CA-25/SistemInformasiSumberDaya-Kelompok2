<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/SanksiModel.php';

/**
 * SanksiController
 */
class SanksiController extends Controller {
    private $model;
    
    public function __construct() {
        $this->model = new SanksiModel();
    }

    public function index($params = []) {
        $this->view('praktikum/sanksi');
    }

    public function adminIndex($params = []) {
        $this->view('admin/sanksi/index');
    }

    public function create($params = []) {
        $this->view('admin/sanksi/form', ['action' => 'create']);
    }

    public function edit($params = []) {
        $this->view('admin/sanksi/form', ['action' => 'edit', 'id' => $params['id']]);
    }
    
    /**
     * API endpoint untuk mendapatkan semua sanksi
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
                'message' => 'Gagal mengambil data sanksi'
            ], 500);
        }
    }
}
?>