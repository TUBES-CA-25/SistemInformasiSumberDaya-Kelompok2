<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4 animate__animated animate__fadeIn">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-calendar-check text-blue-600"></i> 
        Manajemen Jadwal UPK
    </h1>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" id="searchInput" placeholder="Cari MK, Dosen, Kelas..." 
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow text-sm">
        </div>

        <button onclick="openUploadModal()" 
           class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
            <i class="fas fa-file-excel"></i> Upload Exel
        </button>

        <button onclick="openFormModal()" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
            <i class="fas fa-plus"></i> Tambah Jadwal
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500 font-medium">Daftar Jadwal UPK</span>
            <div id="bulkActions" class="hidden flex items-center gap-2 animate-fade-in">
                <span class="text-xs text-gray-400">|</span>
                <span id="selectedCount" class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded">0 terpilih</span>
                <button onclick="bulkDelete()" class="text-xs font-bold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-lg transition-colors flex items-center gap-1">
                    <i class="fas fa-trash-alt"></i> Hapus Terpilih
                </button>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span id="totalData" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Total: 0</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold text-center w-8">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </th>
                    <th class="px-6 py-4 font-semibold text-center w-10">No</th>
                    <th class="px-6 py-4 font-semibold w-48">Mata Kuliah & Dosen</th>
                    <th class="px-6 py-4 font-semibold text-center w-20">Prodi</th>
                    <th class="px-6 py-4 font-semibold text-center w-40">Waktu & Tanggal</th>
                    <th class="px-6 py-4 font-semibold text-center w-20">Kelas</th>
                    <th class="px-6 py-4 font-semibold text-center w-24">Ruangan</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-gray-200 text-gray-700 text-sm">
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
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
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-2xl border border-gray-100">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 z-10">
                <h3 id="formModalTitle" class="text-xl font-bold text-gray-800 flex items-center gap-2">Tambah Jadwal Baru</h3>
                <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600 transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6">
                <form id="jadwalForm" class="space-y-5">
                    <input type="hidden" id="inputId" name="id">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Program Studi</label>
                            <select id="inputProdi" name="prodi" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                <option value="TI">Teknik Informatika</option>
                                <option value="SI">Sistem Informasi</option>
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mata Kuliah <span class="text-red-500">*</span></label>
                            <select id="inputMK" name="mata_kuliah" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                <option value="">-- Pilih Mata Kuliah --</option>
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Dosen Pengampu</label>
                            <input type="text" id="inputDosen" name="dosen" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal</label>
                            <input type="date" id="inputTanggal" name="tanggal" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Waktu (Jam)</label>
                            <input type="text" id="inputJam" name="jam" placeholder="08.00 - 10.00" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Ruangan <span class="text-red-500">*</span></label>
                            <select id="inputRuangan" name="ruangan" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                <option value="">-- Pilih Ruangan --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kelas</label>
                            <input type="text" id="inputKelas" name="kelas" placeholder="A1" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Frekuensi</label>
                            <input type="text" id="inputFreq" name="frekuensi" placeholder="Contoh: TI_SD-1, TI_BD-2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-6">
                        <button type="button" onclick="closeModal('formModal')" class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-lg font-semibold hover:bg-gray-200 transition-all">Batal</button>
                        <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white rounded-lg font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="uploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-lg border border-gray-100">
            
            <div class="bg-emerald-50 px-6 py-4 border-b border-emerald-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-emerald-800 flex items-center gap-2">
                    <i class="fas fa-file-excel"></i> Upload Jadwal UPK Excel
                </h3>
                <button onclick="closeModal('uploadModal')" class="text-emerald-600 hover:text-emerald-800 transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            
            <div class="p-6">
                <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-lg flex items-start gap-3">
                    <div class="shrink-0 text-blue-500 mt-0.5"><i class="fas fa-info-circle text-lg"></i></div>
                    <div class="text-sm text-blue-800">
                        <p class="font-bold mb-1">Panduan Upload:</p>
                        <ul class="list-disc ml-4 space-y-1 text-blue-700/80 text-xs">
                            <li>Gunakan template resmi agar format sesuai.</li>
                            <li>Pastikan kolom data lengkap (Prodi, Tanggal, Jam, dst).</li>
                            <li>Format Excel: <strong>.xlsx</strong> atau <strong>.xls</strong>.</li>
                        </ul>
                    </div>
                </div>

                <form id="uploadForm">
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih File Excel (.xlsx / .xls)</label>
                        
                        <div class="flex items-center justify-center w-full">
                            <label for="fileExcel" class="flex flex-col items-center justify-center w-full h-40 border-2 border-emerald-300 border-dashed rounded-xl cursor-pointer bg-emerald-50/30 hover:bg-emerald-50 transition-colors group relative">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                    <div class="w-12 h-12 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-cloud-upload-alt text-2xl"></i>
                                    </div>
                                    <p class="mb-1 text-sm text-gray-600"><span class="font-semibold text-emerald-600">Klik upload</span> atau drag file</p>
                                    <p class="text-xs text-gray-400">Format: .xlsx, .xls (Max 5MB)</p>
                                </div>
                                <input id="fileExcel" name="excel_file" type="file" class="hidden" accept=".xlsx, .xls" required />
                            </label>
                        </div>
                        
                        <div id="fileInfo" class="hidden mt-3 p-3 bg-emerald-50 border border-emerald-100 rounded-lg flex items-center justify-between">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <i class="fas fa-file-excel text-emerald-600 text-lg"></i>
                                <span id="fileNameDisplay" class="text-sm font-medium text-gray-700 truncate">filename.xlsx</span>
                            </div>
                            <span id="fileSizeDisplay" class="text-xs text-gray-500 whitespace-nowrap">0 MB</span>
                        </div>
                    </div>

                    <div id="uploadProgress" class="hidden mb-4">
                        <div class="flex justify-between text-xs font-semibold text-gray-600 mb-1">
                            <span id="progressText">Mengunggah...</span>
                            <span id="progressPercent">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div id="progressBar" class="bg-emerald-500 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>

                    <div id="uploadMessage" class="hidden mb-4 p-3 rounded-lg text-sm"></div>

                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                        <button type="button" onclick="closeModal('uploadModal')" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 font-medium transition-colors">Batal</button>
                        <button type="submit" id="btnUpload" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium flex items-center gap-2 shadow-sm shadow-emerald-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-upload"></i> Upload & Proses
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full max-w-lg border border-gray-100">
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-blue-800 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i> Detail Jadwal UPK
                </h3>
                <button onclick="closeModal('detailModal')" class="text-blue-400 hover:text-blue-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                    <span class="text-gray-500 text-sm">Mata Kuliah</span>
                    <span id="detailMK" class="col-span-2 font-bold text-gray-800"></span>
                </div>
                <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                    <span class="text-gray-500 text-sm">Dosen</span>
                    <span id="detailDosen" class="col-span-2 font-medium text-gray-700"></span>
                </div>
                <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                    <span class="text-gray-500 text-sm">Program Studi</span>
                    <span id="detailProdi" class="col-span-2 font-bold text-blue-600"></span>
                </div>
                <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                    <span class="text-gray-500 text-sm">Waktu & Tanggal</span>
                    <div class="col-span-2">
                        <span id="detailTanggal" class="block font-medium text-gray-700"></span>
                        <span id="detailJam" class="text-sm text-blue-500 font-bold"></span>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                    <span class="text-gray-500 text-sm">Kelas & Ruangan</span>
                    <span class="col-span-2 font-medium text-gray-700">
                        <span id="detailKelas" class="font-bold"></span> - <span id="detailRuangan"></span>
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <span class="text-gray-500 text-sm">Frekuensi</span>
                    <span id="detailFreq" class="col-span-2 font-mono text-xs bg-gray-100 px-2 py-1 rounded w-fit"></span>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button onclick="closeModal('detailModal')" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 transition-all">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.API_URL = "<?= API_URL ?>";       // Mengoper nilai API_URL dari PHP ke JS
    window.PUBLIC_URL = "<?= PUBLIC_URL ?>"; // Mengoper nilai PUBLIC_URL dari PHP ke JS
</script>

<script src="<?= PUBLIC_URL ?>/js/admin/jadwalupk.js"></script>