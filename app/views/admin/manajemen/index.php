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

<script>
// Pastikan BASE_URL dan API_URL didefinisikan di layout utama (header)
// window.BASE_URL = 'http://localhost:8000'; // Contoh jika belum ada

let allManajemenData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadManajemen();
    
    // Live Search
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterTable(searchTerm);
    });
});

// --- 1. LOAD DATA ---
function loadManajemen() {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-circle-notch fa-spin text-2xl mb-2"></i><p>Memuat data...</p></td></tr>`;
    
    fetch(API_URL + '/manajemen')
    .then(res => res.json())
    .then(response => {
        if((response.status === 'success' || response.code === 200) && response.data) {
            allManajemenData = response.data;
            renderTable(allManajemenData);
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
    
    let rowsHtml = '';
    data.forEach((item, index) => {
        // 1. Buat URL Avatar Default (Inisial Nama) sebagai cadangan
        const defaultAvatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(item.nama)}&background=random&color=fff&size=128`;
        
        // 2. Tentukan URL Foto Utama
        // Jika ada foto di DB, pakai path lokal. Jika tidak, pakai default.
        let photoUrl = item.foto ? `${BASE_URL}/assets/uploads/${item.foto}` : defaultAvatar;

        rowsHtml += `
            <tr onclick="openDetailModal(${item.idManajemen})" class="hover:bg-blue-50 transition-colors duration-150 group border-b border-gray-100 cursor-pointer">
                <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                <td class="px-6 py-4 text-center">
                    <img src="${photoUrl}" 
                         class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm inline-block bg-gray-100"
                         alt="${item.nama}"
                         onerror="this.onerror=null; this.src='${defaultAvatar}';">
                </td>
                <td class="px-6 py-4">
                    <span class="font-bold text-gray-800 text-sm block group-hover:text-blue-600 transition-colors">${escapeHtml(item.nama)}</span>
                    <div class="flex flex-col gap-0.5 mt-1">
                        <span class="text-xs text-blue-600 flex items-center gap-1.5 font-medium">
                            <i class="far fa-envelope text-[10px]"></i> ${escapeHtml(item.email || '-')}
                        </span>
                        ${item.nidn ? `
                        <span class="text-[10px] text-gray-500 flex items-center gap-1.5">
                            <i class="far fa-id-card text-[10px]"></i> ${escapeHtml(item.nidn)}
                        </span>` : ''}
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                        ${escapeHtml(item.jabatan)}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center items-center gap-2">
                        <button onclick="openFormModal(${item.idManajemen}, event)" class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-sm flex items-center justify-center border border-transparent" title="Edit">
                            <i class="fas fa-pen text-xs"></i>
                        </button>
                        <button onclick="hapusManajemen(${item.idManajemen}, event)" class="w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-all shadow-sm flex items-center justify-center border border-transparent" title="Hapus">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = rowsHtml;
}

// --- 2. MODAL DETAIL ---
function openDetailModal(id) {
    const data = allManajemenData.find(i => i.idManajemen == id);
    if (!data) return;

    const modal = document.getElementById('detailModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    document.getElementById('detailNama').innerText = data.nama;
    document.getElementById('detailEmail').innerText = data.email || '';
    document.getElementById('detailNidn').innerText = data.nidn ? `NIDN: ${data.nidn}` : 'NIDN: -';
    document.getElementById('detailJabatan').innerText = data.jabatan;
    
    // Logika Gambar Detail
    const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(data.nama)}&background=random&color=fff&size=256`;
    const fotoUrl = data.foto ? `${BASE_URL}/assets/uploads/${data.foto}` : avatarUrl;
    
    const imgEl = document.getElementById('detailFoto');
    imgEl.src = fotoUrl;
    imgEl.onerror = function() {
        this.onerror = null;
        this.src = avatarUrl;
    };
}

// --- 3. MODAL FORM ---
function openFormModal(id = null, event = null) {
    if (event) event.stopPropagation();
    
    const modal = document.getElementById('formModal');
    const form = document.getElementById('manajemenForm');
    const title = document.getElementById('formModalTitle');
    const btnSave = document.getElementById('btnSave');
    
    // Reset Image Preview
    const previewEl = document.getElementById('previewFoto');
    const placeholderEl = document.getElementById('placeholderFoto');
    previewEl.classList.add('hidden');
    placeholderEl.classList.remove('hidden');
    previewEl.src = "";

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('formMessage').classList.add('hidden');
    form.reset();
    document.getElementById('inputId').value = '';

    if (id) {
        title.innerHTML = '<i class="fas fa-edit text-blue-600"></i> Edit Anggota';
        btnSave.innerHTML = '<i class="fas fa-save"></i> Update Data';
        
        const data = allManajemenData.find(i => i.idManajemen == id);
        if (data) {
            document.getElementById('inputId').value = data.idManajemen;
            document.getElementById('inputNama').value = data.nama;
            document.getElementById('inputEmail').value = data.email || '';
            document.getElementById('inputNidn').value = data.nidn || '';
            document.getElementById('inputJabatan').value = data.jabatan;
            
            // Logic Preview saat Edit
            if (data.foto) {
                const fotoUrl = `${BASE_URL}/assets/uploads/${data.foto}`;
                previewEl.src = fotoUrl;
                previewEl.classList.remove('hidden');
                placeholderEl.classList.add('hidden');
                
                // Fallback jika gambar edit juga 404
                previewEl.onerror = function() {
                    // Jika error, kita sembunyikan preview dan tampilkan placeholder saja
                    previewEl.classList.add('hidden');
                    placeholderEl.classList.remove('hidden');
                };
            }
        }
    } else {
        title.innerHTML = '<i class="fas fa-user-plus text-emerald-600"></i> Tambah Anggota';
        btnSave.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
    }
}

// Submit Form
document.getElementById('manajemenForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('formMessage');
    const id = document.getElementById('inputId').value;
    const url = id ? API_URL + '/manajemen/' + id : API_URL + '/manajemen';
    
    const formData = new FormData(this);
    if (id) formData.append('_method', 'POST'); // Trik untuk update file di PHP

    btn.disabled = true; 
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';

    fetch(url, { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success' || data.code === 200 || data.code === 201) {
            closeModal('formModal');
            loadManajemen();
            showSuccess(id ? 'Data anggota berhasil diperbarui!' : 'Anggota baru berhasil ditambahkan!');
        } else {
            throw new Error(data.message || 'Gagal menyimpan data');
        }
    })
    .catch(err => {
        hideLoading();
        msg.innerHTML = `<div class="bg-red-50 text-red-800 p-3 rounded text-sm mb-4 border border-red-200"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`;
        msg.classList.remove('hidden');
        showError(err.message);
    })
    .finally(() => { 
        btn.disabled = false; 
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data'; 
    });
});

// Preview Image Local (Saat Upload)
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewFoto').src = e.target.result;
            document.getElementById('previewFoto').classList.remove('hidden');
            document.getElementById('placeholderFoto').classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// --- HELPER FUNCTIONS ---
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function hapusManajemen(id, event) {
    if (event) event.stopPropagation();
    
    confirmDelete(() => {
        showLoading('Menghapus data...');
        fetch(API_URL + '/manajemen/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(res => { 
            hideLoading();
            if(res.status === 'success' || res.code === 200) {
                loadManajemen(); 
                showSuccess('Data anggota berhasil dihapus!');
            } else {
                showError('Gagal menghapus: ' + (res.message || 'Error tidak diketahui'));
            }
        }) 
        .catch(err => {
            hideLoading();
            showError('Gagal menghapus (Network Error)');
        });
    });
}

function filterTable(searchTerm) {
    if (!searchTerm) {
        renderTable(allManajemenData);
        return;
    }
    const filtered = allManajemenData.filter(item => 
        (item.nama && item.nama.toLowerCase().includes(searchTerm)) ||
        (item.jabatan && item.jabatan.toLowerCase().includes(searchTerm)) ||
        (item.email && item.email.toLowerCase().includes(searchTerm)) ||
        (item.nidn && item.nidn.toLowerCase().includes(searchTerm))
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