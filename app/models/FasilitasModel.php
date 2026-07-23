<?php

/**
 * FasilitasModel
 * * Mengelola operasi database untuk entitas Laboratorium dan Ruang Riset.
 * Menyediakan fungsionalitas CRUD serta pengecekan integritas relasi data.
 * * @package App\Models
 */

require_once __DIR__ . '/Model.php';

class FasilitasModel extends Model 
{
    /** @var string Nama tabel target di database */
    protected $table = 'laboratorium';

    /** @var string Primary key tabel laboratorium */
    protected $primaryKey = 'idLaboratorium';

    /**
     * Mengambil seluruh daftar fasilitas.
     * * @return array List data dalam format associative array.
     */
    public function getAll(): array 
    {
        $query = "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} ASC";
        
        $result = $this->db->query($query);
        
        // Mengembalikan array kosong jika query gagal atau data tidak ditemukan
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }

    /**
     * Mencari detail fasilitas berdasarkan ID.
     * * Menggunakan Prepared Statement untuk mencegah SQL Injection.
     * * @param int|string $id Nilai ID unik.
     * @param string $idColumn Nama kolom identitas (default: idLaboratorium).
     * @return array|null Data hasil pencarian atau null jika tidak ada.
     */
    public function getById($id, $idColumn = 'idLaboratorium'): ?array 
    {
        $query = "SELECT * FROM {$this->table} WHERE {$idColumn} = ? LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            // "s" digunakan agar fleksibel menerima ID string maupun integer
            $stmt->bind_param("s", $id); 
            $stmt->execute();
            
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            
            // Tutup statement untuk membebaskan sumber daya
            $stmt->close();
            
            return $data ?: null;
        }
        
        return null;
    }

    /**
     * Mencari detail fasilitas berdasarkan Slug nama lab, Hash ID, atau ID numerik.
     * @param string|int $identifier
     * @return array|null
     */
    public function getBySlugOrId($identifier): ?array 
    {
        if (empty($identifier)) return null;

        // 1. Cek jika ID numerik langsung (misal: 23)
        if (is_numeric($identifier)) {
            $lab = $this->getById((int)$identifier);
            if ($lab) return $lab;
        }

        // 2. Cek jika $identifier adalah Hash ID valid (misal: 2aln4nba)
        if (class_exists('Helper')) {
            $decodedId = Helper::decodeId($identifier);
            if ($decodedId !== null) {
                $lab = $this->getById($decodedId);
                if ($lab) return $lab;
            }
        }

        // 3. Pencarian berbasis Slug (misal: 'start-up', 'computer-network')
        $all = $this->getAll();
        $targetSlug = class_exists('Helper') ? Helper::slugify($identifier) : strtolower(trim($identifier));
        
        foreach ($all as $item) {
            if (!empty($item['nama'])) {
                $itemSlug = class_exists('Helper') ? Helper::slugify($item['nama']) : strtolower(trim($item['nama']));
                if ($itemSlug === $targetSlug) {
                    return $item;
                }
            }
        }

        return null;
    }

    /**
     * Proteksi Integritas Data (Manual Foreign Key Check).
     * * Memeriksa apakah laboratorium masih memiliki jadwal praktikum yang aktif.
     * Sangat disarankan dipanggil sebelum menjalankan fungsi delete().
     * * 
     * * @param int $id ID Laboratorium.
     * @return bool True jika ada jadwal (tidak boleh dihapus), False jika aman.
     */
    public function hasJadwal(int $id): bool 
    {
        $query = "SELECT COUNT(*) as total FROM jadwalpraktikum WHERE idLaboratorium = ?";
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            
            // Pastikan nilai dikembalikan sebagai boolean murni
            return isset($result['total']) && (int)$result['total'] > 0;
        }
        
        return false;
    }
}