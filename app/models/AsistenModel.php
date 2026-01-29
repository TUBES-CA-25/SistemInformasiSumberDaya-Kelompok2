<?php
require_once __DIR__ . '/Model.php';

class AsistenModel extends Model {
    protected $table = 'asisten';
    
    // Penting: Definisikan Primary Key agar method parent (update/delete) bekerja dengan benar
    protected $primaryKey = 'idAsisten';

    /**
     * Ambil SEMUA data asisten tanpa filter
     * Urutan: Koordinator paling atas, sisanya urut Abjad
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY isKoordinator DESC, nama ASC";
        
        $result = $this->db->query($query);
        
        // Return array kosong jika query gagal atau tabel kosong
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Ambil data Alumni
     * Saat ini dikembalikan array kosong [] agar aman (sesuai request sebelumnya),
     * karena data di database Anda statusnya masih "Asisten" semua.
     */
    public function getAlumni() {
        // Jika nanti ingin mengaktifkan alumni, gunakan query ini:
        // $query = "SELECT * FROM " . $this->table . " WHERE statusAktif = 'Alumni' ORDER BY nama ASC";
        // return $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
        
        return [];
    }
    
    /**
     * Ambil satu data berdasarkan ID
     * Parameter $col ditambahkan agar kompatibel dengan panggilan Controller ($id, 'idAsisten')
     */
    public function getById($id, $col = 'idAsisten') {
        $id = (int)$id;
        // Pastikan query menggunakan idAsisten
        $query = "SELECT * FROM " . $this->table . " WHERE idAsisten = $id";
        
        $result = $this->db->query($query);
        return $result ? $result->fetch_assoc() : null;
    }

    /**
     * Fungsi Wrapper untuk Admin (Sama dengan getAll)
     */
    public function getAllForAdmin() {
        return $this->getAll();
    }
    
    /**
     * Reset status koordinator semua asisten menjadi 0
     * Digunakan sebelum menset koordinator baru
     */
    public function resetAllKoordinator() {
        $query = "UPDATE " . $this->table . " SET isKoordinator = 0";
        $stmt = $this->db->prepare($query);
        return $stmt->execute();
    }
}
?>