<?php

/**
 * ModulModel
 * * Model ini bertanggung jawab untuk mengelola data modul praktikum (PDF).
 * Menangani operasi CRUD, filter berdasarkan jurusan, serta manajemen file fisik.
 * * @package App\Models
 */
class ModulModel {
    /** @var string Nama tabel di database */
    private $table = 'modul';
    
    /** @var PDO Instance koneksi database */
    private $pdo;

    /**
     * Inisialisasi koneksi database menggunakan PDO.
     */
    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }

    /**
     * Mengambil semua data modul dari database.
     * * @return array Daftar modul diurutkan berdasarkan jurusan dan nama matakuliah.
     */
    public function getAllModul(): array {
        $sql = "SELECT * FROM {$this->table} ORDER BY jurusan ASC, nama_matakuliah ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Mengambil data modul berdasarkan jurusan tertentu.
     * Menggunakan fungsi UPPER untuk memastikan pencarian tidak sensitif terhadap huruf besar/kecil.
     * * @param string $jurusan Nama jurusan (Contoh: 'TI' atau 'SI').
     * @return array
     */
    public function getByJurusan(string $jurusan): array {
        $sql = "SELECT * FROM {$this->table} WHERE UPPER(jurusan) = UPPER(:j) ORDER BY nama_matakuliah ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':j', $jurusan);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Mengambil satu data modul berdasarkan ID.
     * * @param int|string $id ID modul yang dicari.
     * @return array|null Mengembalikan array data atau null jika tidak ditemukan.
     */
    public function getModulById($id): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE id_modul = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Menambahkan modul praktikum baru beserta file PDF-nya.
     * * @param array $data Data input (jurusan, nama_matakuliah, judul, deskripsi).
     * @param array $file Superglobal $_FILES['file'].
     * @return int Jumlah baris yang terpengaruh (1 jika berhasil).
     */
    public function tambahModul(array $data, array $file): int {
        $fileName = $this->uploadFile($file);
        if (!$fileName) return 0;

        $sql = "INSERT INTO {$this->table} (jurusan, nama_matakuliah, judul, deskripsi, file) 
                VALUES (:jurusan, :mk, :judul, :desc, :file)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':jurusan', $data['jurusan']);
        $stmt->bindValue(':mk', $data['nama_matakuliah']);
        $stmt->bindValue(':judul', $data['judul']);
        $stmt->bindValue(':desc', $data['deskripsi'] ?? '');
        $stmt->bindValue(':file', $fileName);
        
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Memperbarui data modul. Jika mengunggah file baru, file lama akan dihapus.
     * * @param array $data Data input termasuk id_modul.
     * @param array $file Superglobal $_FILES['file'].
     * @return int
     */
    public function updateModul(array $data, array $file): int {
        $modulLama = $this->getModulById($data['id_modul']);
        if (!$modulLama) return 0;
        
        $fileName = $modulLama['file'];

        // Logika Penggantian File
        if (isset($file['error']) && $file['error'] === 0) {
            $newFile = $this->uploadFile($file);
            if ($newFile) {
                $this->hapusFileFisik($modulLama['file']); // Hapus file lama
                $fileName = $newFile;
            }
        }

        $sql = "UPDATE {$this->table} SET 
                jurusan = :jurusan, nama_matakuliah = :mk, judul = :judul, 
                deskripsi = :desc, file = :file 
                WHERE id_modul = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':jurusan', $data['jurusan']);
        $stmt->bindValue(':mk', $data['nama_matakuliah']);
        $stmt->bindValue(':judul', $data['judul']);
        $stmt->bindValue(':desc', $data['deskripsi'] ?? '');
        $stmt->bindValue(':file', $fileName);
        $stmt->bindValue(':id', $data['id_modul']);
        
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Menghapus data modul dari database dan menghapus file PDF terkait dari server.
     * * @param int|string $id
     * @return int
     */
    public function deleteModul($id): int {
        $data = $this->getModulById($id);
        if ($data) {
            $this->hapusFileFisik($data['file']);
        }

        $sql = "DELETE FROM {$this->table} WHERE id_modul = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        return $stmt->rowCount();
    }

    /**
     * Helper: Menangani proses upload file ke server.
     * * @param array $file
     * @return string|false Nama file baru atau false jika gagal.
     */
    private function uploadFile(array $file) {
        if (!isset($file['name']) || empty($file['name'])) return false;

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'pdf') return false; // Validasi ekstensi khusus PDF
        
        $newName = "modul_" . uniqid() . '.' . $ext;
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/SistemInformasiSumberDaya-Kelompok2/public/assets/uploads/modul/';
        
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $targetDir . $newName)) {
            return $newName;
        }
        return false;
    }

    /**
     * Helper: Menghapus file secara fisik dari folder uploads.
     * * @param string $fileName
     * @return void
     */
    private function hapusFileFisik(string $fileName): void {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/SistemInformasiSumberDaya-Kelompok2/public/assets/uploads/modul/' . $fileName;
        if (file_exists($path) && !empty($fileName)) {
            unlink($path);
        }
    }
}