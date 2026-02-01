<?php

/**
 * MatakuliahModel
 * * Model ini bertanggung jawab untuk mengelola data Matakuliah serta relasinya 
 * dengan entitas Asisten. Menggunakan MySQLi Prepared Statements untuk keamanan data.
 * * @package App\Models
 */

require_once __DIR__ . '/Model.php';

class MatakuliahModel extends Model 
{
    /** * @var string Nama tabel utama di database.
     */
    protected $table = 'Matakuliah';

    /**
     * Mengambil satu data matakuliah berdasarkan kode uniknya.
     * * Digunakan untuk validasi saat pembuatan matakuliah baru agar tidak terjadi duplikasi kode.
     * * @param string $kode Kode matakuliah (contoh: 'IF123').
     * @return array|null Mengembalikan data matakuliah atau null jika tidak ditemukan.
     */
    public function getMatakuliahByKode(string $kode): ?array 
    {
        $query = "SELECT * FROM {$this->table} WHERE kodeMatakuliah = ? LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("s", $kode);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc() ?: null;
        }

        return null;
    }

    /**
     * Mengambil daftar asisten yang mengampu matakuliah tertentu.
     * * Melakukan JOIN antara tabel Asisten dan tabel pivot AsistenMatakuliah 
     * untuk mendapatkan informasi tahun ajaran dan semester.
     * * 
     * * @param int $idMatakuliah ID unik matakuliah.
     * @return array Kumpulan data asisten dalam format array asosiatif.
     */
    public function getMatakuliahWithAsisten(int $idMatakuliah): array 
    {
        $query = "SELECT a.*, am.tahunAjaran, am.semeserMatakuliah 
                  FROM Asisten a
                  JOIN AsistenMatakuliah am ON a.idAsisten = am.idAsisten
                  WHERE am.idMatakuliah = ?";
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("i", $idMatakuliah);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // fetch_all mengembalikan array kosong [] jika tidak ada data
            return $result->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        return [];
    }

    /**
     * Mengambil semua data matakuliah (Override/Helper).
     * * @return array
     */
    public function getAll(): array 
    {
        $query = "SELECT * FROM {$this->table} ORDER BY namaMatakuliah ASC";
        $result = $this->db->query($query);
        
        return ($result) ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}