<?php

/**
 * PeraturanLabModel
 * * Model ini bertanggung jawab untuk mengelola data peraturan laboratorium.
 * Mewarisi fungsionalitas dasar dari parent class Model.
 * * @package App\Models
 */

require_once __DIR__ . '/Model.php';

class PeraturanLabModel extends Model {
    
    /** * @var string Nama tabel di database 
     */
    protected $table = 'peraturan_lab'; 
    
    /** * @var string Primary key tabel 
     */
    protected $primaryKey = 'id'; 

    /**
     * Mengambil semua data peraturan laboratorium.
     * * Data diurutkan secara ascending (A-Z) berdasarkan ID.
     * * @return array Kumpulan data peraturan dalam format array asosiatif.
     */
    public function getAll() : array {
        $query = "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} ASC";
        
        $result = $this->db->query($query);
        
        // Mengembalikan array kosong jika query gagal atau data tidak ada
        return ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Mengambil data peraturan berdasarkan ID.
     * * Menggunakan Prepared Statement untuk keamanan database dari SQL Injection.
     * * @param int|string $id Nilai ID yang dicari.
     * @param string|null $idColumn Nama kolom identitas (Opsional, default: primaryKey).
     * @return array|null Mengembalikan data peraturan jika ditemukan, atau null jika tidak ada.
     */
    public function getById($id, $idColumn = null) : ?array {
        // Fallback ke primary key jika kolom tidak ditentukan
        $column = $idColumn ?? $this->primaryKey;
        
        // Template query dengan placeholder (?)
        $query = "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1";
        
        
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            // "i" berarti tipe data integer. Jika ID Anda string, ganti menjadi "s"
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            
            // Membersihkan statement setelah digunakan
            $stmt->close();
            
            return $data ?: null;
        }
        
        return null;
    }
}