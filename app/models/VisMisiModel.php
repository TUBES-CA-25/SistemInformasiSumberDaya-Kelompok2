<?php
require_once __DIR__ . '/Model.php';

class VisMisiModel extends Model {
    protected $table = 'visMisi';

    public function getLatest() {
        $query = "SELECT * FROM visMisi ORDER BY tanggalPembaruan DESC LIMIT 1";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }
}
?>
