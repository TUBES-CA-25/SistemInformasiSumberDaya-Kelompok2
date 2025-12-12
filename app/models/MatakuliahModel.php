<?php
require_once __DIR__ . '/Model.php';

class MatakuliahModel extends Model {
    protected $table = 'Matakuliah';

    public function getMatakuliahByKode($kode) {
        $query = "SELECT * FROM Matakuliah WHERE kodeMatakuliah = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $kode);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getMatakuliahWithAsisten($idMatakuliah) {
        $query = "SELECT a.*, am.tahunAjaran, am.semeserMatakuliah 
                  FROM Asisten a
                  JOIN AsistenMatakuliah am ON a.idAsisten = am.idAsisten
                  WHERE am.idMatakuliah = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idMatakuliah);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
