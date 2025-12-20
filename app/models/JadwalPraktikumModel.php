<?php
require_once __DIR__ . '/Model.php';

class JadwalPraktikumModel extends Model {
    protected $table = 'jadwalpraktikum';

    public function getAll() {
        $query = "SELECT j.*, 
                         m.namaMatakuliah, m.kodeMatakuliah,
                         l.nama as namaLab, l.nama as namaLaboratorium
                  FROM jadwalpraktikum j
                  JOIN matakuliah m ON j.idMatakuliah = m.idMatakuliah
                  JOIN laboratorium l ON j.idLaboratorium = l.idLaboratorium
                  ORDER BY j.idJadwal DESC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getJadwalByMatakuliah($idMatakuliah) {
        $query = "SELECT j.*, m.namaMatakuliah, l.nama as namaLaboratorium 
                  FROM jadwalpraktikum j
                  JOIN matakuliah m ON j.idMatakuliah = m.idMatakuliah
                  JOIN laboratorium l ON j.idLaboratorium = l.idLaboratorium
                  WHERE j.idMatakuliah = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idMatakuliah);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getJadwalByLaboratorium($idLaboratorium) {
        $query = "SELECT j.*, m.namaMatakuliah, l.nama as namaLaboratorium 
                  FROM jadwalpraktikum j
                  JOIN matakuliah m ON j.idMatakuliah = m.idMatakuliah
                  JOIN laboratorium l ON j.idLaboratorium = l.idLaboratorium
                  WHERE j.idLaboratorium = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idLaboratorium);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
