<?php
require_once __DIR__ . '/Model.php';

class InformasiLabModel extends Model {
    protected $table = 'informasiLab';

    public function getInformasiByTipe($tipe) {
        $query = "SELECT * FROM informasiLab WHERE tipe_informasi = ? AND is_informasi = TRUE ORDER BY tanggal_dibuat DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $tipe);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAktif() {
        $query = "SELECT * FROM informasiLab WHERE is_informasi = TRUE ORDER BY tanggal_dibuat DESC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
