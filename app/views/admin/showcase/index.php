<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-3">
                <i class="fas fa-sliders-h text-blue-600"></i> 
                Manajemen Slider Showcase Home
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Kelola gambar slider, deskripsi, urutan, dan rute navigasi beranda</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                    <i class="fas fa-search text-sm"></i>
                </span>
                <input type="text" id="searchInput" placeholder="Cari Badge atau Judul..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all">
            </div>

            <button onclick="openFormModal()" 
               class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-md shadow-blue-600/20 transition-all duration-200 flex items-center justify-center gap-2 font-semibold text-xs sm:text-sm transform hover:-translate-y-0.5">
                <i class="fas fa-plus"></i> Tambah Slide Showcase
            </button>
        </div>
    </div>

    <!-- Desktop Table View (hidden on mobile) -->
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-xs font-bold uppercase tracking-wider">
                        <th class="px-5 py-4 text-center w-12">No</th>
                        <th class="px-5 py-4 w-24">Gambar</th>
                        <th class="px-5 py-4">Badge & Judul</th>
                        <th class="px-5 py-4">Deskripsi</th>
                        <th class="px-5 py-4">Link Routing</th>
                        <th class="px-5 py-4 text-center w-16">Urutan</th>
                        <th class="px-5 py-4 text-center w-24">Status</th>
                        <th class="px-5 py-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBodyDesktop" class="divide-y divide-gray-100 text-gray-700 text-sm">
                    <!-- Data loaded via JS -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View (shown only on mobile/tablet) -->
    <div id="cardContainerMobile" class="grid grid-cols-1 gap-4 md:hidden">
        <!-- Mobile cards loaded via JS -->
    </div>
</div>

<!-- Modal Form -->
<div id="formModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

    <!-- Modal Dialog Positioner -->
    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center sm:items-center">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-lg border border-gray-100 my-auto">
            
            <!-- Sticky Header -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-5 py-4 border-b border-blue-100 flex justify-between items-center sticky top-0 z-10">
                <h3 id="formModalTitle" class="text-base sm:text-lg font-bold text-blue-900 flex items-center gap-2">
                    <i class="fas fa-sliders-h text-blue-600"></i>
                    <span>Tambah Slide Showcase</span>
                </h3>
                <button onclick="closeModal()" class="w-8 h-8 rounded-full bg-white/80 hover:bg-white text-gray-400 hover:text-gray-600 transition-colors flex items-center justify-center shadow-sm">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            
            <!-- Form Body with Scroll Limit -->
            <form id="showcaseForm" enctype="multipart/form-data">
                <div class="p-4 sm:p-6 space-y-4 max-h-[70vh] sm:max-h-[75vh] overflow-y-auto">
                    <input type="hidden" id="inputId" name="id">
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Badge Text <span class="text-red-500">*</span></label>
                        <input type="text" id="inputBadge" name="badge_text" required placeholder="Contoh: PENCAPAIAN UNGGULAN"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Judul Slide <span class="text-red-500">*</span></label>
                        <input type="text" id="inputJudul" name="judul" required placeholder="Contoh: <span class='text-blue'>Pencapaian</span> & Inovasi Riset"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all">
                        <p class="text-[11px] text-gray-400 mt-1">Gunakan &lt;span class="text-blue"&gt;kata&lt;/span&gt; untuk warna biru highlight.</p>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Deskripsi Singkat <span class="text-red-500">*</span></label>
                        <textarea id="inputDeskripsi" name="deskripsi" rows="3" required placeholder="Tuliskan penjelasan singkat mengenai slide ini..."
                                  class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">URL / Target Route</label>
                            <input type="text" id="inputLinkUrl" name="link_url" placeholder="Contoh: /riset atau /laboratorium"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Label Tombol</label>
                            <input type="text" id="inputLinkLabel" name="link_label" placeholder="Contoh: Lihat Riset"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Urutan Display</label>
                            <input type="number" id="inputUrutan" name="urutan" value="1" min="1"
                                   class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Status Visibilitas</label>
                            <select id="inputStatus" name="is_active" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm bg-white transition-all">
                                <option value="1">Aktif (Tampil)</option>
                                <option value="0">Draft / Sembunyikan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Gambar Slide Showcase</label>
                        <input type="file" id="inputGambar" name="gambar" accept="image/*"
                               class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                        <div id="imagePreview" class="mt-3 hidden">
                            <img id="previewImg" src="" alt="Preview" class="h-28 w-auto rounded-xl border border-gray-200 object-cover shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Sticky Footer -->
                <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-2xl sticky bottom-0 z-10">
                    <button type="button" onclick="closeModal()" class="px-4 py-2.5 border border-gray-300 rounded-xl text-gray-700 text-xs sm:text-sm font-medium hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-md shadow-blue-600/20 flex items-center gap-2 transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= PUBLIC_URL ?>/js/admin/showcase.js"></script>
