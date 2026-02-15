<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-book-reader text-blue-600"></i> 
        Manajemen SOP Laboratorium
    </h1>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" id="searchInput" placeholder="Cari Judul atau Deskripsi SOP..." 
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
        </div>

        <button onclick="openFormModal()" 
           class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all flex items-center justify-center gap-2 font-medium">
            <i class="fas fa-plus"></i> Tambah SOP
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold w-72">Judul & Ikon</th>
                    <th class="px-6 py-4 font-semibold">File Dokumen</th>
                    <th class="px-6 py-4 font-semibold">Deskripsi</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-gray-200 text-gray-700 text-sm">
                </tbody>
        </table>
    </div>
</div>

<div id="formModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg border border-gray-100">
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center rounded-t-2xl">
                <h3 id="formModalTitle" class="text-lg font-bold text-blue-800"></h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <form id="sopForm">
                    <input type="hidden" id="inputId" name="id_sop">
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul SOP <span class="text-red-500">*</span></label>
                            <input type="text" id="inputJudul" name="judul" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                                   placeholder="Contoh: SOP Peminjaman Alat">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Upload File PDF <span class="text-red-500">*</span></label>
                            <input type="file" id="inputFile" name="file" accept=".pdf"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                            <div id="currentFile" class="mt-2 text-xs text-green-600 font-medium hidden">
                                <i class="fas fa-check-circle"></i> File tersimpan: <span id="currentFileName"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi Singkat</label>
                            <textarea id="inputDeskripsi" name="deskripsi" rows="3" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                        <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-semibold hover:bg-gray-200">Batal</button>
                        <button type="submit" id="btnSave" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan SOP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= PUBLIC_URL ?>/js/admin/sop.js"></script>