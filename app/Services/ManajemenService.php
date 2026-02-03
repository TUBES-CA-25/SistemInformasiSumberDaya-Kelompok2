<?php

/**
 * ManajemenService - Business Logic Layer
 * Menangani logika bisnis untuk data Manajemen (Kepala Lab & Laboran).
 */

require_once ROOT_PROJECT . '/app/helpers/Helper.php';
class ManajemenService {
    private $model;
    private $uploadPath;

    public function __construct() {
        $this->model = new ManajemenModel();
        $this->uploadPath = ROOT_PROJECT . '/public/assets/uploads/manajemen/';
    }

    /**
     * Mengambil data untuk tampilan publik, dipisahkan berdasarkan jabatan.
     */
    public function getPublicStructure() {
        $all_data = $this->model->getAll();
        $structure = [
            'pimpinan' => [],
            'laboran' => []
        ];

        foreach ($all_data as $row) {
            $row['foto_url'] = Helper::processPhotoUrl($row['foto'] ?? '', $row['nama'] ?? '');
            
            if (stripos(($row['jabatan'] ?? ''), 'Kepala') !== false) {
                $structure['pimpinan'][] = $row;
            } else {
                $structure['laboran'][] = $row;
            }
        }
        return $structure;
    }

    /**
     * Memproses penyimpanan data manajemen baru termasuk upload foto.
     */
    public function storeManajemen($input, $file = null) {
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $input['foto'] = $this->uploadFile($file, $input['nama']);
        }

        return $this->model->insert($input);
    }

    /**
     * Memproses pembaharuan data manajemen dan penggantian foto lama.
     */
    public function updateManajemen($id, $input, $existing, $file = null) {
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            // Hapus foto lama jika ada
            if (!empty($existing['foto'])) {
                $oldFile = ROOT_PROJECT . '/public/assets/uploads/' . $existing['foto'];
                if (file_exists($oldFile)) @unlink($oldFile);
            }
            $input['foto'] = $this->uploadFile($file, $input['nama']);
        }

        return $this->model->update($id, $input, 'idManajemen');
    }

    /**
     * Helper untuk menangani proses upload file fisik.
     */
    private function uploadFile($file, $name) {
        if (!is_dir($this->uploadPath)) mkdir($this->uploadPath, 0777, true);

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = Helper::generateFilename('manajemen', $name, $ext);
        
        if (move_uploaded_file($file['tmp_name'], $this->uploadPath . $filename)) {
            return 'manajemen/' . $filename;
        }
        return '';
    }

    // Proxy methods ke Model
    public function getAll() { return $this->model->getAll(); }
    public function getById($id) { return $this->model->getById($id, 'idManajemen'); }
    public function delete($id) { return $this->model->delete($id, 'idManajemen'); }
}