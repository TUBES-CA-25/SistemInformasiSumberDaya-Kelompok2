<?php

/**
 * LaboratoriumModel
 * * Menangani interaksi database untuk tabel 'laboratorium'.
 * Kelas ini meng-override method getById agar kompatibel dengan kelas Model induk.
 */

require_once __DIR__ . '/Model.php';

class LaboratoriumModel extends Model {
    
    /** @var string Nama tabel di database */
    protected $table = 'laboratorium';

    /**
     * Get All - Ambil semua data laboratorium
     * * @return array Array asosiatif berisi semua data laboratorium
     */
    public function getAll(): array {
        $query = "SELECT * FROM {$this->table} ORDER BY idLaboratorium ASC";
        $result = $this->db->query($query);
        
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Get By ID - Ambil data berdasarkan ID unik
     * * [FIXED] Parameter disesuaikan agar kompatibel dengan Model::getById($id, $idColumn)
     * * @param int|string $id Nilai ID yang dicari
     * @param string $idColumn Nama kolom primary key (Default: idLaboratorium)
     * @return array|null Data laboratorium atau null jika tidak ditemukan
     */
    public function getById($id, $idColumn = 'idLaboratorium'): ?array {
        // Gunakan Prepared Statement untuk keamanan SQL Injection
        $query = "SELECT * FROM {$this->table} WHERE {$idColumn} = ?";
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            // "i" = integer, asumsi ID laboratorium selalu angka
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            
            return $data ?: null;
        }
        
        return null;
    }
}