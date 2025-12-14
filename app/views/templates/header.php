<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Sumber Daya Lab</title>
    
    <?php 
    // Load config untuk base URL
    if (!defined('ROOT_PROJECT')) {
        define('ROOT_PROJECT', dirname(dirname(dirname(__FILE__))));
    }
    require_once ROOT_PROJECT . '/app/config.php';
    ?>
    
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
    
    <script>
        // Global variable untuk semua JavaScript
        const API_URL = '<?php echo API_URL; ?>';
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    </head>
<body>

    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="<?php echo BASE_URL; ?>/public/index.php">
                    ★ LOGO
                </a>
            </div>
            <ul class="nav-links">
                <li><a href="<?php echo BASE_URL; ?>/public/index.php">Home</a></li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">Praktikum ▾</a>
                    <div class="dropdown-content">
                        <a href="<?php echo BASE_URL; ?>/public/praktikum.php">Peraturan & Ketentuan</a>
                        <a href="<?php echo BASE_URL; ?>/public/sanksi.php">Sanksi Pelanggaran</a> <a href="<?php echo BASE_URL; ?>/public/jadwal.php">Jadwal Praktikum</a> </div>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropbtn">Sumber Daya ▾</a>
                    <div class="dropdown-content">
                        <a href="<?php echo BASE_URL; ?>/public/kepala-lab.php">Kepala Laboratorium</a>
                        <a href="<?php echo BASE_URL; ?>/public/asisten.php">Asisten Laboratorium</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">Fasilitas ▾</a>
                    <div class="dropdown-content">
                        <a href="<?php echo BASE_URL; ?>/public/laboratorium.php">Ruang Laboratorium</a>
                        <a href="<?php echo BASE_URL; ?>/public/riset.php">Ruang Riset</a>
                    </div>
                </li>
                <li><a href="<?php echo BASE_URL; ?>/public/alumni.php">Alumni</a></li>
                <li><a href="<?php echo BASE_URL; ?>/public/contact.php">Contact</a></li>
            </ul>
        </div>
    </nav>
    
    <main>