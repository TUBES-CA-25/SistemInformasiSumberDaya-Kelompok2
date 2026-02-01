<?php

/**
 * FormatPenulisanModel
 * * Model ini bertanggung jawab untuk mengelola data pedoman atau format penulisan 
 * laporan praktikum dan dokumen akademik lainnya di dalam database.
 * * @package App\Models
 */

require_once __DIR__ . '/Model.php';

class FormatPenulisanModel extends Model 
{
    /** * @var string $table Nama tabel utama di database.
     */
    protected $table = 'format_penulisan';

    /** * @var string $primaryKey Nama kolom kunci utama.
     */
    protected $primaryKey = 'id_format';

    /**
     * Mengambil semua data format penulisan.
     * * Data diurutkan berdasarkan kategori secara alfabetis (A-Z) 
     * dan tanggal pembaharuan terbaru (Terbaru ke Lama).
     * * 
     * * @return array Mengembalikan array asosiatif berisi daftar format, 
     * atau array kosong jika tidak ada data/query gagal.
     */
    public function getAllFormat(): array 
    {
        // Menggunakan kurung kurawal pada variabel di dalam string untuk kejelasan (String Interpolation)
        $query = "SELECT * FROM {$this->table} ORDER BY kategori ASC, tanggal_update DESC";
        
        $result = $this->db->query($query);

        // Memastikan hasil query valid sebelum melakukan fetch
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    /**
     * Mengambil format penulisan berdasarkan kategori spesifik.
     * * @param string $kategori Nama kategori (contoh: 'Laporan', 'Skripsi').
     * @return array
     */
    public function getByKategori(string $kategori): array 
    {
        $query = "SELECT * FROM {$this->table} WHERE kategori = ? ORDER BY tanggal_update DESC";
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("s", $kategori);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }
}