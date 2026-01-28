<?php
require_once __DIR__ . '/Model.php';

class ManajemenModel extends Model {
    // Pastikan nama tabel sesuai dengan di database (huruf kecil biasanya lebih aman)
    protected $table = 'manajemen'; 

    /**
     * Mengambil semua data manajemen dengan cara paling aman (MySQLi Compatible)
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY idManajemen ASC";
        $result = $this->db->query($query);
        
        $data = [];
        if ($result) {
            // Kita gunakan while loop manual agar tidak error di berbagai versi PHP/MySQLi
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }
}
?>