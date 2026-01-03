<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-gavel text-blue-600"></i> 
        Manajemen Peraturan Lab
    </h1>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" id="searchInput" placeholder="Cari peraturan..." 
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow text-sm">
        </div>

        <button onclick="loadPeraturan()" 
           class="bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-blue-600 px-4 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium">
            <i class="fas fa-sync-alt"></i>
        </button>

        <button onclick="openFormModal()" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
            <i class="fas fa-plus"></i> Tambah Peraturan
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <span class="text-sm text-gray-500 font-medium">Daftar Peraturan & Tata Tertib</span>
        <span id="totalData" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Total: 0</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold w-1/4">Nama Peraturan</th>
                    <th class="px-6 py-4 font-semibold w-24">Kategori</th>
                    <th class="px-6 py-4 font-semibold">Deskripsi Singkat</th>
                    <th class="px-6 py-4 font-semibold text-center w-40">Tanggal</th>
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
        Klik pada baris tabel untuk melihat detail lengkap.
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
                <form id="peraturanForm">
                    <input type="hidden" id="inputId" name="id">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Peraturan <span class="text-red-500">*</span></label>
                            <input type="text" id="inputJudul" name="judul" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                   placeholder="Contoh: Tata Tertib Penggunaan Lab Komputer">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select id="inputKategori" name="kategori" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                                <option value="Kehadiran & Akademik">Kehadiran & Akademik</option>
                                <option value="Standar Pakaian">Standar Pakaian</option>
                                <option value="Larangan Umum">Larangan Umum</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi / Isi Peraturan <span class="text-red-500">*</span></label>
                            <textarea id="inputDeskripsi" name="deskripsi" rows="6" required
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors resize-none"
                                      placeholder="Tuliskan detail peraturan di sini..."></textarea>
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

<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-2xl border border-gray-100">
            
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center sticky top-0 z-10">
                <h3 class="text-xl font-bold text-blue-800 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i> Detail Peraturan
                </h3>
                <button onclick="closeModal('detailModal')" class="text-blue-600 hover:text-blue-800 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[70vh] custom-scrollbar">
                <div id="detailContent" class="space-y-4">
                    <div class="animate-pulse space-y-3">
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                        <div class="h-24 bg-gray-200 rounded"></div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-3 flex justify-end">
                <button type="button" onclick="closeModal('detailModal')" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-medium shadow-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
window.BASE_URL = '<?= BASE_URL ?>';
let allPeraturanData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadPeraturan();
    
    // Live Search
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterTable(searchTerm);
    });
});

// --- 1. LOAD DATA ---
function loadPeraturan() {
    const tbody = document.getElementById('tableBody');
    // Loader saat refresh
    tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-circle-notch fa-spin text-2xl mb-2"></i><p>Memuat data...</p></td></tr>`;
    
    fetch(API_URL + '/peraturan-lab')
    .then(res => res.json())
    .then(response => {
        if((response.status === 'success' || response.code === 200) && response.data) {
            allPeraturanData = response.data;
            renderTable(allPeraturanData);
        } else {
            renderTable([]);
        }
    })
    .catch(err => {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-red-500">Gagal memuat data</td></tr>`;
    });
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    const totalEl = document.getElementById('totalData');
    
    tbody.innerHTML = '';
    totalEl.innerText = `Total: ${data.length}`;
    
    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Data tidak ditemukan</td></tr>`;
        return;
    }
    
    data.forEach((item, index) => {
        const deskripsi = item.deskripsi ? (item.deskripsi.length > 60 ? item.deskripsi.substring(0, 60) + '...' : item.deskripsi) : '-';
        const tanggal = item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', {
            day: 'numeric', month: 'short', year: 'numeric'
        }) : '-';

        // CLICK EVENT DI TR UNTUK BUKA DETAIL
        const row = `
            <tr onclick="openDetailModal(${item.id})" class="hover:bg-blue-50 transition-colors duration-150 group border-b border-gray-100 cursor-pointer">
                <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                <td class="px-6 py-4">
                    <span class="font-bold text-gray-800 text-sm block group-hover:text-blue-600 transition-colors">${escapeHtml(item.judul)}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold bg-purple-100 text-purple-800">
                        ${escapeHtml(item.kategori || 'Larangan Umum')}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-600 text-sm">
                    ${escapeHtml(deskripsi)}
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        ${tanggal}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center items-center gap-2">
                        <button onclick="openFormModal(${item.id}, event)" class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-sm flex items-center justify-center border border-transparent" title="Edit">
                            <i class="fas fa-pen text-xs"></i>
                        </button>
                        <button onclick="hapusPeraturan(${item.id}, event)" class="w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-all shadow-sm flex items-center justify-center border border-transparent" title="Hapus">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// --- 2. MODAL FORM (CREATE / EDIT) ---
function openFormModal(id = null, event = null) {
    if (event) event.stopPropagation(); // Mencegah klik tembus ke row (agar detail tidak terbuka)
    
    const modal = document.getElementById('formModal');
    const form = document.getElementById('peraturanForm');
    const title = document.getElementById('formModalTitle');
    const btnSave = document.getElementById('btnSave');

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('formMessage').classList.add('hidden');
    form.reset();
    document.getElementById('inputId').value = '';

    if (id) {
        title.innerHTML = '<i class="fas fa-edit text-blue-600"></i> Edit Peraturan';
        btnSave.innerHTML = '<i class="fas fa-save"></i> Update Data';
        const data = allPeraturanData.find(i => i.id == id);
        if (data) {
            document.getElementById('inputId').value = data.id;
            document.getElementById('inputJudul').value = data.judul;
            document.getElementById('inputKategori').value = data.kategori || 'Larangan Umum';
            document.getElementById('inputDeskripsi').value = data.deskripsi;
        }
    } else {
        title.innerHTML = '<i class="fas fa-plus text-emerald-600"></i> Tambah Peraturan';
        btnSave.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
    }
}

// Submit Form
document.getElementById('peraturanForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('formMessage');
    const id = document.getElementById('inputId').value;
    const url = id ? API_URL + '/peraturan-lab/' + id : API_URL + '/peraturan-lab';
    const method = id ? 'PUT' : 'POST';

    const formData = new FormData(this);
    const dataObj = Object.fromEntries(formData.entries());

    btn.disabled = true; 
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';

    fetch(url, { 
        method: method, 
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dataObj)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.code === 200 || data.code === 201) {
            closeModal('formModal');
            loadPeraturan();
            alert(id ? 'Data berhasil diupdate!' : 'Data berhasil disimpan!');
        } else {
            throw new Error(data.message || 'Gagal menyimpan data');
        }
    })
    .catch(err => {
        msg.innerHTML = `<div class="bg-red-50 text-red-800 p-3 rounded text-sm mb-4 border border-red-200"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`;
        msg.classList.remove('hidden');
    })
    .finally(() => { 
        btn.disabled = false; 
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data'; 
    });
});

// --- 3. MODAL DETAIL (READ ONLY) ---
function openDetailModal(id) {
    // Fungsi ini dipanggil otomatis saat TR diklik
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('detailContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Tampilan Loading
    content.innerHTML = `
        <div class="animate-pulse space-y-4">
            <div class="h-6 bg-gray-200 rounded w-3/4"></div>
            <div class="h-4 bg-gray-200 rounded w-1/4 mb-4"></div>
            <div class="space-y-2">
                <div class="h-4 bg-gray-200 rounded"></div>
                <div class="h-4 bg-gray-200 rounded"></div>
                <div class="h-4 bg-gray-200 rounded w-5/6"></div>
            </div>
        </div>`;

    fetch(API_URL + '/peraturan-lab/' + id)
    .then(res => res.json())
    .then(res => {
        const data = res.data || res;
        const tanggal = new Date(data.created_at).toLocaleDateString('id-ID', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        });

        content.innerHTML = `
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Nama Peraturan</h4>
                <p class="text-xl font-bold text-gray-900">${escapeHtml(data.judul)}</p>
            </div>
            
            <div class="border-t border-gray-100 pt-3">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Kategori</h4>
                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-sm font-semibold">
                    <i class="fas fa-tag mr-2"></i> ${escapeHtml(data.kategori || 'Larangan Umum')}
                </span>
            </div>
            
            <div class="border-t border-gray-100 pt-3">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal Upload</h4>
                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-sm font-medium">
                    <i class="far fa-calendar-alt mr-2"></i> ${tanggal}
                </span>
            </div>

            <div class="border-t border-gray-100 pt-3">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Deskripsi / Isi Peraturan</h4>
                <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 text-gray-700 leading-relaxed whitespace-pre-wrap font-serif text-sm">
                    ${escapeHtml(data.deskripsi)}
                </div>
            </div>
        `;
    })
    .catch(err => {
        content.innerHTML = `<p class="text-red-500 text-center py-4">Gagal memuat detail data.</p>`;
    });
}

// --- HELPER FUNCTIONS ---
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function hapusPeraturan(id, event) {
    if (event) event.stopPropagation(); // Mencegah klik tembus
    
    if(confirm('Yakin ingin menghapus peraturan ini?')) {
        fetch(API_URL + '/peraturan-lab/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(() => { loadPeraturan(); }) 
        .catch(err => alert('Gagal menghapus'));
    }
}

function filterTable(searchTerm) {
    if (!searchTerm) {
        renderTable(allPeraturanData);
        return;
    }
    const filtered = allPeraturanData.filter(item => 
        (item.judul && item.judul.toLowerCase().includes(searchTerm)) ||
        (item.deskripsi && item.deskripsi.toLowerCase().includes(searchTerm))
    );
    renderTable(filtered);
}

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}

document.onkeydown = function(evt) {
    if (evt.keyCode == 27) { closeModal('formModal'); closeModal('detailModal'); }
};
</script>