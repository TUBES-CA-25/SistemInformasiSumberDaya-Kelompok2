<?php

/**
 * SopModel Class
 * * Mengelola data Standar Operasional Prosedur (SOP) di Laboratorium.
 * Bertanggung jawab atas operasi CRUD ke database serta manajemen file PDF fisik.
 * * @package App\Models
 */

require_once __DIR__ . '/../config/Database.php';

class SopModel {
    /** @var string Nama tabel di database */
    private $table = 'sop';
    
    /** @var PDO Instance koneksi database */
    private $pdo;

    /**
     * Inisialisasi koneksi database menggunakan objek PDO.
     */
    public function __construct() {
        $db = new Database;
        $this->pdo = method_exists($db, 'getPdo') ? $db->getPdo() : $db;
    }

    /**
     * Mengambil seluruh daftar SOP.
     * * @return array Kumpulan data SOP dalam format array asosiatif.
     */
    public function getAllSop(): array {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil detail satu SOP berdasarkan ID.
     * * @param int|string $id ID SOP yang dicari.
     * @return array|null Data SOP atau null jika tidak ditemukan.
     */
    public function getSopById($id): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE id_sop = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Menambahkan data SOP baru beserta unggahan file PDF.
     * * @param array $data Data input (judul, deskripsi).
     * @param array $fileInfo Data dari $_FILES['file'].
     * @return int Jumlah baris yang terpengaruh (rowCount).
     * @throws Exception Jika validasi atau upload gagal.
     */
    public function tambahDataSop(array $data, array $fileInfo): int {
        try {
            // Proses Upload File
            $fileName = $this->uploadFile($fileInfo, $data['judul']);
            
            $sql = "INSERT INTO {$this->table} (judul, file, deskripsi) VALUES (:judul, :file, :deskripsi)";
            $stmt = $this->pdo->prepare($sql);
            
            $stmt->bindValue(':judul', $data['judul']);
            $stmt->bindValue(':file', $fileName);
            $stmt->bindValue(':deskripsi', $data['deskripsi'] ?? '');

            $stmt->execute();
            return $stmt->rowCount();
        } catch (Exception $e) {
            error_log("SopModel::tambahDataSop Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Memperbarui data SOP. Jika ada file baru, file lama akan dihapus.
     * * @param array $data Data input termasuk id_sop.
     * @param array $fileInfo Data dari $_FILES['file'].
     * @return int
     * @throws Exception
     */
    public function updateDataSop(array $data, array $fileInfo): int {
        try {
            $id = $data['id_sop'];
            $currentData = $this->getSopById($id);
            if (!$currentData) throw new Exception("Data SOP tidak ditemukan.");

            $fileName = $currentData['file'];
            $updateFile = (isset($fileInfo['error']) && $fileInfo['error'] === UPLOAD_ERR_OK);

            if ($updateFile) {
                // Hapus file lama jika ada file baru yang diunggah
                $this->hapusFileFisik($currentData['file']);
                $fileName = $this->uploadFile($fileInfo, $data['judul']);
            }

            $sql = "UPDATE {$this->table} SET judul = :judul, file = :file, deskripsi = :deskripsi WHERE id_sop = :id";
            $stmt = $this->pdo->prepare($sql);
            
            $stmt->bindValue(':judul', $data['judul']);
            $stmt->bindValue(':file', $fileName);
            $stmt->bindValue(':deskripsi', $data['deskripsi'] ?? '');
            $stmt->bindValue(':id', $id);

            $stmt->execute();
            return $stmt->rowCount();
        } catch (Exception $e) {
            error_log("SopModel::updateDataSop Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Menghapus data SOP dan file fisiknya dari server.
     * * @param int|string $id
     * @return int
     */
    public function deleteSop($id): int {
        $data = $this->getSopById($id);
        if ($data) {
            $this->hapusFileFisik($data['file']);
        }

        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_sop = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Helper: Menangani proses upload file PDF secara aman.
     * * 
     * * @param array $file Data $_FILES.
     * @param string|null $judul Digunakan untuk menamai file secara otomatis.
     * @return string Nama file baru yang berhasil disimpan.
     * @throws Exception Jika validasi file gagal.
     */
    private function uploadFile(array $file, ?string $judul = null): string {
        // 1. Validasi Error Upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error upload: " . ($this->getUploadErrorMessage($file['error'])));
        }

        // 2. Validasi Ekstensi & Ukuran (Max 5MB)
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'pdf') throw new Exception("Format file harus PDF.");
        if ($file['size'] > 5000000) throw new Exception("File maksimal 5MB.");

        // 3. Sanitasi Nama File berdasarkan Judul
        $cleanTitle = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', trim($judul ?? 'sop')));
        $newName = $cleanTitle . '_' . time() . '.pdf';

        // 4. Penentuan Path Tujuan
        $targetDir = (defined('ROOT_PROJECT') ? ROOT_PROJECT : __DIR__ . '/../..') . '/public/assets/uploads/pdf/';
        
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        if (!is_writable($targetDir)) throw new Exception("Folder penyimpanan tidak dapat diakses.");

        if (!move_uploaded_file($file['tmp_name'], $targetDir . $newName)) {
            throw new Exception("Gagal memindahkan file ke server.");
        }

        return $newName;
    }

    /**
     * Helper: Menghapus file fisik dari direktori uploads.
     * * @param string|null $fileName
     */
    private function hapusFileFisik(?string $fileName): void {
        if (empty($fileName)) return;
        $targetDir = (defined('ROOT_PROJECT') ? ROOT_PROJECT : __DIR__ . '/../..') . '/public/assets/uploads/pdf/';
        $path = $targetDir . $fileName;
        if (file_exists($path)) @unlink($path);
    }

    /**
     * Helper: Mendapatkan pesan error upload yang human-readable.
     */
    private function getUploadErrorMessage(int $errorCode): string {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'Ukuran melebihi upload_max_filesize di server.',
            UPLOAD_ERR_FORM_SIZE => 'Ukuran melebihi MAX_FILE_SIZE di form.',
            UPLOAD_ERR_NO_FILE => 'Tidak ada file yang dipilih.',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk.',
        ];
        return $errors[$errorCode] ?? 'Kesalahan upload tidak diketahui.';
    }
}