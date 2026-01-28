<?php
require_once __DIR__ . '/Model.php';

class PeraturanLabModel extends Model {
    // Sesuai screenshot: peraturan_lab (pakai underscore)
    protected $table = 'peraturan_lab'; 
    
    // Sesuai screenshot: kolom id (huruf kecil semua)
    protected $primaryKey = 'id'; 

    public function getAll() {
        // Mengurutkan berdasarkan id
        $query = "SELECT * FROM " . $this->table . " ORDER BY id ASC";
        
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getById($id, $idColumn = null) {
        if ($idColumn === null) {
            $idColumn = $this->primaryKey;
        }

        $id = (int)$id;
        $query = "SELECT * FROM " . $this->table . " WHERE $idColumn = $id";
        
        $result = $this->db->query($query);
        return $result ? $result->fetch_assoc() : null;
    }
}
?>