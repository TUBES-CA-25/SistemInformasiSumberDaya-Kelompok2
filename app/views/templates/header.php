<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Sumber Daya Lab</title>
    
    <?php 
    // Load config untuk base URL
    // Config sudah dimuat di index.php, tidak perlu dimuat lagi
    ?>
    
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css?v=<?php echo time(); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        // Global variable untuk semua JavaScript
        const API_URL = '<?php echo API_URL; ?>';
        const BASE_URL = '<?php echo BASE_URL; ?>';
        const PUBLIC_URL = '<?php echo PUBLIC_URL; ?>';
        
        // Function untuk generate URL yang kompatibel
        function getUrl(route) {
            // Detect environment
            if (window.location.port === '8000') {
                // PHP built-in server
                return '/index.php?route=' + route;
            } else {
                // XAMPP/Apache dengan .htaccess
                return '/' + route;
            }
        }
    </script>
    </head>
<body>

    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="javascript:void(0)" onclick="goHome()">
                    ★ LOGO
                </a>
            </div>

            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <ul class="nav-links">
                <li><a href="javascript:void(0)" onclick="navigate('')">Home</a></li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">Praktikum ▾</a>
                    <div class="dropdown-content">
                        <a href="javascript:void(0)" onclick="navigate('praktikum')">Peraturan & Ketentuan</a>
                        <a href="javascript:void(0)" onclick="navigate('praktikum/sanksi')">Sanksi Pelanggaran</a>
                        <a href="javascript:void(0)" onclick="navigate('jadwal')">Jadwal Praktikum</a>
                    </div>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropbtn">Sumber Daya ▾</a>
                    <div class="dropdown-content">
                        <a href="javascript:void(0)" onclick="navigate('kepala-lab')">Kepala Laboratorium</a>
                        <a href="javascript:void(0)" onclick="navigate('asisten')">Asisten Laboratorium</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">Fasilitas ▾</a>
                    <div class="dropdown-content">
                        <a href="javascript:void(0)" onclick="navigate('laboratorium')">Ruang Laboratorium</a>
                        <a href="javascript:void(0)" onclick="navigate('riset')">Ruang Riset</a>
                    </div>
                </li>
                <li><a href="javascript:void(0)" onclick="navigate('alumni')">Alumni</a></li>
                <li><a href="javascript:void(0)" onclick="navigate('contact')">Contact</a></li>
            </ul>
        </div>
    </nav>

    <script>
        function navigate(route) {
            // Use consistent ?route= format for both environments
            const basePath = window.location.pathname.includes('SistemInformasiSumberDaya-Kelompok2') 
                ? '/SistemInformasiSumberDaya-Kelompok2/public/'
                : '/';
            
            if (route === '') {
                window.location.href = basePath;
            } else {
                window.location.href = basePath + '?route=' + route;
            }
        }
        
        function goHome() {
            const basePath = window.location.pathname.includes('SistemInformasiSumberDaya-Kelompok2') 
                ? '/SistemInformasiSumberDaya-Kelompok2/public/'
                : '/';
            window.location.href = basePath;
        }
    </script>
        </div>
    </nav>
    
    <main>