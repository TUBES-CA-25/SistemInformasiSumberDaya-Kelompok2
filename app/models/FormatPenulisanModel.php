<?php
require_once __DIR__ . '/Model.php';

class FormatPenulisanModel extends Model {
    protected $table = 'format_penulisan';

    public function getAllFormat() {
        $query = "SELECT * FROM {$this->table} ORDER BY id_format DESC";
        $result = $this->db->query($query);
        return ($result) ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}