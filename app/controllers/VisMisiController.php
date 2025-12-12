<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/VisMisiModel.php';

class VisMisiController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new \VisMisiModel();
    }

    public function getLatest() {
        $data = $this->model->getLatest();
        if (!$data) {
            $this->error('Visi misi not found', null, 404);
        }
        $this->success($data, 'Visi Misi retrieved successfully');
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID visi misi tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'idVisMisi');
        if (!$data) {
            $this->error('Visi misi tidak ditemukan', null, 404);
        }

        $this->success($data, 'Visi Misi retrieved successfully');
    }

    public function store() {
        $input = $this->getJson();
        $required = ['visi', 'misi'];
        $missing = $this->validateRequired($input, $required);

        if (!empty($missing)) {
            $this->error('Field required: ' . implode(', ', $missing), null, 400);
        }

        $result = $this->model->insert($input);
        if ($result) {
            $this->success(['id' => $this->model->getLastInsertId()], 'Visi Misi created successfully', 201);
        }
        $this->error('Failed to create visi misi', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID visi misi tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idVisMisi');
        if (!$existing) {
            $this->error('Visi misi tidak ditemukan', null, 404);
        }

        $input = $this->getJson();
        $result = $this->model->update($id, $input, 'idVisMisi');
        
        if ($result) {
            $this->success([], 'Visi Misi updated successfully');
        }
        $this->error('Failed to update visi misi', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID visi misi tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idVisMisi');
        if (!$existing) {
            $this->error('Visi misi tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idVisMisi');
        
        if ($result) {
            $this->success([], 'Visi Misi deleted successfully');
        }
        $this->error('Failed to delete visi misi', null, 500);
    }
}
?>
