<?php
require_once __DIR__ . '/Model.php';

class KontakModel extends Model {
    protected $table = 'kontakt';

    public function getLatest() {
        $query = "SELECT * FROM kontakt ORDER BY tanggalPembaruan DESC LIMIT 1";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }
}
?>
