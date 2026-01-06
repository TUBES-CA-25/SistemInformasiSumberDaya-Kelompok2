<?php
require_once __DIR__ . '/Model.php';

class JadwalPraktikumModel extends Model {
    protected $table = 'jadwalpraktikum';

    public function getAll() {
        // LEFT JOIN memastikan data tampil meskipun asisten belum diisi di database
        $query = "SELECT j.*, m.namaMatakuliah, l.nama as namaLab 
                  FROM jadwalpraktikum j
                  LEFT JOIN matakuliah m ON j.idMatakuliah = m.idMatakuliah
                  LEFT JOIN laboratorium l ON j.idLaboratorium = l.idLaboratorium
                  ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), j.waktuMulai ASC";
        $result = $this->db->query($query);
        return ($result) ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function findIdByName($table, $col, $val) {
        $query = "SELECT * FROM $table WHERE LCASE(TRIM($col)) = LCASE(TRIM(?)) LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $val);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res[array_key_first($res)] : null;
    }

    public function insert($data) {
        $query = "INSERT INTO jadwalpraktikum 
                  (idMatakuliah, kelas, idLaboratorium, hari, waktuMulai, waktuSelesai, dosen, asisten1, asisten2, frekuensi, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isissssssss", 
            $data['idMatakuliah'], $data['kelas'], $data['idLaboratorium'], 
            $data['hari'], $data['waktuMulai'], $data['waktuSelesai'], 
            $data['dosen'], $data['asisten1'], $data['asisten2'], 
            $data['frekuensi'], $data['status']);
        return $stmt->execute();
    }
}