<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-book-open text-blue-600"></i> 
        Manajemen Mata Kuliah
    </h1>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" id="searchInput" placeholder="Cari Kode / Nama MK..." 
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow text-sm">
        </div>

        <button onclick="openFormModal()" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
            <i class="fas fa-plus"></i> Tambah MK
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <span class="text-sm text-gray-500 font-medium">Daftar Mata Kuliah</span>
        <span id="totalData" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Total: 0</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Kode MK</th>
                    <th class="px-6 py-4 font-semibold">Nama Mata Kuliah</th>
                    <th class="px-6 py-4 font-semibold text-center w-24">Semester</th>
                    <th class="px-6 py-4 font-semibold text-center w-24">SKS</th>
                    <th class="px-6 py-4 font-semibold text-center w-40">Aksi</th>
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
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-lg border border-gray-100">
            
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 z-10">
                <h3 id="formModalTitle" class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    </h3>
                <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <form id="matkulForm">
                    <input type="hidden" id="inputId" name="idMatakuliah">

                    <div class="space-y-6">
                        
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-barcode text-gray-400 mr-1"></i> Kode MK <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="inputKode" name="kodeMatakuliah" placeholder="IF-101" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors placeholder-gray-400 uppercase font-mono tracking-wide text-gray-800">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-weight-hanging text-gray-400 mr-1"></i> SKS <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" id="inputSks" name="sksKuliah" placeholder="3" min="1" max="6" required
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors placeholder-gray-400 text-gray-800">
                                    <span class="absolute right-3 top-2.5 text-gray-400 text-sm font-medium pointer-events-none">SKS</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-book text-gray-400 mr-1"></i> Nama Mata Kuliah <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="inputNama" name="namaMatakuliah" placeholder="Contoh: Algoritma & Pemrograman" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors placeholder-gray-400 text-gray-800">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-layer-group text-gray-400 mr-1"></i> Semester <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="inputSemester" name="semester" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white transition-colors appearance-none cursor-pointer text-gray-800">
                                    <option value="" disabled selected>-- Pilih Semester --</option>
                                    <option value="1">Semester 1 (Ganjil)</option>
                                    <option value="2">Semester 2 (Genap)</option>
                                    <option value="3">Semester 3 (Ganjil)</option>
                                    <option value="4">Semester 4 (Genap)</option>
                                    <option value="5">Semester 5 (Ganjil)</option>
                                    <option value="6">Semester 6 (Genap)</option>
                                    <option value="7">Semester 7 (Ganjil)</option>
                                    <option value="8">Semester 8 (Genap)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="formMessage" class="hidden mt-6"></div>

                    <div class="flex justify-end gap-3 pt-6 mt-2 border-t border-gray-100">
                        <button type="button" onclick="closeModal('formModal')" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 font-medium transition-colors border border-gray-200">
                            Batal
                        </button>
                        <button type="submit" id="btnSave" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors flex items-center gap-2 shadow-sm shadow-blue-200">
                            <i class="fas fa-save"></i> <span>Simpan Data</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    window.API_URL = "<?= API_URL ?>";
    window.PUBLIC_URL = "<?= PUBLIC_URL ?>";
</script>
<script src="<?= PUBLIC_URL ?>/js/admin/matakuliah.js"></script>