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
           class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
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
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold text-center w-28">Gambar</th>
                    <th class="px-6 py-4 font-semibold">Nama Laboratorium</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Jenis</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Kapasitas</th>
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
                            </label>
                        </div>
                        
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
window.BASE_URL = '<?= BASE_URL ?>';
let allLabData = [];
let pendingKordinator = null;
let searchTimeout; // Untuk debounce search

// Variabel Global untuk Slider
let currentLabImages = [];
let currentSlideIndex = 0;

document.addEventListener('DOMContentLoaded', function() {
    loadLaboratorium();
    loadAsistenOptions(); // Load data asisten untuk dropdown

    // ✅ OPTIMASI: Debounce search (tunggu 300ms setelah user selesai ketik)
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const keyword = e.target.value.toLowerCase();
            const filtered = allLabData.filter(item => 
                item.nama.toLowerCase().includes(keyword) || 
                (item.deskripsi && item.deskripsi.toLowerCase().includes(keyword))
            );
            renderTable(filtered);
        }, 300);
    });

    // Image Preview (Form)
    document.getElementById('inputGambar').addEventListener('change', function(e) {
        const container = document.getElementById('newImagesContainer');
        container.innerHTML = '';
        if(this.files) {
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-square rounded-lg overflow-hidden border border-emerald-300 shadow-sm';
                    div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">
                                     <div class="absolute top-0 right-0 p-1"><span class="bg-emerald-500 text-white text-[8px] px-1 rounded-bl">BARU</span></div>`;
                    container.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }
    });
    
    // ✅ OPTIMASI: Event Delegation (1 listener untuk semua row)
    document.getElementById('tableBody').addEventListener('click', function(e) {
        if(e.target.closest('.edit-btn, .delete-btn')) {
            e.stopPropagation();
        }
        
        const row = e.target.closest('tr[data-id]');
        if(!row) return;
        
        const id = row.dataset.id;
        
        if(e.target.closest('.edit-btn')) {
            openFormModal(id, e);
        } else if(e.target.closest('.delete-btn')) {
            hapusLaboratorium(id, e);
        } else {
            openDetailModal(id);
        }
    }, { passive: true });
});

// --- 1. LOAD DATA ---
function loadLaboratorium() {
    fetch(API_URL + '/laboratorium').then(res => res.json()).then(res => {
        if((res.status === 'success' || res.code === 200) && res.data) {
            allLabData = res.data;
            renderTable(allLabData);
        } else {
            renderTable([]);
        }
    }).catch(err => {
        console.error(err);
        document.getElementById('tableBody').innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-red-500">Gagal memuat data</td></tr>`;
    });
}

function loadAsistenOptions() {
    fetch(API_URL + '/asisten').then(res => res.json()).then(res => {
        if(res.data) {
            const select = document.getElementById('inputKoordinator');
            select.innerHTML = '<option value="">-- Pilih Asisten --</option>';
            res.data.forEach(a => {
                const opt = document.createElement('option');
                opt.value = a.idAsisten;
                opt.text = a.nama;
                select.appendChild(opt);
            });
            if(pendingKordinator) {
                select.value = pendingKordinator;
                pendingKordinator = null;
            }
        }
    });
}

// --- OPTIMASI: Intersection Observer untuk Lazy Load Gambar Lab ---
let labPhotoObserver;
function initLabPhotoObserver() {
    if(labPhotoObserver) return;
    labPhotoObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                const img = entry.target;
                if(img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    labPhotoObserver.unobserve(img);
                }
            }
        });
    }, { rootMargin: '50px' });
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    const totalEl = document.getElementById('totalData');
    totalEl.innerText = `Total: ${data.length}`;

    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-search text-2xl mb-2"></i><p>Tidak ada data ditemukan</p></td></tr>`;
        return;
    }

    // ✅ OPTIMASI: Gunakan DocumentFragment + Event Delegation
    const fragment = document.createDocumentFragment();
    const placeholderImg = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 70"%3E%3Crect fill="%23e5e7eb" width="100" height="70"/%3E%3C/svg%3E';

    data.forEach((item, index) => {
        // Cek gambar pertama
        let firstImg = 'https://placehold.co/100x70?text=No+Img';
        if (item.images && Array.isArray(item.images) && item.images.length > 0) {
             const path = item.images[0].namaGambar || item.images[0];
             firstImg = path.includes('http') ? path : ASSETS_URL + '/assets/uploads/' + path;
        } else if (item.gambar) {
             let clean = item.gambar.replace(/[\[\]"]/g, '').split(',')[0];
             firstImg = clean.includes('http') ? clean : ASSETS_URL + '/assets/uploads/' + clean;
        }

        let badgeColor = 'bg-blue-50 text-blue-700 border-blue-100';
        if (item.jenis === 'Riset') badgeColor = 'bg-purple-50 text-purple-700 border-purple-100';
        if (item.jenis === 'Multimedia') badgeColor = 'bg-orange-50 text-orange-700 border-orange-100';

        const row = document.createElement('tr');
        row.className = 'hover:bg-blue-50 transition-colors group border-b border-gray-100 cursor-pointer';
        row.dataset.id = item.idLaboratorium;
        row.innerHTML = `
            <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
            <td class="px-6 py-4 text-center">
                <img src="${placeholderImg}" data-src="${firstImg}" class="w-16 h-12 object-cover rounded-lg border border-gray-200 mx-auto shadow-sm group-hover:scale-105 transition-transform">
            </td>
            <td class="px-6 py-4">
                <span class="font-bold text-gray-800 text-sm block">${item.nama}</span>
                <span class="text-xs text-gray-400 truncate block max-w-[200px]">${item.deskripsi ? item.deskripsi.substring(0, 50) + '...' : ''}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="${badgeColor} px-2.5 py-1 rounded-full text-xs font-semibold border">${item.jenis}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-xs font-bold"><i class="fas fa-desktop mr-1"></i> ${item.jumlahPc}</span>
            </td>
            <td class="px-6 py-4">
                <div class="flex justify-center items-center gap-2">
                    <button class="edit-btn w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-sm flex items-center justify-center"><i class="fas fa-pen text-xs"></i></button>
                    <button class="delete-btn w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-all shadow-sm flex items-center justify-center"><i class="fas fa-trash-alt text-xs"></i></button>
                </div>
            </td>
        `;
        fragment.appendChild(row);
    });
    
    tbody.innerHTML = '';
    tbody.appendChild(fragment);
    
    // ✅ Setup Intersection Observer untuk lazy-load gambar
    initLabPhotoObserver();
    tbody.querySelectorAll('img[data-src]').forEach(img => labPhotoObserver.observe(img));
}

// --- 2. MODAL FORM ---
function openFormModal(id = null, event = null) {
    if(event) event.stopPropagation();
    document.getElementById('formModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('formMessage').classList.add('hidden');
    document.getElementById('labForm').reset();
    document.getElementById('inputId').value = '';
    document.getElementById('savedImagesContainer').innerHTML = '';
    document.getElementById('newImagesContainer').innerHTML = '';
    document.getElementById('gambarPreviewInfo').classList.add('hidden');

    if (id) {
        document.getElementById('formModalTitle').innerHTML = '<i class="fas fa-edit text-blue-600"></i> Edit Laboratorium';
        document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> Update Data';
        
        const data = allLabData.find(i => i.idLaboratorium == id);
        if (data) {
            document.getElementById('inputId').value = data.idLaboratorium;
            document.getElementById('inputNama').value = data.nama;
            document.getElementById('inputJenis').value = data.jenis || 'Laboratorium';
            document.getElementById('inputJumlahPc').value = data.jumlahPc;
            document.getElementById('inputKapasitas').value = data.kapasitas;
            document.getElementById('inputDeskripsi').value = data.deskripsi;
            
            // Specs
            document.getElementById('inputProcessor').value = data.processor || '';
            document.getElementById('inputRam').value = data.ram || '';
            document.getElementById('inputStorage').value = data.storage || '';
            document.getElementById('inputGpu').value = data.gpu || '';
            document.getElementById('inputMonitor').value = data.monitor || '';
            
            // Soft & Fasilitas
            document.getElementById('inputSoftware').value = data.software || '';
            document.getElementById('inputFasilitas').value = data.fasilitas_pendukung || data.fasilitas || '';

            // Koordinator
            const coordSelect = document.getElementById('inputKoordinator');
            if(coordSelect.options.length > 1) coordSelect.value = data.idKordinatorAsisten || '';
            else pendingKordinator = data.idKordinatorAsisten;

            // Preview Gambar Lama (dengan fitur hapus)
            const savedContainer = document.getElementById('savedImagesContainer');
            
            let images = [];
            if (data.images && Array.isArray(data.images)) images = data.images;
            else if (data.gambar) images = data.gambar.replace(/[\[\]"]/g, '').split(',').map(img => ({ namaGambar: img.trim() }));

            if (images.length > 0) {
                document.getElementById('gambarPreviewInfo').classList.remove('hidden');
                images.forEach(img => {
                    let idGambar = img.idGambar || null;
                    let namaFile = img.namaGambar || img;
                    if (!namaFile || typeof namaFile !== 'string') return;

                    let imgUrl = namaFile.includes('http') ? namaFile : ASSETS_URL + '/assets/uploads/' + namaFile;
                    
                    const div = document.createElement('div');
                    div.className = 'relative group aspect-square rounded-lg overflow-hidden border border-blue-200 shadow-sm';
                    div.innerHTML = `
                        <img src="${imgUrl}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            ${idGambar ? `
                                <button type="button" onclick="hapusGambar(${idGambar}, this)" class="bg-red-500 hover:bg-red-600 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-lg transform transition-transform hover:scale-110">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            ` : ''}
                        </div>
                    `;
                    savedContainer.appendChild(div);
                });
            }
        }
    } else {
        document.getElementById('formModalTitle').innerHTML = '<i class="fas fa-plus text-emerald-600"></i> Tambah Laboratorium';
        document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> Simpan Data';
    }
}

document.getElementById('labForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('formMessage');
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';

    const formData = new FormData(this);
    const id = document.getElementById('inputId').value;
    const url = id ? API_URL + '/laboratorium/' + id : API_URL + '/laboratorium';

    fetch(url, { 
        method: 'POST', 
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP ${res.status}: ${res.statusText}`);
        }
        return res.json();
    })
    .then(data => {
        hideLoading();
        if (data.status === 'success' || data.code === 200 || data.code === 201) {
            closeModal('formModal');
            loadLaboratorium();
            showSuccess(id ? 'Data laboratorium berhasil diperbarui!' : 'Laboratorium baru berhasil ditambahkan!');
        } else {
            throw new Error(data.message || 'Gagal menyimpan data');
        }
    })
    .catch(err => {
        hideLoading();
        console.error('Form submit error:', err);
        msg.innerHTML = `<div class="bg-red-50 text-red-800 p-3 rounded text-sm mb-4 border border-red-200"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`;
        msg.classList.remove('hidden');
        showError(err.message);
    })
    .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data'; });
});

// --- 3. MODAL DETAIL & SLIDER LOGIC ---
function openDetailModal(id) {
    document.getElementById('detailModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Reset Slider
    currentLabImages = [];
    currentSlideIndex = 0;
    
    const data = allLabData.find(i => i.idLaboratorium == id);
    
    if(data) {
        document.getElementById('dNama').innerText = data.nama;
        document.getElementById('dJenis').innerText = data.jenis;
        document.getElementById('dKapasitasBadge').innerText = data.jumlahPc;
        document.getElementById('dDeskripsi').innerText = data.deskripsi || 'Tidak ada deskripsi.';
        
        // --- PARSING GAMBAR UNTUK SLIDER ---
        if (data.images && Array.isArray(data.images) && data.images.length > 0) {
            // Jika data dari relasi images
            data.images.forEach(img => {
                let path = (typeof img === 'object' && img.namaGambar) ? img.namaGambar : img;
                if(path) {
                    let fullUrl = path.includes('http') ? path : ASSETS_URL + '/assets/uploads/' + path;
                    currentLabImages.push(fullUrl);
                }
            });
        } 
        else if (data.gambar) {
            // Jika data string (mungkin koma separated atau JSON string)
            let raw = data.gambar.replace(/[\[\]"]/g, ''); // Hapus kurung siku & petik
            let parts = raw.split(',');
            parts.forEach(p => {
                if(p.trim() !== '') {
                    let fullUrl = p.trim().includes('http') ? p.trim() : ASSETS_URL + '/assets/uploads/' + p.trim();
                    currentLabImages.push(fullUrl);
                }
            });
        }
        
        // Fallback
        if (currentLabImages.length === 0) {
            currentLabImages.push('https://placehold.co/600x400?text=No+Image');
        }

        renderSlider();

        // Data Lain
        document.getElementById('dProcessor').innerText = data.processor || '-';
        document.getElementById('dRam').innerText = data.ram || '-';
        document.getElementById('dGpu').innerText = data.gpu || '-';
        document.getElementById('dStorage').innerText = data.storage || '-';
        document.getElementById('dSoftware').innerText = data.software || '-';
        document.getElementById('dFasilitas').innerText = data.fasilitas_pendukung || data.fasilitas || '-';

        // Koordinator
        const coordSelect = document.getElementById('inputKoordinator');
        let coordName = '-';
        if(data.idKordinatorAsisten && coordSelect.options.length > 0) {
             for(let i=0; i<coordSelect.options.length; i++) {
                 if(coordSelect.options[i].value == data.idKordinatorAsisten) {
                     coordName = coordSelect.options[i].text; break;
                 }
             }
        }
        document.getElementById('dKoordinator').innerText = coordName;

        document.getElementById('btnEditDetail').onclick = () => { closeModal('detailModal'); openFormModal(id); };
    }
}

function renderSlider() {
    const imgEl = document.getElementById('dSliderImage');
    const btnPrev = document.getElementById('btnPrevSlide');
    const btnNext = document.getElementById('btnNextSlide');
    const dotsContainer = document.getElementById('sliderDots');

    // 1. Set Image
    if (currentLabImages.length > 0) {
        imgEl.src = currentLabImages[currentSlideIndex];
    }

    // 2. Button Logic (Hapus hidden dulu, lalu tambah jika perlu)
    btnPrev.classList.remove('hidden', 'flex');
    btnNext.classList.remove('hidden', 'flex');

    if (currentLabImages.length > 1) {
        btnPrev.classList.add('flex');
        btnNext.classList.add('flex');
    } else {
        btnPrev.classList.add('hidden');
        btnNext.classList.add('hidden');
    }

    // 3. Dots Logic
    dotsContainer.innerHTML = '';
    if (currentLabImages.length > 1) {
        currentLabImages.forEach((_, idx) => {
            const dot = document.createElement('div');
            let activeClass = idx === currentSlideIndex 
                ? 'bg-white w-8 opacity-100' 
                : 'bg-white w-2 opacity-50 hover:opacity-80';
            dot.className = `h-1.5 rounded-full transition-all duration-300 cursor-pointer shadow-sm ${activeClass}`;
            dot.onclick = (e) => { e.stopPropagation(); currentSlideIndex = idx; renderSlider(); };
            dotsContainer.appendChild(dot);
        });
    }
}

function changeSlide(direction) {
    currentSlideIndex += direction;
    if (currentSlideIndex >= currentLabImages.length) currentSlideIndex = 0;
    else if (currentSlideIndex < 0) currentSlideIndex = currentLabImages.length - 1;
    renderSlider();
}

// --- HELPER FUNCTIONS ---
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function hapusLaboratorium(id, event) {
    if(event) event.stopPropagation();
    confirmDelete(() => {
        showLoading('Menghapus data...');
        fetch(API_URL + '/laboratorium/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(() => { 
            hideLoading();
            loadLaboratorium(); 
            showSuccess('Laboratorium berhasil dihapus!'); 
        })
        .catch(err => {
            hideLoading();
            showError('Gagal menghapus data');
        });
    }, 'Data laboratorium dan gambar terkait akan dihapus permanen!');
}

function hapusGambar(idGambar, btnEl) {
    Swal.fire({
        title: 'Hapus Gambar?',
        text: "Gambar akan dihapus permanen dari server.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading('Menghapus gambar...');
            const deleteUrl = API_URL + '/laboratorium/image/' + idGambar;
            console.log('Deleting image:', deleteUrl);
            
            fetch(deleteUrl, { 
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                console.log('Delete response status:', res.status, res.statusText);
                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                }
                return res.json();
            })
            .then(data => {
                console.log('Delete response data:', data);
                hideLoading();
                if (data.status === 'success' || data.code === 200) {
                    showSuccess('Gambar berhasil dihapus');
                    // Hapus elemen dari UI
                    const divImg = btnEl.closest('.relative.group');
                    if (divImg) divImg.remove();
                    
                    // Refresh data agar slider terupdate jika nanti dibuka
                    loadLaboratorium();
                } else {
                    showError(data.message || 'Gagal menghapus gambar');
                }
            })
            .catch(err => {
                hideLoading();
                console.error('Delete error:', err);
                showError('Terjadi kesalahan saat menghapus gambar: ' + err.message);
            });
        }
    });
}

document.onkeydown = function(evt) {
    if (evt.keyCode == 27) { closeModal('formModal'); closeModal('detailModal'); }
};
</script>