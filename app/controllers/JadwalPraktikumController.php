<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/JadwalPraktikumModel.php';

class JadwalPraktikumController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new \JadwalPraktikumModel();
    }

    public function index() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Jadwal Praktikum retrieved successfully');
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID jadwal tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'idJadwal');
        if (!$data) {
            $this->error('Jadwal tidak ditemukan', null, 404);
        }

        $this->success($data, 'Jadwal retrieved successfully');
    }

    public function store() {
        $input = $this->getJson();
        $required = ['idMatakuliah', 'idLaboratorium'];
        $missing = $this->validateRequired($input, $required);

        if (!empty($missing)) {
            $this->error('Field required: ' . implode(', ', $missing), null, 400);
        }

        $result = $this->model->insert($input);
        if ($result) {
            $this->success(['id' => $this->model->getLastInsertId()], 'Jadwal created successfully', 201);
        }
        $this->error('Failed to create jadwal', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID jadwal tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idJadwal');
        if (!$existing) {
            $this->error('Jadwal tidak ditemukan', null, 404);
        }

        $input = $this->getJson();
        $result = $this->model->update($id, $input, 'idJadwal');
        
        if ($result) {
            $this->success([], 'Jadwal updated successfully');
        }
        $this->error('Failed to update jadwal', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID jadwal tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idJadwal');
        if (!$existing) {
            $this->error('Jadwal tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idJadwal');
        
        if ($result) {
            $this->success([], 'Jadwal deleted successfully');
        }
        $this->error('Failed to delete jadwal', null, 500);
    }
}
?>
