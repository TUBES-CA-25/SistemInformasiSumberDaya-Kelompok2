<?php

/**
 * FasilitasModel
 * * Mengelola operasi database untuk entitas Laboratorium/Fasilitas.
 * Mewarisi fungsionalitas CRUD dasar dari class Model.
 * * @package App\Models
 */

require_once __DIR__ . '/Model.php';

class FasilitasModel extends Model {
    
    /** * @var string $table Menentukan tabel target di database
     */
    protected $table = 'laboratorium';

    /**
     * getAll
     * * Mengambil semua daftar laboratorium dari database.
     * * @return array List data laboratorium dalam format associative array.
     */
    public function getAll() : array {
        $query = "SELECT * FROM " . $this->table . " ORDER BY idLaboratorium ASC";
        $result = $this->db->query($query);
        
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * getById
     * * Mencari detail laboratorium berdasarkan kolom identitas tertentu.
     * Menggunakan Prepared Statement untuk keamanan data.
     * * @param int $id Nilai ID unik laboratorium.
     * @param string $idColumn Nama kolom primary key (default: idLaboratorium).
     * @return array|null Data laboratorium jika ditemukan, null jika tidak ada.
     */
    public function getById($id, $idColumn = 'idLaboratorium') : ?array {
        $query = "SELECT * FROM " . $this->table . " WHERE $idColumn = ?";
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
        return null;
    }

    /**
     * hasJadwal
     * * Proteksi Integritas Data: Memeriksa keberadaan jadwal praktikum
     * yang masih tertaut pada laboratorium ini sebelum diizinkan untuk dihapus.
     * * @param int $id ID Laboratorium yang akan diperiksa relasinya.
     * @return bool True jika lab masih memiliki jadwal aktif, False jika kosong.
     */
    public function hasJadwal($id) {
        // Menghitung kaitan data di tabel jadwalpraktikum
        $query = "SELECT COUNT(*) as total FROM jadwalpraktikum WHERE idLaboratorium = ?";
        
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            
            // Konversi hasil COUNT ke boolean
            return (int)$result['total'] > 0;
        }
        
        return false;
    }
}