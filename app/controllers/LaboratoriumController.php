<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/LaboratoriumModel.php';

class LaboratoriumController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new \LaboratoriumModel();
    }

    // API methods
    public function apiIndex() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Laboratorium retrieved successfully');
    }

    public function apiShow($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID laboratorium tidak ditemukan', null, 400);
            return;
        }

        $data = $this->model->getById($id, 'idLaboratorium');
        if (!$data) {
            $this->error('Laboratorium tidak ditemukan', null, 404);
            return;
        }

        $this->success($data, 'Laboratorium retrieved successfully');
    }

    // Web view methods
    public function index() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Laboratorium retrieved successfully');
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID laboratorium tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'idLaboratorium');
        if (!$data) {
            $this->error('Laboratorium tidak ditemukan', null, 404);
        }

        $this->success($data, 'Laboratorium retrieved successfully');
    }

    public function store() {
        $input = $this->getJson();
        $required = ['nama'];
        $missing = $this->validateRequired($input, $required);

        if (!empty($missing)) {
            $this->error('Field required: ' . implode(', ', $missing), null, 400);
        }

        $result = $this->model->insert($input);
        if ($result) {
            $this->success(['id' => $this->model->getLastInsertId()], 'Laboratorium created successfully', 201);
        } else {
            // Debug: Log the actual error
            $error_msg = 'Failed to create laboratorium';
            if ($this->model->db) {
                $error_msg .= ' - DB Error: ' . $this->model->db->error;
            }
            $this->error($error_msg, null, 500);
        }
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID laboratorium tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idLaboratorium');
        if (!$existing) {
            $this->error('Laboratorium tidak ditemukan', null, 404);
        }

        $input = $this->getJson();
        $result = $this->model->update($id, $input, 'idLaboratorium');
        
        if ($result) {
            $this->success([], 'Laboratorium updated successfully');
        }
        $this->error('Failed to update laboratorium', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID laboratorium tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idLaboratorium');
        if (!$existing) {
            $this->error('Laboratorium tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idLaboratorium');
        
        if ($result) {
            $this->success([], 'Laboratorium deleted successfully');
        }
        $this->error('Failed to delete laboratorium', null, 500);
    }
}
?>
