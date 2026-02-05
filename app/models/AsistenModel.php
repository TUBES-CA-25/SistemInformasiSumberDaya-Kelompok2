<?php

/**
 * AsistenModel
 * * Model ini menangani seluruh logika interaksi database untuk tabel 'asisten'.
 * Mewarisi fungsi dasar dari parent class Model.
 * * @package App\Models
 */

require_once __DIR__ . '/Model.php';

class AsistenModel extends Model {
    
    /** @var string Nama tabel di database */
    protected $table = 'asisten';
    
    /** @var string Kolom Primary Key untuk referensi CRUD parent */
    protected $primaryKey = 'idAsisten';

    /**
     * Ambil SEMUA data asisten.
     * * Logika pengurutan:
     * 1. Koordinator muncul di baris paling atas (isKoordinator DESC).
     * 2. Sisanya diurutkan berdasarkan abjad nama (nama ASC).
     * * @return array Array asosiatif dari semua asisten atau array kosong.
     */
    public function getAll() : array {
        $query = "SELECT * FROM " . $this->table . " ORDER BY isKoordinator DESC, nama ASC";
        
        $result = $this->db->query($query);
        
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Ambil data khusus untuk Alumni.
     * * @note Saat ini mengembalikan array kosong sesuai kebijakan bisnis sementara,
     * namun telah disediakan placeholder query jika status 'Alumni' diaktifkan.
     * * @return array
     */
    public function getAlumni() {
        /** * Placeholder Query:
         * $query = "SELECT * FROM " . $this->table . " WHERE statusAktif = 'Alumni' ORDER BY nama ASC";
         * return $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
         */
        return [];
    }
    
    /**
     * Ambil data satu asisten berdasarkan ID unik.
     * * Menggunakan Prepared Statement untuk mencegah SQL Injection.
     * * @param int|string $id ID asisten yang dicari.
     * @param string $col Nama kolom identitas (default: idAsisten).
     * @return array|null Data asisten dalam bentuk array asosiatif atau null.
     */
    public function getById($id, $col = 'idAsisten') : ?array {
        // Normalisasi parameter untuk keamanan
        $id = (int)$id;
        $column = $this->db->real_escape_string($col);

        $query = "SELECT * FROM " . $this->table . " WHERE $column = ?";
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        return null;
    }

    /**
     * Alias untuk kebutuhan administratif.
     * * @return array
     */
    public function getAllForAdmin() {
        return $this->getAll();
    }
    
    /**
     * Reset status koordinator.
     * * Mengatur kolom 'isKoordinator' menjadi 0 untuk semua baris asisten.
     * Digunakan sebagai langkah awal sebelum menetapkan koordinator baru.
     * * 
     * * @return bool True jika berhasil, False jika gagal.
     */
    public function resetAllKoordinator() {
        $query = "UPDATE " . $this->table . " SET isKoordinator = 0";
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            return $stmt->execute();
        }

        return false;
    }

    /**
     * Ambil data berdasarkan kolom tertentu.
     * * @param string $column Nama kolom
     * @param mixed $value Nilai yang dicari
     * @return array|null Data asisten atau null jika tidak ditemukan
     */
    public function getByColumn($column, $value) {
        $column = $this->db->real_escape_string($column);
        $query = "SELECT * FROM " . $this->table . " WHERE " . $column . " = ? LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("s", $value);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        return null;
    }
}