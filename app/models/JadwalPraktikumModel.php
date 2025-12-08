<?php
require_once __DIR__ . '/Model.php';

class JadwalPraktikumModel extends Model {
    protected $table = 'jadwalPraktikum';

    public function getJadwalByMatakuliah($idMatakuliah) {
        $query = "SELECT j.*, m.namaMatakuliah, l.nama as namaLaboratorium 
                  FROM jadwalPraktikum j
                  JOIN Matakuliah m ON j.idMatakuliah = m.idMatakuliah
                  JOIN Laboratorium l ON j.idLaboratorium = l.idLaboratorium
                  WHERE j.idMatakuliah = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idMatakuliah);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getJadwalByLaboratorium($idLaboratorium) {
        $query = "SELECT j.*, m.namaMatakuliah, l.nama as namaLaboratorium 
                  FROM jadwalPraktikum j
                  JOIN Matakuliah m ON j.idMatakuliah = m.idMatakuliah
                  JOIN Laboratorium l ON j.idLaboratorium = l.idLaboratorium
                  WHERE j.idLaboratorium = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idLaboratorium);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
