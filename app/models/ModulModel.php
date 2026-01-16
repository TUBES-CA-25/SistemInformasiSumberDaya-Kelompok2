<?php
class ModulModel {
    private $table = 'modul';
    private $pdo;

    public function __construct() {
        $db = new Database;
        $this->pdo = $db->getPdo();
    }

    public function getAllModul() {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table ORDER BY jurusan ASC, nama_matakuliah ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByJurusan($jurusan) {
        // Gunakan UPPER untuk menangani ketidaksamaan case di database
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE UPPER(jurusan) = UPPER(:j) ORDER BY nama_matakuliah ASC");
        $stmt->bindValue(':j', $jurusan);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getModulById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE id_modul = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function tambahModul($data, $file) {
        $fileName = $this->uploadFile($file);
        if (!$fileName) return 0;

        // Note: Deskripsi dikosongkan sesuai permintaan Anda yang ingin tampilan minimalis
        $stmt = $this->pdo->prepare("INSERT INTO $this->table (jurusan, nama_matakuliah, judul, deskripsi, file) VALUES (:jurusan, :mk, :judul, :desc, :file)");
        $stmt->bindValue(':jurusan', $data['jurusan']);
        $stmt->bindValue(':mk', $data['nama_matakuliah']);
        $stmt->bindValue(':judul', $data['judul']);
        $stmt->bindValue(':desc', $data['deskripsi'] ?? '');
        $stmt->bindValue(':file', $fileName);
        
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateModul($data, $file) {
        $modulLama = $this->getModulById($data['id_modul']);
        if (!$modulLama) return 0;
        
        $fileName = $modulLama['file'];

        // Cek jika ada file baru yang diunggah
        if (isset($file['error']) && $file['error'] === 0) {
            $newFile = $this->uploadFile($file);
            if ($newFile) {
                // Hapus file fisik lama
                $pathLama = $_SERVER['DOCUMENT_ROOT'] . '/SistemInformasiSumberDaya-Kelompok2/public/assets/uploads/modul/' . $modulLama['file'];
                if (file_exists($pathLama)) unlink($pathLama);
                $fileName = $newFile;
            }
        }

        $stmt = $this->pdo->prepare("UPDATE $this->table SET jurusan = :jurusan, nama_matakuliah = :mk, judul = :judul, deskripsi = :desc, file = :file WHERE id_modul = :id");
        $stmt->bindValue(':jurusan', $data['jurusan']);
        $stmt->bindValue(':mk', $data['nama_matakuliah']);
        $stmt->bindValue(':judul', $data['judul']);
        $stmt->bindValue(':desc', $data['deskripsi'] ?? '');
        $stmt->bindValue(':file', $fileName);
        $stmt->bindValue(':id', $data['id_modul']);
        
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteModul($id) {
        $data = $this->getModulById($id);
        if ($data) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/SistemInformasiSumberDaya-Kelompok2/public/assets/uploads/modul/' . $data['file'];
            if (file_exists($path)) unlink($path);
        }
        $stmt = $this->pdo->prepare("DELETE FROM $this->table WHERE id_modul = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    private function uploadFile($file) {
        if (!isset($file['name']) || empty($file['name'])) return false;

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'pdf') return false;
        
        // Buat nama unik agar tidak bentrok
        $newName = uniqid() . '.' . $ext;
        
        // Path absolut sesuai struktur folder proyek Anda
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/SistemInformasiSumberDaya-Kelompok2/public/assets/uploads/modul/';
        
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $targetDir . $newName)) {
            return $newName;
        }
        return false;
    }
}