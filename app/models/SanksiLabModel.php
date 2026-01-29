<?php
require_once __DIR__ . '/Model.php';

class SanksiLabModel extends Model {
    // Sesuai daftar tabel di screenshot sebelumnya: 'sanksi_lab'
    protected $table = 'sanksi_lab'; 
    
    // Primary Key biasanya 'id'
    protected $primaryKey = 'id'; 

    public function getAll() {
        // PERBAIKAN: Ganti 'ORDER BY urutan' menjadi 'ORDER BY id'
        $query = "SELECT * FROM " . $this->table . " ORDER BY id ASC";
        
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // PERBAIKAN: Menyesuaikan parameter dengan Parent Model agar tidak error Declaration
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