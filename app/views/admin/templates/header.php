<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sistem Lab</title>
    <?php
    // [BAGIAN 1: KONFIGURASI]
    // Tetap dipertahankan sesuai permintaan Anda (Fallback Config)
    if (!defined('DB_HOST')) {
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
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        // Global variable untuk semua JavaScript
        const API_URL = '<?php echo API_URL; ?>';
        const BASE_URL = '<?php echo BASE_URL; ?>';
        const ASSETS_URL = '<?php echo ASSETS_URL; ?>';
        console.log('BASE_URL:', BASE_URL);
        console.log('ASSETS_URL:', ASSETS_URL);
        console.log('API_URL:', API_URL);

        // Simple navigate helper used by admin buttons
        function navigate(path) {
            if (!path) return;
            if (path.startsWith('http://') || path.startsWith('https://')) {
                window.location.href = path;
                return;
            }
            // ensure leading slash
            const p = path.startsWith('/') ? path : '/' + path;
            window.location.href = '<?php echo rtrim(PUBLIC_URL, "/"); ?>' + p;
        }
    </script>
</head>
<body>

    <?php
    // Logika untuk mengecek URL saat ini
    $uri = $_SERVER['REQUEST_URI'];
    ?>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li>
                <a href="<?= PUBLIC_URL ?>/admin" 
                   class="<?= (strpos($uri, '/admin') !== false && strpos($uri, '/admin/') === false) ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            
            <li>
                <a href="<?= PUBLIC_URL ?>/admin/asisten" 
                   class="<?= strpos($uri, '/admin/asisten') !== false ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Data Asisten
                </a>
            </li>
            
            <li>
                <a href="<?= PUBLIC_URL ?>/admin/alumni" 
                   class="<?= strpos($uri, '/admin/alumni') !== false ? 'active' : '' ?>">
                    <i class="fas fa-user-graduate"></i> Data Alumni
                </a>
            </li>
            
            <li>
                <a href="<?= PUBLIC_URL ?>/admin/laboratorium" 
                   class="<?= strpos($uri, '/admin/laboratorium') !== false ? 'active' : '' ?>">
                    <i class="fas fa-desktop"></i> Data Fasilitas
                </a>
            </li>
            
            <li>
                <a href="<?= PUBLIC_URL ?>/admin/matakuliah" 
                   class="<?= strpos($uri, '/admin/matakuliah') !== false ? 'active' : '' ?>">
                    <i class="fas fa-book"></i> Data Mata Kuliah
                </a>
            </li>
            
            <li>
                <a href="<?= PUBLIC_URL ?>/admin/jadwal" 
                   class="<?= strpos($uri, '/admin/jadwal') !== false ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i> Jadwal Praktikum
                </a>
            </li>

            <li>
                <a href="<?= PUBLIC_URL ?>/admin/peraturan" 
                   class="<?= strpos($uri, '/admin/peraturan') !== false ? 'active' : '' ?>">
                    <i class="fas fa-gavel"></i> Peraturan Lab
                </a>
            </li>

            <li>
                <a href="<?= PUBLIC_URL ?>/admin/sanksi" 
                   class="<?= strpos($uri, '/admin/sanksi') !== false ? 'active' : '' ?>">
                    <i class="fas fa-exclamation-triangle"></i> Sanksi Lab
                </a>
            </li>

            <li>
                <a href="<?= PUBLIC_URL ?>/admin/manajemen" 
                   class="<?= strpos($uri, '/admin/manajemen') !== false ? 'active' : '' ?>">
                    <i class="fas fa-user-tie"></i> Kepala Laboratorium
                </a>
            </li>
            
            <li>
                <a href="<?= PUBLIC_URL ?>/logout" style="margin-top: 50px; color: #e74c3c;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="admin-header">
            <h1>Selamat Datang, Admin!</h1>
            <div>
                <span style="margin-right: 15px;">Halo, <?= $_SESSION['username'] ?? 'Admin' ?></span>
                <a href="<?= PUBLIC_URL ?>/logout" style="color: #e74c3c; margin-right: 15px; text-decoration: none; font-weight: bold;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <img src="https://placehold.co/40x40" style="border-radius: 50%; vertical-align: middle;">
            </div>
        </div>