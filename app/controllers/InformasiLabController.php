<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/InformasiLabModel.php';

class InformasiLabController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new \InformasiLabModel();
    }

    /**
     * Halaman publik laboratorium
     */
    public function index($params = []) {
        $data = $this->model->getAktif();
        $this->view('fasilitas/laboratorium', ['laboratorium' => $data]);
    }

    /**
     * Detail laboratorium publik
     */
    public function detail($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/laboratorium');
            return;
        }
        
        $lab = $this->model->getById($id, 'id_informasi');
        if (!$lab) {
            $this->redirect('/laboratorium');
            return;
        }
        
        $this->view('fasilitas/detail', ['laboratorium' => $lab]);
    }

    /**
     * Halaman admin laboratorium
     */
    public function adminIndex($params = []) {
        $data = $this->model->getAll();
        $this->view('admin/laboratorium/index', ['laboratorium' => $data]);
    }

    /**
     * Form create admin
     */
    public function create($params = []) {
        $this->view('admin/laboratorium/form', ['laboratorium' => null, 'action' => 'create']);
    }

    /**
     * Form edit admin
     */
    public function edit($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/laboratorium');
            return;
        }
        
        $lab = $this->model->getById($id, 'id_informasi');
        if (!$lab) {
            $this->setFlash('error', 'Data laboratorium tidak ditemukan');
            $this->redirect('/admin/laboratorium');
            return;
        }
        
        $this->view('admin/laboratorium/form', ['laboratorium' => $lab, 'action' => 'edit']);
    }

    /**
     * Admin detail laboratorium
     */
    public function adminDetail($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/laboratorium');
            return;
        }
        
        $lab = $this->model->getById($id, 'id_informasi');
        if (!$lab) {
            $this->setFlash('error', 'Data laboratorium tidak ditemukan');
            $this->redirect('/admin/laboratorium');
            return;
        }
        
        $this->view('admin/laboratorium/detail', ['laboratorium' => $lab]);
    }

    /**
     * API endpoints
     */
    public function apiIndex() {
        $data = $this->model->getAktif();
        $this->success($data, 'Data Informasi Lab retrieved successfully');
    }

    public function apiShow($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID informasi tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'id_informasi');
        if (!$data) {
            $this->error('Informasi tidak ditemukan', null, 404);
        }

        $this->success($data, 'Informasi retrieved successfully');
    }

    public function byType($params) {
        $type = $params['type'] ?? null;
        if (!$type) {
            $this->error('Tipe informasi tidak ditemukan', null, 400);
        }

        $data = $this->model->getInformasiByTipe($type);
        $this->success($data, 'Informasi by type retrieved successfully');
    }

    public function store() {
        $input = $this->getJson();
        $required = ['informasi', 'tipe_informasi'];
        $missing = $this->validateRequired($input, $required);

        if (!empty($missing)) {
            $this->error('Field required: ' . implode(', ', $missing), null, 400);
        }

        $result = $this->model->insert($input);
        if ($result) {
            $this->success(['id' => $this->model->getLastInsertId()], 'Informasi created successfully', 201);
        }
        $this->error('Failed to create informasi', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID informasi tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'id_informasi');
        if (!$existing) {
            $this->error('Informasi tidak ditemukan', null, 404);
        }

        $input = $this->getJson();
        $result = $this->model->update($id, $input, 'id_informasi');
        
        if ($result) {
            $this->success([], 'Informasi updated successfully');
        }
        $this->error('Failed to update informasi', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID informasi tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'id_informasi');
        if (!$existing) {
            $this->error('Informasi tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'id_informasi');
        
        if ($result) {
            $this->success([], 'Informasi deleted successfully');
        }
        $this->error('Failed to delete informasi', null, 500);
    }
}
?>
