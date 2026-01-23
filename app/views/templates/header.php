<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Sumber Daya Lab</title>
    
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
            'kepala-lab'       => 'kepala',
            'struktur'         => 'kepala',
            'profil'           => 'kepala',
            'fasilitas'        => 'riset',
            'kontak'           => 'contact',
            'hubungi'          => 'contact',
            'peta'             => 'denah',
            'daftar-asisten'   => 'asisten'
        ];

        if (array_key_exists($curPage, $aliases)) {
            $curPage = $aliases[$curPage];
        }

        // --- PERBAIKAN DISINI ---
        // Jika halaman terdeteksi sebagai 'index.php', 'public', atau kosong, set jadi 'home'
        if ($curPage === 'index.php' || $curPage === 'public' || empty($curPage)) {
            $curPage = 'home';
        }

        // 2. LOGIKA SMART MAPPING (Memperbaiki Detail dari Card)
        // Kita paksa curPage menjadi kategori utama jika mengandung kata kunci tertentu
        if (strpos($curPage, 'detail') !== false || strpos($curPage, 'asisten') !== false) {
            if (strpos($curPage, 'alumni') !== false) {
                $curPage = 'alumni'; 
            } elseif (strpos($curPage, 'fasilitas') !== false || strpos($curPage, 'laboratorium') !== false) {
                $curPage = 'fasilitas';
            } else {
                // Default untuk detail asisten atau manajemen lab
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
            'kepala'       => 'sumberdaya.css',
            'asisten'      => 'sumberdaya.css',
            'sumberdaya'   => 'sumberdaya.css', 
            'detail'       => 'sumberdaya.css', 
            'fasilitas'    => 'fasilitas.css',
            'riset'        => 'fasilitas.css',
            'laboratorium' => 'fasilitas.css',
            'denah'        => 'fasilitas.css',
            'sop'          => 'fasilitas.css',
            'alumni'       => 'alumni.css',
            'contact'      => 'contact.css',
            'apps'         => 'apps.css'
            
        ];

        // 4. Load CSS
        if (array_key_exists($curPage, $cssMap)) {
            echo '<link rel="stylesheet" href="' . PUBLIC_URL . '/css/' . $cssMap[$curPage] . '">';
        }
    ?>
    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <base href="<?= rtrim(PUBLIC_URL, '/') ?>/">
</head>
<body>

    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="<?= PUBLIC_URL ?>/home" class="brand-logo">
                    <img src="<?= PUBLIC_URL ?>/images/navbar-icon.png" alt="Logo IC-Labs" class="logo-img">
                </a>
            </div>

            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <ul class="nav-links">
                <li><a href="<?= PUBLIC_URL ?>/home">Beranda</a></li>
                
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropbtn" onclick="toggleDropdown(this, event)">Praktikum ▾</a>
                    <div class="dropdown-content">
                        <a href="<?= PUBLIC_URL ?>/tatatertib">Tata Tertib</a>
                        <a href="<?= PUBLIC_URL ?>/jadwal">Jadwal Praktikum</a>
                        <a href="<?= PUBLIC_URL ?>/jadwalupk">Jadwal UPK</a>
                        <a href="<?= PUBLIC_URL ?>/modul">Modul Praktikum</a>
                        <a href="<?= PUBLIC_URL ?>/formatpenulisan">Format Penulisan</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropbtn" onclick="toggleDropdown(this, event)">Sumber Daya ▾</a>
                    <div class="dropdown-content">
                        <a href="<?= PUBLIC_URL ?>/kepala">Pimpinan</a>
                        <a href="<?= PUBLIC_URL ?>/asisten">Asisten</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropbtn" onclick="toggleDropdown(this, event)">Fasilitas ▾</a>
                    <div class="dropdown-content">
                        <a href="<?= PUBLIC_URL ?>/laboratorium">Ruang Lab</a>
                        <a href="<?= PUBLIC_URL ?>/riset">Ruang Riset</a>
                        <a href="<?= PUBLIC_URL ?>/denah">Denah Lokasi</a>
                        <a href="<?= PUBLIC_URL ?>/sop">SOP & Prosedur</a>
                    </div>
                </li>
                
                <li><a href="<?= PUBLIC_URL ?>/alumni">Alumni</a></li>
                <li><a href="<?= PUBLIC_URL ?>/contact">Kontak</a></li>

                <li>
                    <a href="<?= PUBLIC_URL ?>/apps" class="btn-nav-apps">
                        <i class="ri-apps-2-line"></i> IC-Labs Apps
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <script>
    function toggleDropdown(element, event) {
        // Mencegah klik merambat ke window.onclick agar tidak terjadi konflik tutup-paksa
        if (event) event.stopPropagation();

        const dropdownContent = element.nextElementSibling;
        
        // 1. Tutup semua dropdown lain
        document.querySelectorAll('.dropdown-content').forEach(content => {
            if (content !== dropdownContent) {
                content.classList.remove('show');
            }
        });

        // 2. Toggle class show pada elemen yang diklik
        dropdownContent.classList.toggle('show');
    }

    // Menutup dropdown jika pengguna mengklik di luar area dropdown
    window.onclick = function(event) {
        if (!event.target.closest('.dropdown')) {
            const dropdowns = document.getElementsByClassName("dropdown-content");
            for (let i = 0; i < dropdowns.length; i++) {
                if (dropdowns[i].classList.contains('show')) {
                    dropdowns[i].classList.remove('show');
                }
            }
        }
    }
    </script>
    
    <main>