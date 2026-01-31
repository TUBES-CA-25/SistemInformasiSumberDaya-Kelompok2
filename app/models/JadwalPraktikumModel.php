<?php
require_once __DIR__ . '/Model.php';

class JadwalPraktikumModel extends Model {
    protected $table = 'jadwalpraktikum';

    public function getAll() {
        // LEFT JOIN dengan tabel asisten berdasarkan ID
        // Menambahkan foto (url) dan ID asli untuk frontend
        $query = "SELECT j.*, m.namaMatakuliah, m.kodeMatakuliah, l.nama as namaLab,
                  COALESCE(a1.nama, j.asisten1) as namaAsisten1, 
                  COALESCE(a2.nama, j.asisten2) as namaAsisten2,
                  a1.foto as fotoAsisten1, a2.foto as fotoAsisten2,
                  a1.idAsisten as idAsisten1, a2.idAsisten as idAsisten2
                  FROM jadwalpraktikum j
                  LEFT JOIN matakuliah m ON j.idMatakuliah = m.idMatakuliah
                  LEFT JOIN laboratorium l ON j.idLaboratorium = l.idLaboratorium
                  LEFT JOIN asisten a1 ON j.asisten1 = a1.idAsisten
                  LEFT JOIN asisten a2 ON j.asisten2 = a2.idAsisten
                  ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), j.waktuMulai ASC";
        
        $result = $this->db->query($query);
        
        // Error checking
        if (!$result) {
            throw new Exception('Database error: ' . $this->db->error);
        }
        
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return ($data !== null) ? $data : [];
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
        // i=MK, s=Kls, i=Lab, s=Hari, s=Mulai, s=Selesai, s=Dosen, i=Ast1, i=Ast2, s=Freq, s=Status
        $stmt->bind_param("isissssiiss", 
            $data['idMatakuliah'], $data['kelas'], $data['idLaboratorium'], 
            $data['hari'], $data['waktuMulai'], $data['waktuSelesai'], 
            $data['dosen'], $data['asisten1'], $data['asisten2'], 
            $data['frekuensi'], $data['status']);
        return $stmt->execute();
    }

    /**
     * Cek apakah jadwal sudah ada (untuk menghindari duplikasi pada import Excel)
     * Kombinasi unik: idMatakuliah + kelas + hari + waktuMulai + waktuSelesai + idLaboratorium
     */
    public function checkDuplicate($idMatakuliah, $kelas, $hari, $waktuMulai, $waktuSelesai, $idLaboratorium) {
        $query = "SELECT idJadwal FROM jadwalpraktikum 
                  WHERE idMatakuliah = ? 
                  AND kelas = ? 
                  AND hari = ? 
                  AND waktuMulai = ? 
                  AND waktuSelesai = ? 
                  AND idLaboratorium = ? 
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("issssi", $idMatakuliah, $kelas, $hari, $waktuMulai, $waktuSelesai, $idLaboratorium);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0; // Return true jika ada duplikat
    }

    /**
     * Delete jadwal berdasarkan ID
     */
    public function delete($id, $idColumn = 'idJadwal') {
        $query = "DELETE FROM jadwalpraktikum WHERE {$idColumn} = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Delete multiple jadwal
     */
    public function deleteMultiple($ids) {
        if (empty($ids)) return false;
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "DELETE FROM jadwalpraktikum WHERE idJadwal IN ($placeholders)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
        return $stmt->execute();
    }

    /**
     * Get jadwal by ID
     */
    public function getById($id, $idColumn = 'idJadwal') {
        $query = "SELECT j.*, m.namaMatakuliah, m.kodeMatakuliah, l.nama as namaLab,
                  COALESCE(a1.nama, j.asisten1) as namaAsisten1, 
                  COALESCE(a2.nama, j.asisten2) as namaAsisten2,
                  a1.foto as fotoAsisten1, a2.foto as fotoAsisten2,
                  a1.idAsisten as idAsisten1, a2.idAsisten as idAsisten2
                  FROM jadwalpraktikum j
                  LEFT JOIN matakuliah m ON j.idMatakuliah = m.idMatakuliah
                  LEFT JOIN laboratorium l ON j.idLaboratorium = l.idLaboratorium
                  LEFT JOIN asisten a1 ON j.asisten1 = a1.idAsisten
                  LEFT JOIN asisten a2 ON j.asisten2 = a2.idAsisten
                  WHERE j.{$idColumn} = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}