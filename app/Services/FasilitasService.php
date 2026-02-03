<?php

/**
 * FasilitasService - Pusat Logika Bisnis Fasilitas & Laboratorium
 * * Menangani pengolahan data teknis, manajemen galeri foto, 
 * dan informasi koordinator untuk Laboratorium Praktikum serta Ruang Riset.
 * * @package App\Services
 */

require_once ROOT_PROJECT . '/app/models/FasilitasModel.php';
require_once ROOT_PROJECT . '/app/models/LaboratoriumGambarModel.php';
require_once ROOT_PROJECT . '/app/models/AsistenModel.php';
require_once ROOT_PROJECT . '/app/helpers/Helper.php';

class FasilitasService {
    private $model;
    private $gambarModel;
    private $asistenModel;

    public function __construct() {
        $this->model = new FasilitasModel();
        $this->gambarModel = new LaboratoriumGambarModel();
        $this->asistenModel = new AsistenModel();
    }

    // =========================================================================
    // READ OPERATIONS (PUBLIC & ADMIN)
    // =========================================================================

    /**
     * Mengambil daftar fasilitas dengan thumbnail dan deskripsi singkat.
     * * @return array
     */
    public function getFasilitasWithThumbnails(): array {
        $data = $this->model->getAll();
        
        foreach ($data as &$row) {
            $row['img_src'] = $this->resolveThumbnail($row);
            $row['short_desc'] = Helper::limitText($row['deskripsi'] ?? '', 150);
        }
        
        return $data;
    }

    /**
     * Mengambil data fasilitas berdasarkan kategori (Contoh: 'Lab' atau 'Riset').
     * * Digunakan oleh Controller untuk memisahkan halaman Lab Praktikum dan Ruang Riset.
     * * @param string $keyword Kata kunci filter (jenis).
     * @return array
     */
    public function getFasilitasByJenis(string $keyword): array {
        $allData = $this->getFasilitasWithThumbnails();
        
        $filtered = array_filter($allData, function($item) use ($keyword) {
            // Cek apakah kolom jenis mengandung kata kunci (tidak sensitif huruf besar/kecil)
            return stripos($item['jenis'] ?? '', $keyword) !== false;
        });

        // Reset index array agar urutan 0, 1, 2...
        return array_values($filtered);
    }

    /**
     * Ambil daftar Ruang Riset yang sudah dinormalisasi untuk tampilan publik.
     * Memastikan field seperti img_src_final, deskripsi_final dan style_final tersedia.
     * @return array
     */
    public function getRisetFacilities(): array {
        $riset = $this->getFasilitasByJenis('riset');

        foreach ($riset as &$row) {
            $row['img_src_final'] = $row['img_src'] ?? null;
            $row['deskripsi_final'] = $row['short_desc'] ?? $row['deskripsi'] ?? '';
            $row['style_final'] = $row['style_final'] ?? [
                'bg' => '#f8fafc', 'icon' => 'ri-flask-line', 'color' => '#64748b', 'badge_bg' => '#f1f5f9', 'badge_text' => '#475569'
            ];
        }

        return $riset;
    }

    /**
     * Menyusun data detail lengkap untuk satu fasilitas.
     * * @param int $id ID Laboratorium.
     * @return array|null
     */
    public function getFullDetail(int $id): ?array {
        $lab = $this->model->getById($id, 'idLaboratorium');
        if (!$lab) return null;

        return [
            'lab'         => $lab,
            'gallery'     => $this->buildGallery($lab, (int)$id),
            'koordinator' => $this->resolveKoordinator($lab['idKordinatorAsisten'] ?? null),
            'hardware'    => $this->formatHardwareSpecs($lab),
            'software'    => $this->parseCsvList($lab['software'] ?? ''),
            'pendukung'   => $this->parseCsvList($lab['fasilitas_pendukung'] ?? '')
        ];
    }

    // =========================================================================
    // WRITE OPERATIONS (ADMIN)
    // =========================================================================

    /**
     * Menghapus gambar, file fisik, dan memperbarui thumbnail jika perlu.
     * * @param int $idGambar
     * @return bool
     * @throws Exception
     */
    public function deleteImageWithLogic(int $idGambar): bool {
        $gambar = $this->gambarModel->getById($idGambar, 'idGambar');
        if (!$gambar) throw new Exception("Data gambar tidak ditemukan.");

        $labId = $gambar['idLaboratorium'];
        $filename = $gambar['namaGambar'];

        // 1. Hapus file fisik
        $filePath = ROOT_PROJECT . '/public/assets/uploads/' . $filename;
        if (file_exists($filePath)) @unlink($filePath);

        // 2. Hapus dari DB Galeri
        $this->gambarModel->deleteImage($idGambar);

        // 3. Update thumbnail utama jika yang dihapus adalah foto profil
        $lab = $this->model->getById($labId, 'idLaboratorium');
        if ($lab && ($lab['gambar'] ?? '') === $filename) {
            $sisaFoto = $this->gambarModel->getByLaboratorium($labId);
            $pengganti = !empty($sisaFoto) ? $sisaFoto[0]['namaGambar'] : null;
            $this->model->update($labId, ['gambar' => $pengganti], 'idLaboratorium');
        }

        return true;
    }

    /**
     * Menghapus total fasilitas beserta seluruh aset gambarnya.
     * * @param int $idLaboratorium
     */
    public function deleteFasilitasComplete(int $idLaboratorium): void {
        $gallery = $this->gambarModel->getByLaboratorium($idLaboratorium);
        
        foreach ($gallery as $img) {
            $this->deleteImageWithLogic((int)$img['idGambar']);
        }

        $this->model->delete($idLaboratorium, 'idLaboratorium');
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    private function resolveThumbnail(array $lab): ?string {
        $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : '';
        
        if (!empty($lab['gambar'])) {
            return $baseUrl . '/assets/uploads/' . $lab['gambar'];
        }
        
        $gallery = $this->gambarModel->getByLaboratorium($lab['idLaboratorium']);
        return !empty($gallery) ? $baseUrl . '/assets/uploads/' . $gallery[0]['namaGambar'] : null;
    }

    private function formatHardwareSpecs(array $lab): array {
        return array_filter([
            'Processor' => $lab['processor'] ?? '',
            'RAM'       => $lab['ram'] ?? '',
            'Storage'   => $lab['storage'] ?? '',
            'GPU'       => $lab['gpu'] ?? '',
            'Monitor'   => $lab['monitor'] ?? '',
            'Jumlah PC' => !empty($lab['jumlahPc']) ? $lab['jumlahPc'] . ' Unit' : null,
        ]);
    }

    private function resolveKoordinator(?int $idAsisten): array {
        $info = ['nama' => 'Staff Laboratorium', 'email' => null, 'foto' => null, 'initials' => 'SL'];
        if (!$idAsisten) return $info;

        $asisten = $this->asistenModel->getById($idAsisten, 'idAsisten');
        if ($asisten) {
            $info = [
                'nama'     => $asisten['nama'],
                'email'    => $asisten['email'],
                'initials' => Helper::getInitials($asisten['nama']),
                'foto'     => !empty($asisten['foto']) ? (defined('PUBLIC_URL') ? PUBLIC_URL : '') . '/assets/uploads/' . $asisten['foto'] : null
            ];
        }
        return $info;
    }

    private function buildGallery(array $lab, int $id): array {
        $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : '';
        $images = $this->gambarModel->getByLaboratorium($id);
        $gallery = [];

        foreach ($images as $img) {
            $gallery[] = [
                'id'      => $img['idGambar'],
                'src'     => $baseUrl . '/assets/uploads/' . $img['namaGambar'],
                'desc'    => $img['deskripsiGambar'] ?? '',
                'is_main' => ($img['namaGambar'] === ($lab['gambar'] ?? ''))
            ];
        }
        return $gallery;
    }

    private function parseCsvList(string $str): array {
        return !empty($str) ? array_map('trim', explode(',', $str)) : [];
    }
}