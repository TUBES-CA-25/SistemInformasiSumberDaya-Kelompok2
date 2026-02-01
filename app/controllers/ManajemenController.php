<?php

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/services/ManajemenService.php';

/**
 * ManajemenController - Web & API Orchestrator
 * Menangani permintaan user dan mengembalikan tampilan atau data JSON.
 */
class ManajemenController extends Controller {
    private $service;

    public function __construct() {
        $this->service = new ManajemenService();
    }

    /**
     * Tampilan Publik: Struktur Organisasi
     */
    public function index() {
        $data = $this->service->getPublicStructure();
        $data['judul'] = 'Struktur Manajemen';
        $this->view('sumberdaya/kepala', $data);
    }

    /**
     * Dashboard Admin: List Manajemen
     */
    public function adminIndex() {
        $data = $this->service->getAll();
        $this->view('admin/manajemen/index', ['manajemen' => $data]);
    }

    /**
     * Admin: Proses Simpan Data (POST)
     */
    public function store() {
        try {
            $input = $_POST ?: ($this->getJson() ?? []);
            $required = ['nama', 'email', 'jabatan'];
            
            if ($missing = $this->validateRequired($input, $required)) {
                $this->error('Field required: ' . implode(', ', $missing), null, 400);
            }

            $file = $_FILES['foto'] ?? null;
            if ($this->service->storeManajemen($input, $file)) {
                $this->success(null, 'Manajemen created successfully', 201);
            }
            $this->error('Failed to create manajemen');
        } catch (\Exception $e) {
            $this->error($e->getMessage(), null, 500);
        }
    }

    /**
     * Admin: Proses Update Data (PUT/POST)
     */
    public function update($params) {
        try {
            $id = $params['id'] ?? null;
            $existing = $this->service->getById($id);
            if (!$existing) $this->error('Data tidak ditemukan', null, 404);

            $input = $_POST ?: ($this->getJson() ?? []);
            $file = $_FILES['foto'] ?? null;

            if ($this->service->updateManajemen($id, $input, $existing, $file)) {
                $this->success(null, 'Manajemen updated successfully');
            }
            $this->error('Failed to update manajemen');
        } catch (\Exception $e) {
            $this->error($e->getMessage(), null, 500);
        }
    }

    /**
     * Admin: Proses Hapus Data
     */
    public function delete($params) {
        $id = $params['id'] ?? null;
        if ($this->service->delete($id)) {
            $this->success(null, 'Manajemen deleted successfully');
        }
        $this->error('Failed to delete data');
    }

    /**
     * Form Renderers
     */
    public function create() {
        $this->view('admin/manajemen/form', ['manajemen' => null, 'action' => 'create']);
    }

    public function edit($params) {
        $id = $params['id'] ?? null;
        if ($data = $this->service->getById($id)) {
            $this->view('admin/manajemen/form', ['manajemen' => $data, 'action' => 'edit']);
        } else {
            $this->redirect('/admin/manajemen');
        }
    }
}