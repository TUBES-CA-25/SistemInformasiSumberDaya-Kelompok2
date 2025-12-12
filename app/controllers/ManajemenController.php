<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/ManajemenModel.php';

class ManajemenController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new \ManajemenModel();
    }

    public function index() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Manajemen retrieved successfully');
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID manajemen tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'idManajemen');
        if (!$data) {
            $this->error('Manajemen tidak ditemukan', null, 404);
        }

        $this->success($data, 'Manajemen retrieved successfully');
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
            $this->success(['id' => $this->model->getLastInsertId()], 'Manajemen created successfully', 201);
        }
        $this->error('Failed to create manajemen', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID manajemen tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idManajemen');
        if (!$existing) {
            $this->error('Manajemen tidak ditemukan', null, 404);
        }

        $input = $this->getJson();
        $result = $this->model->update($id, $input, 'idManajemen');
        
        if ($result) {
            $this->success([], 'Manajemen updated successfully');
        }
        $this->error('Failed to update manajemen', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID manajemen tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idManajemen');
        if (!$existing) {
            $this->error('Manajemen tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idManajemen');
        
        if ($result) {
            $this->success([], 'Manajemen deleted successfully');
        }
        $this->error('Failed to delete manajemen', null, 500);
    }
}
?>
