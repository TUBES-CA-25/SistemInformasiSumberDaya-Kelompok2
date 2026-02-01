<?php
/**
 * --------------------------------------------------------------------------
 * Database Connection Manager (Hybrid Version)
 * --------------------------------------------------------------------------
 * File ini menangani dua jenis koneksi sekaligus untuk kompatibilitas:
 * 1. PDO (Global): Digunakan oleh kode Anda (Native/Procedural).
 * 2. MySQLi (OOP Wrapper): Digunakan oleh kode teman Anda (Model/Class).
 *
 * @author   Sistem Team
 * @package  Core
 */

require_once __DIR__ . '/config.php';

// ==========================================================================
// BAGIAN 1: GLOBAL PDO INSTANCE (UNTUK KODE ANDA)
// ==========================================================================

/**
 * Variabel global $pdo.
 * Dapat diakses langsung di file lain menggunakan 'global $pdo'.
 */
$pdo = null;

try {
    // 1. Menyusun DSN (Data Source Name)
    // Menentukan driver (mysql), host, nama database, dan encoding karakter.
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    // 2. Konfigurasi Opsi PDO
    $options = [
        // Melempar exception jika terjadi error SQL (memudahkan debugging)
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // Mengembalikan data sebagai Array Asosiatif secara default
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Mematikan emulasi prepare statement (meningkatkan keamanan dari SQL Injection)
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // 3. Inisialisasi Koneksi PDO
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (PDOException $e) {
    // 4. Penanganan Error Koneksi (Fail-safe)
    // Jika mode development, tampilkan error asli. Jika production, tampilkan pesan umum.
    $errorMessage = (defined('APP_ENV') && APP_ENV === 'development')
        ? "Koneksi Database Gagal: " . $e->getMessage()
        : "Maaf, sistem sedang mengalami gangguan koneksi database.";
    
    die($errorMessage);
}

// ==========================================================================
// BAGIAN 2: CLASS DATABASE WRAPPER (UNTUK KODE TEMAN/MODEL.PHP)
// ==========================================================================

/**
 * Class Database
 * Wrapper class untuk mengakomodasi gaya penulisan OOP teman Anda.
 * Class ini mendukung lazy-loading untuk koneksi MySQLi.
 */
class Database
{
    // Properti Konfigurasi Database
    private string $host = DB_HOST;
    private string $user = DB_USER;
    private string $pass = DB_PASS;
    private string $dbname = DB_NAME;

    // Properti Penyimpan Koneksi
    // Tanda tanya (?) berarti boleh null (belum terkoneksi)
    private ?mysqli $mysqli = null;
    private ?PDO $pdo = null;

    /**
     * Constructor
     * Mengambil instance PDO yang sudah dibuat di Bagian 1.
     */
    public function __construct()
    {
        // Mengambil variabel $pdo dari scope global agar resource hemat
        global $pdo;
        $this->pdo = $pdo;
    }

    /**
     * Membuka atau mengambil koneksi MySQLi (Lazy Connection).
     * Koneksi hanya dibuat jika method ini dipanggil.
     *
     * @return mysqli Object koneksi MySQLi
     * @throws Exception Jika koneksi gagal
     */
    public function connect(): mysqli
    {
        // Cek: Jika sudah ada koneksi tersimpan, pakai yang itu (Singleton pattern sederhana)
        if ($this->mysqli instanceof mysqli) {
            return $this->mysqli;
        }

        // Jika belum ada, buat koneksi baru menggunakan driver MySQLi
        $this->mysqli = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->dbname
        );

        // Cek apakah ada error saat connect
        if ($this->mysqli->connect_error) {
            throw new Exception("MySQLi Connection failed: " . $this->mysqli->connect_error);
        }

        return $this->mysqli;
    }

    /**
     * Alias untuk method connect().
     * Berguna jika kode teman Anda memanggil getConnection() alih-alih connect().
     *
     * @return mysqli
     */
    public function getConnection(): mysqli
    {
        return $this->connect();
    }

    /**
     * Mengambil instance PDO.
     * Berguna jika di dalam Class/Model teman Anda tiba-tiba ingin memakai PDO.
     *
     * @return PDO|null
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }
}
?>