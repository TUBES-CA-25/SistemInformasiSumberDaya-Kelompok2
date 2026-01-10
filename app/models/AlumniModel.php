<?php
require_once __DIR__ . '/Model.php';

class AlumniModel extends Model {
    protected $table = 'alumni';

    /**
     * Get all alumni sorted by urutan tampilan
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY urutanTampilan ASC, nama ASC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
