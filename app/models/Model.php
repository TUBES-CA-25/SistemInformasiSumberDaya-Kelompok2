<?php

/**
 * Base Model Class
 * * Menyediakan fungsionalitas CRUD (Create, Read, Update, Delete) dasar
 * menggunakan driver MySQLi. Semua model di aplikasi harus mewarisi kelas ini.
 * * @package App\Models
 */

require_once __DIR__ . '/../config/Database.php';

class Model {
    /** * @var mysqli $db Instance koneksi database 
     */
    public $db;

    /** * @var string $table Nama tabel database yang didefinisikan di child class 
     */
    protected $table;

    /**
     * Inisialisasi koneksi database saat objek dibuat.
     */
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * Mengambil instance koneksi database.
     * * @return mysqli
     */
    public function getDb(): mysqli {
        return $this->db;
    }

    /**
     * Mengambil seluruh record dari tabel.
     * * @return array Kumpulan data dalam format array asosiatif.
     */
    public function getAll(): array {
        $query = "SELECT * FROM {$this->table}";
        $result = $this->db->query($query);
        
        if (!$result) return [];
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Menghitung total jumlah record dalam tabel.
     * * @return int Total jumlah record.
     */
    public function countAll(): int {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->query($query);
        
        if ($result) {
            $row = $result->fetch_assoc();
            return (int) $row['total'];
        }
        
        return 0;
    }

    /**
     * Mencari data berdasarkan ID unik.
     * * @param int|string $id Nilai ID yang dicari.
     * @param string $idColumn Nama kolom primary key (default: 'id').
     * @return array|null Data hasil pencarian atau null jika tidak ditemukan.
     */
    public function getById($id, string $idColumn = 'id'): ?array {
        $query = "SELECT * FROM {$this->table} WHERE {$idColumn} = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        
        if ($stmt) {
            // "s" digunakan agar fleksibel untuk ID bertipe string maupun integer
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
            
            return $data ?: null;
        }
        
        return null;
    }

    /**
     * Menyisipkan data baru secara dinamis.
     * * @param array $data Array asosiatif (nama_kolom => nilai).
     * @return bool True jika berhasil, false jika gagal.
     */
    public function insert(array $data): bool {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $types = str_repeat("s", count($data)); // Default semua tipe data ke string

        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($query);
        
        if (!$stmt) return false;
        
        $stmt->bind_param($types, ...array_values($data));
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    /**
     * Memperbarui data berdasarkan ID.
     * * @param int|string $id ID record yang akan diupdate.
     * @param array $data Array asosiatif data baru.
     * @param string $idColumn Nama kolom primary key.
     * @return bool
     */
    public function update($id, array $data, string $idColumn = 'id'): bool {
        $set = "";
        $types = "";
        $values = [];

        foreach ($data as $column => $value) {
            $set .= "{$column} = ?, ";
            $types .= "s";
            $values[] = $value;
        }

        $set = rtrim($set, ", ");
        $types .= "s"; // Tipe data untuk ID
        $values[] = $id;

        $query = "UPDATE {$this->table} SET {$set} WHERE {$idColumn} = ?";
        $stmt = $this->db->prepare($query);
        
        if (!$stmt) return false;
        
        $stmt->bind_param($types, ...$values);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /**
     * Menghapus record dari database.
     * * @param int|string $id
     * @param string $idColumn
     * @return bool
     */
    public function delete($id, string $idColumn = 'id'): bool {
        $query = "DELETE FROM {$this->table} WHERE {$idColumn} = ?";
        $stmt = $this->db->prepare($query);
        
        if (!$stmt) return false;

        $stmt->bind_param("s", $id);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    /**
     * Mengambil ID terakhir yang dibuat oleh operasi INSERT.
     * * @return int|string
     */
    public function getLastInsertId() {
        return $this->db->insert_id;
    }
}