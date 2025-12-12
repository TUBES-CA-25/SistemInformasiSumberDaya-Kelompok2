<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/MatakuliahModel.php';

class MatakuliahController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new \MatakuliahModel();
    }

    public function index() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Matakuliah retrieved successfully');
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID matakuliah tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'idMatakuliah');
        if (!$data) {
            $this->error('Matakuliah tidak ditemukan', null, 404);
        }

        $this->success($data, 'Matakuliah retrieved successfully');
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
