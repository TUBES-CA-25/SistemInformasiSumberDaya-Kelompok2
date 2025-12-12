<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/AsistenMatakuliahModel.php';

class AsistenMatakuliahController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new \AsistenMatakuliahModel();
    }

    public function index() {
        $data = $this->model->getAll();
        $this->success($data, 'Data AsistenMatakuliah retrieved successfully');
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID asisten matakuliah tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'idAsistenMatakuliah');
        if (!$data) {
            $this->error('AsistenMatakuliah tidak ditemukan', null, 404);
        }

        $this->success($data, 'AsistenMatakuliah retrieved successfully');
    }

    public function store() {
        $input = $this->getJson();
        $required = ['idAsisten', 'idMatakuliah'];
        $missing = $this->validateRequired($input, $required);

        if (!empty($missing)) {
            $this->error('Field required: ' . implode(', ', $missing), null, 400);
        }

        $result = $this->model->insert($input);
        if ($result) {
            $this->success(['id' => $this->model->getLastInsertId()], 'AsistenMatakuliah created successfully', 201);
        }
        $this->error('Failed to create asisten matakuliah', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID asisten matakuliah tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idAsistenMatakuliah');
        if (!$existing) {
            $this->error('AsistenMatakuliah tidak ditemukan', null, 404);
        }

        $input = $this->getJson();
        $result = $this->model->update($id, $input, 'idAsistenMatakuliah');
        
        if ($result) {
            $this->success([], 'AsistenMatakuliah updated successfully');
        }
        $this->error('Failed to update asisten matakuliah', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID asisten matakuliah tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idAsistenMatakuliah');
        if (!$existing) {
            $this->error('AsistenMatakuliah tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idAsistenMatakuliah');
        
        if ($result) {
            $this->success([], 'AsistenMatakuliah deleted successfully');
        }
        $this->error('Failed to delete asisten matakuliah', null, 500);
    }
}
?>
