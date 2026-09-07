<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-3">
                <i class="fas fa-address-book text-blue-600"></i> 
                Manajemen Informasi Kontak & Kotak Masuk
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Kelola informasi saluran kontak lab serta pesan masuk dari formulir pengunjung</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-blue-50/70 border border-blue-100 text-xs text-blue-800">
                <i class="fas fa-paper-plane text-blue-600"></i>
                <span class="font-medium">Penerima Pesan:</span>
                <span id="activeRecipientEmail" class="font-bold text-blue-700 font-mono"><?= htmlspecialchars($recipient_email ?? 'Memuat...') ?></span>
                <span id="activeRecipientSource" class="text-[10px] text-gray-500 font-normal">
                    (<?= ($email_source ?? 'kontak') === 'kontak' ? 'Otomatis dari Kontak' : 'Dari .env' ?>)
                </span>
            </div>

            <button id="btnTambahKontak" onclick="openKontakModal()" 
               class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-md shadow-blue-600/20 transition-all duration-200 flex items-center justify-center gap-2 font-semibold text-xs sm:text-sm transform hover:-translate-y-0.5">
                <i class="fas fa-plus"></i> Tambah Saluran Kontak
            </button>
        </div>
    </div>

    <!-- Navigation Tabs & Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fas fa-phone-alt"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Saluran Kontak</p>
                <h3 id="statTotalKontak" class="text-xl font-bold text-gray-800">0</h3>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Pesan Belum Dibaca</p>
                <h3 id="statPesanUnread" class="text-xl font-bold text-amber-600">0</h3>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fas fa-inbox"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Pesan Masuk</p>
                <h3 id="statTotalPesan" class="text-xl font-bold text-gray-800">0</h3>
            </div>
        </div>
    </div>

    <!-- Tab Bar -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-1.5 flex flex-wrap gap-2">
        <button id="tabBtnSaluran" onclick="switchTab('saluran')" 
                class="flex-1 py-2.5 px-4 rounded-xl font-semibold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all bg-blue-600 text-white shadow-sm">
            <i class="fas fa-satellite-dish"></i> Saluran Kontak Lab
            <span id="badgeCountSaluran" class="px-2 py-0.5 rounded-full text-[10px] bg-white/20 text-white font-bold">0</span>
        </button>
        
        <button id="tabBtnInbox" onclick="switchTab('inbox')" 
                class="flex-1 py-2.5 px-4 rounded-xl font-semibold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all text-gray-600 hover:text-gray-900 hover:bg-gray-50">
            <i class="fas fa-envelope"></i> Kotak Masuk Pesan
            <span id="badgeCountInbox" class="px-2 py-0.5 rounded-full text-[10px] bg-amber-500 text-white font-bold hidden">0</span>
        </button>
    </div>

    <!-- SECTION 1: SALURAN KONTAK TAB -->
    <div id="sectionSaluran" class="space-y-4">
        <!-- Search Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <div class="relative flex-1 sm:max-w-xs">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <input type="text" id="searchKontakInput" placeholder="Cari nama atau nilai informasi..." 
                       class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all">
            </div>
            
            <div class="text-xs text-gray-500 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-500"></i>
                <span>Informasi yang disimpan otomatis tampil di halaman Kontak & Footer website.</span>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-xs font-bold uppercase tracking-wider">
                            <th class="px-5 py-4 text-center w-12">No</th>
                            <th class="px-5 py-4 w-60">Saluran / Media</th>
                            <th class="px-5 py-4">Nilai / Isi Informasi</th>
                            <th class="px-5 py-4 w-60">Tautan (Link)</th>
                            <th class="px-5 py-4 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="kontakTableBody" class="divide-y divide-gray-100 text-gray-700 text-sm">
                        <!-- Data loaded via JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div id="kontakCardContainer" class="grid grid-cols-1 gap-4 md:hidden">
            <!-- Mobile cards loaded via JS -->
        </div>
    </div>

    <!-- SECTION 2: KOTAK MASUK PESAN TAB -->
    <div id="sectionInbox" class="space-y-4 hidden">
        <!-- Search & Filter Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <div class="relative flex-1 sm:max-w-sm">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <input type="text" id="searchInboxInput" placeholder="Cari nama, email, subjek pesan..." 
                       class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all">
            </div>

            <div class="flex items-center gap-2">
                <button onclick="filterInboxStatus('semua')" id="btnFilterSemua" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-200">
                    Semua
                </button>
                <button onclick="filterInboxStatus('belum_dibaca')" id="btnFilterUnread" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100">
                    Belum Dibaca
                </button>
                <button onclick="filterInboxStatus('dibaca')" id="btnFilterRead" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100">
                    Sudah Dibaca
                </button>
            </div>
        </div>

        <!-- Inbox Table (Desktop) -->
        <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-xs font-bold uppercase tracking-wider">
                            <th class="px-4 py-4 text-center w-10"></th>
                            <th class="px-5 py-4 w-48">Pengirim</th>
                            <th class="px-5 py-4">Subjek & Pesan</th>
                            <th class="px-5 py-4 w-40 text-center">Waktu Diterima</th>
                            <th class="px-5 py-4 text-center w-28">Status</th>
                            <th class="px-5 py-4 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="inboxTableBody" class="divide-y divide-gray-100 text-gray-700 text-sm">
                        <!-- Messages loaded via JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Inbox Cards (Mobile) -->
        <div id="inboxCardContainer" class="grid grid-cols-1 gap-3 md:hidden">
            <!-- Messages mobile cards loaded via JS -->
        </div>
    </div>
</div>

<!-- MODAL 1: TAMBAH / EDIT SALURAN KONTAK (SIMPLE) -->
<div id="kontakModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeKontakModal()"></div>

    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center sm:items-center">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-lg border border-gray-100 my-auto">
            
            <!-- Sticky Header -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-5 py-4 border-b border-blue-100 flex justify-between items-center sticky top-0 z-10">
                <h3 id="kontakModalTitle" class="text-base sm:text-lg font-bold text-blue-900 flex items-center gap-2">
                    <i class="fas fa-address-card text-blue-600"></i>
                    <span>Tambah Saluran Kontak</span>
                </h3>
                <button onclick="closeKontakModal()" class="w-8 h-8 rounded-full bg-white/80 hover:bg-white text-gray-400 hover:text-gray-600 transition-colors flex items-center justify-center shadow-sm">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            
            <!-- Form Body -->
            <form id="kontakForm">
                <div class="p-4 sm:p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                    <input type="hidden" id="kontakId" name="id">
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Nama Saluran <span class="text-red-500">*</span></label>
                        <input type="text" id="kontakNama" name="nama" required placeholder="Contoh: Email Resmi, WhatsApp Support, Lokasi Lab..."
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all">
                    </div>

                    <!-- Nilai / Isi Informasi -->
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Nilai / Isi Informasi <span class="text-red-500">*</span></label>
                        <textarea id="kontakNilai" name="nilai" rows="3" required placeholder="Contoh: fikom.iclabs@umi.ac.id, +62 411 455666, atau link Google Maps..."
                                  class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all"></textarea>
                        <p class="text-[11px] text-gray-400 mt-1">Dapat berupa alamat email, nomor telepon/WhatsApp, alamat lab, maupun URL Google Maps.</p>
                    </div>

                    <!-- Tautan URL -->
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Tautan Navigasi (Link Opsional)</label>
                        <input type="text" id="kontakTautan" name="tautan" placeholder="Contoh: mailto:fikom.iclabs@umi.ac.id atau https://wa.me/..."
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all">
                        <p class="text-[11px] text-gray-400 mt-1">Bila dikosongkan, sistem akan otomatis mengatur tautan email/WhatsApp.</p>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="bg-gray-50 px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-2 sticky bottom-0">
                    <button type="button" onclick="closeKontakModal()" 
                            class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 text-xs sm:text-sm font-medium transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btnSimpanKontak" 
                            class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-blue-600/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> <span>Simpan Saluran</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 2: DETAIL BACA PESAN KOTAK MASUK -->
<div id="pesanModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closePesanModal()"></div>

    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center sm:items-center">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-lg border border-gray-100 my-auto">
            
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-5 py-4 border-b border-blue-100 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-blue-900">Detail Pesan Pengunjung</h3>
                        <p id="pesanWaktuHeader" class="text-[11px] text-gray-500">-</p>
                    </div>
                </div>
                <button onclick="closePesanModal()" class="w-8 h-8 rounded-full bg-white/80 hover:bg-white text-gray-400 hover:text-gray-600 transition-colors flex items-center justify-center shadow-sm">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div class="p-5 sm:p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                <!-- Info Pengirim Card -->
                <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 space-y-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold">Nama Pengirim</p>
                            <h4 id="pesanNama" class="text-sm font-bold text-gray-800">-</h4>
                        </div>
                        <span id="pesanStatusBadge" class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600">Dibaca</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Alamat Email</p>
                        <a id="pesanEmailLink" href="#" class="text-sm font-medium text-blue-600 hover:underline flex items-center gap-1.5">
                            <i class="fas fa-envelope text-xs"></i> <span id="pesanEmail">-</span>
                        </a>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Subjek</p>
                        <p id="pesanSubjek" class="text-sm font-semibold text-gray-800">-</p>
                    </div>
                </div>

                <!-- Isi Pesan Card -->
                <div>
                    <p class="text-xs text-gray-400 uppercase font-semibold mb-1.5">Isi Pesan:</p>
                    <div id="pesanIsi" class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 text-gray-700 text-sm leading-relaxed whitespace-pre-line font-sans">
                        -
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-2">
                <button type="button" id="btnHapusPesanDetail" onclick="" 
                        class="w-full sm:w-auto px-4 py-2 rounded-xl text-red-600 hover:bg-red-50 text-xs font-semibold transition-colors flex items-center justify-center gap-1.5">
                    <i class="fas fa-trash-alt"></i> Hapus Pesan
                </button>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="button" onclick="closePesanModal()" 
                            class="flex-1 sm:flex-initial px-4 py-2 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 text-xs font-medium transition-colors">
                        Tutup
                    </button>
                    <a id="btnBalasEmail" href="#" target="_blank"
                       class="flex-1 sm:flex-initial px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition-all flex items-center justify-center gap-1.5">
                        <i class="fas fa-reply"></i> Balas Email
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Load RemixIcon for contact icons -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

<!-- Admin Kontak JS Script -->
<script src="<?= PUBLIC_URL ?>/js/admin/kontak.js"></script>
