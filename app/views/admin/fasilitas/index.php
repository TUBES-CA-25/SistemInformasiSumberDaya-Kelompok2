<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-desktop text-blue-600"></i> 
        Manajemen Laboratorium
    </h1>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" id="searchInput" placeholder="Cari nama / deskripsi..." 
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow text-sm">
        </div>

        <button onclick="openFormModal()" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
            <i class="fas fa-plus"></i> Tambah Lab
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <span class="text-sm text-gray-500 font-medium">Daftar Laboratorium</span>
        <span id="totalData" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Total: 0</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold text-center w-12">No</th>
                    <th class="px-6 py-4 font-semibold text-center w-24">Gambar</th>
                    <th class="px-6 py-4 font-semibold w-48">Nama Laboratorium</th>
                    <th class="px-6 py-4 font-semibold text-center w-28">Jenis</th>
                    <th class="px-6 py-4 font-semibold text-center w-28">Kapasitas</th>
                    <th class="px-6 py-4 font-semibold text-center w-36">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-gray-200 text-gray-700 text-sm">
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-circle-notch fa-spin text-blue-500 text-2xl"></i>
                            <span class="font-medium">Memuat data...</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="formModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-3xl border border-gray-100">
            
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 z-10">
                <h3 id="formModalTitle" class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    </h3>
                <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 max-h-[80vh] overflow-y-auto custom-scrollbar">
                <form id="labForm" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" id="inputId" name="idLaboratorium">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Laboratorium <span class="text-red-500">*</span></label>
                        <input type="text" id="inputNama" name="nama" placeholder="Contoh: Lab Rekayasa Perangkat Lunak" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-colors placeholder-gray-400">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Ruangan <span class="text-red-500">*</span></label>
                            <select id="inputJenis" name="jenis" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Laboratorium">Ruangan Laboratorium</option>
                                <option value="Riset">Ruangan Riset</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Koordinator Lab</label>
                            <select id="inputKoordinator" name="idKordinatorAsisten" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                <option value="">-- Pilih Asisten --</option>
                                </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Umum</label>
                        <textarea id="inputDeskripsi" name="deskripsi" rows="3" placeholder="Deskripsi singkat fungsi laboratorium..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-colors placeholder-gray-400"></textarea>
                    </div>

                    <div class="bg-blue-50 p-5 rounded-xl border border-blue-100">
                        <h4 class="text-sm font-bold text-blue-800 mb-4 flex items-center gap-2 border-b border-blue-200 pb-2">
                            <i class="fas fa-microchip"></i> Spesifikasi Hardware (PC Utama)
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Processor</label>
                                <input type="text" id="inputProcessor" name="processor" placeholder="ex: Intel Core i7 Gen 12"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">RAM</label>
                                <input type="text" id="inputRam" name="ram" placeholder="ex: 32 GB DDR4"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Storage</label>
                                <input type="text" id="inputStorage" name="storage" placeholder="ex: SSD NVMe 1 TB"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">GPU (VGA)</label>
                                <input type="text" id="inputGpu" name="gpu" placeholder="ex: NVIDIA RTX 3060"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Monitor</label>
                                <input type="text" id="inputMonitor" name="monitor" placeholder="ex: 24 Inch IPS 144Hz"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah Unit PC</label>
                                <input type="number" id="inputJumlahPc" name="jumlahPc" placeholder="0" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Kapasitas Mahasiswa </label>
                                <input type="number" id="inputKapasitas" name="kapasitas" placeholder="0" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="bg-emerald-50 p-5 rounded-xl border border-emerald-100">
                        <h4 class="text-sm font-bold text-emerald-800 mb-4 flex items-center gap-2 border-b border-emerald-200 pb-2">
                            <i class="fas fa-layer-group"></i> Software & Fasilitas
                        </h4>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Software Terinstall</label>
                            <textarea id="inputSoftware" name="software" rows="2" placeholder="Visual Studio Code, XAMPP, Android Studio..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-emerald-500 outline-none text-sm"></textarea>
                            <p class="text-[10px] text-gray-400 mt-1">Pisahkan dengan koma.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Fasilitas Pendukung Lainnya</label>
                            <textarea id="inputFasilitas" name="fasilitas" rows="2" placeholder="AC Central, Proyektor HD, Papan Tulis Kaca..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-emerald-500 outline-none text-sm"></textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Laboratorium (Bisa Multiple)</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="inputGambar" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                    <p class="mb-1 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500">JPG, PNG (Max 2MB per file)</p>
                                </div>
                                <input type="file" id="inputGambar" name="gambar[]" accept="image/*" multiple class="hidden" />
                            </div>
                        </label>
                        
                        <div id="previewContainer" class="mt-3 grid grid-cols-3 sm:grid-cols-4 gap-3">
                            <div id="savedImagesContainer" class="contents"></div>
                            <div id="newImagesContainer" class="contents"></div>
                        </div>
                        <div id="gambarPreviewInfo" class="hidden mt-2 text-xs text-blue-600 bg-blue-50 p-2 rounded border border-blue-100 flex items-center gap-2">
                            <i class="fas fa-info-circle"></i> Gambar lama tersimpan. Upload baru untuk menambah/mengganti.
                        </div>
                    </div>

                    <div id="formMessage" class="hidden"></div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" onclick="closeModal('formModal')" class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition-colors">Batal</button>
                        <button type="submit" id="btnSave" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors flex items-center gap-2 shadow-sm">
                            <i class="fas fa-save"></i> <span>Simpan Data</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal('detailModal')"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl w-full sm:max-w-3xl border border-gray-100">
            
            <button onclick="closeModal('detailModal')" class="absolute top-4 right-4 z-30 bg-white/20 hover:bg-white/40 text-white rounded-full p-2 transition-colors shadow-lg">
                <i class="fas fa-times text-xl drop-shadow-md"></i>
            </button>

            <div class="bg-white">
                <div class="w-full h-64 bg-gray-900 relative group overflow-hidden">
                    
                    <img id="dSliderImage" src="" class="w-full h-full object-cover transition-opacity duration-300">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end pointer-events-none z-10">
                        <div class="p-6 text-white w-full">
                            <h2 id="dNama" class="text-2xl font-bold shadow-sm">Nama Lab</h2>
                            <div class="flex gap-2 mt-2">
                                <span id="dJenis" class="px-2 py-0.5 bg-white/20 backdrop-blur-md rounded text-xs font-semibold border border-white/30">Jenis</span>
                                <span class="px-2 py-0.5 bg-blue-500/80 backdrop-blur-md rounded text-xs font-semibold border border-blue-400/50 flex items-center gap-1">
                                    <i class="fas fa-desktop text-[10px]"></i> <span id="dKapasitasBadge">0</span> PC
                                </span>
                            </div>
                        </div>
                    </div>

                    <button id="btnPrevSlide" onclick="changeSlide(-1)" type="button" class="hidden absolute top-1/2 left-3 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white w-10 h-10 rounded-full items-center justify-center transition-all backdrop-blur-sm z-20 border border-white/20 shadow-lg cursor-pointer">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <button id="btnNextSlide" onclick="changeSlide(1)" type="button" class="hidden absolute top-1/2 right-3 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white w-10 h-10 rounded-full items-center justify-center transition-all backdrop-blur-sm z-20 border border-white/20 shadow-lg cursor-pointer">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                    <div id="sliderDots" class="absolute bottom-4 right-6 flex gap-1.5 z-20"></div>
                </div>

                <div class="p-6">
                    <div class="mb-5 flex items-center p-3 bg-yellow-50 rounded-lg border border-yellow-100 text-sm text-yellow-800">
                        <i class="fas fa-user-tie text-yellow-600 mr-2 text-lg"></i> 
                        <span class="font-bold mr-1">Koordinator Lab:</span> 
                        <span id="dKoordinator">-</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <h4 class="text-blue-800 font-bold text-xs uppercase mb-3 flex items-center gap-2">
                                <i class="fas fa-microchip"></i> Spesifikasi Utama
                            </h4>
                            <ul class="text-sm space-y-2 text-gray-700">
                                <li class="flex justify-between border-b border-blue-200/50 pb-1"><span>CPU:</span> <span id="dProcessor" class="font-medium">-</span></li>
                                <li class="flex justify-between border-b border-blue-200/50 pb-1"><span>RAM:</span> <span id="dRam" class="font-medium">-</span></li>
                                <li class="flex justify-between border-b border-blue-200/50 pb-1"><span>GPU:</span> <span id="dGpu" class="font-medium">-</span></li>
                                <li class="flex justify-between"><span>Storage:</span> <span id="dStorage" class="font-medium">-</span></li>
                            </ul>
                        </div>
                        
                        <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100">
                            <h4 class="text-emerald-800 font-bold text-xs uppercase mb-3 flex items-center gap-2">
                                <i class="fas fa-code"></i> Software & Tools
                            </h4>
                            <p id="dSoftware" class="text-sm text-gray-700 leading-relaxed">-</p>
                            <h4 class="text-emerald-800 font-bold text-xs uppercase mt-3 mb-2 flex items-center gap-2">
                                <i class="fas fa-couch"></i> Fasilitas
                            </h4>
                            <p id="dFasilitas" class="text-sm text-gray-700 leading-relaxed">-</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <h3 class="text-gray-500 font-bold mb-2 text-xs uppercase">Deskripsi Umum</h3>
                        <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line" id="dDeskripsi">-</div>
                    </div>

                    <div class="flex justify-end pt-4 mt-4 border-t border-gray-100">
                        <button id="btnEditDetail" class="bg-amber-100 text-amber-700 hover:bg-amber-200 px-5 py-2.5 rounded-lg font-medium transition-colors flex items-center gap-2 text-sm shadow-sm">
                            <i class="fas fa-pen"></i> Edit Data Ini
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.API_URL = "<?= API_URL ?>";
    window.PUBLIC_URL = "<?= PUBLIC_URL ?>";
</script>
<script src="<?= PUBLIC_URL ?>/js/admin/fasilitas.js"></script>