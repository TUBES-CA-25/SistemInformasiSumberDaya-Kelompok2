<?php

/**
 * DosenModel
 * * Model ini menangani seluruh logika interaksi database untuk tabel 'dosen'.
 * Mewarisi fungsi dasar dari parent class Model.
 * * @package App\Models
 */

require_once __DIR__ . '/Model.php';

class DosenModel extends Model {
    
    /** @var string Nama tabel di database */
    protected $table = 'dosen';
    
    /** @var string Kolom Primary Key untuk referensi CRUD parent */
    protected $primaryKey = 'idDosen';

    /**
     * Ambil SEMUA data dosen, terurut berdasarkan abjad nama.
     * * @return array Array asosiatif dari semua dosen atau array kosong.
     */
    public function getAll() : array {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nama ASC";
        $result = $this->db->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Ambil data satu dosen berdasarkan ID unik.
     * * @param int|string $id ID dosen yang dicari.
     * @param string $col Nama kolom identitas (default: idDosen).
     * @return array|null Data dosen dalam bentuk array asosiatif atau null.
     */
    public function getById($id, $col = 'idDosen') : ?array {
        $id = (int)$id;
        $column = $this->db->real_escape_string($col);
        $query = "SELECT * FROM " . $this->table . " WHERE $column = ?";
        
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return null;
    }

    /**
     * Cari dosen berdasarkan nama (Case Insensitive, Exact or Trimmed).
     * Berguna untuk proses import data Excel/CSV.
     * * @param string $name Nama dosen.
     * @return array|null
     */
    public function getByName(string $name): ?array {
        $query = "SELECT * FROM {$this->table} WHERE LOWER(TRIM(nama)) = LOWER(TRIM(?)) LIMIT 1";
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param("s", $name);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc() ?: null;
        }
        return null;
    }
}
