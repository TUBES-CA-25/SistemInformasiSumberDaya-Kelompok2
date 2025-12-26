<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Sumber Daya Lab</title>
    
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/variables.css">
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/style.css">

    <?php 
        // Ambil halaman saat ini dari query `page` (compat) atau path URL
        $pageQuery = $_GET['page'] ?? null;
        $uriSegments = explode('/', trim(str_replace(dirname($_SERVER['SCRIPT_NAME']), '', explode('?', $_SERVER['REQUEST_URI'])[0]), '/'));
        $curPage = $pageQuery ?? ($uriSegments[0] ?? 'home');

        // Normalisasi: treat detail pages specially so correct CSS loads
        if (strpos($curPage, 'detail') === 0) {
            // Jika detail berkaitan dengan fasilitas (underscore or dash), map ke 'fasilitas'
            if (strpos($curPage, 'fasilitas') !== false) {
                $curPage = 'fasilitas';
            } elseif (strpos($curPage, 'alumni') !== false) {
                // detail_alumni atau detail-alumni -> gunakan 'alumni'
                $curPage = 'alumni';
            } elseif (strpos($curPage, 'asisten') !== false) {
                // detail-asisten -> gunakan 'alumni' agar styling detail mirip alumni
                $curPage = 'alumni';
            } else {
                // fallback ke 'detail'
                $curPage = 'detail';
            }
        }

        // Daftar CSS khusus berdasarkan folder screenshot Anda
        $cssMap = [
            'home'         => 'home.css',
            'tatatertib'   => 'praktikum.css',
            'jadwal'       => 'praktikum.css',
            'kepala'       => 'sumberdaya.css',
            'asisten'      => 'sumberdaya.css',
            'detail'       => 'sumberdaya.css',
            'fasilitas'    => 'fasilitas.css',
            'riset'        => 'fasilitas.css',
            'laboratorium' => 'fasilitas.css',
            'alumni'       => 'alumni.css',
            'contact'      => 'contact.css',
            'apps'         => 'apps.css'
        ];

        // Load CSS khusus jika ada di daftar
        if (array_key_exists($curPage, $cssMap)) {
            echo '<link rel="stylesheet" href="' . PUBLIC_URL . '/css/' . $cssMap[$curPage] . '">';
        }
    ?>
    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <?php
        // Pastikan PUBLIC_URL tersedia; jika belum, buat fallback dari host saat ini (termasuk port).
        if (!defined('PUBLIC_URL')) {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? '127.0.0.1';
            define('PUBLIC_URL', rtrim($scheme . '://' . $host, '/'));
        }
    ?>
    <base href="<?php echo rtrim(PUBLIC_URL, '/'); ?>/">
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