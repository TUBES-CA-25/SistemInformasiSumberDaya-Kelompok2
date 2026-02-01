<?php

/**
 * ManajemenModel
 * * Model ini bertanggung jawab atas semua interaksi data dengan tabel 'manajemen'.
 * Digunakan untuk mengelola data pimpinan (Kepala Lab) dan staf laboran.
 * * @package App\Models
 */

require_once __DIR__ . '/Model.php';

class ManajemenModel extends Model 
{
    /** @var string Nama tabel di database */
    protected $table = 'manajemen';
    
    /** @var string Nama kolom kunci utama (Primary Key) */
    protected $primaryKey = 'idManajemen';

    /**
     * Mengambil satu baris data berdasarkan ID.
     * * Method ini meng-override method parent untuk memastikan penggunaan 
     * kolom primary key yang benar secara default.
     * * 
     * * @param int|string $id Nilai ID yang dicari.
     * @param string|null $idColumn Nama kolom identitas (Opsional).
     * @return array|null Mengembalikan data dalam bentuk array asosiatif atau null.
     */
    public function getById($id, $idColumn = null): ?array 
    {
        // Tetapkan kolom ke primaryKey jika tidak didefinisikan secara eksplisit
        $column = $idColumn ?? $this->primaryKey;

        // Validasi tipe data ID untuk keamanan tambahan
        $cleanId = (int)$id;
        
        $query = "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("i", $cleanId);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            
            // Mengembalikan null jika data tidak ditemukan (false)
            return $data ?: null;
        }
        
        return null;
    }
    
    /**
     * Mengambil semua data manajemen laboratorium.
     * * Diurutkan berdasarkan ID secara menaik (Ascending).
     * * @return array Kumpulan data manajemen dalam format array asosiatif.
     */
    public function getAll(): array 
    {
        $query = "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} ASC";
        
        $result = $this->db->query($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }
}