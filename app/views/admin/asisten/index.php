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
window.BASE_URL = '<?= BASE_URL ?>';
let allAsistenData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadAsisten();
    
    // ✅ OPTIMASI: Debounce search
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const keyword = e.target.value.toLowerCase();
            const filtered = allAsistenData.filter(item => 
                (item.nama && item.nama.toLowerCase().includes(keyword)) ||
                (item.email && item.email.toLowerCase().includes(keyword)) ||
                (item.jurusan && item.jurusan.toLowerCase().includes(keyword))
            );
            renderTable(filtered);
        }, 300);
    });
    
    // ✅ OPTIMASI: Event Delegation (1 listener vs multiple onclick)
    // Mengurangi DOM listener dari 2N menjadi 1 (N = jumlah asisten)
    document.getElementById('tableBody').addEventListener('click', function(e) {
        // Stop propagation untuk button clicks
        if(e.target.closest('.edit-btn, .delete-btn')) {
            e.stopPropagation();
        }
        
        const row = e.target.closest('tr[data-id]');
        if(!row) return;
        
        const id = row.dataset.id;
        
        // Handle edit button
        if(e.target.closest('.edit-btn')) {
            openFormModal(id, e);
        }
        // Handle delete button
        else if(e.target.closest('.delete-btn')) {
            hapusAsisten(id, e);
        }
        // Handle row click (detail modal)
        else {
            openDetailModal(id);
        }
    }, { passive: true }); // ✅ Passive listener meningkatkan scroll performance
});

// --- OPTIMASI: Cache Global untuk Detail & Search Timeout ---
let allAsistenCache = {}; // Cache detail asisten (avoid multiple API calls)
let searchTimeout;        // Debounce search

// --- 1. LOAD DATA UTAMA (Optimized dengan cache pre-loading) ---
async function loadAsisten() {
    try {
        const response = await fetch(API_URL + '/asisten');
        const res = await response.json();
        
        if((res.status === 'success' || res.code === 200) && res.data) {
            allAsistenData = res.data;
            
            // ✅ OPTIMASI: Pre-cache semua data detail saat load pertama
            // Ini menghindari API call berulang saat klik detail modal
            res.data.forEach(item => {
                allAsistenCache[item.idAsisten] = item;
            });
            
            renderTable(allAsistenData);
        } else {
            renderTable([]);
        }
    } catch(err) {
        console.error(err);
        document.getElementById('tableBody').innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-red-500">Gagal memuat data</td></tr>`;
    }
}

// ✅ OPTIMASI: Intersection Observer untuk Lazy Load Foto
let photoObserver;
function initPhotoObserver() {
    if(photoObserver) return;
    photoObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                const img = entry.target;
                if(img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    photoObserver.unobserve(img);
                }
            }
        });
    }, { rootMargin: '50px' });
}

// ✅ OPTIMASI: Gunakan DocumentFragment untuk batch DOM updates (lebih cepat)
function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    const totalEl = document.getElementById('totalData');
    
    totalEl.innerText = `Total: ${data.length}`;

    if(data && data.length > 0) {
        const fragment = document.createDocumentFragment();
        const placeholderImg = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"%3E%3Crect fill="%23e5e7eb" width="50" height="50"/%3E%3C/svg%3E';
        
        data.forEach((item, index) => {
            let statusBadge = '';
            if (item.isKoordinator == 1) { statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200"><i class="fas fa-crown mr-1 text-xs"></i> Koordinator</span>`; } 
            else {
                let st = item.statusAktif == '1' ? 'Asisten' : (item.statusAktif == '0' ? 'Tidak Aktif' : item.statusAktif);
                let cls = st === 'Tidak Aktif' ? 'bg-red-100 text-red-800' : (st === 'CA' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800');
                statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${cls}">${st}</span>`;
            }
            
            const fotoUrl = item.foto ? (item.foto.includes('http') ? item.foto : ASSETS_URL + '/assets/uploads/' + item.foto) : 'https://placehold.co/50x50?text=Foto';
            
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 transition-colors group border-b border-gray-100 cursor-pointer';
            row.dataset.id = item.idAsisten;
            row.innerHTML = `
                <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                <td class="px-6 py-4"><div class="flex justify-center"><img src="${placeholderImg}" data-src="${fotoUrl}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-md" alt="${item.nama}"></div></td>
                <td class="px-6 py-4"><div class="flex flex-col"><span class="font-bold text-gray-800 text-base group-hover:text-blue-600 transition-colors">${item.nama}</span><span class="text-xs text-gray-500 mt-0.5 flex items-center gap-1"><i class="fas fa-envelope text-gray-300"></i> ${item.email || '-'}</span></div></td>
                <td class="px-6 py-4 text-gray-600 font-medium">${item.jurusan || '-'}</td>
                <td class="px-6 py-4 text-center">${statusBadge}</td>
                <td class="px-6 py-4">
                    <div class="flex justify-center items-center gap-2">
                        <button class="edit-btn w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all duration-200 flex items-center justify-center"><i class="fas fa-pen text-xs"></i></button>
                        <button class="delete-btn w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-all duration-200 flex items-center justify-center"><i class="fas fa-trash-alt text-xs"></i></button>
                    </div>
                </td>
            `;
            fragment.appendChild(row);
        });
        
        tbody.innerHTML = '';
        tbody.appendChild(fragment);
        
        initPhotoObserver();
        tbody.querySelectorAll('img[data-src]').forEach(img => photoObserver.observe(img));
    } else { 
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Data asisten tidak ditemukan</td></tr>`; 
    }
}

// --- 2. MODAL DETAIL (Optimized dengan Cache) ---
// ✅ OPTIMASI: Check cache dulu sebelum API call
// Ini eliminasi multiple API calls untuk data yang sama
function openDetailModal(id) {
    document.getElementById('detailModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('mNama').innerText = "Memuat...";
    
    // ✅ Check cache dulu (instant, no API call)
    if(allAsistenCache[id]) {
        populateDetailModal(allAsistenCache[id]);
    } else {
        // Jika belum cache, fetch dari API (hanya pertama kali)
        fetch(API_URL + '/asisten/' + id)
            .then(res => res.json())
            .then(res => {
                if((res.status === 'success' || res.code === 200) && res.data) {
                    allAsistenCache[id] = res.data; // Simpan ke cache
                    populateDetailModal(res.data);
                }
            });
    }
}

// ✅ Helper function untuk populate detail modal
function populateDetailModal(d) {
    document.getElementById('mNama').innerText = d.nama;
    document.getElementById('mJurusan').innerText = d.jurusan || '-';
    document.getElementById('mEmail').innerText = d.email || '-';
    document.getElementById('mBio').innerText = d.bio || 'Tidak ada bio.';
    
    const fotoUrl = d.foto ? (d.foto.includes('http') ? d.foto : ASSETS_URL + '/assets/uploads/' + d.foto) : `https://placehold.co/150x150?text=${d.nama.charAt(0)}`;
    document.getElementById('mFoto').src = fotoUrl;
    
    const bDiv = document.getElementById('mBadges');
    bDiv.innerHTML = '';
    let st = d.statusAktif == '1' ? 'Asisten' : (d.statusAktif == '0' ? 'Tidak Aktif' : d.statusAktif);
    let color = st === 'Asisten' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800';
    bDiv.innerHTML += `<span class="${color} px-3 py-1 rounded-full text-xs font-bold border border-transparent">${st}</span>`;
    if(d.isKoordinator == 1) {
        bDiv.innerHTML += `<span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold border border-blue-700 shadow flex items-center gap-1"><i class="fas fa-crown text-yellow-300"></i> Koordinator</span>`;
    }
    
    const sDiv = document.getElementById('mSkills');
    sDiv.innerHTML = '';
    let skills = d.skills;
    try {
        if(skills && skills.includes('[')) {
            skills = JSON.parse(skills);
        } else if(skills) {
            skills = skills.split(',');
        }
    } catch(e) {
        if(skills) skills = skills.split(',');
    }
    
    if(Array.isArray(skills) && skills.length > 0) {
        skills.forEach(s => {
            if(s && s.trim()) {
                sDiv.innerHTML += `<span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-xs font-bold border border-gray-200">${s.trim()}</span>`;
            }
        });
    } else {
        sDiv.innerHTML = '<span class="text-gray-400 text-xs italic">Tidak ada data</span>';
    }
}

// --- 3. MODAL FORM (Optimized dengan Cache) ---
function openFormModal(id = null, event = null) {
    if(event) event.stopPropagation();
    document.getElementById('formModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('formMessage').classList.add('hidden');
    document.getElementById('asistenForm').reset();
    document.getElementById('inputIdAsisten').value = '';
    document.getElementById('fotoPreviewInfo').classList.add('hidden');

    if (id) {
        document.getElementById('formModalTitle').innerHTML = '<i class="fas fa-user-edit text-blue-600"></i> Edit Asisten';
        document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> Update Data';
        
        // ✅ OPTIMASI: Check cache dulu sebelum API call
        if(allAsistenCache[id]) {
            populateFormData(allAsistenCache[id]);
        } else {
            fetch(API_URL + '/asisten/' + id)
                .then(res => res.json())
                .then(res => {
                    if(res.data) {
                        allAsistenCache[id] = res.data; // Simpan ke cache
                        populateFormData(res.data);
                    }
                });
        }
    } else {
        document.getElementById('formModalTitle').innerHTML = '<i class="fas fa-user-plus text-blue-600"></i> Tambah Asisten Baru';
        document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> Simpan Data';
        
        // Reset Skills Tags for NEW entry
        selectedSkills = [];
        const container = document.getElementById('skillsTagContainer');
        container.querySelectorAll('.skill-tag').forEach(el => el.remove());
        document.getElementById('inputSkills').value = '';
    }
}

// ✅ Helper function untuk populate form data
function populateFormData(d) {
    document.getElementById('inputIdAsisten').value = d.idAsisten;
    document.getElementById('inputNama').value = d.nama;
    document.getElementById('inputEmail').value = d.email;
    document.getElementById('inputJurusan').value = d.jurusan || 'Teknik Informatika';
    document.getElementById('inputBio').value = d.bio || '';
    let status = d.statusAktif;
    if (status == '1') status = 'Asisten';
    if (status == '0') status = 'Tidak Aktif';
    document.getElementById('inputStatus').value = status || 'Asisten';
    
    // --- UPDATE SKILLS TAGS ---
    let skillsArray = [];
    try {
        let s = JSON.parse(d.skills);
        if(Array.isArray(s)) skillsArray = s;
        else if(d.skills) skillsArray = d.skills.split(',').map(item => item.trim());
    } catch(e) {
        if(d.skills) skillsArray = d.skills.split(',').map(item => item.trim());
    }
    
    selectedSkills = [];
    const container = document.getElementById('skillsTagContainer');
    const tagInput = document.getElementById('tagInput');
    // Hapus tag lama kecuali input
    container.querySelectorAll('.skill-tag').forEach(el => el.remove());
    
    skillsArray.forEach(skill => {
        if(skill) addTag(skill, false); // false agar tidak fokus ke input saat loading data
    });
    
    if(d.foto) document.getElementById('fotoPreviewInfo').classList.remove('hidden');
}

document.getElementById('asistenForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSave'); const formMsg = document.getElementById('formMessage');
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
    showLoading('Menyimpan data asisten...');
    const formData = new FormData(this);
    const id = document.getElementById('inputIdAsisten').value;
    const url = id ? API_URL + '/asisten/' + id : API_URL + '/asisten';
    fetch(url, { method: 'POST', body: formData }).then(res => res.json()).then(data => {
        hideLoading();
        if (data.status === 'success' || data.code === 201 || data.code === 200) { 
            closeModal('formModal'); 
            loadAsisten(); 
            showSuccess(id ? 'Data asisten berhasil diperbarui!' : 'Asisten baru berhasil ditambahkan!'); 
        } 
        else { throw new Error(data.message || 'Gagal menyimpan'); }
    }).catch(err => { 
        hideLoading();
        formMsg.innerHTML = `<div class="bg-red-50 text-red-800 p-3 rounded text-sm"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`; 
        formMsg.classList.remove('hidden'); 
        showError(err.message);
    })
    .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data'; });
});

// --- 4. LOGIKA MODAL PILIH KOORDINATOR (NEW!) ---
function openKoordinatorModal() {
    document.getElementById('koordinatorModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    const listDiv = document.getElementById('coordList');
    listDiv.innerHTML = '<div class="text-center py-4 text-gray-400"><i class="fas fa-circle-notch fa-spin"></i> Memuat daftar...</div>';
    
    fetch(API_URL + '/asisten').then(res => res.json()).then(res => {
        listDiv.innerHTML = '';
        if ((res.status === 'success' || res.code === 200) && res.data) {
            // Set Current Coordinator UI
            const current = res.data.find(a => a.isKoordinator == 1);
            if (current) {
                document.getElementById('currentCoordName').innerText = current.nama;
                document.getElementById('currentCoordName').classList.remove('text-gray-400', 'italic');
            } else {
                document.getElementById('currentCoordName').innerText = "Belum ditentukan";
                document.getElementById('currentCoordName').classList.add('text-gray-400', 'italic');
            }

            // Generate List
            // Filter hanya asisten aktif / 'Asisten'
            const aktif = res.data.filter(a => a.statusAktif == 1 || a.statusAktif === 'Asisten');
            if (aktif.length === 0) {
                listDiv.innerHTML = '<div class="text-center py-4 text-gray-400">Tidak ada asisten aktif.</div>';
                return;
            }

            aktif.forEach(item => {
                const isSelected = item.isKoordinator == 1;
                const fotoUrl = item.foto ? (item.foto.includes('http') ? item.foto : ASSETS_URL + '/assets/uploads/' + item.foto) : 'https://placehold.co/50x50?text=Foto';
                
                // Item List Style
                const div = document.createElement('label');
                div.className = `flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-all ${isSelected ? 'bg-emerald-50 border-emerald-500 ring-1 ring-emerald-500' : 'bg-white border-gray-200 hover:bg-gray-50'}`;
                div.innerHTML = `
                    <input type="radio" name="idKoordinator" value="${item.idAsisten}" class="w-4 h-4 text-emerald-600" ${isSelected ? 'checked' : ''}>
                    <img src="${fotoUrl}" class="w-10 h-10 rounded-full object-cover border border-gray-300">
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-800">${item.nama}</p>
                        <p class="text-xs text-gray-500">${item.jurusan || '-'} ${isSelected ? '<span class="ml-2 text-emerald-600 font-bold">(Current)</span>' : ''}</p>
                    </div>
                `;
                // Simple toggle visual effect
                div.addEventListener('change', () => {
                    document.querySelectorAll('#coordList label').forEach(el => el.className = 'flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-all bg-white border-gray-200 hover:bg-gray-50');
                    div.className = 'flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-all bg-emerald-50 border-emerald-500 ring-1 ring-emerald-500';
                });
                listDiv.appendChild(div);
            });
        }
    });
}

document.getElementById('koordinatorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const selected = document.querySelector('input[name="idKoordinator"]:checked');
    const msg = document.getElementById('coordMessage');
    const btn = document.getElementById('btnSaveCoord');

    if (!selected) {
        msg.innerHTML = '<div class="text-red-500 text-sm">Pilih salah satu asisten dulu!</div>';
        msg.classList.remove('hidden');
        return;
    }

    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    showLoading('Memperbarui koordinator...');
    fetch(API_URL + '/asisten/' + selected.value + '/koordinator', { method: 'POST' })
    .then(res => res.json())
    .then(data => {
        hideLoading();
        if(data.status === 'success' || data.code === 200 || data.code === 201) {
            closeModal('koordinatorModal');
            loadAsisten(); // Reload tabel utama
            showSuccess('Koordinator berhasil diperbarui!');
        } else {
            msg.innerHTML = `<div class="text-red-500 text-sm">${data.message || 'Gagal update'}</div>`;
            msg.classList.remove('hidden');
            showError(data.message || 'Gagal memperbarui koordinator');
        }
    })
    .catch(err => {
        hideLoading();
        console.error(err);
        showError('Kesalahan sistem saat update koordinator');
    })
    .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan Pilihan'; });
});

// --- 4. SISTEM TAGGING KEAHLIAN (LinkedIn Style) ---
const IT_SKILLS_LIST = [
    "Web Development", "PHP", "Laravel", "Javascript", "React", "Vue", "Node.js", "Express", "Tailwind CSS", "Bootstrap",
    "Database Management", "SQL", "MySQL", "PostgreSQL", "MongoDB", "Firebase",
    "Mobile Development", "Flutter", "React Native", "Android Studio", "iOS Development",
    "Artificial Intelligence", "Machine Learning", "Python", "Data Science", "R Programming",
    "Cybersecurity", "Networking", "Cisco", "Linux Administration",
    "Cloud Computing", "AWS", "Google Cloud", "UI/UX Design", "Figma", "Adobe XD",
    "Internet of Things (IoT)", "Arduino", "Raspberry Pi", "Game Development", "Unity", "C#", "C++"
];

let selectedSkills = [];

function initTaggingSystem() {
    const container = document.getElementById('skillsTagContainer');
    const tagInput = document.getElementById('tagInput');
    const suggestionBox = document.getElementById('skillsSuggestions');
    const hiddenInput = document.getElementById('inputSkills');

    // Klik container otomatis fokus ke input
    container.addEventListener('click', () => tagInput.focus());

    tagInput.addEventListener('input', (e) => {
        const val = e.target.value.toLowerCase();
        if(!val) {
            suggestionBox.classList.add('hidden');
            return;
        }

        const filtered = IT_SKILLS_LIST.filter(s => 
            s.toLowerCase().includes(val) && !selectedSkills.includes(s)
        ).slice(0, 5); // Limit 5 saran

        if(filtered.length > 0) {
            suggestionBox.innerHTML = filtered.map(s => `
                <div class="px-4 py-2 hover:bg-blue-50 cursor-pointer flex items-center gap-2 group" onclick="addTag('${s.replace(/'/g, "\\'")}')">
                    <i class="fas fa-plus-circle text-gray-300 group-hover:text-blue-500 transition-colors"></i>
                    <span class="text-sm font-medium text-gray-700">${s}</span>
                </div>
            `).join('');
            suggestionBox.classList.remove('hidden');
        } else {
            // Beri opsi tambah manual jika tidak ada di list
            suggestionBox.innerHTML = `
                <div class="px-4 py-2 hover:bg-blue-50 cursor-pointer flex items-center gap-2 group" onclick="addTag('${tagInput.value.replace(/'/g, "\\'")}')">
                    <i class="fas fa-plus-circle text-blue-500"></i>
                    <span class="text-sm font-medium text-gray-700">Tambahkan "${tagInput.value}"</span>
                </div>`;
            suggestionBox.classList.remove('hidden');
        }
    });

    tagInput.addEventListener('keydown', (e) => {
        if(e.key === 'Enter') {
            e.preventDefault();
            const val = tagInput.value.trim();
            if(val) addTag(val);
        } else if(e.key === 'Backspace' && !tagInput.value && selectedSkills.length > 0) {
            removeTag(selectedSkills[selectedSkills.length - 1]);
        }
    });

    // Sembunyikan saran saat klik di luar
    document.addEventListener('click', (e) => {
        if(!container.contains(e.target) && !suggestionBox.contains(e.target)) {
            suggestionBox.classList.add('hidden');
        }
    });
}

function addTag(skill, focus = true) {
    if(!skill || selectedSkills.includes(skill)) {
        document.getElementById('tagInput').value = '';
        document.getElementById('skillsSuggestions').classList.add('hidden');
        return;
    }

    selectedSkills.push(skill);
    updateHiddenInput();

    const container = document.getElementById('skillsTagContainer');
    const tagInput = document.getElementById('tagInput');

    const tagEl = document.createElement('div');
    tagEl.className = 'skill-tag inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold border border-blue-100 animate-in fade-in zoom-in duration-200';
    tagEl.dataset.skill = skill;
    tagEl.innerHTML = `
        <span>${skill}</span>
        <button type="button" onclick="event.stopPropagation(); removeTag('${skill.replace(/'/g, "\\'")}')" class="hover:text-blue-900 transition-colors">
            <i class="fas fa-times-circle"></i>
        </button>
    `;
    
    container.insertBefore(tagEl, tagInput);
    tagInput.value = '';
    document.getElementById('skillsSuggestions').classList.add('hidden');
    if(focus) tagInput.focus();
}

function removeTag(skill) {
    selectedSkills = selectedSkills.filter(s => s !== skill);
    updateHiddenInput();
    const tagEl = document.querySelector(`.skill-tag[data-skill="${skill}"]`);
    if(tagEl) tagEl.remove();
}

function updateHiddenInput() {
    document.getElementById('inputSkills').value = JSON.stringify(selectedSkills);
}

// Jalankan init tagging saat halaman siap
document.addEventListener('DOMContentLoaded', initTaggingSystem);

// --- HELPER FUNCTIONS ---
function closeModal(modalId) { document.getElementById(modalId).classList.add('hidden'); document.body.style.overflow = 'auto'; }
document.onkeydown = function(evt) { 
    if (evt.keyCode == 27) { 
        closeModal('detailModal'); closeModal('formModal'); closeModal('koordinatorModal'); 
    } 
};
function hapusAsisten(id, event) { 
    if(event) event.stopPropagation();
    confirmDelete(() => {
        showLoading('Menghapus data...');
        fetch(API_URL + '/asisten/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(() => { 
            hideLoading();
            loadAsisten(); 
            showSuccess('Data asisten berhasil dihapus!'); 
        })
        .catch(err => {
            hideLoading();
            showError('Gagal menghapus data');
        });
    });
}
</script>