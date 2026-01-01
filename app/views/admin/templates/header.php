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

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-8">
                <?php
                // --- LOGIKA ROUTING PINTAR (UPDATED) ---
                
                // 1. Ambil URL
                $request = $_SERVER['REQUEST_URI'];
                $request = strtok($request, '?');
                
                // Default Variables
                $module = 'dashboard'; 
                $action = 'index';     
                $id     = null;       

                // 2. Parsing URL
                if (strpos($request, '/admin/') !== false) {
                    $parts = explode('/admin/', $request);
                    
                    if (isset($parts[1]) && !empty($parts[1])) {
                        $urlSegments = explode('/', $parts[1]);
                        
                        // Segment 1: Nama Modul (asisten, alumni, dll)
                        $module = isset($urlSegments[0]) ? $urlSegments[0] : 'dashboard';
                        
                        // Segment 2: Aksi (create, edit, koordinator)
                        if (isset($urlSegments[1])) {
                            if ($urlSegments[1] === 'create') {
                                $action = 'form'; 
                            } 
                            elseif ($urlSegments[1] === 'edit') {
                                $action = 'form'; 
                                if (isset($urlSegments[2])) $id = $urlSegments[2];
                            }
                            // --- TAMBAHAN BARU: KOORDINATOR ---
                            elseif ($urlSegments[1] === 'pilih-koordinator') {
                                $action = 'pilih-koordinator'; // Ini akan mencari file koordinator.php
                            }
                            elseif ($urlSegments[1] === 'detail') {
                                $action = 'detail';
                                if (isset($urlSegments[2])) $id = $urlSegments[2];
                            }
                        }
                    }
                }

                $module = preg_replace('/[^a-zA-Z0-9_]/', '', $module);

                // 3. Tentukan File Target
                $viewsPath = ROOT_PROJECT . '/app/views/admin/';
                
                if ($module === 'dashboard') {
                    $targetFile = $viewsPath . 'dashboard.php';
                    if (!file_exists($targetFile)) $targetFile = $viewsPath . 'dashboard/index.php';
                } 
                else {
                    // Cek Action apa yang diminta
                    if ($action === 'form') {
                        $targetFile = $viewsPath . $module . '/form.php';
                    } 
                    elseif ($action === 'pilih-koordinator') {
                        // Target file: views/admin/asisten/koordinator.php
                        $targetFile = $viewsPath . $module . '/pilih-koordinator.php';
                    }
                    elseif ($action === 'detail') {
                        $targetFile = $viewsPath . $module . '/detail.php';
                    }
                    else {
                        $targetFile = $viewsPath . $module . '/index.php';
                    }
                }

                // 4. Eksekusi Include
                if (file_exists($targetFile)) {
                    include $targetFile;
                } else {
                    // Tampilan Error 404 (Code block sebelumnya)
                    echo "<div class='p-8 text-center text-red-500'>File tidak ditemukan: $targetFile</div>";
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