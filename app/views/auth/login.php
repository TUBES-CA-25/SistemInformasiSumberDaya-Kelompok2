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
        
        /* Floating Labels untuk Input */
        .floating-input:focus ~ label,
        .floating-input:not(:placeholder-shown) ~ label {
            top: -10px;
            left: 10px;
            font-size: 0.75rem;
            color: #2563eb;
            background-color: white;
            padding: 0 8px;
            font-weight: 600;
        }

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
                <img src="<?php echo ASSETS_URL; ?>/images/gedung-fikom-umi.jpg" 
                     alt="Gedung" class="w-full h-full object-cover opacity-60">
                <div class="absolute inset-0 bg-gradient-to-tr from-blue-900 via-blue-900/80 to-indigo-900/60 mix-blend-multiply"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            </div>

            <canvas id="particle-canvas" class="z-10 absolute inset-0"></canvas>

            <div class="relative z-20 text-center px-12 -translate-y-10">
                
                <div class="inline-flex items-center justify-center w-28 h-28 rounded-3xl bg-white/10 backdrop-blur-md mb-8 border border-white/20 shadow-[0_0_40px_rgba(59,130,246,0.3)] p-5 animate-levitate">
                    <img src="<?php echo ASSETS_URL; ?>/images/navbar-icon.png" 
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

                    <form action="<?= PUBLIC_URL ?>/auth" method="POST" class="space-y-6">
                        
                        <div class="relative group">
                            <input type="text" name="username" id="username" class="floating-input block px-4 py-4 w-full text-sm text-slate-900 bg-white/60 rounded-xl border border-slate-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer placeholder-transparent shadow-sm transition-all" placeholder=" " required />
                            <label for="username" class="absolute text-sm text-slate-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-transparent px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-3 cursor-text pointer-events-none">
                                <i class="fas fa-user mr-1"></i> Username
                            </label>
                        </div>

                        <div class="relative group">
                            <input type="password" name="password" id="password" class="floating-input block px-4 py-4 w-full text-sm text-slate-900 bg-white/60 rounded-xl border border-slate-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer placeholder-transparent shadow-sm transition-all" placeholder=" " required />
                            <label for="password" class="absolute text-sm text-slate-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-transparent px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-3 cursor-text pointer-events-none">
                                <i class="fas fa-lock mr-1"></i> Password
                            </label>
                        </div>
                        
                        <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-600/30 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
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

    <script>
        const canvas = document.getElementById('particle-canvas');
        const ctx = canvas.getContext('2d');
        let particlesArray;

        function setCanvasSize() {
            const parent = canvas.parentElement;
            canvas.width = parent.offsetWidth;
            canvas.height = parent.offsetHeight;
        }
        setCanvasSize();
        window.addEventListener('resize', setCanvasSize);

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 0.5;
                this.speedX = Math.random() * 0.3 - 0.15;
                this.speedY = Math.random() * 0.3 - 0.15;
                this.color = 'rgba(255, 255, 255, 0.4)';
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x > canvas.width || this.x < 0) this.speedX = -this.speedX;
                if (this.y > canvas.height || this.y < 0) this.speedY = -this.speedY;
            }
            draw() {
                ctx.fillStyle = this.color;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function init() {
            particlesArray = [];
            let numberOfParticles = (canvas.height * canvas.width) / 15000;
            for (let i = 0; i < numberOfParticles; i++) {
                particlesArray.push(new Particle());
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let i = 0; i < particlesArray.length; i++) {
                particlesArray[i].update();
                particlesArray[i].draw();
                for (let j = i; j < particlesArray.length; j++) {
                    const dx = particlesArray[i].x - particlesArray[j].x;
                    const dy = particlesArray[i].y - particlesArray[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    if (distance < 100) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(255, 255, 255, ${0.15 - distance/800})`;
                        ctx.lineWidth = 0.5;
                        ctx.moveTo(particlesArray[i].x, particlesArray[i].y);
                        ctx.lineTo(particlesArray[j].x, particlesArray[j].y);
                        ctx.stroke();
                        ctx.closePath();
                    }
                }
            }
            requestAnimationFrame(animate);
        }
        init();
        animate();

        // 2. 3D Tilt Effect Script
        const card = document.getElementById('login-card');
        const container = document.body;

        container.addEventListener('mousemove', (e) => {
            if (window.innerWidth < 1024) return; // Disable on mobile
            const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 25;
            card.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });

        // Reset animation when mouse leaves
        container.addEventListener('mouseenter', (e) => {
            if (window.innerWidth < 1024) return;
            card.style.transition = 'none';
        });

        container.addEventListener('mouseleave', (e) => {
            if (window.innerWidth < 1024) return;
            card.style.transition = 'all 0.5s ease';
            card.style.transform = `rotateY(0deg) rotateX(0deg)`;
        });
    </script>

</body>
</html>