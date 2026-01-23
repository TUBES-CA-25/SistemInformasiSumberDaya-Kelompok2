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
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
