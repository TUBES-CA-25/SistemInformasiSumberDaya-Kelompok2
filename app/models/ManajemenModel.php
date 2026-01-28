<?php
require_once __DIR__ . '/Model.php';

class ManajemenModel extends Model {
    protected $table = 'manajemen';
    
    // Definisikan Primary Key agar jelas
    protected $primaryKey = 'idManajemen';

    /**
     * Override getById agar menggunakan kolom 'idManajemen'
     * BUKAN 'id'
     */
    public function getById($id, $col = null) {
        // Jika kolom tidak disebutkan, gunakan primary key tabel ini
        if ($col === null) {
            $col = $this->primaryKey;
        }

        $id = (int)$id;
        
        // Query spesifik menggunakan idManajemen
        $query = "SELECT * FROM " . $this->table . " WHERE $col = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Helper function untuk mengambil semua data
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY idManajemen ASC";
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
?>