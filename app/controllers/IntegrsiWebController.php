<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/IntegrsiWebModel.php';

class IntegrsiWebController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new \IntegrsiWebModel();
    }

    public function index() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Integrasi Web retrieved successfully');
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID integrasi web tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'idIntegrasi');
        if (!$data) {
            $this->error('Integrasi Web tidak ditemukan', null, 404);
        }

        $this->success($data, 'Integrasi Web retrieved successfully');
    }

    public function store() {
        $input = $this->getJson();
        $required = ['namaWeb'];
        $missing = $this->validateRequired($input, $required);

        if (!empty($missing)) {
            $this->error('Field required: ' . implode(', ', $missing), null, 400);
        }

        $result = $this->model->insert($input);
        if ($result) {
            $this->success(['id' => $this->model->getLastInsertId()], 'Integrasi Web created successfully', 201);
        }
        $this->error('Failed to create integrasi web', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID integrasi web tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idIntegrasi');
        if (!$existing) {
            $this->error('Integrasi Web tidak ditemukan', null, 404);
        }

        $input = $this->getJson();
        $result = $this->model->update($id, $input, 'idIntegrasi');
        
        if ($result) {
            $this->success([], 'Integrasi Web updated successfully');
        }
        $this->error('Failed to update integrasi web', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID integrasi web tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idIntegrasi');
        if (!$existing) {
            $this->error('Integrasi Web tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idIntegrasi');
        
        if ($result) {
            $this->success([], 'Integrasi Web deleted successfully');
        }
        $this->error('Failed to delete integrasi web', null, 500);
    }
}
?>
