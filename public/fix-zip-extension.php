<?php
/**
 * Script untuk mengecek dan memperbaiki masalah ekstensi ZIP
 */

echo "<h1>Diagnosa Masalah ZIP Extension</h1>";

// Check if ZIP extension is loaded
echo "<h2>1. Status Ekstensi PHP ZIP:</h2>";
if (extension_loaded('zip')) {
    echo "<span style='color: green'>✅ Ekstensi ZIP sudah aktif</span><br>";
} else {
    echo "<span style='color: red'>❌ Ekstensi ZIP TIDAK aktif</span><br>";
}

echo "<h2>2. Status Class ZipArchive:</h2>";
if (class_exists('ZipArchive')) {
    echo "<span style='color: green'>✅ Class ZipArchive tersedia</span><br>";
} else {
    echo "<span style='color: red'>❌ Class ZipArchive TIDAK tersedia</span><br>";
}

echo "<h2>3. Ekstensi yang Dimuat:</h2>";
$extensions = get_loaded_extensions();
echo "Total ekstensi: " . count($extensions) . "<br>";
echo "Ekstensi terkait file:<ul>";
foreach ($extensions as $ext) {
    if (stripos($ext, 'zip') !== false || stripos($ext, 'xml') !== false || stripos($ext, 'file') !== false) {
        echo "<li>$ext</li>";
    }
}
echo "</ul>";

echo "<h2>4. Path PHP.ini:</h2>";
echo "PHP.ini file: " . php_ini_loaded_file() . "<br>";
$additional_ini = php_ini_scanned_files();
if ($additional_ini) {
    echo "Additional ini files: " . $additional_ini . "<br>";
}

echo "<h2>5. Solusi untuk XAMPP:</h2>";
echo "<div style='background: #f0f0f0; padding: 15px; border-left: 4px solid #007bff;'>";
echo "<h3>Langkah-langkah Aktivasi Ekstensi ZIP:</h3>";
echo "<ol>";
echo "<li>Buka XAMPP Control Panel</li>";
echo "<li>Klik tombol 'Config' di sebelah Apache</li>";
echo "<li>Pilih 'PHP (php.ini)'</li>";
echo "<li>Cari baris <code>;extension=zip</code></li>";
echo "<li>Hapus tanda semicolon (;) di depannya menjadi <code>extension=zip</code></li>";
echo "<li>Simpan file dan restart Apache</li>";
echo "</ol>";
echo "</div>";

echo "<h2>6. Lokasi File PHP.ini XAMPP:</h2>";
echo "<code>C:\\xampp\\php\\php.ini</code><br><br>";

echo "<h2>7. Test Manual Edit PHP.ini:</h2>";
$php_ini_path = "C:\\xampp\\php\\php.ini";
if (file_exists($php_ini_path)) {
    echo "<span style='color: green'>✅ File php.ini ditemukan di: $php_ini_path</span><br>";
    
    // Read and check php.ini content
    $ini_content = file_get_contents($php_ini_path);
    if (strpos($ini_content, 'extension=zip') !== false) {
        echo "<span style='color: green'>✅ Ekstensi ZIP sudah diaktifkan di php.ini</span><br>";
    } elseif (strpos($ini_content, ';extension=zip') !== false) {
        echo "<span style='color: orange'>⚠️ Ekstensi ZIP ada tapi dikomentari (;extension=zip)</span><br>";
        echo "<strong>Solusi:</strong> Hapus tanda semicolon di depan extension=zip<br>";
    } else {
        echo "<span style='color: red'>❌ Tidak ada konfigurasi extension=zip di php.ini</span><br>";
        echo "<strong>Solusi:</strong> Tambahkan baris <code>extension=zip</code> di bagian ekstensi<br>";
    }
} else {
    echo "<span style='color: red'>❌ File php.ini tidak ditemukan di lokasi standar</span><br>";
}

echo "<h2>8. Alternative - Manual ZIP Enable:</h2>";
if (!extension_loaded('zip')) {
    echo "<div style='background: #fff3cd; padding: 15px; border: 1px solid #ffeaa7;'>";
    echo "<h4>Jika ekstensi ZIP tidak bisa diaktifkan, gunakan solusi alternatif:</h4>";
    echo "<ol>";
    echo "<li>Download ekstensi ZIP manual</li>";
    echo "<li>Atau gunakan library CSV sebagai alternatif</li>";
    echo "<li>Atau install XAMPP versi yang lebih baru</li>";
    echo "</ol>";
    echo "</div>";
}

echo "<h2>9. Quick Fix Script:</h2>";
?>
<form method="post">
    <button type="submit" name="enable_zip" style="background: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 5px;">
        Coba Aktifkan ZIP Extension Otomatis
    </button>
</form>

<?php
if (isset($_POST['enable_zip'])) {
    echo "<h3>Mencoba mengaktifkan ekstensi ZIP...</h3>";
    
    $php_ini_path = "C:\\xampp\\php\\php.ini";
    if (file_exists($php_ini_path)) {
        $ini_content = file_get_contents($php_ini_path);
        
        if (strpos($ini_content, ';extension=zip') !== false) {
            $new_content = str_replace(';extension=zip', 'extension=zip', $ini_content);
            if (file_put_contents($php_ini_path, $new_content)) {
                echo "<span style='color: green'>✅ Berhasil mengubah php.ini</span><br>";
                echo "<span style='color: blue'>ℹ️ Silakan restart Apache XAMPP untuk menerapkan perubahan</span><br>";
            } else {
                echo "<span style='color: red'>❌ Gagal mengubah php.ini (permission denied)</span><br>";
            }
        } else {
            echo "<span style='color: orange'>⚠️ Ekstensi ZIP sudah aktif atau tidak ditemukan dalam format yang diharapkan</span><br>";
        }
    } else {
        echo "<span style='color: red'>❌ File php.ini tidak ditemukan</span><br>";
    }
}

echo "<h2>10. Test Setelah Fix:</h2>";
?>
<a href="simple-upload-test.php" style="background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
    Test Upload Lagi
</a>