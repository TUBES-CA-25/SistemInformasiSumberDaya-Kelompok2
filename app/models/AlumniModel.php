<?php
require_once __DIR__ . '/Model.php';

class AlumniModel extends Model {
    protected $table = 'alumni';

    /**
     * Get all alumni sorted by nama
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nama ASC";
        $result = $this->db->query($query);
        
        // Pastikan result tidak error sebelum fetch
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    /**
     * [BARU] Ambil 1 data alumni berdasarkan ID
     * Menggunakan Prepared Statement agar aman dari SQL Injection
     */
    public function getAlumniById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        
        // 1. Siapkan Statement
        $stmt = $this->db->prepare($query);

        if ($stmt) {
            // 2. Bind Parameter ("i" artinya integer)
            $stmt->bind_param("i", $id);
            
            // 3. Eksekusi
            $stmt->execute();
            
            // 4. Ambil Hasil
            $result = $stmt->get_result();
            
            // 5. Kembalikan 1 baris sebagai array asosiatif
            return $result->fetch_assoc();
        }

        return null; // Kembalikan null jika query gagal
    }
}
?>