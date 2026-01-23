<?php
require_once __DIR__ . '/../config/Database.php';

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
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table . " ORDER BY created_at DESC");
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
        try {
            // Upload File Logic - dengan nama dari form input
            $fileName = $this->uploadFile($fileInfo, $data['judul']);
            if (!$fileName) {
                throw new Exception('Gagal upload file');
            }

            $query = "INSERT INTO " . $this->table . " 
                        (judul, file, deskripsi) 
                      VALUES 
                        (:judul, :file, :deskripsi)";

            $stmt = $this->pdo->prepare($query);
            if (!$stmt) {
                throw new Exception('Prepare query gagal: ' . implode(', ', $this->pdo->errorInfo()));
            }
            
            $stmt->bindValue(':judul', $data['judul']);
            $stmt->bindValue(':file', $fileName);
            $stmt->bindValue(':deskripsi', $data['deskripsi'] ?? '');

            if (!$stmt->execute()) {
                throw new Exception('Execute query gagal: ' . implode(', ', $stmt->errorInfo()));
            }
            
            return $stmt->rowCount();
        } catch (Exception $e) {
            error_log('tambahDataSop Error: ' . $e->getMessage());
            throw $e;
        } catch (PDOException $e) {
            $msg = 'Database Error: ' . $e->getMessage();
            error_log('tambahDataSop PDOException: ' . $msg);
            throw new Exception($msg);
        }
    }

    public function updateDataSop($data, $fileInfo) {
        try {
            // Default Query: Update tanpa ganti file
            $query = "UPDATE " . $this->table . " SET 
                        judul = :judul, 
                        deskripsi = :deskripsi 
                      WHERE id_sop = :id";
            
            $params = [
                ':judul' => $data['judul'],
                ':deskripsi' => $data['deskripsi'] ?? '',
                ':id' => $data['id_sop']
            ];

            // Cek jika ada file baru yang diupload
            if (isset($fileInfo['error']) && $fileInfo['error'] === 0) {
                // Hapus file lama terlebih dahulu
                $oldData = $this->getSopById($data['id_sop']);
                if ($oldData && !empty($oldData['file'])) {
                    $oldPath = ROOT_PROJECT . '/public/assets/uploads/pdf/' . $oldData['file'];
                    if (!file_exists($oldPath)) {
                        $oldPath = '../public/assets/uploads/pdf/' . $oldData['file'];
                    }
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
                
                $fileBaru = $this->uploadFile($fileInfo, $data['judul']);
                if ($fileBaru) {
                    $query = "UPDATE " . $this->table . " SET 
                                judul = :judul, 
                                file = :file, 
                                deskripsi = :deskripsi 
                              WHERE id_sop = :id";
                    $params[':file'] = $fileBaru;
                }
            }

            $stmt = $this->pdo->prepare($query);
            if (!$stmt) {
                throw new Exception('Prepare query gagal: ' . implode(', ', $this->pdo->errorInfo()));
            }
            
            // Bind semua parameter
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }

            if (!$stmt->execute()) {
                throw new Exception('Execute query gagal: ' . implode(', ', $stmt->errorInfo()));
            }
            
            return $stmt->rowCount();
        } catch (Exception $e) {
            error_log('updateDataSop Error: ' . $e->getMessage());
            throw $e;
        } catch (PDOException $e) {
            $msg = 'Database Error: ' . $e->getMessage();
            error_log('updateDataSop PDOException: ' . $msg);
            throw new Exception($msg);
        }
    }

    public function deleteSop($id) {
        // Hapus file fisik (Opsional, fitur tambahan biar bersih)
        $data = $this->getSopById($id);
        if ($data && !empty($data['file'])) {
            $path = ROOT_PROJECT . '/public/assets/uploads/pdf/' . $data['file'];
            if (!file_exists($path)) {
                $path = '../public/assets/uploads/pdf/' . $data['file'];
            }
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table . " WHERE id_sop = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    // Helper Upload File (Versi Aman) - dengan nama custom dari form input
    private function uploadFile($file, $namaJudul = null) {
        // Cek error upload
        if (isset($file['error']) && $file['error'] !== UPLOAD_ERR_OK) {
            $uploadErrors = [
                UPLOAD_ERR_INI_SIZE => 'File terlalu besar (melebihi php.ini upload_max_filesize)',
                UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (melebihi form MAX_FILE_SIZE)',
                UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian',
                UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diupload',
                UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan',
                UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
                UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh extension'
            ];
            throw new Exception($uploadErrors[$file['error']] ?? 'Error upload file tidak dikenal');
        }
        
        if (empty($file['name']) || empty($file['tmp_name'])) {
            throw new Exception('File tidak valid');
        }
        
        $namaFile = $file['name'];
        $tmpName = $file['tmp_name'];
        
        // Cek Ekstensi
        $ekstensiValid = ['pdf'];
        $ekstensiFile = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if (!in_array($ekstensiFile, $ekstensiValid)) {
            throw new Exception('Format file hanya boleh PDF');
        }

        // Cek Ukuran (Max 5MB)
        if ($file['size'] > 5000000) {
            throw new Exception('File terlalu besar (max 5MB)');
        }
        
        if ($file['size'] <= 0) {
            throw new Exception('File kosong atau tidak valid');
        }

        // Buat nama file dari judul (sanitize)
        if (!empty($namaJudul)) {
            // Trim whitespace terlebih dahulu
            $namaJudul = trim($namaJudul);
            
            // Hapus karakter spesial, ganti spasi dengan underscore
            $namaFileBaru = preg_replace('/[^a-zA-Z0-9\s-]/', '', $namaJudul);
            $namaFileBaru = preg_replace('/[\s]+/', '_', $namaFileBaru);
            $namaFileBaru = strtolower(trim($namaFileBaru, '_'));
            
            // Jika nama kosong setelah sanitasi, gunakan fallback
            if (empty($namaFileBaru)) {
                $namaFileBaru = 'sop_' . time();
            }
            
            // Tambahkan timestamp untuk menghindari duplikasi
            $namaFileBaru = $namaFileBaru . '_' . time() . '.' . $ekstensiFile;
        } else {
            // Fallback jika judul kosong
            $namaFileBaru = 'sop_' . time() . '_' . uniqid() . '.' . $ekstensiFile;
        }
        
        // Debug log
        error_log('PDF Upload - Judul: ' . ($namaJudul ?? 'NULL') . ' | Generated Name: ' . $namaFileBaru);
        
        // Gunakan ROOT_PROJECT atau path relatif yang aman
        // Simpan di folder pdf
        if (defined('ROOT_PROJECT')) {
            $targetDir = ROOT_PROJECT . '/public/assets/uploads/pdf/';
        } else {
            // Fallback path relatif
            $targetDir = __DIR__ . '/../../public/assets/uploads/pdf/';
        }
        
        if (!file_exists($targetDir)) {
            if (!mkdir($targetDir, 0777, true)) {
                throw new Exception('Gagal membuat folder uploads/pdf');
            }
        }
        
        // Cek apakah folder writable
        if (!is_writable($targetDir)) {
            throw new Exception('Folder uploads/pdf tidak memiliki write permission');
        }

        if (!move_uploaded_file($tmpName, $targetDir . $namaFileBaru)) {
            throw new Exception('Gagal memindahkan file ke folder tujuan');
        }
        
        return $namaFileBaru;
    }
}