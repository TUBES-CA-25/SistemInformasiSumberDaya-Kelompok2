<?php
// Tentukan base url untuk link fallback
$publicUrl = defined('PUBLIC_URL') ? PUBLIC_URL : '';
$baseUrl   = defined('BASE_URL') ? BASE_URL : $publicUrl;
$assetsUrl = defined('ASSETS_URL') ? ASSETS_URL : $publicUrl;

// Tangkap path yang sedang diakses
$requestedPath = $path ?? ($_SERVER['REQUEST_URI'] ?? '');
if (!empty($requestedPath)) {
    $requestedPath = explode('?', $requestedPath)[0];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | IC-LABS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0f172a;
        }

        /* Glassmorphic Card */
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                        inset 0 1px 1px rgba(255, 255, 255, 0.1);
        }

        /* Ambient Glow Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(2deg); }
        }

        @keyframes pulse-slow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.08); }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-pulse-slow {
            animation: pulse-slow 8s ease-in-out infinite;
        }

        /* Background Pattern */
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Background Elements & Ambient Lights -->
    <div class="absolute inset-0 bg-grid-pattern z-0 pointer-events-none"></div>
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-600/20 rounded-full blur-[120px] animate-pulse-slow pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-600/20 rounded-full blur-[120px] animate-pulse-slow pointer-events-none" style="animation-delay: 4s;"></div>

    <!-- Main Container -->
    <div class="relative z-10 w-full max-w-2xl text-center">
        <div class="glass-card rounded-3xl p-8 sm:p-12 relative overflow-hidden">
            
            <!-- Glow Border Effect Top -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-[1px] bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>

            <!-- Header Icon & Logo Badge -->
            <div class="inline-flex items-center justify-center relative mb-6">
                <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-blue-600/30 to-purple-600/30 border border-white/10 flex items-center justify-center animate-float shadow-2xl backdrop-blur-md">
                    <?php if (!empty($assetsUrl)): ?>
                        <img src="<?= rtrim($assetsUrl, '/') ?>/images/navbar-icon.webp" alt="IC-LABS Logo" class="w-14 h-14 object-contain drop-shadow-[0_0_15px_rgba(59,130,246,0.5)]">
                    <?php else: ?>
                        <i class="fas fa-compass-slash text-4xl text-blue-400"></i>
                    <?php endif; ?>
                </div>
                <div class="absolute -bottom-2 -right-2 bg-red-500/20 border border-red-500/40 text-red-400 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shadow-lg">
                    <i class="fas fa-exclamation"></i>
                </div>
            </div>

            <!-- Big 404 Number with Gradient -->
            <h1 class="text-7xl sm:text-8xl font-black tracking-tight bg-gradient-to-r from-blue-400 via-indigo-300 to-purple-400 bg-clip-text text-transparent drop-shadow-sm mb-2">
                404
            </h1>

            <div class="mb-4">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                    <span class="w-2 h-2 rounded-full bg-red-400 animate-ping"></span>
                    HALAMAN TIDAK DITEMUKAN
                </span>
            </div>

            <!-- Error Description -->
            <h2 class="text-xl sm:text-2xl font-bold text-white mb-3">
                Waduh! Jalur yang Anda tuju tidak ada.
            </h2>
            <p class="text-slate-400 text-sm sm:text-base max-w-md mx-auto mb-6 leading-relaxed">
                Halaman yang Anda cari mungkin telah dipindahkan, dihapus, atau alamat URL yang Anda masukkan salah.
            </p>

            <?php if (!empty($requestedPath)): ?>
            <!-- Requested Path Code Pill -->
            <div class="mb-8 inline-block">
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800/80 border border-slate-700/80 text-xs font-mono text-slate-300 shadow-inner">
                    <i class="fas fa-link text-blue-400 text-xs"></i>
                    <span>URL:</span>
                    <span class="text-blue-300 font-semibold"><?= htmlspecialchars($requestedPath) ?></span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="<?= !empty($baseUrl) ? rtrim($baseUrl, '/') . '/home' : '/home' ?>" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 shadow-lg shadow-blue-600/30 hover:shadow-blue-500/50 transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-home text-base"></i>
                    <span>Kembali ke Beranda</span>
                </a>

                <button onclick="window.history.back()" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl text-sm font-bold text-slate-300 bg-slate-800/90 hover:bg-slate-800 hover:text-white border border-slate-700/80 shadow-sm transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-arrow-left text-base"></i>
                    <span>Halaman Sebelumnya</span>
                </button>
            </div>

            <!-- Quick Access Links -->
            <div class="mt-10 pt-6 border-t border-slate-800/80 flex flex-wrap justify-center items-center gap-x-6 gap-y-2 text-xs text-slate-400">
                <span class="text-slate-500 font-medium">Akses Cepat:</span>
                <a href="<?= !empty($baseUrl) ? rtrim($baseUrl, '/') . '/laboratorium' : '/laboratorium' ?>" class="hover:text-blue-400 transition-colors">Laboratorium</a>
                <a href="<?= !empty($baseUrl) ? rtrim($baseUrl, '/') . '/jadwal' : '/jadwal' ?>" class="hover:text-blue-400 transition-colors">Jadwal Praktikum</a>
                <a href="<?= !empty($baseUrl) ? rtrim($baseUrl, '/') . '/kontak' : '/kontak' ?>" class="hover:text-blue-400 transition-colors">Kontak Kami</a>
                <a href="<?= !empty($baseUrl) ? rtrim($baseUrl, '/') . '/iclabs-login' : '/iclabs-login' ?>" class="hover:text-blue-400 transition-colors">Login Admin</a>
            </div>

        </div>

        <!-- Footer watermark -->
        <p class="mt-6 text-xs text-slate-500 font-medium">
            &copy; <?= date('Y') ?> IC-LABS - Sistem Informasi Sumber Daya Laboratorium.
        </p>
    </div>

</body>
</html>