<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sistem Lab</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <?php
    // --- 1. KONFIGURASI SYSTEM ---
    if (!defined('DB_HOST')) {
        if (!defined('ROOT_PROJECT')) {
            $currentDir = __DIR__; 
            $adminDir = dirname($currentDir); 
            $viewsDir = dirname($adminDir); 
            $appDir = dirname($viewsDir); 
            $rootDir = dirname($appDir); 
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

    <script>
        const API_URL = '<?php echo API_URL; ?>';
        const BASE_URL = '<?php echo BASE_URL; ?>';
        const ASSETS_URL = '<?php echo ASSETS_URL; ?>';
    </script>
</head>
<body class="bg-gray-100 font-sans text-gray-800 antialiased">

    <?php
    // --- 2. LOGIKA NAVIGASI PHP ---
    $uri = $_SERVER['REQUEST_URI'];
    
    // Fungsi helper ini HARUS tetap disini agar bisa dibaca oleh sidebar.php
    function getMenuClass($uri, $path) {
        $base = "flex items-center px-6 py-3 text-gray-400 hover:bg-gray-800 hover:text-white transition-colors duration-200 border-l-4 border-transparent";
        $active = "bg-gray-800 text-white border-blue-500";

        if ($path === 'admin' && strpos($uri, 'admin') !== false && strpos($uri, 'admin/') === false) {
            return "$base $active";
        }
        if ($path !== 'admin' && strpos($uri, $path) !== false) {
            return "$base $active";
        }
        return $base;
    }
    ?>

    <div class="flex h-screen overflow-hidden">

        <?php 
        // --- 3. INCLUDE SIDEBAR ---
        // Pastikan path ini sesuai dengan lokasi file sidebar.php Anda
        $sidebarPath = ROOT_PROJECT . '/app/views/admin/templates/sidebar.php';
        
        if (file_exists($sidebarPath)) {
            include $sidebarPath;
        } else {
            echo "<div class='w-64 bg-red-800 text-white p-4'>Error: Sidebar not found at $sidebarPath</div>";
        }
        ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10">
                <div>
                    <h1 class="text-xl font-bold text-gray-700">Selamat Datang, Admin!</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <div class="text-sm font-semibold text-gray-800"><?= $_SESSION['username'] ?? 'Admin' ?></div>
                        <div class="text-xs text-gray-500">Administrator</div>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['username'] ?? 'Admin' ?>&background=0D8ABC&color=fff" alt="Profile" class="h-10 w-10 rounded-full border-2 border-gray-100 shadow-sm">
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-8">
                <?php
                // --- LOGIKA ROUTING PINTAR ---
                
                // 1. Ambil URL & Bersihkan
                $request = $_SERVER['REQUEST_URI'];
                $request = strtok($request, '?');
                
                // Default Variables
                $module = 'dashboard'; // Nama folder (misal: alumni, asisten)
                $action = 'index';     // File yang diload (index.php atau form.php)
                $id     = null;        // ID data (untuk edit)

                // 2. Parsing URL: /public/admin/alumni/edit/5
                if (strpos($request, '/admin/') !== false) {
                    $parts = explode('/admin/', $request);
                    
                    if (isset($parts[1]) && !empty($parts[1])) {
                        $urlSegments = explode('/', $parts[1]);
                        
                        // Segment 1: Nama Modul (alumni)
                        $module = isset($urlSegments[0]) ? $urlSegments[0] : 'dashboard';
                        
                        // Segment 2: Aksi (create / edit)
                        if (isset($urlSegments[1])) {
                            if ($urlSegments[1] === 'create') {
                                $action = 'form'; // Load form.php
                            } elseif ($urlSegments[1] === 'edit') {
                                $action = 'form'; // Load form.php
                                // Segment 3: ID Data
                                if (isset($urlSegments[2])) {
                                    $id = $urlSegments[2];
                                }
                            }
                            // Jika butuh logika lain (misal: detail), tambahkan disini
                        }
                    }
                }

                // Sanitasi nama folder agar aman
                $module = preg_replace('/[^a-zA-Z0-9_]/', '', $module);

                // 3. Tentukan File Target
                $viewsPath = ROOT_PROJECT . '/app/views/admin/';
                
                // Cek apakah ini Dashboard (biasanya file tunggal)
                if ($module === 'dashboard') {
                    $targetFile = $viewsPath . 'dashboard.php';
                    if (!file_exists($targetFile)) $targetFile = $viewsPath . 'dashboard/index.php';
                } 
                else {
                    // Cek apakah masuk ke mode Form (Create/Edit) atau Index (Tabel)
                    if ($action === 'form') {
                        $targetFile = $viewsPath . $module . '/form.php';
                    } else {
                        $targetFile = $viewsPath . $module . '/index.php';
                    }
                }

                // 4. Eksekusi Include
                if (file_exists($targetFile)) {
                    // Kita kirim variabel $id ke dalam file form.php agar bisa dipakai
                    // $id akan berisi angka ID jika mode edit, atau null jika create
                    include $targetFile;
                } else {
                    // Tampilan Error 404 Cantik
                    echo "
                    <div class='flex flex-col items-center justify-center pt-20'>
                        <div class='bg-white p-8 rounded-lg shadow-md text-center max-w-lg'>
                            <i class='fas fa-search text-6xl text-gray-300 mb-4'></i>
                            <h2 class='text-xl font-bold text-gray-800'>Halaman Tidak Ditemukan</h2>
                            <p class='text-gray-500 mt-2'>Sistem mencari file:</p>
                            <code class='block bg-red-50 text-red-600 p-2 rounded mt-2 text-sm'>$targetFile</code>
                        </div>
                    </div>";
                }
                ?>
            </main>
        </div>
    </div>

    <script>
        function navigate(route) {
            const basePath = window.location.pathname.includes('SistemInformasiSumberDaya-Kelompok2') 
                ? '/SistemInformasiSumberDaya-Kelompok2/public'
                : '';

            if (!route) {
                window.location.href = basePath + '/admin';
            } else {
                window.location.href = basePath + '/' + route;
            }
        }
    </script>
</body>
</html>