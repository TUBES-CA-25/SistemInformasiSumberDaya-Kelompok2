<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/PeraturanModel.php';

/**
 * PeraturanController
 */
class PeraturanController extends Controller {
    private $model;
    
    public function __construct() {
        $this->model = new PeraturanModel();
    }

    public function index($params = []) {
        $this->view('praktikum/peraturan');
    }

    public function adminIndex($params = []) {
        $this->view('admin/peraturan/index');
    }

    public function create($params = []) {
        $this->view('admin/peraturan/form', ['action' => 'create']);
    }

    public function edit($params = []) {
        $this->view('admin/peraturan/form', ['action' => 'edit', 'id' => $params['id']]);
    }
    
    /**
     * API endpoint untuk mendapatkan semua peraturan/tata tertib
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
                'message' => 'Gagal mengambil data peraturan'
            ], 500);
        }
    }
}
?>