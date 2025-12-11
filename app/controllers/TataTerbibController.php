<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/TataTerbibModel.php';

class TataTerbibController extends Controller {
    private $model;
    private $upload_dir = '/public/uploads/tata-tertib/';

    public function __construct() {
        $this->model = new \TataTerbibModel();
    }

    public function index() {
        $data = $this->model->getAll();
        $this->success($data, 'Data Tata Tertib retrieved successfully');
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tata tertib tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'idTataTerib');
        if (!$data) {
            $this->error('Tata Tertib tidak ditemukan', null, 404);
        }

        $this->success($data, 'Tata Tertib retrieved successfully');
    }

    public function store() {
        $input = $this->getJson();
        $required = ['namaFile'];
        $missing = $this->validateRequired($input, $required);

        if (!empty($missing)) {
            $this->error('Field required: ' . implode(', ', $missing), null, 400);
        }

        $result = $this->model->insert($input);
        if ($result) {
            $this->success(['id' => $this->model->getLastInsertId()], 'Tata Tertib created successfully', 201);
        }
        $this->error('Failed to create tata tertib', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tata tertib tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idTataTerib');
        if (!$existing) {
            $this->error('Tata Tertib tidak ditemukan', null, 404);
        }

        $input = $this->getJson();
        $result = $this->model->update($id, $input, 'idTataTerib');
        
        if ($result) {
            $this->success([], 'Tata Tertib updated successfully');
        }
        $this->error('Failed to update tata tertib', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID tata tertib tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'idTataTerib');
        if (!$existing) {
            $this->error('Tata Tertib tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'idTataTerib');
        
        if ($result) {
            $this->success([], 'Tata Tertib deleted successfully');
        }
        $this->error('Failed to delete tata tertib', null, 500);
    }
}
?>
