<?php
// FILE: public/index.php

// 1. Tampilkan Error (Penting untuk debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Definisi Folder Utama (ROOT)
// __DIR__ = .../SistemInformasiSumberDaya/public
// dirname(__DIR__) = .../SistemInformasiSumberDaya (Naik satu level)
define('ROOT_PROJECT', dirname(__DIR__)); 

// 3. Tangkap Request Halaman (Default 'home')
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// 4. Konfigurasi Halaman (Path File View & Nama CSS)
$contentView = '';
$pageCss = ''; 

switch ($page) {
    case 'home':
        $contentView = '/app/views/home/index.php';
        $pageCss = 'home.css';
        break;

    // --- PRAKTIKUM ---
    case 'tatatertib':
        $contentView = '/app/views/praktikum/tatatertib.php';
        $pageCss = 'praktikum.css';
        break;
    case 'jadwal':
        $contentView = '/app/views/praktikum/jadwal.php';
        $pageCss = 'praktikum.css';
        break;

    // --- SUMBER DAYA ---
    case 'asisten':
        $contentView = '/app/views/sumberdaya/asisten.php';
        $pageCss = 'sumberdaya.css'; 
        break;
    case 'detail': 
        $contentView = '/app/views/sumberdaya/detail.php';
        $pageCss = 'sumberdaya.css'; 
        break;
    case 'kepala':
        $contentView = '/app/views/sumberdaya/kepala.php';
        $pageCss = 'sumberdaya.css';
        break;
    
    // --- FASILITAS ---
    case 'laboratorium':
        $contentView = '/app/views/fasilitas/laboratorium.php';
        $pageCss = 'fasilitas.css';
        break;
    case 'riset':
        $contentView = '/app/views/fasilitas/riset.php';
        $pageCss = 'fasilitas.css';
        break;
    case 'detail_fasilitas':
        $contentView = '/app/views/fasilitas/detail.php'; 
        $pageCss = 'fasilitas.css';
        break;

    // --- LAINNYA ---
    case 'alumni':
        $contentView = '/app/views/alumni/alumni.php'; 
        $pageCss = 'alumni.css'; 
        break;

    case 'detail_alumni':
        $contentView = '/app/views/alumni/detail.php'; 
        $pageCss = 'alumni.css';
        break;

    case 'contact':
        $contentView = '/app/views/contact/index.php';
        $pageCss = 'contact.css';
        break;

    default:
        echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
        exit;
}

// 5. RAKIT HALAMAN
// Gunakan require_once dengan Path Absolut (ROOT_PROJECT)

// a. Header
require_once ROOT_PROJECT . '/app/views/templates/header.php';

// b. Konten Tengah
if (file_exists(ROOT_PROJECT . $contentView)) {
    require_once ROOT_PROJECT . $contentView;
} else {
    echo "<div style='text-align:center; padding:50px; font-family:sans-serif;'>";
    echo "<h2 style='color:red;'>Error: File View Tidak Ditemukan</h2>";
    echo "<p>Sistem mencoba membuka: <strong>" . $contentView . "</strong></p>";
    echo "<p>Pastikan file tersebut ada di dalam folder <code>app/views/...</code></p>";
    echo "</div>";
}

// c. Footer
require_once ROOT_PROJECT . '/app/views/templates/footer.php';
?>