<style>
    .desc-container {
        max-height: 80px;
        overflow-y: auto;
        padding-right: 8px;
    }
    .desc-container::-webkit-scrollbar {
        width: 4px;
    }
    .desc-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .desc-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    .desc-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-gavel text-blue-600"></i> 
        Peraturan & Sanksi Lab
    </h1>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" id="searchInput" placeholder="Cari peraturan / sanksi..." 
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow text-sm">
        </div>

        <button onclick="openFormModal()" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
            <i class="fas fa-plus"></i> Tambah Data
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500 font-medium">Daftar Aturan Kerja & Sanksi</span>
            <select id="filterTipe" onchange="filterData()" class="text-xs border border-gray-300 rounded-lg px-3 py-1.5 outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="all">Semua Tipe</option>
                <option value="peraturan">Peraturan Saja</option>
                <option value="sanksi">Sanksi Saja</option>
            </select>
        </div>
        <span id="totalData" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Total: 0</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold w-48">Judul Aturan</th>
                    <th class="px-6 py-4 font-semibold text-center w-24">Tipe</th>
                    <th class="px-6 py-4 font-semibold">Deskripsi</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-gray-200 text-gray-700 text-sm">
                <!-- Data loaded via JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-xl border border-gray-100">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i> Detail Peraturan/Sanksi
                </h3>
                <button onclick="closeModal('detailModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div id="detailContent" class="space-y-4">
                    <!-- Loaded via JS -->
                </div>
                <div class="mt-6 pt-6 border-t border-gray-100 flex justify-end">
                    <button onclick="closeModal('detailModal')" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="formModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-xl border border-gray-100">
            
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 id="formModalTitle" class="text-xl font-bold text-gray-800 flex items-center gap-2"></h3>
                <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <form id="mainForm">
                    <input type="hidden" id="inputId" name="id">
                    <input type="hidden" id="oldTipe">

                    <div class="space-y-5">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe Data <span class="text-red-500">*</span></label>
                                <select id="inputTipe" name="tipe" required onchange="toggleTipeFields(this.value)"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none bg-white">
                                    <option value="peraturan">Peraturan Lab</option>
                                    <option value="sanksi">Sanksi Pelanggaran</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Format Tampilan <span class="text-red-500">*</span></label>
                            <select id="inputDisplayFormat" name="display_format" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none bg-white">
                                <option value="list">List / Poin-Poin (Dipisah Baris Baru)</option>
                                <option value="plain">Plain / Teks Biasa</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                💡 <strong>List:</strong> Akan menampilkan setiap baris sebagai poin. <strong>Plain:</strong> Akan menampilkan sebagai paragraf biasa.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Aturan <span class="text-red-500">*</span></label>
                            <input type="text" id="inputJudul" name="judul" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none"
                                   placeholder="Contoh: Terlambat Datang Sanksi">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi Detail <span class="text-red-500">*</span></label>
                            <textarea id="inputDeskripsi" name="deskripsi" rows="5" required
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none resize-none"
                                      placeholder="Tuliskan detail poin-poin di sini..."></textarea>
                        </div>
                    </div>

                    <div id="formMessage" class="hidden mt-4"></div>

                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                        <button type="button" onclick="closeModal('formModal')" 
                                class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 font-semibold transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit" id="btnSave" 
                                class="px-8 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold transition-all duration-200 flex items-center justify-center gap-2 shadow-lg shadow-blue-200">
                            <i class="fas fa-save"></i> <span>Simpan Data</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= PUBLIC_URL ?>/js/admin/peraturan_sanksi.js"></script>