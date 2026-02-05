<?php

/**
 * Service Alumni
 * * Kelas ini menangani semua logika bisnis yang berkaitan dengan data alumni.
 * Memisahkan logika pemrosesan data, manajemen file (upload/delete), 
 * dan transformasi format dari Controller agar kode lebih modular dan reusable.
 * * @package App\Services
 */
class AlumniService
{
    /**
     * @var AlumniModel Instance model untuk akses database alumni
     */
    private $model;

    /**
     * Konstruktor AlumniService
     * Inisialisasi model database.
     */
    public function __construct()
    {
        $this->model = new AlumniModel();
    }

    /**
     * Mengambil dan mengelompokkan alumni berdasarkan tahun angkatan.
     * * Berguna untuk tampilan index publik agar alumni tampil terorganisir 
     * per tahun dari yang terbaru ke terlama.
     * * @return array Array multidimensi dengan key berupa tahun angkatan
     */
    public function getAlumniGroupedByYear()
    {
        // Ambil data mentah dari database
        $raw_data = $this->model->getAll();
        $grouped = [];

        if (!empty($raw_data) && is_array($raw_data)) {
            foreach ($raw_data as $row) {
                // Gunakan 'Unknown' jika kolom angkatan kosong
                $year = $row['angkatan'] ?? 'Unknown';
                $grouped[$year][] = $row;
            }
            // Urutkan key (tahun) secara Descending (Krsort)
            krsort($grouped);
        }
        return $grouped;
    }

    /**
     * Menyiapkan data profil alumni yang sudah diformat untuk halaman detail.
     * * Melakukan transformasi pada data mentah seperti:
     * - Pemrosesan URL gambar/avatar.
     * - Pembersihan string keahlian menjadi array.
     * - Penggabungan daftar mata kuliah menjadi string comma-separated.
     * * @param int|string $id ID alumni
     * @return array|null Mengembalikan array data matang atau null jika data tidak ditemukan
     */
    public function getFormattedDetail($id)
    {
        $alumni = $this->model->getById((int)$id, 'id');
        if (!$alumni) return null;

        return [
            'alumni'        => $alumni,
            'img_url'       => $this->processImage($alumni),
            'skills_list'   => $this->cleanArrayField($alumni['keahlian'] ?? ''),
            'matkul_string' => $this->cleanArrayField($alumni['mata_kuliah'] ?? '', true),
        ];
    }

    /**
     * Mengelola proses upload foto alumni ke direktori server.
     * * @param array $file Superglobal $_FILES['key']
     * @param string $name Nama alumni untuk dasar penamaan file
     * @return string|false Relatif path file jika berhasil, atau false jika gagal
     */
    public function uploadPhoto($file, $name)
    {
        $subFolder = 'alumni/';
        // Tentukan path absolut ke folder upload
        $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/' . $subFolder;

        // Pastikan direktori tujuan tersedia
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        // Ambil ekstensi dan buat nama file unik melalui Helper
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = Helper::generateFilename('alumni', $name, $ext);
        
        // Pindahkan file dari temp ke folder tujuan
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            return $subFolder . $filename;
        }
        return false;
    }

    /**
     * Menghapus file fisik foto dari penyimpanan server.
     * * @param string $path Relatif path file (dari database) yang akan dihapus
     * @return bool True jika berhasil dihapus atau file memang tidak ada
     */
    public function deletePhoto($path)
    {
        $fullPath = dirname(__DIR__, 2) . '/public/assets/uploads/' . $path;
        if (file_exists($fullPath) && is_file($fullPath)) {
            return @unlink($fullPath);
        }
        return false;
    }

    // ============================================
    // HELPER LOGIC INTERNAL (Private)
    // ============================================

    /**
     * Memproses logika pemilihan gambar profil.
     * Jika foto ada: cek apakah URL eksternal atau file lokal.
     * Jika foto kosong: buatkan URL avatar inisial otomatis.
     * * @param array $alumni Data baris alumni
     * @return string URL gambar siap pakai
     */
    private function processImage($alumni)
    {
        $dbFoto = $alumni['foto'] ?? '';
        
        // Kasus 1: Tidak ada foto, generate avatar placeholder
        if (empty($dbFoto)) {
            return "https://ui-avatars.com/api/?name=" . urlencode($alumni['nama']) . "&size=512&background=random";
        }
        
        // Kasus 2: Foto adalah link eksternal (misal: https://...)
        if (strpos($dbFoto, 'http') === 0) return $dbFoto;
        
        // Kasus 3: Foto adalah file lokal di server
        $baseUrl = defined('PUBLIC_URL') ? PUBLIC_URL : '';
        return $baseUrl . '/assets/uploads/' . $dbFoto;
    }

    /**
     * Membersihkan string "sampah" hasil input array yang disimpan sebagai teks.
     * Menghapus karakter seperti [, ], ", \, dsb.
     * * @param string $rawData String mentah dari database
     * @param bool $asString Jika true, kembalikan sebagai string comma-separated. Jika false, array.
     * @return mixed Array atau String
     */
    private function cleanArrayField($rawData, $asString = false)
    {
        // Regex atau str_replace untuk membersihkan karakter bracket dan quote
        $cleaned = str_replace(['[', ']', '"', "'", '\\'], '', $rawData);
        
        // Pecah menjadi array dan bersihkan whitespace di setiap elemen
        $items = array_filter(array_map('trim', explode(',', $cleaned)));
        
        return $asString ? implode(', ', $items) : $items;
    }
}