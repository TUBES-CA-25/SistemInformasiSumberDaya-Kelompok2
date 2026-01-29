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
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Upload File PDF</label>
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

<script>
let allData = [];

// Endpoint API baru khusus SOP
const ENDPOINT_SOP = API_URL + '/sop'; 

document.addEventListener('DOMContentLoaded', function() {
    loadData();
    document.getElementById('searchInput').addEventListener('keyup', renderTable);
});

function loadData() {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-circle-notch fa-spin text-2xl mb-2"></i><p>Memuat data SOP...</p></td></tr>`;
    
    fetch(ENDPOINT_SOP)
    .then(res => res.json())
    .then(response => {
        if((response.status === 'success' || response.code === 200) && response.data) {
            allData = response.data;
            renderTable();
        } else {
            tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada data SOP.</td></tr>`;
        }
    })
    .catch(err => {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-red-500">Gagal koneksi ke server</td></tr>`;
    });
}

// FUNGSI UTAMA PENCARIAN
function renderTable() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    
    // Memfilter berdasarkan Judul ATAU Deskripsi
    const filtered = allData.filter(item => {
        const matchJudul = item.judul && item.judul.toLowerCase().includes(keyword);
        const matchDesc = item.deskripsi && item.deskripsi.toLowerCase().includes(keyword);
        return matchJudul || matchDesc;
    });
    
    const tbody = document.getElementById('tableBody');
    
    if (filtered.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Data tidak ditemukan</td></tr>`;
        return;
    }

    let html = '';
    filtered.forEach((item, index) => {
        html += `
            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
                <td class="px-6 py-4 text-center font-medium text-gray-400 text-xs">${index + 1}</td>
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800 text-sm">${escapeHtml(item.judul)}</div>
                </td>
                <td class="px-6 py-4">
                    ${item.file ? 
                        `<a href="${PUBLIC_URL}/assets/uploads/pdf/${item.file}" target="_blank" class="flex items-center gap-2 text-xs font-medium text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full hover:bg-blue-100 w-fit">
                            <i class="fas fa-file-pdf"></i> Lihat PDF
                        </a>` : 
                        '<span class="text-gray-400 text-xs italic">Tidak ada file</span>'}
                </td>
                <td class="px-6 py-4 text-xs text-gray-600 max-w-xs truncate">
                    ${escapeHtml(item.deskripsi || '-')}
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        <button onclick="openFormModal(${item.id_sop})" class="w-8 h-8 rounded bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center">
                            <i class="fas fa-pen text-xs"></i>
                        </button>
                        <button onclick="deleteData(${item.id_sop})" class="w-8 h-8 rounded bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

function openFormModal(id = null) {
    const modal = document.getElementById('formModal');
    const form = document.getElementById('sopForm');
    modal.classList.remove('hidden');
    form.reset();
    document.getElementById('inputId').value = '';
    document.getElementById('currentFile').classList.add('hidden');

    if (id) {
        document.getElementById('formModalTitle').innerText = 'Edit SOP';
        // Cari data berdasarkan ID SOP
        const data = allData.find(i => i.id_sop == id);
        if (data) {
            document.getElementById('inputId').value = data.id_sop;
            document.getElementById('inputJudul').value = data.judul;

            document.getElementById('inputDeskripsi').value = data.deskripsi || '';
            
            if (data.file) {
                document.getElementById('currentFile').classList.remove('hidden');
                document.getElementById('currentFileName').innerText = data.file;
            }
        }
    } else {
        document.getElementById('formModalTitle').innerText = 'Tambah SOP Baru';
    }
}

document.getElementById('sopForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('inputId').value;
    // URL API disesuaikan ke /sop
    const url = id ? ENDPOINT_SOP + '/' + id : ENDPOINT_SOP;
    
    const formData = new FormData(this);
    if (id) formData.append('_method', 'PUT');

    const btn = document.getElementById('btnSave');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Menyimpan...';

    fetch(url, { method: 'POST', body: formData })
    .then(res => {
        // Check if response is actually JSON
        const contentType = res.headers.get('content-type');
        if (!res.ok) {
            throw new Error(`HTTP ${res.status}: ${res.statusText}`);
        }
        if (!contentType || !contentType.includes('application/json')) {
            return res.text().then(text => {
                throw new Error('Server returned non-JSON response: ' + text.substring(0, 200));
            });
        }
        return res.json();
    })
    .then(data => {
        if (data.status === 'success' || data.code === 200) {
            closeModal();
            loadData();
            showSuccess(id ? 'SOP berhasil diperbarui!' : 'SOP baru berhasil ditambahkan!');
        } else {
            showError('Gagal: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(err => {
        console.error('SOP Save Error:', err);
        showError('Error System: ' + err.message);
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});

function deleteData(id) {
    confirmDelete(() => {
        fetch(ENDPOINT_SOP + '/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                loadData();
                showSuccess('SOP berhasil dihapus!');
            } else {
                showError('Gagal menghapus SOP');
            }
        })
        .catch(err => {
            showError('Error: ' + err.message);
        });
    }, 'Apakah Anda yakin ingin menghapus SOP ini?');
}

function closeModal() {
    document.getElementById('formModal').classList.add('hidden');
}

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>"']/g, function(m) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
    });
}
</script>