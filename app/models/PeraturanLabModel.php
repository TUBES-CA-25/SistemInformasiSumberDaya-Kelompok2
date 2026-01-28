<?php
require_once __DIR__ . '/Model.php';

class PeraturanLabModel extends Model {
    protected $table = 'peraturan_lab'; 

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