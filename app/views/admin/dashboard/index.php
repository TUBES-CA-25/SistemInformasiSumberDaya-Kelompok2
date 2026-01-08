<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-500 text-sm">Selamat datang, Admin! Berikut ringkasan aktivitas laboratorium hari ini.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between hover:shadow-md transition-all cursor-default group">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Jadwal Hari Ini</p>
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
                        <h3 class="font-bold text-lg text-gray-800">Timeline Praktikum</h3>
                        <p class="text-xs text-gray-500">Sesi yang berlangsung hari ini</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-blue-600 text-white px-4 py-2.5 rounded-xl shadow-md flex items-center gap-3 border border-blue-700">
                        <div class="hidden sm:flex items-center justify-center w-9 h-9 rounded-lg bg-blue-500/30">
                            <i class="fas fa-clock text-blue-100 animate-pulse text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[9px] uppercase tracking-[0.2em] font-black text-blue-200 leading-none mb-1.5">Waktu Sekarang</p>
                            <div id="realtimeClock" class="text-2xl font-black font-mono tabular-nums leading-none tracking-tight">00:00:00</div>
                        </div>
                    </div>
                    <div class="bg-white px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm hidden md:block">
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

                <button onclick="navigate('admin/manajemen')" class="group flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:border-emerald-200 hover:bg-emerald-50 hover:shadow-sm transition-all text-left bg-white">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-700 group-hover:text-emerald-700 text-sm">Anggota Baru</h4>
                        <p class="text-[10px] text-gray-400">Tambah asisten/staf</p>
                    </div>
                </button>

                <button onclick="navigate('admin/peraturan')" class="group flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:border-purple-200 hover:bg-purple-50 hover:shadow-sm transition-all text-left bg-white">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                        <i class="fas fa-book"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-700 group-hover:text-purple-700 text-sm">Peraturan</h4>
                        <p class="text-[10px] text-gray-400">Update tata tertib</p>
                    </div>
                </button>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Tampilkan Tanggal Hari Ini (Format Indonesia)
    const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
    document.getElementById('currentDate').innerText = new Date().toLocaleDateString('id-ID', options);

    // 2. Update Jam Realtime setiap detik
    updateRealtimeClock();
    setInterval(updateRealtimeClock, 1000);

    // 3. Load Data dari Database pertama kali
    loadDashboardStats();
    
    // 4. Refresh jadwal setiap menit (opsional untuk filter jam)
    setInterval(loadDashboardStats, 60000); // Refresh setiap 60 detik
});

function updateRealtimeClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    document.getElementById('realtimeClock').innerText = `${hours}:${minutes}:${seconds}`;
}

function loadDashboardStats() {
    // Panggil API yang baru kita buat
    fetch(API_URL + '/dashboard/stats') 
    .then(res => res.json())
    .then(res => {
        if(res.status === 'success' || res.code === 200) {
            const data = res.data;

            // A. Isi Kartu Statistik
            document.getElementById('statJadwal').innerText = data.total_jadwal_hari_ini || 0;
            document.getElementById('statAsisten').innerText = data.total_asisten || 0;
            document.getElementById('statLab').innerText = data.total_lab || 0;
            document.getElementById('statAlumni').innerText = data.total_alumni || 0;

            // B. Render Card Jadwal
            renderJadwalCards(data.jadwal_hari_ini);
        }
    })
    .catch(err => {
        console.error("Error loading dashboard:", err);
        // Tampilkan pesan error di container jika gagal
        document.getElementById('jadwalCardContainer').innerHTML = `
            <div class="text-center py-10 text-red-400">
                <p>Gagal memuat data dari server.</p>
            </div>`;
    });
}

function renderJadwalCards(data) {
    const container = document.getElementById('jadwalCardContainer');
    container.innerHTML = '';

    // Cek jika data kosong (Misal hari Minggu tidak ada jadwal)
    if (!data || data.length === 0) {
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4 border border-gray-100">
                    <i class="far fa-calendar-check text-3xl"></i>
                </div>
                <h3 class="text-gray-600 font-bold text-lg">Jadwal Kosong</h3>
                <p class="text-gray-400 text-sm mt-1">Hari ini tidak ada praktikum yang aktif.</p>
            </div>`;
        return;
    }

    // Render Data Asli
    data.forEach(item => {
        // Format Waktu (Hapus detik 00)
        const mulai = item.waktuMulai.substring(0, 5);
        const selesai = item.waktuSelesai.substring(0, 5);

        // Styling Card
        const statusColor = "border-l-blue-500"; 
        const timeBg = "bg-blue-50 text-blue-700";

        const card = `
            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 border-l-4 ${statusColor} group relative overflow-hidden">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    
                    <div class="flex items-center gap-5">
                        <div class="flex flex-col items-center justify-center w-16 h-16 rounded-xl ${timeBg} border border-blue-100 flex-shrink-0 shadow-inner">
                            <span class="text-sm font-bold">${mulai}</span>
                            <div class="h-px w-8 bg-blue-200 my-0.5"></div>
                            <span class="text-xs text-blue-500 opacity-80">${selesai}</span>
                        </div>
                        
                        <div>
                            <h4 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition-colors line-clamp-1">
                                ${item.namaMatakuliah}
                            </h4>
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <span class="flex items-center gap-1.5 bg-purple-50 px-2.5 py-1 rounded text-[11px] font-semibold text-purple-700 border border-purple-100">
                                    <i class="fas fa-flask"></i> ${item.namaLab}
                                </span>
                                <span class="flex items-center gap-1.5 bg-gray-100 px-2.5 py-1 rounded text-[11px] font-semibold text-gray-600 border border-gray-200">
                                    <i class="fas fa-code"></i> ${item.kodeMatakuliah || '-'}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-6 sm:border-l sm:border-gray-100 sm:pl-6 min-w-[100px]">
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-0.5">Kelas</p>
                            <span class="text-2xl font-bold text-gray-700 font-mono">${item.kelas}</span>
                        </div>
                        
                        <div class="relative w-10 h-10 flex items-center justify-center" title="Status: Aktif">
                            <span class="absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-20 animate-ping"></span>
                            <div class="relative inline-flex rounded-full h-10 w-10 bg-emerald-50 text-emerald-500 items-center justify-center border border-emerald-100">
                                <i class="fas fa-check text-lg"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        `;
        container.innerHTML += card;
    });
}
</script>