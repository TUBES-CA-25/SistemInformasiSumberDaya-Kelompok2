<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-500 text-sm">Selamat datang, Admin! Berikut ringkasan aktivitas laboratorium hari ini.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between hover:shadow-md transition-all cursor-default group">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Sedang Berlangsung</p>
            <h2 class="text-3xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors" id="statJadwal">0</h2>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all transform group-hover:scale-110">
            <i class="fas fa-calendar-day text-xl"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between hover:shadow-md transition-all cursor-default group">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Asisten</p>
            <h2 class="text-3xl font-bold text-gray-800 group-hover:text-emerald-600 transition-colors" id="statAsisten">0</h2>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all transform group-hover:scale-110">
            <i class="fas fa-users text-xl"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between hover:shadow-md transition-all cursor-default group">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Ruang Lab</p>
            <h2 class="text-3xl font-bold text-gray-800 group-hover:text-purple-600 transition-colors" id="statLab">0</h2>
        </div>
        <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all transform group-hover:scale-110">
            <i class="fas fa-flask text-xl"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between hover:shadow-md transition-all cursor-default group">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Alumni</p>
            <h2 class="text-3xl font-bold text-gray-800 group-hover:text-amber-600 transition-colors" id="statAlumni">0</h2>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all transform group-hover:scale-110">
            <i class="fas fa-user-graduate text-xl"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    
    <div class="lg:col-span-3">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 min-h-[500px] flex flex-col">
            
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-8 bg-blue-600 rounded-full"></div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Praktikum Saat Ini</h3>
                        <p class="text-xs text-gray-500">Sesi yang sedang berjalan di laboratorium</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm">
                        <p class="text-[9px] uppercase tracking-[0.2em] font-black text-gray-400 leading-none mb-1.5 text-center">Tanggal</p>
                        <span class="text-sm font-bold text-gray-700 block whitespace-nowrap" id="currentDate">-</span>
                    </div>
                </div>
            </div>
            
            <div class="p-6 flex-1 bg-gray-50/30">
                <div id="jadwalCardContainer" class="space-y-4">
                    <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                        <i class="fas fa-circle-notch fa-spin text-3xl mb-3"></i>
                        <span>Memuat data...</span>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100 bg-white rounded-b-xl text-center">
                <a href="javascript:void(0)" onclick="navigate('admin/jadwal')" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    Lihat Jadwal Selengkapnya <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
            <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="font-bold text-gray-800 text-sm">Menu Pintasan</h3>
            </div>
            
            <div class="p-4 grid grid-cols-1 gap-3">
                
                <button onclick="navigate('admin/jadwal')" class="group flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50 hover:shadow-sm transition-all text-left bg-white">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-700 group-hover:text-blue-700 text-sm">Kelola Jadwal</h4>
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

<script src="<?= PUBLIC_URL ?>/js/admin/dashboard.js"></script>