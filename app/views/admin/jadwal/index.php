<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-calendar-alt text-blue-600"></i> 
        Manajemen Jadwal Praktikum
    </h1>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" id="searchInput" placeholder="Cari MK, Lab, Hari..." 
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow text-sm">
        </div>

        <button onclick="openUploadModal()" 
           class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
            <i class="fas fa-file-excel"></i> Upload Excel
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
            <span class="text-sm text-gray-500 font-medium">Daftar Jadwal Praktikum</span>
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
                    <th class="px-6 py-4 font-semibold text-center w-12">No</th>
                    <th class="px-6 py-4 font-semibold">Mata Kuliah</th>
                    <th class="px-6 py-4 font-semibold">Laboratorium</th>
                    <th class="px-6 py-4 font-semibold min-w-[160px]">Hari & Waktu</th>
                    <th class="px-6 py-4 font-semibold text-center w-24">Kelas</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Status</th>
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
                <h3 id="formModalTitle" class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    </h3>
                <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 max-h-[80vh] overflow-y-auto custom-scrollbar">
                <form id="jadwalForm" class="space-y-5">
                    <input type="hidden" id="inputId" name="idJadwal">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mata Kuliah <span class="text-red-500">*</span></label>
                            <select id="inputMatakuliah" name="idMatakuliah" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="">-- Pilih MK --</option>
                                </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Laboratorium <span class="text-red-500">*</span></label>
                            <select id="inputLab" name="idLaboratorium" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="">-- Pilih Lab --</option>
                                </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Hari <span class="text-red-500">*</span></label>
                            <select id="inputHari" name="hari" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kelas <span class="text-red-500">*</span></label>
                            <input type="text" id="inputKelas" name="kelas" placeholder="A1 / B2 / C..." required 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none uppercase font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Mulai <span class="text-red-500">*</span></label>
                            <input type="time" id="inputMulai" name="waktuMulai" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Selesai <span class="text-red-500">*</span></label>
                            <input type="time" id="inputSelesai" name="waktuSelesai" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Frekuensi</label>
                            <input type="text" id="inputFrekuensi" name="frekuensi" placeholder="Contoh: TI_PW-1" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Dosen Pengampu</label>
                            <input type="text" id="inputDosen" name="dosen" placeholder="Nama Dosen" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Asisten 1</label>
                            <select id="inputAsisten1" name="idAsisten1" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="">-- Pilih Asisten 1 --</option>
                                </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Asisten 2</label>
                            <select id="inputAsisten2" name="idAsisten2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="">-- Pilih Asisten 2 --</option>
                                </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status Jadwal</label>
                        <div class="flex gap-4 mt-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="Aktif" checked class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="Nonaktif" class="w-4 h-4 text-red-600 focus:ring-red-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Nonaktif</span>
                            </label>
                        </div>
                    </div>

                    <div id="formMessage" class="hidden mt-4"></div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 mt-2">
                        <button type="button" onclick="closeModal('formModal')" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 font-medium transition-colors border border-gray-200">Batal</button>
                        <button type="submit" id="btnSave" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors flex items-center gap-2 shadow-sm shadow-blue-200">
                            <i class="fas fa-save"></i> <span>Simpan Jadwal</span>
                        </button>
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
                    <i class="fas fa-file-excel"></i> Upload Jadwal Excel
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
                            <li>Pastikan nama Mata Kuliah & Lab sama persis dengan di sistem.</li>
                            <li>Format waktu: <strong>HH:MM</strong> (Contoh: 08:00).</li>
                        </ul>
                        <a href="<?= API_URL ?>/jadwal-praktikum/template" class="inline-flex items-center gap-1 mt-3 text-blue-600 font-bold hover:text-blue-800 hover:underline">
                            <i class="fas fa-download"></i> Download Template Excel
                        </a>
                    </div>
                </div>

                <form id="uploadForm" enctype="multipart/form-data">
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

<script src="<?= PUBLIC_URL ?>/js/admin/jadwal.js"></script>
