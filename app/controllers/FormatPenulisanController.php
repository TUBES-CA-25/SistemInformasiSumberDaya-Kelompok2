<?php

require_once ROOT_PROJECT . '/app/services/FormatPenulisanService.php';
require_once ROOT_PROJECT . '/app/models/FormatPenulisanModel.php';

/**
 * FormatPenulisanController - Orchestrator Manajemen Format Penulisan
 * * Mengatur koordinasi antara request user dan layanan FormatPenulisanService.
 */
class FormatPenulisanController extends Controller {
    private $service;
    private $model;

    public function __construct() {
        $this->service = new FormatPenulisanService();
        $this->model = new FormatPenulisanModel();
    }

    // =========================================================================
    // RUTE VIEW
    // =========================================================================

    public function index(): void {
        $this->view('praktikum/format_penulisan', [
            'informasi' => $this->service->getAllFormat()
        ]);
    }

    public function adminIndex(): void {
        $this->view('admin/formatpenulisan/index');
    }

    // =========================================================================
    // API ENDPOINTS
    // =========================================================================

    public function apiIndex(): void {
        $this->success($this->service->getAllFormat(), 'Data berhasil diambil');
    }

    public function store(): void {
        try {
            $input = !empty($_POST) ? $_POST : ($this->getJson() ?? []);
            
            // Validasi Input Dasar
            $missing = $this->validateRequired($input, ['judul', 'kategori']);
            if (!empty($missing)) {
                $this->error('Field wajib: ' . implode(', ', $missing), null, 400);
                return;
            }

            $data = $this->service->prepareData($input, $_FILES);
            
            if ($this->model->insert($data)) {
                $this->success(['id' => $this->model->getLastInsertId()], 'Data berhasil dibuat', 201);
            }
        } catch (Exception $e) {
            $this->error($e->getMessage(), null, 500);
        }
    }

    public function update($params): void {
        try {
            $id = $params['id'] ?? null;
            $existing = $this->model->getById($id, 'id_format');
            
            if (!$existing) {
                $this->error('Data tidak ditemukan', null, 404);
                return;
            }

            $input = !empty($_POST) ? $_POST : ($this->getJson() ?? []);
            $data = $this->service->prepareData($input, $_FILES, $existing);

            if ($this->model->update($id, $data, 'id_format')) {
                $this->success([], 'Data berhasil diupdate');
            }
        } catch (Exception $e) {
            $this->error($e->getMessage(), null, 500);
        }
    }

    public function delete($params): void {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->error('ID diperlukan', null, 400);
            return;
        }

        if ($this->service->deleteWithFile($id)) {
            $this->success([], 'Data berhasil dihapus');
        } else {
            $this->error('Gagal menghapus data', null, 500);
        }
    }
}