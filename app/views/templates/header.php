<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Sumber Daya Lab</title>
    
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/variables.css">
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/style.css">

    <?php 
        // 1. Ambil dari variabel $page (jika dikirim dari controller)
        // 2. Jika tidak ada, deteksi otomatis dari URL
        if (!isset($page)) {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $scriptName = dirname($_SERVER['SCRIPT_NAME']);
            $path = str_replace($scriptName, '', $uri);
            $segments = array_values(array_filter(explode('/', trim($path, '/'))));
            $page = $segments[0] ?? 'home';
        }

        // Normalisasi: Jika halaman kosong atau index.php, paksa jadi 'home'
        $curPage = (empty($page) || $page == 'index.php') ? 'home' : $page;

        // Map CSS berdasarkan halaman
        $cssMap = [
            'home'             => 'home.css',
            'tatatertib'       => 'praktikum.css',
            'jadwal'           => 'praktikum.css',
            'kepala'           => 'sumberdaya.css',
            'asisten'          => 'sumberdaya.css',
            'detail'           => 'sumberdaya.css',
            'fasilitas'        => 'fasilitas.css',
            'riset'            => 'fasilitas.css',
            'laboratorium'     => 'fasilitas.css',
            'detail_fasilitas' => 'fasilitas.css',
            'alumni'           => 'alumni.css',
            'detail_alumni'    => 'alumni.css',
            'contact'          => 'contact.css',
            'apps'             => 'apps.css'
        ];

        // Muat CSS khusus jika ada di map
        if (array_key_exists($curPage, $cssMap)) {
            echo '<link rel="stylesheet" href="' . PUBLIC_URL . '/css/' . $cssMap[$curPage] . '">';
        }
    ?>
    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <?php
        // Pastikan PUBLIC_URL selalu absolut
        if (!defined('PUBLIC_URL')) {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? '127.0.0.1';
            define('PUBLIC_URL', rtrim($scheme . '://' . $host, '/'));
        }
    ?>
    <base href="<?= rtrim(PUBLIC_URL, '/') ?>/">
</head>
<body>

    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="<?= PUBLIC_URL ?>/home" class="brand-logo">
                    <img src="<?= PUBLIC_URL ?>/images/logo-iclabs.png" alt="Logo IC-Labs" class="logo-img">
                </a>
            </div>

            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <ul class="nav-links">
                <li><a href="<?= PUBLIC_URL ?>/home">Home</a></li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">Praktikum ▾</a>
                    <div class="dropdown-content">
                        <a href="<?= PUBLIC_URL ?>/tatatertib">Tata Tertib</a>
                        <a href="<?= PUBLIC_URL ?>/jadwal">Jadwal</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">Sumber Daya ▾</a>
                    <div class="dropdown-content">
                        <a href="<?= PUBLIC_URL ?>/kepala">Kepala Lab</a>
                        <a href="<?= PUBLIC_URL ?>/asisten">Asisten</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">Fasilitas ▾</a>
                    <div class="dropdown-content">
                        <a href="<?= PUBLIC_URL ?>/laboratorium">Ruang Lab</a>
                        <a href="<?= PUBLIC_URL ?>/riset">Ruang Riset</a>
                    </div>
                </li>
                
                <li><a href="<?= PUBLIC_URL ?>/alumni">Alumni</a></li>
                <li><a href="<?= PUBLIC_URL ?>/contact">Contact</a></li>

                <li>
                    <a href="<?= PUBLIC_URL ?>/apps" class="btn-nav-apps">
                        <i class="ri-apps-2-line"></i> Apps
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    
    <main>