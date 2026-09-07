<style>
/* App Icon Box Preview Styles */
.app-icon-preview {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: all 0.2s ease;
    flex-shrink: 0;
}
.app-icon-preview-lg {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    font-size: 1.85rem;
}
.color-blue { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #2563eb; }
.color-green { background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); color: #16a34a; }
.color-purple { background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%); color: #9333ea; }
.color-orange { background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); color: #ea580c; }
.color-red { background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); color: #dc2626; }
.color-cyan { background: linear-gradient(135deg, #ecfeff 0%, #cffafe 100%); color: #0891b2; }
.color-yellow { background: linear-gradient(135deg, #fefce8 0%, #fef9c3 100%); color: #ca8a04; }
.color-indigo { background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); color: #4f46e5; }
.color-pink { background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%); color: #db2777; }

/* Color Swatch Selector */
.color-swatch-btn {
    width: 32px;
    height: 32px;
    border-radius: 9999px;
    cursor: pointer;
    transition: all 0.2s;
    border: 2px solid transparent;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
}
.color-swatch-btn:hover {
    transform: scale(1.12);
}
.color-swatch-btn.active {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
}

/* Toggle Switch Smooth */
.toggle-checkbox:checked {
    right: 0;
    border-color: #10b981;
}
.toggle-checkbox:checked + .toggle-label {
    background-color: #10b981;
}
</style>

<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-3">
                <i class="ri-apps-2-line text-blue-600 text-2xl"></i>
                Manajemen Ekosistem IC-Labs Apps
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Kelola aplikasi portal lab, tautan web tujuan, ikon, deskripsi, urutan, serta toggle aktif/nonaktif
            </p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <a href="<?= BASE_URL ?>/apps" target="_blank" 
               class="px-4 py-2.5 rounded-xl border border-blue-200 bg-blue-50/70 hover:bg-blue-100 text-blue-700 text-xs sm:text-sm font-semibold transition-all flex items-center gap-2">
                <i class="ri-external-link-line"></i>
                <span>Lihat Halaman Publik</span>
            </a>

            <button type="button" onclick="openAppModal()" 
               class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-md shadow-blue-600/20 transition-all duration-200 flex items-center justify-center gap-2 font-semibold text-xs sm:text-sm transform hover:-translate-y-0.5">
                <i class="fas fa-plus"></i> Tambah Aplikasi
            </button>
        </div>
    </div>

    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                <i class="ri-apps-line"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Aplikasi</p>
                <h3 id="statTotalApps" class="text-xl font-bold text-gray-800">0</h3>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                <i class="ri-checkbox-circle-line"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Aplikasi Aktif (Dapat Diakses)</p>
                <h3 id="statActiveApps" class="text-xl font-bold text-emerald-600">0</h3>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                <i class="ri-pause-circle-line"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Aplikasi Nonaktif</p>
                <h3 id="statInactiveApps" class="text-xl font-bold text-amber-600">0</h3>
            </div>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <div class="relative flex-1 sm:max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                <i class="fas fa-search text-xs"></i>
            </span>
            <input type="text" id="searchAppInput" placeholder="Cari judul, deskripsi, atau tautan web..." 
                   class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all">
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs text-gray-500 font-medium mr-1">Status:</span>
            <button type="button" onclick="filterAppStatus('semua')" id="btnFilterSemua" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-blue-600 text-white shadow-sm transition-all">
                Semua
            </button>
            <button type="button" onclick="filterAppStatus('aktif')" id="btnFilterAktif" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">
                Aktif
            </button>
            <button type="button" onclick="filterAppStatus('nonaktif')" id="btnFilterNonaktif" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">
                Nonaktif
            </button>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-xs font-bold uppercase tracking-wider">
                        <th class="px-4 py-4 text-center w-14">Urut</th>
                        <th class="px-5 py-4 w-72">Aplikasi & Ikon</th>
                        <th class="px-5 py-4">Deskripsi</th>
                        <th class="px-5 py-4 w-72">Link Web Tujuan</th>
                        <th class="px-4 py-4 text-center w-28">Status</th>
                        <th class="px-5 py-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody id="appTableBody" class="divide-y divide-gray-100 text-gray-700 text-sm">
                    <!-- Loaded via JavaScript -->
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400">
                            <i class="fas fa-spinner fa-spin text-xl mr-2"></i> Memuat daftar aplikasi...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div id="appCardsMobile" class="grid grid-cols-1 gap-4 md:hidden">
        <!-- Loaded via JavaScript -->
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL FORM: TAMBAH & EDIT APLIKASI                       -->
<!-- ======================================================== -->
<div id="appModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Backdrop Blur -->
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeAppModal()"></div>

    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center sm:items-center">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-xl border border-gray-100 my-auto">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div id="modalHeaderIcon" class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-lg shadow-sm">
                        <i class="ri-apps-line"></i>
                    </div>
                    <div>
                        <h3 id="modalAppTitle" class="text-base font-bold text-blue-900">Tambah Aplikasi Baru</h3>
                        <p class="text-xs text-gray-500">Kelola informasi portal dan tautan navigasi web</p>
                    </div>
                </div>
                <button type="button" onclick="closeAppModal()" class="w-8 h-8 rounded-full bg-white/80 hover:bg-white text-gray-400 hover:text-gray-600 transition-colors flex items-center justify-center shadow-sm">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Form Content -->
            <form id="appForm" class="space-y-4">
                <input type="hidden" id="appId" name="id" value="">

                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                    
                    <!-- Judul Aplikasi -->
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">
                            Judul Aplikasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="appJudul" name="judul" required placeholder="Contoh: Monitoring Praktikum" 
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all font-medium">
                    </div>

                    <!-- Deskripsi Aplikasi -->
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">
                            Deskripsi Singkat <span class="text-red-500">*</span>
                        </label>
                        <textarea id="appDeskripsi" name="deskripsi" rows="2" required placeholder="Contoh: Input berita acara, absensi, dan update progres praktikum real-time."
                                  class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all leading-relaxed"></textarea>
                    </div>

                    <!-- Link Web Tujuan (Krusial) -->
                    <div class="bg-blue-50/50 p-3.5 rounded-xl border border-blue-100 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs sm:text-sm font-bold text-blue-900 flex items-center gap-1.5">
                                <i class="ri-links-line text-blue-600"></i> Link Web Tujuan (URL) <span class="text-red-500">*</span>
                            </label>
                            <span class="text-[11px] text-blue-600 font-medium">Website yang dibuka saat ditekan</span>
                        </div>
                        
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-globe text-xs"></i>
                            </span>
                            <input type="text" id="appUrl" name="url" required placeholder="https://iclabs.fikom.umi.ac.id/s/monitoring-praktikum/ atau /jadwalupk"
                                   class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all font-mono">
                        </div>

                        <!-- Shortcut Prefixes -->
                        <div class="flex flex-wrap items-center gap-1.5 pt-1 text-[11px]">
                            <span class="text-gray-500">Shortcut:</span>
                            <button type="button" onclick="setAppUrlPrefix('https://')" class="px-2 py-0.5 rounded-md bg-white border border-gray-200 text-blue-600 hover:bg-blue-50">https://</button>
                            <button type="button" onclick="setAppUrlPrefix('http://')" class="px-2 py-0.5 rounded-md bg-white border border-gray-200 text-blue-600 hover:bg-blue-50">http://</button>
                            <button type="button" onclick="setAppUrlPrefix('/')" class="px-2 py-0.5 rounded-md bg-white border border-gray-200 text-emerald-600 hover:bg-emerald-50">/ (Halaman Dalam)</button>
                        </div>
                        <p class="text-[11px] text-gray-500 leading-tight">
                            Bila aplikasi ini aktif, pengunjung yang menekan kartu aplikasi akan diarahkan ke tautan ini.
                        </p>
                    </div>

                    <!-- Target Buka Tautan & Urutan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Target Buka Tautan</label>
                            <select id="appTarget" name="target" 
                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm bg-white transition-all">
                                <option value="_blank">Tab Baru (_blank) - Rekomendasi</option>
                                <option value="_self">Tab yang Sama (_self)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Urutan Tampil (No)</label>
                            <input type="number" id="appUrutan" name="urutan" min="0" value="1" 
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all">
                        </div>
                    </div>

                    <!-- Ikon & Pratinjau Visual -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700">
                                Ikon Aplikasi & Pratinjau
                            </label>
                            <span class="text-[11px] text-gray-400">Gunakan class RemixIcon (ri-*)</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <!-- Live Preview Box -->
                            <div id="livePreviewIconBox" class="app-icon-preview app-icon-preview-lg color-blue shadow-sm">
                                <i id="livePreviewIcon" class="ri-apps-line"></i>
                            </div>

                            <div class="flex-1">
                                <input type="text" id="appIkon" name="ikon" required value="ri-apps-line" placeholder="ri-computer-line"
                                       class="w-full px-3.5 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm font-mono transition-all">
                                <p class="text-[10px] text-gray-400 mt-1">Ketik nama class ikon atau pilih rekomendasi di bawah.</p>
                            </div>
                        </div>

                        <!-- Quick Icon Picker Chips -->
                        <div class="mt-2.5 p-2 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Pilihan Ikon Populer:</p>
                            <div class="flex flex-wrap gap-1.5" id="quickIconList">
                                <!-- Populated or static chips -->
                                <button type="button" onclick="selectPresetIcon('ri-computer-line')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-1">
                                    <i class="ri-computer-line"></i> <span>Praktikum</span>
                                </button>
                                <button type="button" onclick="selectPresetIcon('ri-team-line')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-1">
                                    <i class="ri-team-line"></i> <span>SDM/Tim</span>
                                </button>
                                <button type="button" onclick="selectPresetIcon('ri-user-add-line')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-1">
                                    <i class="ri-user-add-line"></i> <span>Pendaftaran</span>
                                </button>
                                <button type="button" onclick="selectPresetIcon('ri-bank-card-line')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-1">
                                    <i class="ri-bank-card-line"></i> <span>Pembayaran</span>
                                </button>
                                <button type="button" onclick="selectPresetIcon('ri-survey-line')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-1">
                                    <i class="ri-survey-line"></i> <span>Kuesioner</span>
                                </button>
                                <button type="button" onclick="selectPresetIcon('ri-file-chart-line')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-1">
                                    <i class="ri-file-chart-line"></i> <span>Nilai</span>
                                </button>
                                <button type="button" onclick="selectPresetIcon('ri-chat-voice-line')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-1">
                                    <i class="ri-chat-voice-line"></i> <span>AI/Chat</span>
                                </button>
                                <button type="button" onclick="selectPresetIcon('ri-microscope-line')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-1">
                                    <i class="ri-microscope-line"></i> <span>Riset/Lab</span>
                                </button>
                                <button type="button" onclick="selectPresetIcon('ri-tools-line')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-1">
                                    <i class="ri-tools-line"></i> <span>Alat/Inventaris</span>
                                </button>
                                <button type="button" onclick="selectPresetIcon('ri-calendar-line')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-1">
                                    <i class="ri-calendar-line"></i> <span>Jadwal</span>
                                </button>
                                <button type="button" onclick="selectPresetIcon('ri-database-2-line')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-1">
                                    <i class="ri-database-2-line"></i> <span>Database</span>
                                </button>
                                <button type="button" onclick="selectPresetIcon('ri-global-line')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-1">
                                    <i class="ri-global-line"></i> <span>Website</span>
                                </button>
                                <button type="button" onclick="selectPresetIcon('ri-shield-keyhole-line')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-1">
                                    <i class="ri-shield-keyhole-line"></i> <span>Keamanan</span>
                                </button>
                                <button type="button" onclick="selectPresetIcon('ri-apps-line')" class="px-2 py-1 bg-white border border-gray-200 rounded-lg text-xs hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-1">
                                    <i class="ri-apps-line"></i> <span>Umum</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Pilihan Warna Gradien Tema -->
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5">
                            Warna Tema Gradien Ikon
                        </label>
                        <input type="hidden" id="appWarnaInput" name="warna" value="color-blue">
                        
                        <div class="flex flex-wrap items-center gap-2.5 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <button type="button" onclick="selectColorTheme('color-blue')" data-color="color-blue" title="Biru" class="color-swatch-btn color-blue active">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" onclick="selectColorTheme('color-green')" data-color="color-green" title="Hijau" class="color-swatch-btn color-green">
                                <i class="fas fa-check hidden"></i>
                            </button>
                            <button type="button" onclick="selectColorTheme('color-purple')" data-color="color-purple" title="Ungu" class="color-swatch-btn color-purple">
                                <i class="fas fa-check hidden"></i>
                            </button>
                            <button type="button" onclick="selectColorTheme('color-orange')" data-color="color-orange" title="Oranye" class="color-swatch-btn color-orange">
                                <i class="fas fa-check hidden"></i>
                            </button>
                            <button type="button" onclick="selectColorTheme('color-red')" data-color="color-red" title="Merah" class="color-swatch-btn color-red">
                                <i class="fas fa-check hidden"></i>
                            </button>
                            <button type="button" onclick="selectColorTheme('color-cyan')" data-color="color-cyan" title="Cyan" class="color-swatch-btn color-cyan">
                                <i class="fas fa-check hidden"></i>
                            </button>
                            <button type="button" onclick="selectColorTheme('color-yellow')" data-color="color-yellow" title="Kuning" class="color-swatch-btn color-yellow">
                                <i class="fas fa-check hidden"></i>
                            </button>
                            <button type="button" onclick="selectColorTheme('color-indigo')" data-color="color-indigo" title="Indigo" class="color-swatch-btn color-indigo">
                                <i class="fas fa-check hidden"></i>
                            </button>
                            <button type="button" onclick="selectColorTheme('color-pink')" data-color="color-pink" title="Pink" class="color-swatch-btn color-pink">
                                <i class="fas fa-check hidden"></i>
                            </button>
                            <span id="labelActiveColor" class="text-xs text-gray-500 font-medium ml-1">Biru (color-blue)</span>
                        </div>
                    </div>

                    <!-- Status Aplikasi Toggle -->
                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between p-3 rounded-xl bg-gray-50">
                        <div>
                            <p class="text-xs sm:text-sm font-bold text-gray-800">Status Publikasi Aplikasi</p>
                            <p class="text-[11px] text-gray-500">Jika aktif, dapat diklik oleh publik. Jika nonaktif, aplikasi tidak dapat diklik.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="appIsActive" name="is_active" value="1" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-2 sticky bottom-0">
                    <button type="button" onclick="closeAppModal()" 
                            class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 text-xs sm:text-sm font-medium transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btnSimpanApp" 
                            class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-blue-600/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> <span>Simpan Aplikasi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Load RemixIcon for consistent icon previews -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

<!-- Admin Apps JavaScript Script -->
<script src="<?= PUBLIC_URL ?>/js/admin/apps.js"></script>
