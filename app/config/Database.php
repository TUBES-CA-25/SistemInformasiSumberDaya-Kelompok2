<?php
/**
 * Database Connection Configuration
 * 
 * File ini menangani koneksi database menggunakan PDO (PHP Data Objects)
 * Mendukung PDO global dan Database wrapper class untuk kompatibilitas
 */

require_once __DIR__ . '/config.php';

// ============================================
// SECTION 1: PDO CONNECTION
// ============================================
// Membuat koneksi database global menggunakan PDO
// PDO lebih aman dan mendukung prepared statements

try {
    // Persiapkan DSN (Data Source Name)
    // Format: mysql:host=localhost;dbname=nama_database;charset=utf8mb4
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    // Opsi konfigurasi PDO untuk keamanan dan konsistensi
    $options = [
        // ERRMODE_EXCEPTION: Error ditangani sebagai exception untuk debugging lebih baik
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        
        // FETCH_ASSOC: Data query otomatis dikembalikan sebagai array asosiatif
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        
        // Emulate prepares FALSE: Gunakan prepared statements native untuk keamanan SQL Injection
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    // Buat instance PDO global
    // Variabel $pdo ini dapat diakses di seluruh aplikasi
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (PDOException $e) {
    // Tangani error koneksi database
    if (defined('APP_ENV') && APP_ENV === 'development') {
        // Tampilkan detail error untuk development
        die("Koneksi Database Gagal: " . $e->getMessage());
    } else {
        // Tampilkan pesan ramah untuk production
        die("Maaf, sistem sedang mengalami gangguan koneksi database.");
    }
}

// ============================================
// SECTION 2: DATABASE WRAPPER CLASS
// ============================================
// Class Database untuk kompatibilitas dengan Model.php
// Menyediakan interface yang fleksibel untuk akses database

class Database {

    // -------- Properties --------
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $mysqli;
    private $pdo;

    // -------- Constructor --------
    /**
     * Inisialisasi Database class
     * Mengambil koneksi PDO global untuk konsistensi
     */
    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    // -------- Methods --------
    /**
     * Buat koneksi MySQLi
     * 
     * @return mysqli
     * @throws Exception
     */
    public function connect()
    {
        // Return jika koneksi sudah ada
        if ($this->mysqli) {
            return $this->mysqli;
        }

        // Buat instance MySQLi baru
        $this->mysqli = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->dbname
        );

        // Cek error koneksi
        if ($this->mysqli->connect_error) {
            throw new Exception("Connection failed: " . $this->mysqli->connect_error);
        }

        return $this->mysqli;
    }

    /**
     * Dapatkan koneksi database
     * 
     * @return mysqli
     */
    public function getConnection()
    {
        return $this->connect();
    }

    /**
     * Dapatkan koneksi PDO
     * 
     * @return PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }
}

?>