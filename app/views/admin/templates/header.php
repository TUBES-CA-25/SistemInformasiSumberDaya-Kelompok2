<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sistem Lab</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center gap-2 text-sm font-medium text-gray-400">
                        <i class="fas fa-home text-xs"></i>
                        <span>Admin</span>
                        <i class="fas fa-chevron-right text-[10px]"></i>
                        <span class="text-blue-600 font-bold capitalize" id="breadcrumb-current"><?= $module ?? 'Dashboard' ?></span>
                    </div>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="hidden lg:flex items-center bg-gray-50 px-3 py-1.5 rounded-full border border-gray-100 text-gray-500">
                        <i class="far fa-clock text-xs mr-2 text-blue-500"></i>
                        <span id="headerClock" class="text-xs font-mono font-bold uppercase tracking-widest text-gray-600">00:00:00</span>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-bold text-gray-800 leading-none mb-0.5"><?= $_SESSION['username'] ?? 'Administrator' ?></div>
                            <div class="text-[10px] text-emerald-500 font-black uppercase tracking-tighter flex items-center justify-end gap-1">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                Online
                            </div>
                        </div>
                        <div class="relative group">
                            <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['username'] ?? 'Admin' ?>&background=0D8ABC&color=fff" alt="Profile" class="h-10 w-10 rounded-xl border-2 border-white shadow-md cursor-pointer group-hover:ring-2 group-hover:ring-blue-100 transition-all">
                        </div>
                    </div>
                </div>
            </header>

            <script>
            function updateHeaderClock() {
                const now = new Date();
                const timeStr = now.toLocaleTimeString('en-GB', { hour12: false });
                const clockEl = document.getElementById('headerClock');
                if(clockEl) clockEl.innerText = timeStr;
            }
            setInterval(updateHeaderClock, 1000);
            updateHeaderClock();
            </script>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-8">
                <?php
                // --- LOGIKA ROUTING SEDERHANA ---
                
                // Prioritas 1: Gunakan module dari global (set di index.php)
                // Prioritas 2: Gunakan module dari local variable (jika di-include)
                // Fallback: Parsing manual
                if (isset($GLOBALS['module'])) {
                    $module = $GLOBALS['module'];
                } elseif (!isset($module)) {
                    $request = $_SERVER['REQUEST_URI'];
                    $request = strtok($request, '?');
                    $module = 'dashboard'; 
                    if (strpos($request, '/admin/') !== false) {
                        $parts = explode('/admin/', $request);
                        if (isset($parts[1]) && !empty($parts[1])) {
                            $urlSegments = explode('/', $parts[1]);
                            $module = isset($urlSegments[0]) ? $urlSegments[0] : 'dashboard';
                        }
                    }
                    $module = preg_replace('/[^a-zA-Z0-9_]/', '', $module);
                }

                // Normalisasi nama modul (Alias)
                if ($module === 'peraturan' || $module === 'sanksi') {
                    $module = 'peraturan_sanksi';
                }
                if ($module === 'format_penulisan' || $module === 'format-penulisan') {
                    $module = 'formatpenulisan';
                }

                // 3. Tentukan File Target
                // Gunakan VIEW_PATH yang sudah didefinisikan di index.php jika ada
                $baseAdminPath = defined('VIEW_PATH') ? VIEW_PATH . '/admin/' : ROOT_PROJECT . '/app/views/admin/';
                
                if ($module === 'dashboard') {
                    $targetFile = $baseAdminPath . 'dashboard.php';
                    if (!file_exists($targetFile)) $targetFile = $baseAdminPath . 'dashboard/index.php';
                } 
                else {
                    // Apapun yang terjadi, selalu buka index.php milik module tersebut
                    $targetFile = $baseAdminPath . $module . '/index.php';
                }

                // 4. Eksekusi Include
                if (file_exists($targetFile)) {
                    include $targetFile;
                } else {
                    $debugInfo = "Module: $module, Route: " . ($_SERVER['REQUEST_URI'] ?? 'unknown');
                    echo "
                    <!-- DEBUG: $debugInfo -->
                    <div class='flex flex-col items-center justify-center min-h-[50vh] text-gray-500'>
                        <i class='fas fa-exclamation-triangle text-4xl mb-4 text-yellow-500'></i>
                        <h2 class='text-xl font-bold'>Halaman Tidak Ditemukan</h2>
                        <p class='text-sm mt-2'>Modul <b>'$module'</b> belum tersedia.</p>
                        <p class='text-[10px] text-gray-400 mt-1'>Mencoba memuat: $targetFile</p>
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

        // Set PUBLIC_URL untuk session timeout script
        const PUBLIC_URL = '<?= PUBLIC_URL ?>';
        
        // Pass Flash Messages ke JS
        <?php
        $flashSuccess = $_SESSION['flash']['success'] ?? null;
        $flashError = $_SESSION['flash']['error'] ?? null;
        $flashWarning = $_SESSION['flash']['warning'] ?? null;
        
        // Clear flash messages after reading
        if ($flashSuccess) unset($_SESSION['flash']['success']);
        if ($flashError) unset($_SESSION['flash']['error']);
        if ($flashWarning) unset($_SESSION['flash']['warning']);
        ?>
        const FLASH_SUCCESS = <?= json_encode($flashSuccess) ?>;
        const FLASH_ERROR = <?= json_encode($flashError) ?>;
        const FLASH_WARNING = <?= json_encode($flashWarning) ?>;
    </script>
    
    <!-- Feedback System (SweetAlert2) -->
    <script src="<?= PUBLIC_URL ?>/js/feedback.js"></script>
    
    <!-- Session Timeout Handler - Auto logout setelah 30 menit tidak aktif -->
    <script src="<?= PUBLIC_URL ?>/js/session-timeout.js"></script>
</body>
</html>