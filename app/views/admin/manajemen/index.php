<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-users-cog text-blue-600"></i> 
        Manajemen Anggota Lab
    </h1>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" id="searchInput" placeholder="Cari nama anggota..." 
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow text-sm">
        </div>

        <button onclick="openFormModal()" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
            <i class="fas fa-user-plus"></i> Tambah Anggota
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <span class="text-sm text-gray-500 font-medium">Daftar Struktur Organisasi</span>
        <span id="totalData" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Total: 0</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold w-24 text-center">Foto</th>
                    <th class="px-6 py-4 font-semibold">Nama Lengkap</th>
                    <th class="px-6 py-4 font-semibold">Jabatan</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-gray-200 text-gray-700 text-sm">
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-circle-notch fa-spin text-blue-500 text-2xl"></i>
                            <span class="font-medium">Memuat data...</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-3 bg-gray-50 text-xs text-gray-500 italic text-center border-t border-gray-100">
        Klik pada baris untuk melihat detail anggota.
    </div>
</div>

<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-md border border-gray-100">
            
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center sticky top-0 z-10">
                <h3 class="text-xl font-bold text-blue-800 flex items-center gap-2">
                    <i class="fas fa-id-card"></i> Detail Anggota
                </h3>
                <button onclick="closeModal('detailModal')" class="text-blue-600 hover:text-blue-800 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-8 flex flex-col items-center">
                <div class="w-32 h-32 rounded-full border-4 border-blue-100 shadow-lg overflow-hidden mb-5 bg-gray-100">
                    <img id="detailFoto" src="" class="w-full h-full object-cover">
                </div>
                
                <h2 id="detailNama" class="text-2xl font-bold text-gray-800 text-center mb-1">Nama Lengkap</h2>
                <p id="detailEmail" class="text-sm text-blue-600 mb-1 font-medium"></p>
                <p id="detailNidn" class="text-sm text-gray-500 mb-4 font-medium italic"></p>
                <div class="bg-blue-50 text-blue-700 px-4 py-1.5 rounded-full text-sm font-bold border border-blue-100 mb-6">
                    <span id="detailJabatan">Jabatan</span>
                </div>

                <div id="detailTentangSection" class="w-full text-gray-600 text-sm mb-6 border-t border-gray-100 pt-4 px-2">
                    <p class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-500"></i> Tentang
                    </p>
                    <p id="detailTentang" class="italic leading-relaxed">Belum ada informasi tambahan.</p>
                </div>

                <div class="w-full border-t border-gray-100 pt-4 text-center">
                    <button onclick="closeModal('detailModal')" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors w-full">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="formModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-lg border border-gray-100">
            
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 z-10">
                <h3 id="formModalTitle" class="text-xl font-bold text-gray-800 flex items-center gap-2"></h3>
                <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <form id="manajemenForm" enctype="multipart/form-data">
                    <input type="hidden" id="inputId" name="idManajemen">

                    <div class="space-y-5">
                        <div class="flex flex-col items-center mb-4">
                            <div class="w-24 h-24 rounded-full border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden bg-gray-50 mb-2 relative group cursor-pointer" onclick="document.getElementById('inputFoto').click()">
                                <img id="previewFoto" src="" class="w-full h-full object-cover hidden">
                                <div id="placeholderFoto" class="text-center text-gray-400">
                                    <i class="fas fa-camera text-2xl mb-1"></i>
                                    <span class="text-xs block">Upload</span>
                                </div>
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fas fa-edit"></i>
                                </div>
                            </div>
                            <input type="file" id="inputFoto" name="foto" accept="image/*" class="hidden" onchange="previewImage(this)">
                            <span class="text-xs text-gray-500">Klik lingkaran untuk ganti foto</span>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="inputNama" name="nama" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                   placeholder="Contoh: Dr. Budi Santoso, M.T.">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" id="inputEmail" name="email" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                   placeholder="Contoh: budi@university.ac.id">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">NIDN</label>
                            <input type="text" id="inputNidn" name="nidn"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                   placeholder="Contoh: 0912345678">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jabatan <span class="text-red-500">*</span></label>
                            <input type="text" id="inputJabatan" name="jabatan" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                   placeholder="Contoh: Kepala Laboratorium">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tentang / Deskripsi Singkat</label>
                            <textarea id="inputTentang" name="tentang" rows="3"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors resize-none"
                                   placeholder="Contoh: Bertanggung jawab atas seluruh operasional laboratorium dan pengembangan kurikulum..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">Opsional - Untuk menampilkan deskripsi singkat di profil publik</p>
                        </div>
                    </div>

                    <div id="formMessage" class="hidden mt-4"></div>

                    <div class="flex justify-end gap-3 pt-6 mt-2 border-t border-gray-100">
                        <button type="button" onclick="closeModal('formModal')" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 font-medium transition-colors">Batal</button>
                        <button type="submit" id="btnSave" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors flex items-center gap-2 shadow-sm">
                            <i class="fas fa-save"></i> <span>Simpan Data</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= PUBLIC_URL ?>/js/admin/manajemen.js"></script>
