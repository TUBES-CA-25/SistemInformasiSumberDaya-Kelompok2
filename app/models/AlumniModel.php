<?php

/**
 * AlumniModel
 * * Kelas ini bertanggung jawab atas semua interaksi data ke tabel 'alumni'.
 * Mewarisi fungsionalitas dasar dari parent class Model.
 * * @package App\Models
 */

require_once __DIR__ . '/Model.php';

class AlumniModel extends Model {

    /**
     * @var string Nama tabel database yang dikelola oleh model ini.
     */
    protected $table = 'alumni';

    /**
     * Get All Alumni
     * * Mengambil seluruh data alumni dari database dan mengurutkannya 
     * berdasarkan nama secara alfabetis (A-Z).
     * * @return array Mengembalikan array asosiatif berisi data alumni, 
     * atau array kosong jika tidak ada data/query gagal.
     */
    public function getAll() : array {
        // Menggunakan properti $this->table agar fleksibel jika nama tabel berubah
        $query = "SELECT * FROM " . $this->table . " ORDER BY nama ASC";
        
        $result = $this->db->query($query);
        
        // Memastikan hasil query valid dan memiliki data
        if ($result && $result->num_rows > 0) {
            // MYSQLI_ASSOC mengubah hasil menjadi array ['kolom' => 'nilai']
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }

    /**
     * Get Alumni By ID
     * * Mencari data satu alumni berdasarkan Primary Key (ID).
     * Menggunakan MySQLi Prepared Statements untuk mencegah serangan SQL Injection.
     * * 
     * * @param int $id ID unik alumni yang ingin dicari.
     * @return array|null Mengembalikan data alumni jika ditemukan, 
     * atau null jika data tidak ada atau terjadi kesalahan.
     */
    public function getAlumniById($id) {
        // Parameter diwakili oleh tanda tanya (?) sebagai placeholder
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        
        // 1. Prepare: Menyiapkan template query di server database
        $stmt = $this->db->prepare($query);

        if ($stmt) {
            /**
             * 2. Bind: Menghubungkan variabel ke placeholder.
             * "i" = parameter harus bertipe Integer (lebih aman).
             */
            $stmt->bind_param("i", $id);
            
            // 3. Execute: Menjalankan query yang sudah aman
            $stmt->execute();
            
            // 4. Result: Mendapatkan objek hasil dari statement
            $result = $stmt->get_result();
            
            // 5. Fetch: Mengambil baris tunggal sebagai array asosiatif
            return $result->fetch_assoc();
        }

        return null;
    }
}