<?php
/**
 * Fix old photo paths in database
 * Mengubah path foto dari /SistemInformasiSumberDaya-Kelompok2/storage/uploads/
 * ke /SistemManagementSumberDaya/storage/uploads/
 */

// Koneksi database - sesuaikan dengan config Anda
require_once 'config/database.php';

$oldPath = '/SistemInformasiSumberDaya-Kelompok2/storage/uploads/';
$newPath = '/SistemManagementSumberDaya/storage/uploads/';

try {
    // Fix alumni table
    $stmt = $db->prepare("UPDATE alumni SET foto = REPLACE(foto, :oldPath, :newPath)");
    $stmt->execute([
        ':oldPath' => $oldPath,
        ':newPath' => $newPath
    ]);
    echo "✓ Updated alumni table: " . $stmt->rowCount() . " records\n";
    
    // Fix asisten table (jika ada kolom foto)
    $stmt = $db->prepare("UPDATE asisten SET foto = REPLACE(foto, :oldPath, :newPath) WHERE foto LIKE :pattern");
    $stmt->execute([
        ':oldPath' => $oldPath,
        ':newPath' => $newPath,
        ':pattern' => '%/SistemInformasiSumberDaya%'
    ]);
    echo "✓ Updated asisten table: " . $stmt->rowCount() . " records\n";
    
    // Fix peraturan_lab table (jika ada)
    $stmt = $db->prepare("UPDATE peraturan_lab SET gambar = REPLACE(gambar, :oldPath, :newPath) WHERE gambar LIKE :pattern");
    $stmt->execute([
        ':oldPath' => $oldPath,
        ':newPath' => $newPath,
        ':pattern' => '%/SistemInformasiSumberDaya%'
    ]);
    echo "✓ Updated peraturan_lab table: " . $stmt->rowCount() . " records\n";
    
    // Fix sanksi_lab table (jika ada)
    $stmt = $db->prepare("UPDATE sanksi_lab SET gambar = REPLACE(gambar, :oldPath, :newPath) WHERE gambar LIKE :pattern");
    $stmt->execute([
        ':oldPath' => $oldPath,
        ':newPath' => $newPath,
        ':pattern' => '%/SistemInformasiSumberDaya%'
    ]);
    echo "✓ Updated sanksi_lab table: " . $stmt->rowCount() . " records\n";
    
    echo "\n✅ All paths have been updated successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
