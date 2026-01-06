<?php
require_once __DIR__ . '/Model.php';

class FormatPenulisanModel extends Model {
    protected $table = 'format_penulisan';
    protected $primaryKey = 'id_format';

    public function getAllFormat() {
        $query = "SELECT * FROM {$this->table} ORDER BY kategori ASC, urutan ASC";
        $result = $this->db->query($query);
        return ($result) ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}