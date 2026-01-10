<?php
/**
 * Migration Script: Menambah kolom display_format
 * Jalankan: php app/migrations/migrate_display_format.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Database.php';

try {
    $db = new Database();
    $conn = $db->connect();
    
    if (!$conn) {
        die("Koneksi database gagal\n");
    }
    
    echo "🔄 Memulai migration...\n";
    
    // 1. Tambah kolom ke peraturan_lab jika belum ada
    $checkPeraturan = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                       WHERE TABLE_NAME='peraturan_lab' AND COLUMN_NAME='display_format' AND TABLE_SCHEMA='" . DB_NAME . "'";
    $result = $conn->query($checkPeraturan);
    
    if ($result->num_rows == 0) {
        $alterPeraturan = "ALTER TABLE `peraturan_lab` ADD COLUMN `display_format` VARCHAR(20) DEFAULT 'list' COMMENT 'Format tampilan: list atau plain'";
        if ($conn->query($alterPeraturan)) {
            echo "✅ Kolom display_format berhasil ditambahkan ke tabel peraturan_lab\n";
        } else {
            echo "❌ Gagal menambah kolom ke peraturan_lab: " . $conn->error . "\n";
        }
    } else {
        echo "ℹ️  Kolom display_format sudah ada di peraturan_lab\n";
    }
    
    // 2. Tambah kolom ke sanksi_lab jika belum ada
    $checkSanksi = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE TABLE_NAME='sanksi_lab' AND COLUMN_NAME='display_format' AND TABLE_SCHEMA='" . DB_NAME . "'";
    $result = $conn->query($checkSanksi);
    
    if ($result->num_rows == 0) {
        $alterSanksi = "ALTER TABLE `sanksi_lab` ADD COLUMN `display_format` VARCHAR(20) DEFAULT 'list' COMMENT 'Format tampilan: list atau plain'";
        if ($conn->query($alterSanksi)) {
            echo "✅ Kolom display_format berhasil ditambahkan ke tabel sanksi_lab\n";
        } else {
            echo "❌ Gagal menambah kolom ke sanksi_lab: " . $conn->error . "\n";
        }
    } else {
        echo "ℹ️  Kolom display_format sudah ada di sanksi_lab\n";
    }
    
    // 3. Set default value untuk data yang ada
    $updatePeraturan = "UPDATE `peraturan_lab` SET `display_format` = 'list' WHERE `display_format` IS NULL OR `display_format` = ''";
    $conn->query($updatePeraturan);
    
    $updateSanksi = "UPDATE `sanksi_lab` SET `display_format` = 'list' WHERE `display_format` IS NULL OR `display_format` = ''";
    $conn->query($updateSanksi);
    
    echo "✅ Migration selesai!\n";
    echo "📊 Kolom display_format sekarang tersedia dengan nilai default 'list'\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
