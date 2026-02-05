<?php

/**
 * AsistenController - Orchestrator Data Asisten
 * * Controller ini bertugas menjembatani request HTTP dengan AsistenService.
 * Fokus pada navigasi, validasi input dasar, dan pemberian response.
 * * @package App\Controllers
 */

require_once ROOT_PROJECT . '/app/services/AsistenService.php';
require_once ROOT_PROJECT . '/app/models/AsistenModel.php';
require_once ROOT_PROJECT . '/app/services/DetailSumberDayaService.php';

class AsistenController extends Controller {
    private $service;
    private $model;

    public function __construct() {
        $this->service = new AsistenService();
        $this->model = new AsistenModel();
    }

    /**
     * Halaman Publik: Daftar Asisten Berdasarkan Kategori
     */
    public function index() {
        $groupedData = $this->service->getGroupedAsisten();
        $this->view('sumberdaya/asisten', array_merge(['judul' => 'Asisten Laboratorium'], $groupedData));
    }

    /**
     * Halaman Publik: Profil Detail Asisten
     */
    public function detail($params = []) {
        $id = $params['id'] ?? null;

        // Gunakan DetailSumberDayaService agar struktur data konsisten dengan view
        $detailService = new DetailSumberDayaService();
        $dataDetail = $detailService->getFormattedAsisten((int)$id);

        if (!$dataDetail) {
            return $this->redirect('/asisten');
        }

        $this->view('sumberdaya/detail', ['dataDetail' => $dataDetail]);
    }

    /**
     * Admin: Halaman Manajemen Asisten (Tabel CRUD)
     * Route: /admin/asisten
     */
    public function adminIndex(): void {
        // Admin view sudah ter-handle oleh admin/templates/header.php
        // Method ini hanya perlu di-define agar Router recognize route /admin/asisten
        // Actual view rendering di-handle oleh header.php auto-routing ke admin/asisten/index.php
        $this->view('admin/asisten/index', [
            'judul' => 'Manajemen Data Asisten'
        ]);
    }

    /**
     * Admin: tampilan tabel data asisten
     */

    public function apiIndex() {
        // Bersihkan output buffer agar JSON tidak rusak oleh warning/HTML
        if (ob_get_level()) ob_end_clean();

        try {
            header('Content-Type: application/json');
            $data = $this->model->getAll(); // Ambil semua data asisten

            echo json_encode([
                'status' => true,
                'message' => 'Data asisten berhasil diambil',
                'data' => $data
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    /**
     * Admin: Simpan Asisten Baru (POST)
     */
    public function store() {
        $input = $_POST;

        if (empty($input['nama']) || empty($input['email'])) {
            return $this->error('Nama dan Email wajib diisi', null, 400);
        }

        // Proses Skills & Foto via Service
        $input['skills'] = $this->service->formatSkillsForDb($input['skills'] ?? '[]');

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $photoPath = $this->service->uploadPhoto($_FILES['foto'], $input['nama']);
            if ($photoPath) $input['foto'] = $photoPath;
        }

        return $this->model->insert($input)
            ? $this->success(['id' => $this->model->getLastInsertId()], 'Berhasil ditambahkan', 201)
            : $this->error('Gagal menyimpan ke database');
    }

    /**
     * Admin: Update Data Asisten (PUT/POST)
     */
    public function update($params) {
        $id = $params['id'] ?? null;
        $existing = $this->model->getById($id);
        if (!$existing) return $this->error('Data tidak ditemukan', null, 404);

        $input = $_POST;
        $input['skills'] = $this->service->formatSkillsForDb($input['skills'] ?? '[]');

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            // Hapus foto lama jika ada upload baru
            if (!empty($existing['foto'])) $this->service->deletePhoto($existing['foto']);
            
            $photoPath = $this->service->uploadPhoto($_FILES['foto'], $input['nama']);
            if ($photoPath) $input['foto'] = $photoPath;
        }

        return $this->model->update($id, $input, 'idAsisten')
            ? $this->success(null, 'Update berhasil')
            : $this->error('Gagal update database');
    }

    /**
     * Admin: Hapus Asisten & Foto
     */
    public function delete($params) {
        $id = $params['id'] ?? null;
        $asisten = $this->model->getById($id);

        if ($asisten && !empty($asisten['foto'])) {
            $this->service->deletePhoto($asisten['foto']);
        }

        return $this->model->delete($id, 'idAsisten')
            ? $this->success(null, 'Berhasil dihapus')
            : $this->error('Gagal menghapus');
    }

    /**
     * Admin: Set Satu Koordinator Utama
     */
    public function setKoordinator($params) {
        $id = $params['id'] ?? null;
        if (!$id) return $this->error('ID tidak valid');

        $this->model->resetAllKoordinator();
        return $this->model->update($id, ['isKoordinator' => 1], 'idAsisten')
            ? $this->success(null, 'Koordinator diperbarui')
            : $this->error('Gagal memperbarui koordinator');
    }

    /**
     * API: Get Current Koordinator
     */
    public function getCoordinator() {
        $coordinator = $this->model->getByColumn('isKoordinator', 1);
        
        if ($coordinator) {
            return $this->success($coordinator, 'Koordinator ditemukan');
        } else {
            return $this->success(null, 'Belum ada koordinator');
        }
    }

    /**
     * API: Set Koordinator via JSON
     */
    public function setCoordinator() {
        $input = $this->getJson() ?? [];
        $idAsisten = $input['idAsisten'] ?? null;

        if (!$idAsisten) {
            return $this->error('ID Asisten tidak valid', null, 400);
        }

        // Verify asisten exists
        $asisten = $this->model->getById($idAsisten);
        if (!$asisten) {
            return $this->error('Asisten tidak ditemukan', null, 404);
        }

        try {
            // Reset all other koordinators
            $this->model->resetAllKoordinator();
            
            // Set new coordinator
            $updated = $this->model->update($idAsisten, ['isKoordinator' => 1], 'idAsisten');
            
            if ($updated) {
                return $this->success(null, 'Koordinator berhasil dipilih');
            } else {
                return $this->error('Gagal menyimpan perubahan', null, 500);
            }
        } catch (Exception $e) {
            return $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }
}