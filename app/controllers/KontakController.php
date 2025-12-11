<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/KontakModel.php';

class KontakController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new \KontakModel();
    }

    public function getLatest() {
        $data = $this->model->getLatest();
        if (!$data) {
            $this->error('Kontak not found', null, 404);
        }
        $this->success($data, 'Kontak retrieved successfully');
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID kontak tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'idKontak');
        if (!$data) {
            $this->error('Kontak tidak ditemukan', null, 404);
        }

        $this->success($data, 'Kontak retrieved successfully');
    }

    public function store() {
        $input = $this->getJson();
        
        $result = $this->model->insert($input);
        if ($result) {
            $this->success(['id' => $this->model->getLastInsertId()], 'Kontak created successfully', 201);
        }
        $this->error('Failed to create kontak', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID kontak tidak ditemukan', null, 400);
        }

        $input = $this->getJson();
        $result = $this->model->update($id, $input, 'idKontak');
        
        if ($result) {
            $this->success([], 'Kontak updated successfully');
        }
        $this->error('Failed to update kontak', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID kontak tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idKontak');
        if (!$existing) {
            $this->error('Kontak tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idKontak');
        
        if ($result) {
            $this->success([], 'Kontak deleted successfully');
        }
        $this->error('Failed to delete kontak', null, 500);
    }
}
?>
