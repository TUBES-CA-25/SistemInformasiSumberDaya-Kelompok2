<?php

require_once __DIR__ . '/Model.php';

/**
 * Model Pengaturan
 * Mengelola konfigurasi umum aplikasi (Key-Value) di database.
 */
class PengaturanModel extends Model 
{
    protected $table = 'pengaturan';

    public function __construct() 
    {
        parent::__construct();
        $this->ensureTableExists();
    }

    /**
     * Memastikan tabel pengaturan tersedia.
     */
    private function ensureTableExists(): void 
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            kunci VARCHAR(50) NOT NULL PRIMARY KEY,
            nilai TEXT NULL,
            keterangan VARCHAR(255) NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $this->db->query($sql);

        // Nilai default untuk jadwal_upk_aktif (1 = Aktif)
        $this->db->query("
            INSERT INTO {$this->table} (kunci, nilai, keterangan)
            VALUES ('jadwal_upk_aktif', '1', 'Status visibilitas Jadwal UPK pada tampilan utama (1=Aktif, 0=Nonaktif)')
            ON DUPLICATE KEY UPDATE kunci = kunci
        ");
    }

    /**
     * Mengambil nilai pengaturan berdasarkan key.
     */
    public function get(string $key, $default = null): ?string 
    {
        $stmt = $this->db->prepare("SELECT nilai FROM {$this->table} WHERE kunci = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $key);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            if ($res !== null) {
                return $res['nilai'];
            }
        }
        return $default;
    }

    /**
     * Menyimpan atau memperbarui nilai pengaturan.
     */
    public function set(string $key, $value, ?string $keterangan = null): bool 
    {
        $valStr = (string)$value;
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (kunci, nilai, keterangan) 
            VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE nilai = VALUES(nilai), keterangan = COALESCE(VALUES(keterangan), keterangan)
        ");
        if ($stmt) {
            $stmt->bind_param("sss", $key, $valStr, $keterangan);
            return $stmt->execute();
        }
        return false;
    }

    /**
     * Helper statis untuk mengambil pengaturan.
     */
    public static function getSetting(string $key, $default = null): ?string 
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance->get($key, $default);
    }

    /**
     * Helper statis untuk menyimpan pengaturan.
     */
    public static function setSetting(string $key, $value, ?string $keterangan = null): bool 
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance->set($key, $value, $keterangan);
    }
}
