<?php

require_once CONTROLLER_PATH . '/Controller.php';
require_once ROOT_PROJECT . '/app/services/ManajemenService.php';
require_once ROOT_PROJECT . '/app/models/ManajemenModel.php';
require_once ROOT_PROJECT . '/app/services/DetailSumberDayaService.php';

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

    /**
     * Halaman Publik: Detail Kepala / Manajemen (clean route /kepala/{id})
     */
    public function detail($params = []) {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->redirect('/kepala');
            return;
        }

        $detailService = new DetailSumberDayaService();
        $dataDetail = $detailService->getFormattedManajemen((int)$id);

        if (!$dataDetail) {
            $this->redirect('/kepala');
            return;
        }

        $this->view('sumberdaya/detail', [
            'dataDetail' => $dataDetail,
            'judul' => 'Detail Pimpinan - ' . $dataDetail['nama']
        ]);
    }

    /**
     * API endpoint untuk mengambil data manajemen dalam format JSON.
     * * Digunakan oleh admin/manajemen.js untuk menampilkan data di tabel.
     * * @return void Mengirimkan JSON response dengan data manajemen
     */
    public function apiIndex() {
        // Bersihkan output buffer agar JSON tidak rusak oleh warning/HTML
        if (ob_get_level()) ob_end_clean();

        try {
            header('Content-Type: application/json');
            $data = $this->service->getAll(); // Ambil semua data manajemen

            echo json_encode([
                'status' => true,
                'message' => 'Data manajemen berhasil diambil',
                'data' => $data
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal mengambil data manajemen: ' . $e->getMessage(),
                'data' => null
            ]);
            exit;
        }
    }

    /**
     * API endpoint untuk mengambil detail satu data manajemen.
     * * @param array $params Parameter route yang berisi 'id'
     * @return void Mengirimkan JSON response dengan data manajemen
     */
    public function apiShow($params = []) {
        if (ob_get_level()) ob_end_clean();

        try {
            header('Content-Type: application/json');
            $id = $params['id'] ?? null;
            
            if (!$id) {
                echo json_encode([
                    'status' => false,
                    'message' => 'ID tidak ditemukan',
                    'data' => null
                ]);
                exit;
            }

            $data = $this->service->getById($id);
            
            if (!$data) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Data manajemen tidak ditemukan',
                    'data' => null
                ]);
                exit;
            }

            echo json_encode([
                'status' => true,
                'message' => 'Data manajemen berhasil diambil',
                'data' => $data
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal mengambil data manajemen: ' . $e->getMessage(),
                'data' => null
            ]);
            exit;
        }
    }
}
