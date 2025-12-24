<?php
// FILE: public/index.php

// 1. Tampilkan Error (Debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Definisi Folder Utama (ROOT)
define('ROOT_PROJECT', dirname(__DIR__)); 

// [2.1] LOAD CONFIGURATION (Wajib untuk Konstanta ASSETS_URL, DB_HOST, dll)
require_once ROOT_PROJECT . '/app/config/config.php';

// [2.2] LOAD DATABASE CONNECTION (Wajib agar $pdo tersedia)
// Pastikan file ini ada di folder app/config/
require_once ROOT_PROJECT . '/app/config/database.php';

// 3. Tangkap Request Halaman (Default 'home')
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// 4. Konfigurasi Halaman
$contentView = '';
$pageCss = ''; 

switch ($page) {
    case 'home':
        $contentView = '/app/views/home/index.php';
        $pageCss = 'home.css';
        break;

    // --- FITUR APPS (BARU) ---
    case 'apps':
        // Pastikan Anda menyimpan file apps.php di folder app/views/home/
        $contentView = '/app/views/home/apps.php'; 
        $pageCss = 'apps.css';
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

// a. Header
require_once ROOT_PROJECT . '/app/views/templates/header.php';

// b. Konten Tengah
if (file_exists(ROOT_PROJECT . $contentView)) {
    require_once ROOT_PROJECT . $contentView;
} else {
    echo "<div style='text-align:center; padding:50px;'>";
    echo "<h2 style='color:red;'>Error: File View Tidak Ditemukan</h2>";
    echo "<p>Sistem mencoba membuka: <strong>" . $contentView . "</strong></p>";
    echo "</div>";
}

// c. Footer
require_once ROOT_PROJECT . '/app/views/templates/footer.php';
?>