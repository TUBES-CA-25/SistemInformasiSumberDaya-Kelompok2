<?php

/**
 * AsistenService - Logika Bisnis Manajemen Asisten
 * * Menangani pengolahan data asisten yang kompleks:
 * - Kategorisasi asisten (Koordinator, CA, Alumni, Aktif)
 * - Transformasi URL foto (Local, Legacy, External, UI-Avatars)
 * - Normalisasi data skills (JSON/Array/String)
 * - Manajemen File (Upload & Hapus foto)
 * * @package App\Services
 */
class AsistenService {
    private $model;

    public function __construct() {
        $this->model = new AsistenModel();
    }

    /**
     * Mengambil semua asisten dan mengelompokkannya berdasarkan peran/status.
     * * @return array Array asosiatif berisi list kategori
     */
    public function getGroupedAsisten() {
        $all_data = $this->model->getAll();
        
        $groups = [
            'koordinator' => [],
            'asisten'     => [],
            'ca'          => [],
            'alumni'      => []
        ];

        if (empty($all_data)) return $groups;

        foreach ($all_data as $row) {
            $row['foto_url'] = $this->processPhotoUrl($row);
            $status = strtolower($row['statusAktif'] ?? '');
            $isCoord = $row['isKoordinator'] ?? 0;

            if ($isCoord == 1) {
                $groups['koordinator'][] = $row;
            } elseif ($status == 'ca' || strpos($status, 'calon') !== false) {
                $groups['ca'][] = $row;
            } elseif ($status == 'alumni') {
                $groups['alumni'][] = $row;
            } else {
                $groups['asisten'][] = $row;
            }
        }

        return $groups;
    }

    /**
     * Menyiapkan data rinci asisten untuk halaman detail.
     * * @param int $id
     * @return array|null
     */
    public function getFormattedDetail($id) {
        $asisten = $this->model->getById($id);
        if (!$asisten) return null;

        $asisten['foto_url'] = $this->processPhotoUrl($asisten);
        $asisten['skills_list'] = $this->parseSkills($asisten['skills'] ?? '[]');

        return $asisten;
    }

    /**
     * Menangani logika upload foto asisten.
     * * @param array $file Superglobal $_FILES
     * @param string $identifier Nama asisten untuk filename
     * @return string|false Path relatif file jika sukses
     */
    public function uploadPhoto($file, $identifier) {
        $folderName = 'asisten';
        $subFolder = $folderName . '/';
        $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;

        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) return false;

        $filename = Helper::generateFilename($folderName, $identifier, $ext);
        
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            return $subFolder . $filename;
        }

        return false;
    }

    /**
     * Menghapus file foto dari server.
     */
    public function deletePhoto($path) {
        $fullPath = dirname(__DIR__, 2) . '/public/assets/uploads/' . $path;
        if (file_exists($fullPath) && is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    /**
     * Normalisasi input skills ke format JSON untuk database.
     */
    public function formatSkillsForDb($skills) {
        if (is_array($skills)) return json_encode($skills);
        if (is_string($skills) && !empty($skills) && $skills[0] !== '[') {
            return json_encode(array_map('trim', explode(',', $skills)));
        }
        return $skills;
    }

    /**
     * Memproses logika fallback URL foto.
     */
    private function processPhotoUrl($row) {
        $fotoName = $row['foto'] ?? '';
        $namaEnc = urlencode($row['nama'] ?? 'User');
        $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : '';

        // Fallback UI Avatars
        $default = "https://ui-avatars.com/api/?name={$namaEnc}&background=eff6ff&color=2563eb&size=256&bold=true";

        if (empty($fotoName) || strpos($fotoName, 'ui-avatars') !== false) return $default;
        if (strpos($fotoName, 'http') === 0) return $fotoName;

        // Cek path lokal (Struktur baru & legacy)
        $pathNew = '/public/assets/uploads/' . $fotoName;
        $pathOld = '/public/images/asisten/' . $fotoName;

        if (file_exists(ROOT_PROJECT . $pathNew)) return $baseUrl . '/assets/uploads/' . $fotoName;
        if (file_exists(ROOT_PROJECT . $pathOld)) return $baseUrl . '/images/asisten/' . $fotoName;

        return $default;
    }

    /**
     * Konversi string/JSON skills ke array bersih.
     */
    private function parseSkills($skillsRaw) {
        $decoded = json_decode($skillsRaw, true);
        if (is_array($decoded)) return array_filter($decoded);

        $cleaned = str_replace(['[', ']', '"'], '', $skillsRaw);
        return array_filter(array_map('trim', explode(',', $cleaned)));
    }
}