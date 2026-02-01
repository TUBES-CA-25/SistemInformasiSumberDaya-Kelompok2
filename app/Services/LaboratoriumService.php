<?php

/**
 * LaboratoriumService - Business Logic Layer
 * Menangani pengolahan data laboratorium, galeri, dan koordinasi antar model.
 */
class LaboratoriumService {
    private $model;
    private $asistenModel;
    private $gambarModel;

    public function __construct() {
        $this->model = new LaboratoriumModel();
        $this->asistenModel = new AsistenModel();
        $this->gambarModel = new LaboratoriumGambarModel();
    }

    /**
     * Mengambil daftar laboratorium untuk publik dengan thumbnail yang valid.
     */
    public function getListForPublic(): array {
        $raw_data = $this->model->getAll();
        $lab_list = [];

        foreach ($raw_data as $row) {
            if (stripos(trim($row['jenis'] ?? ''), 'Lab') !== false) {
                $row['img_src'] = $this->resolveThumbnail($row);
                $row['short_desc'] = $this->limitDescription($row['deskripsi'] ?? '');
                $lab_list[] = $row;
            }
        }
        return $lab_list;
    }

    /**
     * Menyusun detail data lengkap laboratorium termasuk koordinator dan hardware.
     */
    public function getDetailedData(int $id): ?array {
        $lab = $this->model->getById($id, 'idLaboratorium');
        if (!$lab) return null;

        return [
            'laboratorium' => $lab,
            'gallery'      => $this->buildGallery($lab, $id),
            'hardware'     => $this->formatHardwareSpecs($lab),
            'software'     => $this->parseCsvString($lab['software'] ?? ''),
            'pendukung'    => $this->parseCsvString($lab['fasilitas_pendukung'] ?? ''),
            'koordinator'  => $this->getKoordinatorInfo($lab['idKordinatorAsisten'] ?? null)
        ];
    }

    /**
     * Logika untuk menghapus gambar galeri dan memperbarui thumbnail utama secara otomatis.
     */
    public function deleteImageWithLogic(int $idGambar): bool {
        $gambarToDelete = $this->gambarModel->getById($idGambar, 'idGambar');
        if (!$gambarToDelete) throw new Exception("Data gambar tidak ditemukan.");

        $labId = $gambarToDelete['idLaboratorium'];
        $filename = $gambarToDelete['namaGambar'];

        // 1. Hapus file fisik
        $filePath = ROOT_PROJECT . '/public/assets/uploads/' . $filename;
        if (file_exists($filePath)) @unlink($filePath);

        // 2. Hapus dari DB galeri
        $this->gambarModel->deleteImage($idGambar);

        // 3. Update thumbnail utama jika yang dihapus adalah foto profil lab
        $labInduk = $this->model->getById($labId, 'idLaboratorium');
        if ($labInduk && $labInduk['gambar'] === $filename) {
            $sisaFoto = $this->gambarModel->getByLaboratorium($labId);
            $pengganti = !empty($sisaFoto) ? $sisaFoto[0]['namaGambar'] : null;
            $this->model->update($labId, ['gambar' => $pengganti], 'idLaboratorium');
        }

        return true;
    }

    // --- Private Helpers ---

    private function resolveThumbnail(array $row): ?string {
        $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : '';
        if (!empty($row['gambar'])) return $baseUrl . '/assets/uploads/' . $row['gambar'];

        $backup = $this->gambarModel->getByLaboratorium($row['idLaboratorium']);
        return !empty($backup) ? $baseUrl . '/assets/uploads/' . $backup[0]['namaGambar'] : null;
    }

    private function limitDescription(string $text, int $limit = 150): string {
        return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
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

    private function parseCsvString(string $str): array {
        return !empty($str) ? array_map('trim', explode(',', $str)) : [];
    }

    private function getKoordinatorInfo(?int $idAsisten): array {
        $info = ['nama' => 'Koordinator Lab', 'email' => null, 'foto' => null, 'initials' => 'KL'];
        if (!$idAsisten) return $info;

        $asisten = $this->asistenModel->getById($idAsisten, 'idAsisten');
        if ($asisten) {
            $info['nama'] = $asisten['nama'];
            $info['email'] = $asisten['email'];
            $info['initials'] = Helper::getInitials($asisten['nama']); // Asumsi Helper sudah ada
            if (!empty($asisten['foto'])) {
                $info['foto'] = (defined('PUBLIC_URL') ? PUBLIC_URL : '') . '/assets/uploads/' . $asisten['foto'];
            }
        }
        return $info;
    }

    private function buildGallery(array $lab, int $id): array {
        $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : '';
        $images = $this->gambarModel->getByLaboratorium($id);
        $gallery = [];

        // Masukkan foto utama di urutan pertama
        if (!empty($lab['gambar'])) {
            $gallery[] = ['src' => $baseUrl . '/assets/uploads/' . $lab['gambar'], 'desc' => 'Foto Utama'];
        }

        foreach ($images as $img) {
            if ($img['namaGambar'] !== $lab['gambar']) {
                $gallery[] = [
                    'src' => $baseUrl . '/assets/uploads/' . $img['namaGambar'],
                    'desc' => $img['deskripsiGambar'] ?? ''
                ];
            }
        }
        return $gallery;
    }
}