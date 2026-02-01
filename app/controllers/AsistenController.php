<?php

/**
 * AsistenController - Orchestrator Data Asisten
 * * Controller ini bertugas menjembatani request HTTP dengan AsistenService.
 * Fokus pada navigasi, validasi input dasar, dan pemberian response.
 * * @package App\Controllers
 */

require_once ROOT_PROJECT . '/app/services/AsistenService.php';

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
        $asisten = $this->service->getFormattedDetail($id);

        if (!$asisten) return $this->redirect('/asisten');

        $this->view('sumberdaya/detail', ['id' => $id, 'asisten' => $asisten]);
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
}