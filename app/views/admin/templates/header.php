<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sistem Lab</title>
    <?php
    // Load config untuk URL yang konsisten - HARUS SEBELUM DIPAKAI!
    require_once '../../../app/config.php';
    ?>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/admin.css">
    <script>
        // Global variable untuk semua JavaScript
        const API_URL = '<?php echo API_URL; ?>';
        const BASE_URL = '<?php echo BASE_URL; ?>';
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
                <a href="<?php echo BASE_URL; ?>/public/admin-dashboard.php" class="<?= strpos($uri, 'admin-dashboard') !== false ? 'active' : '' ?>">Dashboard</a>
            </li>
            
            <li>
                <a href="<?php echo BASE_URL; ?>/public/admin-asisten.php" class="<?= strpos($uri, 'admin-asisten') !== false ? 'active' : '' ?>">Data Asisten</a>
            </li>
            
            <li>
                <a href="<?php echo BASE_URL; ?>/public/admin-alumni.php" class="<?= strpos($uri, 'admin-alumni') !== false ? 'active' : '' ?>">Data Alumni</a>
            </li>
            
            <li>
                <a href="<?php echo BASE_URL; ?>/public/admin-laboratorium.php" class="<?= strpos($uri, 'admin-laboratorium') !== false ? 'active' : '' ?>">Data Fasilitas</a>
            </li>
            
            <li>
                <a href="<?php echo BASE_URL; ?>/public/admin-matakuliah.php" class="<?= strpos($uri, 'admin-matakuliah') !== false ? 'active' : '' ?>">Data Mata Kuliah</a>
            </li>
            
            <li>
                <li><a href="<?php echo BASE_URL; ?>/public/admin-jadwal.php" class="<?= strpos($uri, 'admin-jadwal') !== false ? 'active' : '' ?>">Jadwal Praktikum</a></li>
            </li>
            
            <li>
                <a href="<?php echo BASE_URL; ?>/public/index.php" style="margin-top: 50px; color: #e74c3c;">Logout / Ke Web Utama</a>
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