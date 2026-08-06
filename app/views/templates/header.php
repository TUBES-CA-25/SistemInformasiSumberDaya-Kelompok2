<?php 
    $isNightMode = (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'night');

    // Identitas Halaman & Smart Title
    $pageTitle = !empty($data['judul']) ? htmlspecialchars($data['judul']) : (!empty($judul) ? htmlspecialchars($judul) : 'Sistem Informasi Sumber Daya Laboratorium FIKOM UMI');
    $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '');
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
    <title><?= $pageTitle ?> - IC-Labs FIKOM UMI</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Sistem Informasi Sumber Daya Laboratorium Terpadu Fakultas Ilmu Komputer Universitas Muslim Indonesia (IC-Labs FIKOM UMI). Akses jadwal praktikum, rekrutmen asisten, ruang laboratorium, modul, dan riset komputasi.">
    <meta name="keywords" content="FIKOM UMI, Laboratorium FIKOM UMI, IC-Labs, Praktikum FIKOM UMI, Asisten Lab FIKOM, Jadwal Praktikum UMI, Modul Praktikum FIKOM">
    <meta name="author" content="Laboratorium FIKOM UMI">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= htmlspecialchars($currentUrl) ?>">

    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($currentUrl) ?>">
    <meta property="og:title" content="<?= $pageTitle ?> | IC-Labs FIKOM UMI">
    <meta property="og:description" content="Portal resmi Sistem Informasi Sumber Daya Laboratorium Fakultas Ilmu Komputer Universitas Muslim Indonesia (UMI).">
    <meta property="og:image" content="<?= PUBLIC_URL ?>/images/gedung-fikom-siang.webp">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="IC-Labs FIKOM UMI">

    <!-- Twitter Cards Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $pageTitle ?> | IC-Labs FIKOM UMI">
    <meta name="twitter:description" content="Portal resmi Sistem Informasi Sumber Daya Laboratorium Fakultas Ilmu Komputer Universitas Muslim Indonesia (UMI).">
    <meta name="twitter:image" content="<?= PUBLIC_URL ?>/images/gedung-fikom-siang.webp">

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/png" href="<?= PUBLIC_URL ?>/images/navbar-icon.png">
    <link rel="apple-touch-icon" href="<?= PUBLIC_URL ?>/images/navbar-icon.png">
    
    <!-- Preconnect to third-party origins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <!-- Critical Inline CSS: Above-the-fold navbar + layout skeleton for instant FCP -->
    <style>
      *{margin:0;padding:0;box-sizing:border-box}
      body{background-color:#eff6ff;color:#1a202c;line-height:1.6;padding-top:80px;font-family:"Inter","Segoe UI",sans-serif}
      html,body{max-width:100%;overflow-x:hidden}
      .navbar{position:fixed;top:15px;left:50%;transform:translateX(-50%);width:90%;max-width:1200px;z-index:9999;background-color:rgba(255,255,255,0.85);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(191,219,254,0.85);border-radius:12px;box-shadow:0 10px 30px rgba(30,58,138,0.08);padding:6px 24px;transition:all .3s ease}
      .navbar .container{display:flex;justify-content:space-between;align-items:center;width:100%;max-width:100%}
      .container{width:100%;max-width:1200px;margin:0 auto;padding:0 24px}
      .logo{display:flex;align-items:center}
      .brand-logo{display:flex;align-items:center;padding:2px 0;text-decoration:none}
      .logo-img{height:48px;width:auto;object-fit:contain;display:block}
      .nav-links{list-style:none;display:flex;gap:30px;align-items:center}
      .nav-links a{color:#4a5568;font-weight:600;font-size:.95rem;text-decoration:none}
      .nav-actions-right{display:flex;align-items:center;gap:16px;z-index:10001}
      .menu-toggle{display:none}
      html.night-mode,html.night-mode body,body.night-mode{background-color:#0b0f19!important;color:#f8fafc!important}
      body.night-mode .navbar{background-color:rgba(11,15,25,0.92)!important;border:1px solid rgba(255,255,255,0.08)}
      /* Hero section critical CSS for LCP */
      .hero-section{padding:15px 0 30px;background-color:#eff6ff;position:relative;overflow:hidden;z-index:1}
      .hero-boxed-wrapper{position:relative;width:100%;min-height:640px;display:flex;flex-direction:column;justify-content:center;border-radius:24px;overflow:hidden;padding:60px 65px;border:1px solid rgba(226,232,240,0.15);box-shadow:0 25px 60px rgba(0,0,0,0.18);z-index:1}
      .hero-bg-frame{position:absolute;inset:0;width:100%;height:100%;z-index:-1;pointer-events:none;overflow:hidden}
      .hero-bg-img{position:absolute;inset:0;width:100%;height:100%;background-size:cover;background-position:center;transition:opacity 1s cubic-bezier(.4,0,.2,1)}
      .hero-bg-overlay{position:absolute;inset:0;background:linear-gradient(to right,rgba(15,23,42,0.85) 0%,rgba(15,23,42,0.4) 55%,rgba(15,23,42,0) 100%),linear-gradient(to top,rgba(15,23,42,0.75) 0%,rgba(15,23,42,0.3) 25%,transparent 50%);z-index:1}
      .hero-content{flex:0 1 auto;max-width:780px;z-index:2}
      .hero-content h1{font-size:2.75rem;color:#fff;line-height:1.22;margin-bottom:20px;font-weight:800;letter-spacing:-0.8px;text-shadow:0 4px 20px rgba(0,0,0,0.65)}
      .hero-content p{font-size:1.05rem;color:rgba(248,250,252,0.95);margin-bottom:32px;line-height:1.7;max-width:680px;text-shadow:0 2px 12px rgba(0,0,0,0.5)}
      .text-blue{color:#38bdf8!important;font-weight:800}
      .btn-group{display:flex;gap:16px;flex-wrap:wrap}
      .btn-primary{background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%);color:#fff!important;padding:14px 32px;border-radius:50px;font-weight:700;font-size:0.95rem;display:inline-flex;align-items:center;gap:10px;text-decoration:none;box-shadow:0 8px 25px rgba(37,99,235,0.35)}
      .btn-outline{border:2px solid rgba(255,255,255,0.4);color:#fff!important;padding:14px 32px;border-radius:50px;font-weight:700;font-size:0.95rem;display:inline-flex;align-items:center;gap:10px;text-decoration:none;backdrop-filter:blur(8px)}
      .btn-nav-apps{background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 100%);color:#fff!important;padding:8px 16px;border-radius:10px;font-weight:600;font-size:.88rem;display:inline-flex;align-items:center;gap:8px;text-decoration:none}
      .theme-toggle-btn{background:rgba(226,232,240,0.8);border:1px solid rgba(203,213,225,0.8);width:68px;height:34px;border-radius:50px;cursor:pointer;position:relative;padding:3px;display:flex;align-items:center}
      .toggle-slider{width:28px;height:28px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;position:absolute;left:3px;box-shadow:0 2px 8px rgba(0,0,0,0.15)}
      a{text-decoration:none;transition:.3s ease}
      img{max-width:100%;display:block}
      h1,h2,h3,h4{color:#1e3a8a;font-weight:800}
      @media screen and (max-width:991px){body{padding-top:56px!important}.navbar{top:8px!important;width:92%!important;padding:4px 14px!important}.logo-img{height:32px!important}.menu-toggle{display:flex;flex-direction:column;gap:3px;cursor:pointer;width:32px;height:32px;min-width:32px;border-radius:8px;background:rgba(37,99,235,0.08);border:1px solid rgba(37,99,235,0.2);justify-content:center;align-items:center}.menu-toggle span{display:block;width:18px;height:2px;background-color:#2563eb;border-radius:2px}.nav-links{display:flex;position:absolute;top:calc(100% + 10px);left:0;right:0;width:100%!important;flex-direction:column;max-height:0;opacity:0;overflow:hidden;visibility:hidden}.hero-boxed-wrapper{min-height:420px;padding:30px 20px}.hero-content h1{font-size:1.6rem}.btn-primary,.btn-outline{padding:10px 22px;font-size:.85rem}.theme-toggle-btn{width:32px!important;height:32px!important;border-radius:8px!important;padding:0!important;justify-content:center!important}.toggle-slider{position:static!important;background:transparent!important;box-shadow:none!important;width:auto!important;height:auto!important}.btn-nav-apps{height:32px!important;padding:0 10px!important;font-size:.76rem!important;border-radius:8px!important}}
    </style>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <!-- Core CSS (Loaded synchronously to prevent FOUC / layout shift on refresh) -->
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

        // Normalisasi Alias
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

        // Preload LCP Hero Image untuk halaman Home (fetchpriority=high untuk prioritas browser)
        if ($curPage === 'home') {
            echo '<link rel="preload" as="image" href="' . PUBLIC_URL . '/images/gedung-fikom-siang.webp" type="image/webp" fetchpriority="high">' . "\n";
        }

        // 2. LOGIKA SMART MAPPING
        if (strpos($curPage, 'detail') !== false || strpos($curPage, 'asisten') !== false) {
            if (strpos($curPage, 'alumni') !== false) {
                $curPage = 'alumni'; 
            } elseif (strpos($curPage, 'fasilitas') !== false || strpos($curPage, 'laboratorium') !== false) {
                $curPage = 'fasilitas';
            } else {
                $curPage = 'sumberdaya'; 
            }
        }

        // 3. Mapping CSS
        $cssMap = [
            'home'         => 'home.css',
            'apps'         => 'apps.css',
            'tatatertib'   => 'praktikum.css',
            'peraturan'    => 'praktikum.css',
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

        // 4. Load CSS (Gunakan filemtime tanpa time() agar browser caching berfungsi)
        $cssFile = $cssMap[$curPage] ?? $cssMap['default'];
        $cssPath = __DIR__ . '/../../../public/css/' . $cssFile;
        $cssVersion = file_exists($cssPath) ? filemtime($cssPath) : '1.0.0';
        echo '<link rel="stylesheet" href="' . PUBLIC_URL . '/css/' . $cssFile . '?v=' . $cssVersion . '">' . "\n";
    ?>
    
    <!-- Icon Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" crossorigin="anonymous">

    <base href="<?= rtrim(PUBLIC_URL, '/') ?>/">
    <script>window.PUBLIC_URL = "<?= rtrim(PUBLIC_URL, '/') ?>";</script>

    <!-- Schema.org JSON-LD Structured Data untuk SEO -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "EducationalOrganization",
      "name": "Laboratorium Fakultas Ilmu Komputer UMI",
      "alternateName": "IC-Labs FIKOM UMI",
      "url": "<?= PUBLIC_URL ?>",
      "logo": "<?= PUBLIC_URL ?>/images/navbar-icon.png",
      "description": "Sistem Informasi Sumber Daya Laboratorium Terpadu FIKOM UMI Makassar",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Jl. Urip Sumoharjo KM.05",
        "addressLocality": "Makassar",
        "addressRegion": "Sulawesi Selatan",
        "postalCode": "90231",
        "addressCountry": "ID"
      }
    }
    </script>
</head>
<body class="<?= $isNightMode ? 'night-mode' : '' ?>">

<nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="<?= PUBLIC_URL ?>/home" class="brand-logo" aria-label="Beranda IC-Labs FIKOM UMI">
                    <img src="<?= PUBLIC_URL ?>/images/navbar-icon.png" alt="Logo IC-Labs FIKOM UMI" class="logo-img">
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

                <a href="<?= PUBLIC_URL ?>/apps" class="btn-nav-apps" aria-label="Aplikasi IC-Labs">
                    <i class="ri-apps-2-line"></i> <span class="btn-apps-text">IC-Labs Apps</span>
                </a>

                <div class="menu-toggle" aria-label="Buka Menu Navigasi" role="button" tabindex="0">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </nav>
    
    <main>