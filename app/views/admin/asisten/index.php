    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-users text-blue-600"></i> Data Asisten
        </h1>
        <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-0 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="searchInput" placeholder="Cari asisten..." 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-shadow text-sm">
            </div>

            <button onclick="openKoordinatorModal()" 
            class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
                <i class="fas fa-crown"></i> Pilih Koordinator
            </button>
            
            <button onclick="openFormModal()" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5">
                <i class="fas fa-plus"></i> Tambah Asisten
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <span class="text-sm text-gray-500 font-medium">Daftar Asisten Laboratorium</span>
            <span id="totalData" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Total: 0</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                        <th class="px-6 py-4 font-semibold text-center w-24">Foto</th>
                        <th class="px-6 py-4 font-semibold">Nama Lengkap</th>
                        <th class="px-6 py-4 font-semibold">Jurusan</th>
                        <th class="px-6 py-4 font-semibold text-center w-32">Status</th>
                        <th class="px-6 py-4 font-semibold text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-gray-200 text-gray-700 text-sm">
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-circle-notch fa-spin"></i> Memuat data...</td></tr>
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
                        <i class="fas fa-user-edit text-blue-600"></i> Form Asisten
                    </h3>
                    <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600 transition-colors"><i class="fas fa-times text-xl"></i></button>
                </div>
                <div class="p-6 max-h-[80vh] overflow-y-auto custom-scrollbar">
                    <form id="asistenForm" enctype="multipart/form-data" class="space-y-6">
                        <input type="hidden" id="inputIdAsisten" name="idAsisten">
                        
                        <!-- Section IDENTITAS -->
                        <div class="bg-blue-50/30 p-4 rounded-xl border border-blue-100/50 space-y-4">
                            <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-id-card"></i> Identitas Utama
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" id="inputNama" name="nama" placeholder="Contoh: Ahmad Fulan" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email UMI <span class="text-red-500">*</span></label>
                                    <input type="email" id="inputEmail" name="email" placeholder="email@umi.ac.id" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jurusan</label>
                                    <select id="inputJurusan" name="jurusan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                        <option value="Teknik Informatika">Teknik Informatika</option>
                                        <option value="Sistem Informasi">Sistem Informasi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status Asisten</label>
                                    <select id="inputStatus" name="statusAktif" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                        <option value="Asisten">Asisten Aktif</option>
                                        <option value="CA">Calon Asisten</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Section PROFIL -->
                        <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 space-y-4">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-briefcase"></i> Profil Publik
                            </h4>
                            <div class="relative">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kompetensi & Keahlian</label>
                                <div id="skillsTagContainer" class="flex flex-wrap gap-2 p-2.5 min-h-[45px] border border-gray-300 rounded-lg bg-white focus-within:ring-2 focus-within:ring-blue-500 transition-all cursor-text">
                                    <input type="text" id="tagInput" class="flex-grow outline-none text-sm min-w-[150px] bg-transparent" placeholder="Ketik keahlian (contoh: Web Dev)...">
                                </div>
                                <!-- Dropdown Saran -->
                                <div id="skillsSuggestions" class="hidden absolute z-[100] mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-2xl max-h-60 overflow-y-auto py-2">
                                    <!-- List saran diisi JS -->
                                </div>
                                <input type="hidden" id="inputSkills" name="skills">
                                <p class="text-[10px] text-gray-400 mt-1.5 italic"><i class="fas fa-info-circle"></i> Tekan Enter atau pilih saran untuk menambahkan keahlian baru.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bio Singkat</label>
                                <textarea id="inputBio" name="bio" rows="3" placeholder="Deskripsi singkat diri..." 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white"></textarea>
                            </div>
                            <div class="p-4 bg-white border border-dashed border-gray-300 rounded-xl transition-all hover:border-blue-300">
                                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-camera text-gray-400"></i> Foto Profil
                                </label>
                                <input type="file" id="inputFoto" name="foto" accept="image/*" 
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                <div id="fotoPreviewInfo" class="hidden mt-2 text-xs text-blue-600 bg-blue-50 p-2 rounded-lg items-center gap-2">
                                    <i class="fas fa-check-circle"></i> Foto sudah tersedia
                                </div>
                            </div>
                        </div>

                        <div id="formMessage" class="hidden"></div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                            <button type="button" onclick="closeModal('formModal')" 
                                    class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-semibold hover:bg-gray-200 transition-all active:scale-95">
                                Batal
                            </button>
                            <button type="submit" id="btnSave" 
                                    class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-semibold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all transform active:scale-95 flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Data
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
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl w-full sm:max-w-2xl border border-gray-100">
                <button onclick="closeModal('detailModal')" class="absolute top-4 right-4 z-20 bg-white/20 hover:bg-white/40 text-white rounded-full p-2 transition-colors"><i class="fas fa-times text-xl drop-shadow-md"></i></button>
                <div class="bg-white">
                    <div class="h-24 bg-gradient-to-r from-emerald-600 to-teal-600 relative"></div>
                    <div class="px-6 pb-6">
                        <div class="relative flex flex-col sm:flex-row items-center sm:items-end -mt-10 mb-6 gap-4">
                            <img id="mFoto" src="" class="w-24 h-24 rounded-full border-4 border-white shadow-md object-cover bg-gray-200">
                            <div class="text-center sm:text-left flex-1 pb-1">
                                <h2 id="mNama" class="text-2xl font-bold text-gray-900">Nama</h2>
                                <div id="mBadges" class="flex flex-wrap justify-center sm:justify-start gap-2 mt-1"></div>
                            </div>
                        </div>
                        <div class="space-y-4 text-sm">
                            <div class="bg-gray-50 p-3 rounded-lg flex justify-between">
                                <div><p class="text-xs text-gray-500">Jurusan</p><p id="mJurusan" class="font-bold">-</p></div>
                                <div class="text-right"><p class="text-xs text-gray-500">Email</p><p id="mEmail" class="font-bold">-</p></div>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg"><p class="text-xs text-gray-500 mb-1">Kompetensi & Keahlian</p><div id="mSkills" class="flex flex-wrap gap-2 mt-1"></div></div>
                            <div class="bg-gray-50 p-3 rounded-lg"><p class="text-xs text-gray-500 mb-1">Bio</p><p id="mBio" class="italic text-gray-700">-</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="koordinatorModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal('koordinatorModal')"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl w-full sm:max-w-2xl border border-gray-100">
                <div class="bg-emerald-50 px-6 py-4 border-b border-emerald-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-emerald-800 flex items-center gap-2">
                        <i class="fas fa-crown text-yellow-500"></i> Pilih Koordinator Lab
                    </h3>
                    <button onclick="closeModal('koordinatorModal')" class="text-emerald-400 hover:text-emerald-600 transition-colors"><i class="fas fa-times text-xl"></i></button>
                </div>

                <div class="p-6">
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-100 rounded-xl flex items-center gap-4">
                        <div class="shrink-0 bg-yellow-200 text-yellow-700 w-12 h-12 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-check text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-yellow-800 font-bold uppercase tracking-wide">Koordinator Saat Ini</p>
                            <p id="currentCoordName" class="text-lg font-bold text-gray-800">Belum ditentukan</p>
                        </div>
                    </div>

                    <h4 class="text-sm font-bold text-gray-500 uppercase mb-3">Pilih Koordinator Baru</h4>
                    
                    <form id="koordinatorForm">
                        <div id="coordList" class="max-h-60 overflow-y-auto custom-scrollbar space-y-2 border border-gray-100 rounded-lg p-2">
                            <div class="text-center py-4 text-gray-400">Memuat data...</div>
                        </div>

                        <div id="coordMessage" class="hidden mt-4"></div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 mt-4">
                            <button type="button" onclick="closeModal('koordinatorModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">Batal</button>
                            <button type="submit" id="btnSaveCoord" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium flex items-center gap-2 shadow-sm">
                                <i class="fas fa-save"></i> Simpan Pilihan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    // Gunakan window agar menjadi property global yang bisa diakses asisten.js
    window.BASE_URL = "<?= PUBLIC_URL ?>";
    window.API_URL = "<?= PUBLIC_URL ?>";
</script>

<script src="<?= PUBLIC_URL ?>/js/admin/asisten.js?v=<?= time() ?>"></script>