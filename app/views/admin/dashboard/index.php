<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&display=swap');

    #dashPanel .font-display { font-family: 'Space Grotesk', ui-sans-serif, system-ui, sans-serif; }

    #dashPanel .dot-grid {
        background-image: radial-gradient(rgba(255,255,255,0.14) 1px, transparent 1px);
        background-size: 18px 18px;
    }

    #dashPanel .readout-card { position: relative; overflow: hidden; }
    #dashPanel .readout-card::before {
        content: "";
        position: absolute; inset: 0 0 auto 0; height: 3px;
        background: var(--bar-color, #4338CA);
        opacity: .85;
    }
    #dashPanel .readout-card::after {
        content: "";
        position: absolute; inset: 0;
        background-image: linear-gradient(rgba(15,23,42,.035) 1px, transparent 1px), linear-gradient(90deg, rgba(15,23,42,.035) 1px, transparent 1px);
        background-size: 14px 14px;
        opacity: 0; transition: opacity .3s ease;
        pointer-events: none;
    }
    #dashPanel .readout-card:hover::after { opacity: 1; }

    #dashPanel .pulse-dot { position: relative; display: inline-flex; width: 8px; height: 8px; }
    #dashPanel .pulse-dot span.core { position: absolute; inset: 0; border-radius: 9999px; background: #22D3EE; }
    #dashPanel .pulse-dot span.ring { position: absolute; inset: -4px; border-radius: 9999px; background: #22D3EE; opacity: .35; animation: dashPing 1.8s cubic-bezier(0,0,.2,1) infinite; }
    @keyframes dashPing { 75%, 100% { transform: scale(2.4); opacity: 0; } }

    #dashPanel .scan-line {
        position: absolute; left: 0; right: 0; height: 40%;
        background: linear-gradient(to bottom, transparent, rgba(34,211,238,.06), transparent);
        animation: dashScan 6s ease-in-out infinite;
    }
    @keyframes dashScan { 0% { top: -40%; } 50% { top: 100%; } 100% { top: -40%; } }

    @media (prefers-reduced-motion: reduce) {
        #dashPanel .pulse-dot span.ring, #dashPanel .scan-line { animation: none; }
    }
</style>

<div id="dashPanel">

    <!-- HERO / SYSTEM STATUS PANEL -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0B1120] via-[#181447] to-[#312E81] p-7 md:p-9 mb-8 shadow-lg">
        <div class="absolute inset-0 dot-grid"></div>
        <div class="scan-line"></div>

        <div class="relative flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="pulse-dot"><span class="ring"></span><span class="core"></span></span>
                    <span class="text-[10px] font-bold tracking-[0.25em] text-cyan-300/90 uppercase">Sistem IC-Labs &middot; Panel Kontrol</span>
                </div>
                <h1 class="font-display text-2xl md:text-3xl font-bold text-white">Selamat datang, Admin</h1>
                <p class="text-slate-300/80 text-sm mt-1.5 max-w-md">Berikut ringkasan aktivitas laboratorium hari ini, diperbarui otomatis setiap menit.</p>
            </div>

            <div class="flex items-center gap-3 bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl px-5 py-3.5 self-start md:self-auto">
                <i class="far fa-calendar-alt text-cyan-300"></i>
                <div>
                    <p class="text-[9px] uppercase tracking-[0.2em] font-bold text-slate-400 leading-none mb-1">Tanggal Aktif</p>
                    <span class="font-display text-sm font-semibold text-white block whitespace-nowrap" id="currentDate">-</span>
                </div>
            </div>
        </div>
    </div>

    <!-- STAT READOUTS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <div class="readout-card bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all group" style="--bar-color:#4338CA">
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Sedang Berlangsung</p>
                    <h2 class="font-display text-3xl font-bold text-gray-800" id="statJadwal">0</h2>
                </div>
                <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-700 group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>

        <div class="readout-card bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all group" style="--bar-color:#0EA5A0">
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Total Asisten</p>
                    <h2 class="font-display text-3xl font-bold text-gray-800" id="statAsisten">0</h2>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="readout-card bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all group" style="--bar-color:#7C3AED">
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Ruang Lab</p>
                    <h2 class="font-display text-3xl font-bold text-gray-800" id="statLab">0</h2>
                </div>
                <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                    <i class="fas fa-flask"></i>
                </div>
            </div>
        </div>

        <div class="readout-card bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all group" style="--bar-color:#D97706">
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Total Alumni</p>
                    <h2 class="font-display text-3xl font-bold text-gray-800" id="statAlumni">0</h2>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 min-h-[500px] flex flex-col">

                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/40 rounded-t-2xl">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-8 bg-indigo-600 rounded-full"></div>
                        <div>
                            <h3 class="font-display font-bold text-lg text-gray-800">Praktikum Saat Ini</h3>
                            <p class="text-xs text-gray-500">Sesi yang sedang berjalan di laboratorium</p>
                        </div>
                    </div>
                    <div class="hidden sm:flex items-center gap-2 bg-cyan-50 border border-cyan-100 text-cyan-700 px-3 py-1.5 rounded-full">
                        <span class="pulse-dot"><span class="ring" style="background:#0891B2"></span><span class="core" style="background:#0891B2"></span></span>
                        <span class="text-[10px] font-black uppercase tracking-widest">Live</span>
                    </div>
                </div>

                <div class="p-6 flex-1 bg-gray-50/40">
                    <div id="jadwalCardContainer" class="space-y-4">
                        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                            <i class="fas fa-circle-notch fa-spin text-3xl mb-3"></i>
                            <span>Memuat data...</span>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-white rounded-b-2xl text-center">
                    <a href="javascript:void(0)" onclick="navigate('admin/jadwal')" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                        Lihat Jadwal Selengkapnya <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="font-display font-bold text-gray-800 text-sm">Menu Pintasan</h3>
                </div>

                <div class="p-4 grid grid-cols-1 gap-3">

                    <button onclick="navigate('admin/jadwal')" class="group flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50 hover:shadow-sm transition-all text-left bg-white">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-700 group-hover:text-indigo-700 text-sm">Kelola Jadwal</h4>
                            <p class="text-[10px] text-gray-400">Atur waktu & ruang</p>
                        </div>
                    </button>

                    <button onclick="navigate('admin/asisten')" class="group flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:border-emerald-200 hover:bg-emerald-50 hover:shadow-sm transition-all text-left bg-white">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-700 group-hover:text-emerald-700 text-sm">Anggota Baru</h4>
                            <p class="text-[10px] text-gray-400">Kelola asisten lab</p>
                        </div>
                    </button>

                    <button onclick="navigate('admin/matakuliah')" class="group flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:border-purple-200 hover:bg-purple-50 hover:shadow-sm transition-all text-left bg-white">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-700 group-hover:text-purple-700 text-sm">Mata Kuliah</h4>
                            <p class="text-[10px] text-gray-400">Kelola daftar kuliah</p>
                        </div>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= PUBLIC_URL ?>/js/admin/dashboard.js"></script>
