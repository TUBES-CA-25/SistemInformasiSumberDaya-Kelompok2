<?php 
    $isNightMode = (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'night');
?>
<!DOCTYPE html>
<html lang="id" class="<?= $isNightMode ? 'night-mode' : '' ?>">
<head>
    <meta charset="UTF-8">
    <script>
        (function() {
            try {
                var theme = localStorage.getItem('theme');
                if (theme === 'night' || (!theme && <?= $isNightMode ? 'true' : 'false' ?>)) {
                    document.documentElement.classList.add('night-mode');
                    document.write('<style id="anti-flicker-style">html, html.night-mode, body, body.night-mode { background-color: #0b0f19 !important; color: #f8fafc !important; }</style>');
                } else if (theme === 'day') {
                    document.documentElement.classList.remove('night-mode');
                }
            } catch(e) {}
        })();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Sumber Daya Lab</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/variables.css">
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/style.css">

    <?php 
        // 1. Ambil identitas halaman
        $pageQuery = $_GET['page'] ?? null;
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $path = str_replace($scriptName, '', $uri);
        $segments = array_values(array_filter(explode('/', trim($path, '/'))));
        
        // Tentukan curPage awal
        $curPage = $page ?? $pageQuery ?? ($segments[0] ?? 'home');

        // Normalisasi Alias (Agar CSS tetap ke-load meski pakai URL alias)
        $aliases = [
            'tata-tertib'      => 'tatatertib',
            'peraturan'        => 'tatatertib',
            'kepala-lab'       => 'atasan',
            'struktur'         => 'atasan',
            'profil'           => 'atasan',
            'kepala'           => 'atasan',
            'fasilitas'        => 'riset',
            'kontak'           => 'contact',
            'hubungi'          => 'contact',
            'peta'             => 'denah',
            'daftar-asisten'   => 'asisten'
        ];

        if (array_key_exists($curPage, $aliases)) {
            $curPage = $aliases[$curPage];
        }

        // Jika halaman terdeteksi sebagai 'index.php', 'public', atau kosong, set jadi 'home'
        if ($curPage === 'index.php' || $curPage === 'public' || empty($curPage)) {
            $curPage = 'home';
        }

        // Preload LCP Hero Image untuk halaman Home
        if ($curPage === 'home') {
            echo '<link rel="preload" as="image" href="' . PUBLIC_URL . '/images/gedung-fikom-siang.webp" type="image/webp">' . "\n";
        }

        // 2. LOGIKA SMART MAPPING (Memperbaiki Detail dari Card)
        if (strpos($curPage, 'detail') !== false || strpos($curPage, 'asisten') !== false) {
            if (strpos($curPage, 'alumni') !== false) {
                $curPage = 'alumni'; 
            } elseif (strpos($curPage, 'fasilitas') !== false || strpos($curPage, 'laboratorium') !== false) {
                $curPage = 'fasilitas';
            } else {
                $curPage = 'sumberdaya'; 
            }
        }

        // 3. Mapping CSS yang Sesuai dengan Nama File di Folder CSS Anda
        $cssMap = [
            'home'         => 'home.css',
            'tatatertib'   => 'praktikum.css',
            'jadwal'       => 'praktikum.css',
            'jadwalupk'    => 'praktikum.css',
            'modul'        => 'praktikum.css',
            'formatpenulisan' => 'praktikum.css',
            'atasan'       => 'sumberdaya.css',
            'kepala'       => 'sumberdaya.css',
            'asisten'      => 'sumberdaya.css',
            'sumberdaya'   => 'sumberdaya.css', 
            'detail'       => 'sumberdaya.css', 
            'fasilitas'    => 'fasilitas.css',
            'riset'        => 'fasilitas.css',
            'laboratorium' => 'fasilitas.css',
            'denah'        => 'fasilitas.css',
            'sop'          => 'fasilitas.css',
            'alumni'       => 'sumberdaya.css',
            'contact'      => 'contact.css',
            'default'      => 'home.css'
        ];

        // 4. Load CSS
        if (array_key_exists($curPage, $cssMap)) {
            $cssFile = $cssMap[$curPage];
            $cssPath = __DIR__ . '/../../../public/css/' . $cssFile;
            $cssVersion = file_exists($cssPath) ? filemtime($cssPath) : time();
            echo '<link rel="stylesheet" href="' . PUBLIC_URL . '/css/' . $cssFile . '?v=' . $cssVersion . '">';
        }
    ?>
    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css"></noscript>

    <base href="<?= rtrim(PUBLIC_URL, '/') ?>/">
    <script>window.PUBLIC_URL = "<?= rtrim(PUBLIC_URL, '/') ?>";</script>
</head>
<body class="<?= $isNightMode ? 'night-mode' : '' ?>">

<nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="<?= PUBLIC_URL ?>/home" class="brand-logo">
                    <img src="<?= PUBLIC_URL ?>/images/navbar-icon.png" alt="Logo IC-Labs" class="logo-img">
                </a>
            </div>

            <ul class="nav-links">
                <li><a href="<?= PUBLIC_URL ?>/home">Beranda</a></li>
                
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropbtn">Praktikum ▾</a>
                    <div class="dropdown-content">
                        <a href="<?= PUBLIC_URL ?>/tatatertib">Tata Tertib</a>
                        <a href="<?= PUBLIC_URL ?>/jadwal">Jadwal Praktikum</a>
                        <a href="<?= PUBLIC_URL ?>/jadwalupk">Jadwal UPK</a>
                        <a href="<?= PUBLIC_URL ?>/modul">Modul Praktikum</a>
                        <a href="<?= PUBLIC_URL ?>/formatpenulisan">Format Penulisan</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropbtn">Sumber Daya ▾</a>
                    <div class="dropdown-content">
                        <a href="<?= PUBLIC_URL ?>/atasan">Pimpinan</a>
                        <a href="<?= PUBLIC_URL ?>/asisten">Asisten</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropbtn">Fasilitas ▾</a>
                    <div class="dropdown-content">
                        <a href="<?= PUBLIC_URL ?>/laboratorium">Ruang Lab</a>
                        <a href="<?= PUBLIC_URL ?>/riset">Ruang Riset</a>
                        <a href="<?= PUBLIC_URL ?>/denah">Denah Lokasi</a>
                        <a href="<?= PUBLIC_URL ?>/sop">SOP & Prosedur</a>
                    </div>
                </li>
                
                <li><a href="<?= PUBLIC_URL ?>/alumni">Alumni</a></li>
                <li><a href="<?= PUBLIC_URL ?>/contact">Kontak</a></li>
            </ul>

            <div class="nav-actions-right">
                <button id="themeToggle" class="theme-toggle-btn" aria-label="Ganti Tema">
                    <span class="toggle-slider">
                        <i class="ri-sun-fill icon-sun"></i>
                        <i class="ri-moon-fill icon-moon"></i>
                    </span>
                </button>

                <a href="<?= PUBLIC_URL ?>/apps" class="btn-nav-apps">
                    <i class="ri-apps-2-line"></i> <span class="btn-apps-text">IC-Labs Apps</span>
                </a>

                <div class="menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </nav>
    
    <main>