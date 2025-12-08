<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/InformasiLabModel.php';

class InformasiLabController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new \InformasiLabModel();
    }

    public function index() {
        $data = $this->model->getAktif();
        $this->success($data, 'Data Informasi Lab retrieved successfully');
    }

    public function show($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID informasi tidak ditemukan', null, 400);
        }

        $data = $this->model->getById($id, 'id_informasi');
        if (!$data) {
            $this->error('Informasi tidak ditemukan', null, 404);
        }

        $this->success($data, 'Informasi retrieved successfully');
    }

    public function byType($params) {
        $type = $params['type'] ?? null;
        if (!$type) {
            $this->error('Tipe informasi tidak ditemukan', null, 400);
        }

        $data = $this->model->getInformasiByTipe($type);
        $this->success($data, 'Informasi by type retrieved successfully');
    }

    public function store() {
        $input = $this->getJson();
        $required = ['informasi', 'tipe_informasi'];
        $missing = $this->validateRequired($input, $required);

        if (!empty($missing)) {
            $this->error('Field required: ' . implode(', ', $missing), null, 400);
        }

        $result = $this->model->insert($input);
        if ($result) {
            $this->success(['id' => $this->model->getLastInsertId()], 'Informasi created successfully', 201);
        }
        $this->error('Failed to create informasi', null, 500);
    }

    public function update($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID informasi tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'id_informasi');
        if (!$existing) {
            $this->error('Informasi tidak ditemukan', null, 404);
        }

        $input = $this->getJson();
        $result = $this->model->update($id, $input, 'id_informasi');
        
        if ($result) {
            $this->success([], 'Informasi updated successfully');
        }
        $this->error('Failed to update informasi', null, 500);
    }

    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID informasi tidak ditemukan', null, 400);
        }

        $existing = $this->model->getById($id, 'id_informasi');
        if (!$existing) {
            $this->error('Informasi tidak ditemukan', null, 404);
        }

        $result = $this->model->delete($id, 'id_informasi');
        
        if ($result) {
            $this->success([], 'Informasi deleted successfully');
        }
        $this->error('Failed to delete informasi', null, 500);
    }
}
?>
