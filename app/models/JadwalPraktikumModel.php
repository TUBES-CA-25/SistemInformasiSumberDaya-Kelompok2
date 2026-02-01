<?php

/**
 * JadwalPraktikumModel
 * * Mengelola data jadwal praktikum di database.
 * Menangani relasi antara tabel Jadwal, Matakuliah, Laboratorium, dan Asisten.
 * * @package App\Models
 */

require_once __DIR__ . '/Model.php';

class JadwalPraktikumModel extends Model {
    
    /** @var string Nama tabel utama */
    protected $table = 'jadwalpraktikum';

    /**
     * Mengambil semua data jadwal dengan informasi relasi lengkap.
     * * Menggunakan LEFT JOIN untuk menggabungkan nama matakuliah, nama lab, 
     * serta informasi asisten 1 dan asisten 2.
     * * 
     * * @return array Daftar jadwal praktikum terurut berdasarkan hari dan waktu.
     * @throws Exception Jika terjadi kesalahan pada query database.
     */
    public function getAll(): array {
        $query = "SELECT j.*, m.namaMatakuliah, m.kodeMatakuliah, l.nama as namaLab,
                         COALESCE(a1.nama, j.asisten1) as namaAsisten1, 
                         COALESCE(a2.nama, j.asisten2) as namaAsisten2,
                         a1.foto as fotoAsisten1, a2.foto as fotoAsisten2,
                         a1.idAsisten as idAsisten1, a2.idAsisten as idAsisten2
                  FROM {$this->table} j
                  LEFT JOIN matakuliah m ON j.idMatakuliah = m.idMatakuliah
                  LEFT JOIN laboratorium l ON j.idLaboratorium = l.idLaboratorium
                  LEFT JOIN asisten a1 ON j.asisten1 = a1.idAsisten
                  LEFT JOIN asisten a2 ON j.asisten2 = a2.idAsisten
                  ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), 
                           j.waktuMulai ASC";
        
        $result = $this->db->query($query);
        
        if (!$result) {
            throw new Exception('Database error: ' . $this->db->error);
        }
        
        return $result->fetch_all(MYSQLI_ASSOC) ?: [];
    }

    /**
     * Mencari ID record berdasarkan nama kolom tertentu (Case Insensitive).
     * Berguna untuk proses sinkronisasi data saat import Excel/CSV.
     * * @param string $table Nama tabel target.
     * @param string $col Nama kolom pencarian.
     * @param string $val Nilai yang dicari.
     * @return mixed ID record jika ditemukan, null jika tidak.
     */
    public function findIdByName(string $table, string $col, string $val) {
        $query = "SELECT * FROM {$table} WHERE LCASE(TRIM({$col})) = LCASE(TRIM(?)) LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $val);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        
        return $res ? $res[array_key_first($res)] : null;
    }

    /**
     * Menambah jadwal praktikum baru.
     * * @param array $data Data jadwal yang akan dimasukkan.
     * @return bool True jika berhasil.
     */
    public function insert($data): bool {
        $query = "INSERT INTO {$this->table} 
                  (idMatakuliah, kelas, idLaboratorium, hari, waktuMulai, waktuSelesai, dosen, asisten1, asisten2, frekuensi, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isissssiiss", 
            $data['idMatakuliah'], $data['kelas'], $data['idLaboratorium'], 
            $data['hari'], $data['waktuMulai'], $data['waktuSelesai'], 
            $data['dosen'], $data['asisten1'], $data['asisten2'], 
            $data['frekuensi'], $data['status']);
            
        return $stmt->execute();
    }

    /**
     * Memeriksa apakah jadwal serupa sudah ada di database.
     * Digunakan untuk mencegah duplikasi data saat proses import.
     * * @return bool True jika ditemukan duplikat.
     */
    public function checkDuplicate($idMatakuliah, $kelas, $hari, $waktuMulai, $waktuSelesai, $idLaboratorium): bool {
        $query = "SELECT idJadwal FROM {$this->table} 
                  WHERE idMatakuliah = ? AND kelas = ? AND hari = ? 
                  AND waktuMulai = ? AND waktuSelesai = ? AND idLaboratorium = ? 
                  LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("issssi", $idMatakuliah, $kelas, $hari, $waktuMulai, $waktuSelesai, $idLaboratorium);
        $stmt->execute();
        
        return $stmt->get_result()->num_rows > 0;
    }

    /**
     * Menghapus jadwal berdasarkan ID.
     * * @param int $id
     * @param string $idColumn Nama kolom primary key.
     * @return bool
     */
    public function delete($id, $idColumn = 'idJadwal'): bool {
        $query = "DELETE FROM {$this->table} WHERE {$idColumn} = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }

    /**
     * Menghapus banyak jadwal sekaligus (Bulk Delete).
     * * @param array $ids Kumpulan ID yang akan dihapus.
     * @return bool
     */
    public function deleteMultiple(array $ids): bool {
        if (empty($ids)) return false;
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "DELETE FROM {$this->table} WHERE idJadwal IN ($placeholders)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
        
        return $stmt->execute();
    }

    /**
     * Mengambil detail satu jadwal berdasarkan ID beserta data relasinya.
     * * @param int $id
     * @param string $idColumn
     * @return array|null Data jadwal atau null jika tidak ditemukan.
     */
    public function getById($id, $idColumn = 'idJadwal'): ?array {
        $query = "SELECT j.*, m.namaMatakuliah, m.kodeMatakuliah, l.nama as namaLab,
                         COALESCE(a1.nama, j.asisten1) as namaAsisten1, 
                         COALESCE(a2.nama, j.asisten2) as namaAsisten2,
                         a1.foto as fotoAsisten1, a2.foto as fotoAsisten2,
                         a1.idAsisten as idAsisten1, a2.idAsisten as idAsisten2
                  FROM {$this->table} j
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