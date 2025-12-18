<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sistem Lab</title>
    <?php
    // Config sudah di-load dari admin-dashboard.php
    // Fallback jika tidak di-load, gunakan path absolut
    if (!defined('DB_HOST')) {
        // Gunakan __DIR__ untuk akurasi yang lebih baik
        if (!defined('ROOT_PROJECT')) {
            $currentDir = __DIR__;  // templates folder
            $adminDir = dirname($currentDir);  // admin
            $viewsDir = dirname($adminDir);  // views
            $appDir = dirname($viewsDir);  // app
            $rootDir = dirname($appDir);  // ROOT
            define('ROOT_PROJECT', $rootDir);
        }
        
        $configPath = ROOT_PROJECT . '/app/config/config.php';
        
        if (file_exists($configPath)) {
            require_once $configPath;
        } else {
            die('Config file not found: ' . $configPath);
        }
    }
    ?>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        // Global variable untuk semua JavaScript
        const API_URL = '<?php echo API_URL; ?>';
        const BASE_URL = '<?php echo BASE_URL; ?>';
        const ASSETS_URL = '<?php echo ASSETS_URL; ?>';
        console.log('BASE_URL:', BASE_URL);
        console.log('ASSETS_URL:', ASSETS_URL);
        console.log('API_URL:', API_URL);
    </script>
</head>
<body>

    <?php
    // Logika untuk mengecek URL saat ini
    // Agar menu sidebar bisa "Aktif" otomatis sesuai halaman yang dibuka
    $uri = $_SERVER['REQUEST_URI'];
    ?>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li>
                <a href="<?php echo BASE_URL; ?>/public/admin-dashboard.php" class="<?= strpos($uri, 'admin-dashboard') !== false ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            
            <li>
                <a href="<?php echo BASE_URL; ?>/public/admin-asisten.php" class="<?= strpos($uri, 'admin-asisten') !== false ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Data Asisten
                </a>
            </li>
            
            <li>
                <a href="<?php echo BASE_URL; ?>/public/admin-alumni.php" class="<?= strpos($uri, 'admin-alumni') !== false ? 'active' : '' ?>">
                    <i class="fas fa-user-graduate"></i> Data Alumni
                </a>
            </li>
            
            <li>
                <a href="<?php echo BASE_URL; ?>/public/admin-laboratorium.php" class="<?= strpos($uri, 'admin-laboratorium') !== false ? 'active' : '' ?>">
                    <i class="fas fa-desktop"></i> Data Fasilitas
                </a>
            </li>
            
            <li>
                <a href="<?php echo BASE_URL; ?>/public/admin-matakuliah.php" class="<?= strpos($uri, 'admin-matakuliah') !== false ? 'active' : '' ?>">
                    <i class="fas fa-book"></i> Data Mata Kuliah
                </a>
            </li>
            
            <li>
                <a href="<?php echo BASE_URL; ?>/public/admin-jadwal.php" class="<?= strpos($uri, 'admin-jadwal') !== false ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i> Jadwal Praktikum
                </a>
            </li>

            <li>
                <a href="<?php echo BASE_URL; ?>/public/admin-peraturan.php" class="<?= strpos($uri, 'admin-peraturan') !== false ? 'active' : '' ?>">
                    <i class="fas fa-gavel"></i> Peraturan Lab
                </a>
            </li>

            <li>
                <a href="<?php echo BASE_URL; ?>/public/admin-sanksi.php" class="<?= strpos($uri, 'admin-sanksi') !== false ? 'active' : '' ?>">
                    <i class="fas fa-exclamation-triangle"></i> Sanksi Lab
                </a>
            </li>

            <li>
                <a href="<?php echo BASE_URL; ?>/public/admin-manajemen.php" class="<?= strpos($uri, 'admin-manajemen') !== false ? 'active' : '' ?>">
                    <i class="fas fa-user-tie"></i> Kepala Laboratorium
                </a>
            </li>
            
            <li>
                <a href="<?php echo BASE_URL; ?>/public/index.php" style="margin-top: 50px; color: #e74c3c;">
                    <i class="fas fa-sign-out-alt"></i> Logout / Ke Web Utama
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="admin-header">
            <h1>Selamat Datang, Admin!</h1>
            <div>
                <span style="margin-right: 15px;">Halo, Administrator</span>
                <img src="https://placehold.co/40x40" style="border-radius: 50%; vertical-align: middle;">
            </div>
        </div>