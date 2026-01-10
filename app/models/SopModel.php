<?php

class SopModel {
    private $table = 'sop';
    private $pdo;

    public function __construct() {
        // Kita ambil objek PDO asli dari class Database
        $db = new Database;
        if (method_exists($db, 'getPdo')) {
            $this->pdo = $db->getPdo();
        } else {
            // Fallback jika tidak ada method getPdo, mungkin extend PDO langsung
            $this->pdo = $db;
        }
    }

    public function getAllSop() {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table . " ORDER BY urutan ASC, created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSopById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table . " WHERE id_sop = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function tambahDataSop($data, $fileInfo) {
        // Upload File Logic
        $fileName = $this->uploadFile($fileInfo);
        if (!$fileName) return false;

        $query = "INSERT INTO " . $this->table . " 
                    (judul, icon, warna, file, deskripsi, urutan) 
                  VALUES 
                    (:judul, :icon, :warna, :file, :deskripsi, 0)";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':judul', $data['judul']);
        $stmt->bindValue(':icon', !empty($data['icon']) ? $data['icon'] : 'ri-file-text-line');
        $stmt->bindValue(':warna', !empty($data['warna']) ? $data['warna'] : 'icon-blue');
        $stmt->bindValue(':file', $fileName);
        $stmt->bindValue(':deskripsi', $data['deskripsi']);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateDataSop($data, $fileInfo) {
        // Default Query: Update tanpa ganti file
        $query = "UPDATE " . $this->table . " SET 
                    judul = :judul, 
                    icon = :icon, 
                    warna = :warna, 
                    deskripsi = :deskripsi 
                  WHERE id_sop = :id";
        
        $params = [
            ':judul' => $data['judul'],
            ':icon' => $data['icon'],
            ':warna' => $data['warna'],
            ':deskripsi' => $data['deskripsi'],
            ':id' => $data['id_sop'] // Ganti 'id' jadi 'id_sop' sesuai tabel
        ];

        // Cek jika ada file baru yang diupload
        if (isset($fileInfo['error']) && $fileInfo['error'] === 0) {
            $fileBaru = $this->uploadFile($fileInfo);
            if ($fileBaru) {
                $query = "UPDATE " . $this->table . " SET 
                            judul = :judul, 
                            icon = :icon, 
                            warna = :warna, 
                            file = :file, 
                            deskripsi = :deskripsi 
                          WHERE id_sop = :id";
                $params[':file'] = $fileBaru;
            }
        }

        $stmt = $this->pdo->prepare($query);
        
        // Bind semua parameter
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        return $stmt->execute();
    }

    public function deleteSop($id) {
        // Hapus file fisik (Opsional, fitur tambahan biar bersih)
        $data = $this->getSopById($id);
        if ($data && !empty($data['file'])) {
            $path = '../public/assets/uploads/' . $data['file'];
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table . " WHERE id_sop = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    // Helper Upload File (Versi Aman)
    private function uploadFile($file) {
        $namaFile = $file['name'];
        $tmpName = $file['tmp_name'];
        
        // Cek Ekstensi
        $ekstensiValid = ['pdf'];
        $ekstensiFile = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if (!in_array($ekstensiFile, $ekstensiValid)) {
            return false;
        }

        // Cek Ukuran (Max 5MB)
        if ($file['size'] > 5000000) {
            return false;
        }

        $namaFileBaru = uniqid() . '.' . $ekstensiFile;
        
        // Gunakan ROOT_PROJECT atau path relatif yang aman
        // Coba deteksi ROOT_PROJECT jika didefinisikan
        if (defined('ROOT_PROJECT')) {
            $targetDir = ROOT_PROJECT . '/public/assets/uploads/';
        } else {
            // Fallback path relatif
            $targetDir = __DIR__ . '/../../public/assets/uploads/';
        }
        
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($tmpName, $targetDir . $namaFileBaru)) {
            return $namaFileBaru;
        }
        return false;
    }
}