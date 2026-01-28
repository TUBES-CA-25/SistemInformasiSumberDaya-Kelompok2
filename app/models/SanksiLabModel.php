<?php
require_once __DIR__ . '/Model.php';

class SanksiLabModel extends Model {
    protected $table = 'sanksi_lab'; 

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY urutan ASC";
        $result = $this->db->query($query);
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }
}
?>