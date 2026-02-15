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
     * API endpoint untuk mendapatkan data satu asisten berdasarkan ID.
     * Digunakan untuk pre-populate form pada edit modal.
     * 
     * @param array $params Parameter route yang berisi 'id'
     * @return void JSON response dengan data asisten
     */
    public function apiShow($params = [])
    {
        if (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json');

        $id = $params['id'] ?? null;

        if (!$id) {
            echo json_encode([
                'status' => false,
                'code' => 400,
                'message' => 'ID asisten tidak diberikan',
                'data' => null
            ]);
            exit;
        }

        try {
            $asisten = $this->model->getById($id, 'id');

            if (!$asisten) {
                echo json_encode([
                    'status' => false,
                    'code' => 404,
                    'message' => 'Data asisten tidak ditemukan',
                    'data' => null
                ]);
                exit;
            }

            echo json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Data asisten berhasil diambil',
                'data' => $asisten
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'code' => 500,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => null
            ]);
            exit;
        }
    }

    /**
     * Admin: Simpan Asisten Baru (POST)
     */
    public function store() {
        header('Content-Type: application/json');
        
        $input = $_POST;
        
        // Remove non-database fields
        unset($input['_method']);

        if (empty($input['nama']) || empty($input['email'])) {
            return $this->error('Nama dan Email wajib diisi', null, 400);
        }

        try {
            // Proses Skills & Foto via Service
            $input['skills'] = $this->service->formatSkillsForDb($input['skills'] ?? '[]');

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $photoPath = $this->service->uploadPhoto($_FILES['foto'], $input['nama']);
                if ($photoPath) $input['foto'] = $photoPath;
            }

            $inserted = $this->model->insert($input);
            if ($inserted) {
                return $this->success(['id' => $this->model->getLastInsertId()], 'Asisten berhasil ditambahkan', 201);
            } else {
                return $this->error('Gagal menyimpan ke database', null, 500);
            }
        } catch (Exception $e) {
            return $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Admin: Update Data Asisten (PUT/POST)
     */
    public function update($params) {
        header('Content-Type: application/json');
        
        $id = $params['id'] ?? null;
        if (!$id) return $this->error('ID tidak valid', null, 400);

        $existing = $this->model->getById($id);
        if (!$existing) return $this->error('Data asisten tidak ditemukan', null, 404);

        try {
            $input = $_POST;
            
            // Remove non-database fields
            unset($input['_method']);
            
            $input['skills'] = $this->service->formatSkillsForDb($input['skills'] ?? '[]');

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                // Hapus foto lama jika ada upload baru
                if (!empty($existing['foto'])) $this->service->deletePhoto($existing['foto']);
                
                $photoPath = $this->service->uploadPhoto($_FILES['foto'], $input['nama']);
                if ($photoPath) $input['foto'] = $photoPath;
            }

            $updated = $this->model->update($id, $input, 'idAsisten');
            if ($updated) {
                return $this->success(null, 'Asisten berhasil diperbarui');
            } else {
                return $this->error('Gagal memperbarui data', null, 500);
            }
        } catch (Exception $e) {
            return $this->error('Error: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Admin: Hapus Asisten & Foto
     */
    public function delete($params) {
        $id = $params['id'] ?? null;
        if (!$id) return $this->error('ID tidak valid', null, 400);

        $asisten = $this->model->getById($id);
        
        if (!$asisten) return $this->error('Asisten tidak ditemukan', null, 404);

        // Delete associated photo
        if (!empty($asisten['foto'])) {
            $this->service->deletePhoto($asisten['foto']);
        }

        // Delete from database
        $deleted = $this->model->delete($id, 'idAsisten');
        
        return $deleted
            ? $this->success(null, 'Asisten berhasil dihapus')
            : $this->error('Gagal menghapus asisten', null, 500);
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