<?php
require_once __DIR__ . '/Model.php';

class LaboratoriumModel extends Model {
    // Pastikan nama tabel kecil sesuai screenshot phpMyAdmin Anda
    protected $table = 'laboratorium'; 

    /**
     * Mengambil SEMUA data.
     * Menggunakan Syntax MySQLi (untuk Admin) tapi return Array (untuk Public).
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY idLaboratorium ASC";
        
        // Gunakan $this->db->query (MySQLi Style)
        $result = $this->db->query($query);
        
        if ($result) {
            // fetch_all(MYSQLI_ASSOC) menghasilkan Array Asosiatif
            // Ini sama persis strukturnya dengan PDO::FETCH_ASSOC
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    /**
     * Ambil berdasarkan ID.
     * Menggunakan Prepared Statement MySQLi.
     */
    public function getById($id, $col = 'idLaboratorium') {
        $query = "SELECT * FROM " . $this->table . " WHERE $col = ?";
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            // "i" artinya integer. MySQLi butuh definisi tipe data.
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return null;
    }

    // Method insert/update/delete biasanya mewarisi dari Model.php utama
    // Jika Model.php utama pakai MySQLi, maka method insert bawaan akan aman.
}
?>