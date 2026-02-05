<?php

/**
 * FormatPenulisanService - Logika Bisnis Format Penulisan
 * * Menangani aturan validasi kategori, manajemen upload file,
 * dan penyiapan data untuk kebutuhan tampilan.
 */
class FormatPenulisanService {
    private $model;
    private $uploadPath;

    public function __construct() {
        $this->model = new FormatPenulisanModel();
        $this->uploadPath = ROOT_PROJECT . '/public/assets/uploads/format_penulisan/';
    }

    /**
     * Mengambil semua format penulisan yang sudah diurutkan.
     */
    public function getAllFormat() {
        return $this->model->getAllFormat();
    }

    /**
     * Menyiapkan data untuk disimpan (Create/Update).
     * Termasuk penanganan upload file dan validasi kategori.
     */
    public function prepareData($input, $files, $existing = null) {
        // Validasi Kategori: Deskripsi wajib jika kategori 'pedoman'
        if (($input['kategori'] ?? '') === 'pedoman' && empty($input['deskripsi'])) {
            throw new Exception('Deskripsi wajib diisi untuk kategori Pedoman');
        }

        $data = [
            'judul' => $input['judul'],
            'kategori' => $input['kategori'],
            'icon' => $input['icon'] ?? 'ri-file-text-line',
            'warna' => $input['warna'] ?? '#3498db',
            'deskripsi' => $input['deskripsi'] ?? '',
            'urutan' => $input['urutan'] ?? 0,
            'link_external' => $input['link_external'] ?? null,
            'tanggal_update' => date('Y-m-d')
        ];

        // Handle File Upload
        if (isset($files['file']) && $files['file']['error'] === UPLOAD_ERR_OK) {
            $data['file'] = $this->handleFileUpload($files['file'], $data['judul'], $existing['file'] ?? null);
        }

        return $data;
    }

    /**
     * Proses upload file dan penghapusan file lama.
     */
    private function handleFileUpload($file, $judul, $oldFile = null) {
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }

        // Hapus file lama jika ada (untuk update)
        if ($oldFile && file_exists($this->uploadPath . $oldFile)) {
            @unlink($this->uploadPath . $oldFile);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = Helper::generateFilename('format', $judul, $ext);
        
        if (move_uploaded_file($file['tmp_name'], $this->uploadPath . $fileName)) {
            return $fileName;
        }
        
        throw new Exception('Gagal mengupload file ke server');
    }

    /**
     * Menghapus record dan file fisik terkait.
     */
    public function deleteWithFile($id) {
        $existing = $this->model->getById($id, 'id_format');
        if (!$existing) return false;

        if (!empty($existing['file'])) {
            @unlink($this->uploadPath . $existing['file']);
        }

        return $this->model->delete($id, 'id_format');
    }
}