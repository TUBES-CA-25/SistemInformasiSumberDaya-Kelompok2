<?php
require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/models/MatakuliahModel.php';

class MatakuliahController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new MatakuliahModel();
    }

    /**
     * Halaman admin matakuliah
     */
    public function adminIndex($params = []) {
        $data = $this->model->getAll();
        $this->view('admin/matakuliah/index', ['matakuliah' => $data]);
    }

    /**
     * Form create admin
     */
    public function create($params = []) {
        $this->view('admin/matakuliah/form', ['matakuliah' => null, 'action' => 'create']);
    }

    /**
     * Form edit admin
     */
    public function edit($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/admin/matakuliah');
            return;
        }
        
        $matakuliah = $this->model->getById($id, 'idMatakuliah');
        if (!$matakuliah) {
            $this->setFlash('error', 'Data matakuliah tidak ditemukan');
            $this->redirect('/admin/matakuliah');
            return;
        }
        
        $this->view('admin/matakuliah/form', ['matakuliah' => $matakuliah, 'action' => 'edit']);
    }

    /**
     * API endpoints
     */
    public function apiIndex() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Matakuliah retrieved successfully');
    }

    public function apiShow($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID matakuliah tidak ditemukan', null, 400);
            return;
        }

        $data = $this->model->getById($id, 'idMatakuliah');
        if (!$data) {
            $this->error('Matakuliah tidak ditemukan', null, 404);
            return;
        }

        $this->success($data, 'Matakuliah retrieved successfully');
    }

    // Legacy index/show for backwards compatibility
    public function index() {
        return $this->apiIndex();
    }

    public function show($params) {
        return $this->apiShow($params);
    }

    public function store() {
        $input = $this->getJson();
        $required = ['kodeMatakuliah', 'namaMatakuliah'];
        $missing = $this->validateRequired($input, $required);

        if (!empty($missing)) {
            $this->error('Field required: ' . implode(', ', $missing), null, 400);
        }

        $existing = $this->model->getMatakuliahByKode($input['kodeMatakuliah']);
        if ($existing) {
            $this->error('Kode matakuliah sudah terdaftar', null, 400);
        }

        $result = $this->model->insert($input);
        if ($result) {
            $this->success(['id' => $this->model->getLastInsertId()], 'Matakuliah created successfully', 201);
        }
        $this->error('Failed to create matakuliah', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID matakuliah tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idMatakuliah');
        if (!$existing) {
            $this->error('Matakuliah tidak ditemukan', null, 404);
        }

        $input = $this->getJson();
        $result = $this->model->update($id, $input, 'idMatakuliah');
        
        if ($result) {
            $this->success([], 'Matakuliah updated successfully');
        }
        $this->error('Failed to update matakuliah', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID matakuliah tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idMatakuliah');
        if (!$existing) {
            $this->error('Matakuliah tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idMatakuliah');
        
        if ($result) {
            $this->success([], 'Matakuliah deleted successfully');
        }
        $this->error('Failed to delete matakuliah', null, 500);
    }

    public function asisten($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID matakuliah tidak ditemukan', null, 400);
        }

        $data = $this->model->getMatakuliahWithAsisten($id);
        $this->success($data, 'Asisten matakuliah retrieved successfully');
    }
}
?>
