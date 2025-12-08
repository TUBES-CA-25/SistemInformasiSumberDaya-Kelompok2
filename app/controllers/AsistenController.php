<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/AsistenModel.php';

class AsistenController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new \AsistenModel();
    }

    public function index() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Asisten retrieved successfully');
    }

    public function show($params) {
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

    public function store() {
        $input = $this->getJson();
        $required = ['nama', 'email'];
        $missing = $this->validateRequired($input, $required);

        if (!empty($missing)) {
            $this->error('Field required: ' . implode(', ', $missing), null, 400);
        }

        $existing = $this->model->getAsistenByEmail($input['email']);
        if ($existing) {
            $this->error('Email sudah terdaftar', null, 400);
        }

        $result = $this->model->insert($input);
        if ($result) {
            $this->success(['id' => $this->model->getLastInsertId()], 'Asisten created successfully', 201);
        }
        $this->error('Failed to create asisten', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID asisten tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idAsisten');
        if (!$existing) {
            $this->error('Asisten tidak ditemukan', null, 404);
        }

        $input = $this->getJson();
        $result = $this->model->update($id, $input, 'idAsisten');
        
        if ($result) {
            $this->success([], 'Asisten updated successfully');
        }
        $this->error('Failed to update asisten', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID asisten tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idAsisten');
        if (!$existing) {
            $this->error('Asisten tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idAsisten');
        
        if ($result) {
            $this->success([], 'Asisten deleted successfully');
        }
        $this->error('Failed to delete asisten', null, 500);
    }

    public function matakuliah($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID asisten tidak ditemukan', null, 400);
        }

        $data = $this->model->getAsistenMatakuliah($id);
        $this->success($data, 'Matakuliah asisten retrieved successfully');
    }
}
?>
