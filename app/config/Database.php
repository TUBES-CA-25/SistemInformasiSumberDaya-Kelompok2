<?php
/**
 * DATABASE CONNECTION (HYBRID VERSION)
 * 1. Bagian Atas: PDO (Kode asli Anda, tidak ada yang dikurangi).
 * 2. Bagian Bawah: Class Database (Tambahan agar Model.php teman Anda tidak error).
 */

require_once __DIR__ . '/config.php';

// =================================================================
// BAGIAN 1: KODE ASLI ANDA (PDO)
// Variabel global $pdo ini tetap bisa Anda pakai di file lain.
// =================================================================
try {
    // 1. Siapkan DSN (Data Source Name)
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    // 2. Opsi Konfigurasi PDO
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Error akan mematikan script (bagus untuk debugging)
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // Data otomatis jadi array asosiatif
        PDO::ATTR_EMULATE_PREPARES   => false,                // Keamanan (mencegah SQL Injection)
    ];

    // 3. Buat Object PDO (Variabel Global $pdo)
    // Variabel inilah yang akan dipanggil di asisten.php, home.php, dll.
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (PDOException $e) {
    // 4. Tangani Error Koneksi
    if (defined('APP_ENV') && APP_ENV === 'development') {
        die("Koneksi Database Gagal: " . $e->getMessage());
    } else {
        // Pesan ramah user untuk mode production
        die("Maaf, sistem sedang mengalami gangguan koneksi database.");
    }
}


// =================================================================
// BAGIAN 2: TAMBAHAN UNTUK TEMAN ANDA (CLASS DATABASE WRAPPER)
// Wajib ditambahkan karena Model.php memanggil "new Database()"
// =================================================================
class Database {

    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $mysqli;
    private $pdo;

    public function __construct()
    {
        // gunakan PDO global agar view tetap jalan
        global $pdo;
        $this->pdo = $pdo;
    }

    public function connect()
    {
        if ($this->mysqli) return $this->mysqli;

        $this->mysqli = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->dbname
        );

        if ($this->mysqli->connect_error) {
            throw new Exception("Connection failed: " . $this->mysqli->connect_error);
        }

        return $this->mysqli;
    }

    public function getConnection()
    {
        // Cukup panggil fungsi connect() yang sudah ada
        return $this->connect();
    }

    // >>>> tambahan ini <<<<
    public function getPdo()
    {
        return $this->pdo;
    }
}

?>