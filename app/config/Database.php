<?php
/**
 * DATABASE CONNECTION (PDO VERSION)
 * Menggantikan MySQLi agar kompatibel dengan fitur modern.
 */

require_once __DIR__ . '/config.php';

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
?>