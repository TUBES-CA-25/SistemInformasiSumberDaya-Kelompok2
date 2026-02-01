<?php

/**
 * SanksiLabModel
 * * Model ini bertanggung jawab untuk mengelola data sanksi pelanggaran di laboratorium.
 * Mewarisi fungsionalitas dasar dari parent class Model.
 * * @package App\Models
 */

require_once __DIR__ . '/Model.php';

class SanksiLabModel extends Model 
{
    /** * @var string Nama tabel di database 
     */
    protected $table = 'sanksi_lab'; 
    
    /** * @var string Kolom kunci utama 
     */
    protected $primaryKey = 'id'; 

    /**
     * Mengambil semua daftar sanksi laboratorium.
     * * Data diurutkan secara ascending (A-Z) berdasarkan ID.
     * * @return array Kumpulan data sanksi dalam format array asosiatif.
     */
    public function getAll(): array 
    {
        $query = "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} ASC";
        
        $result = $this->db->query($query);
        
        // Mengembalikan array kosong jika query gagal atau tidak ada data
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }

    /**
     * Mengambil data sanksi berdasarkan ID tertentu.
     * * Menggunakan Prepared Statement untuk keamanan database.
     * * 
     * * @param int|string $id Nilai ID sanksi yang dicari.
     * @param string|null $idColumn Nama kolom identitas (Opsional, default: primaryKey).
     * @return array|null Mengembalikan data sanksi jika ditemukan, atau null jika tidak ada.
     */
    public function getById($id, $idColumn = null): ?array 
    {
        // Fallback ke primary key jika kolom tidak ditentukan eksplisit
        $column = $idColumn ?? $this->primaryKey;
        
        // Menggunakan placeholder (?) untuk keamanan
        $query = "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            // "i" berarti integer. Mengonversi $id ke integer untuk keamanan tambahan.
            $cleanId = (int)$id;
            $stmt->bind_param("i", $cleanId);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            
            $stmt->close();
            
            // Mengembalikan null jika fetch_assoc menghasilkan false
            return $data ?: null;
        }
        
        return null;
    }
}