<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - IC-LABS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Animasi Masuk */
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-enter { animation: slide-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        

        /* 3D Tilt Effect Wrapper */
        .tilt-wrapper {
            transform-style: preserve-3d;
            perspective: 1000px;
        }
        .tilt-card {
            transition: transform 0.1s ease-out;
            transform-style: preserve-3d;
        }

        /* Glassmorphism Premium */
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.05),
                0 20px 40px -6px rgba(0, 0, 0, 0.1),
                inset 0 0 20px rgba(255, 255, 255, 0.5);
        }

        /* Background Pattern */
        .bg-grid-slate {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(226, 232, 240, 0.5) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(226, 232, 240, 0.5) 1px, transparent 1px);
        }

        /* UPDATED: Animasi Melayang Lebih Halus (Levitate) */
        @keyframes float-smooth {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); } /* Naik pelan */
            100% { transform: translateY(0px); }
        }
        .animate-levitate { 
            animation: float-smooth 6s ease-in-out infinite; 
        }
    </style>
</head>
<body class="bg-slate-50 overflow-hidden h-screen w-full text-slate-800">

    <div class="flex h-full w-full relative">
        
        <div class="hidden lg:flex w-[55%] h-full bg-blue-900 relative items-center justify-center overflow-hidden">
            
            <div class="absolute inset-0 z-0">
                <img src="<?php echo ASSETS_URL; ?>/assets/uploads/gedungfikomm.webp" 
                     alt="Gedung FIKOM UMI" class="w-full h-full object-cover opacity-75">
                <div class="absolute inset-0 bg-gradient-to-tr from-blue-950/70 via-blue-900/50 to-indigo-900/30 mix-blend-multiply"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            </div>

            <canvas id="particle-canvas" class="z-10 absolute inset-0"></canvas>

            <div class="relative z-20 text-center px-12 -translate-y-10">
                
                <div class="inline-flex items-center justify-center w-28 h-28 rounded-3xl bg-white/10 backdrop-blur-md mb-8 border border-white/20 shadow-[0_0_40px_rgba(59,130,246,0.3)] p-5 animate-levitate">
                    <img src="<?php echo ASSETS_URL; ?>/images/navbar-icon.webp" 
                         alt="Logo" class="w-full h-full object-contain drop-shadow-lg">
                </div>
                
                <h1 class="text-7xl font-extrabold mb-4 tracking-tight text-white drop-shadow-xl animate-enter">
                    IC-LABS
                </h1>

                <p class="text-blue-100/90 text-lg font-light max-w-md mx-auto leading-relaxed animate-enter" style="animation-delay: 0.2s">
                    Sistem Informasi Sumber Daya Laboratorium<br>Terintegrasi & Real-time.
                </p>
            </div>

            <div class="absolute top-0 bottom-0 right-[-1px] w-48 h-[110%] -mt-[5%] z-30 pointer-events-none">
                <svg class="h-full w-full" preserveAspectRatio="none" viewBox="0 0 100 100" fill="currentColor">
                    <path d="M 100 0 C 60 20 40 60 100 100 Z" fill="#93c5fd" opacity="0.2" transform="translate(-15, 0)"></path>
                    <path d="M 100 0 C 50 40 20 80 100 100 Z" fill="#60a5fa" opacity="0.3" transform="translate(-8, 0)"></path>
                    <path d="M 100 0 C 30 30 10 70 100 100 Z" class="text-slate-50"></path>
                </svg>
            </div>
        </div>

        <div class="w-full lg:w-[45%] h-full bg-slate-50 relative flex items-center justify-center p-6 tilt-wrapper">
            
            <div class="absolute inset-0 bg-grid-slate z-0"></div>
            
            <div class="absolute top-1/4 right-1/4 w-72 h-72 bg-blue-300/30 rounded-full mix-blend-multiply filter blur-[80px] animate-pulse"></div>
            <div class="absolute bottom-1/4 left-1/4 w-72 h-72 bg-indigo-300/30 rounded-full mix-blend-multiply filter blur-[80px] animate-pulse" style="animation-delay: 2s"></div>

            <div id="login-card" class="w-full max-w-[420px] relative z-40 tilt-card animate-enter" style="animation-delay: 0.3s">
                
                <div class="glass-card rounded-[2rem] p-8 sm:p-10 relative overflow-hidden group">
                    
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Login Admin</h2>
                        <p class="text-slate-500 mt-2 text-sm">Masuk untuk mengelola inventaris lab.</p>
                    </div>

                    <?php if (isset($_SESSION['flash']['error'])): ?>
                        <div class="flex items-center p-4 mb-6 text-sm text-red-600 bg-red-50/80 rounded-2xl border border-red-200 shadow-sm backdrop-blur-sm">
                            <i class="fas fa-circle-exclamation mr-3 text-lg"></i>
                            <?= $_SESSION['flash']['error']; unset($_SESSION['flash']['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['flash']['success'])): ?>
                        <div class="flex items-center p-4 mb-6 text-sm text-emerald-600 bg-emerald-50/80 rounded-2xl border border-emerald-200 shadow-sm backdrop-blur-sm">
                            <i class="fas fa-check-circle mr-3 text-lg"></i>
                            <?= $_SESSION['flash']['success']; unset($_SESSION['flash']['success']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= PUBLIC_URL ?>/login" method="POST" class="space-y-5">
                        
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                                Email
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <input type="email" name="email" id="email" class="block w-full pl-11 pr-4 py-3.5 text-sm text-slate-900 bg-white/70 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 shadow-sm transition-all" placeholder="admin@example.com" required />
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                                Password
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <input type="password" name="password" id="password" class="block w-full pl-11 pr-12 py-3.5 text-sm text-slate-900 bg-white/70 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 shadow-sm transition-all" placeholder="Masukkan password" required />
                                <button type="button" id="toggle-password" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors z-20">
                                    <i class="fas fa-eye" id="toggle-password-icon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-600/30 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden mt-6">
                            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-[120%] group-hover:translate-x-[120%] transition-transform duration-700 ease-in-out"></div>
                            <span class="flex items-center gap-2">
                                LOGIN SEKARANG <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </button>
                    </form>

                    <div class="mt-8 text-center pt-6 border-t border-slate-200/50">
                        <a href="<?= rtrim(PUBLIC_URL, '/') . '/home' ?>" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-600 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Halaman Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="<?= PUBLIC_URL ?>/js/login.js"></script>

</body>
</html>