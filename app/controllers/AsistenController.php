<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/AsistenModel.php';

class AsistenController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new AsistenModel();
    }

    /**
     * Halaman publik asisten
     */
    public function index($params = []) {
        $data = $this->model->getAll();
        $this->view('sumberdaya/asisten', ['asisten' => $data]);
    }

    /**
     * Halaman admin asisten
     */
    public function adminIndex($params = []) {
        $data = $this->model->getAll();
        $this->view('admin/asisten/index', ['asisten' => $data]);
    }

    /**
     * Form create admin
     */
    public function create($params = []) {
        $this->view('admin/asisten/form', ['asisten' => null, 'action' => 'create']);
    }

    /**
     * Form edit admin
     */
    public function edit($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/asisten');
            return;
        }
        
        $asisten = $this->model->getById($id, 'idAsisten');
        if (!$asisten) {
            $this->setFlash('error', 'Data asisten tidak ditemukan');
            $this->redirect('/admin/asisten');
            return;
        }
        
        $this->view('admin/asisten/form', ['asisten' => $asisten, 'action' => 'edit']);
    }

    /**
     * Pilih koordinator
     */
    public function pilihKoordinator($params = []) {
        $data = $this->model->getAll();
        $this->view('admin/asisten/pilih-koordinator', ['asisten' => $data]);
    }

    /**
     * API endpoints
     */
    public function apiIndex() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Asisten retrieved successfully');
    }

    public function apiShow($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID asisten tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'idAsisten');
        if (!$data) {
            $this->error('Asisten tidak ditemukan', null, 404);
        }
        
        $this->success($data, 'Asisten retrieved successfully');
    }
}
?>
