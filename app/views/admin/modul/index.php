<div class="p-8 w-full">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-folder-open text-blue-600"></i> 
            Manajemen Modul Praktikum
        </h1>
        
        <div class="flex gap-3">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="searchInput" placeholder="Cari Modul..." 
                       class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm w-64">
            </div>

            <button onclick="openFormModal()" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all flex items-center gap-2 font-medium">
                <i class="fas fa-plus"></i> Tambah Modul
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                        <th class="px-6 py-4 font-semibold w-32">Jurusan</th>
                        <th class="px-6 py-4 font-semibold w-64">Mata Kuliah</th>
                        <th class="px-6 py-4 font-semibold w-64">Judul Modul</th>
                        <th class="px-6 py-4 font-semibold">File Dokumen</th>
                        <th class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-gray-200 text-gray-700 text-sm">
                </tbody>
            </table>
        </div>
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
                <form id="modulForm">
                    <input type="hidden" id="inputId" name="id_modul">
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jurusan <span class="text-red-500">*</span></label>
                            <select name="jurusan" id="inputJurusan" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="TI">Teknik Informatika</option>
                                <option value="SI">Sistem Informasi</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Mata Kuliah <span class="text-red-500">*</span></label>
                            <input type="text" id="inputMk" name="nama_matakuliah" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                                   placeholder="Contoh: Pemrograman Web">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Modul <span class="text-red-500">*</span></label>
                            <input type="text" id="inputJudul" name="judul" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                                   placeholder="Contoh: Modul 1 - HTML Dasar">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Upload File Modul (PDF) <span class="text-red-500">*</span></label>
                            <input type="file" id="inputFile" name="file" accept=".pdf"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                            <div id="currentFileBox" class="mt-2 text-xs text-green-600 font-medium hidden">
                                <i class="fas fa-check-circle"></i> File tersimpan: <span id="currentFileName" class="break-all"></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                        <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-semibold hover:bg-gray-200">Batal</button>
                        <button type="submit" id="btnSave" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan Modul
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let allModul = [];
const API_MODUL = '<?= PUBLIC_URL ?>/modul';

document.addEventListener('DOMContentLoaded', function() {
    loadData();
    document.getElementById('searchInput').addEventListener('keyup', renderTable);
});

async function loadData() {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-circle-notch fa-spin text-2xl mb-2"></i><p>Memuat data...</p></td></tr>`;
    
    try {
        const res = await fetch(API_MODUL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const response = await res.json();
        if(response.status === 'success' && response.data) {
            allModul = response.data;
            renderTable();
        } else {
            tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada data modul.</td></tr>`;
        }
    } catch (err) {
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-red-500">Gagal koneksi ke server</td></tr>`;
    }
}

function renderTable() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const filtered = allModul.filter(item => {
        return (item.nama_matakuliah || "").toLowerCase().includes(keyword) || 
               (item.judul || "").toLowerCase().includes(keyword) ||
               (item.jurusan || "").toLowerCase().includes(keyword);
    });
    
    const tbody = document.getElementById('tableBody');
    if (filtered.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Data tidak ditemukan</td></tr>`;
        return;
    }

    tbody.innerHTML = filtered.map((m, i) => `
        <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
            <td class="px-6 py-4 text-center font-medium text-gray-400 text-xs">${i + 1}</td>
            <td class="px-6 py-4">
                <span class="px-3 py-1 ${m.jurusan === 'TI' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600'} rounded-full text-[10px] font-black tracking-widest uppercase">
                    ${m.jurusan}
                </span>
            </td>
            <td class="px-6 py-4 font-bold text-gray-800 text-sm">${escapeHtml(m.nama_matakuliah)}</td>
            <td class="px-6 py-4 text-gray-600 text-sm">${escapeHtml(m.judul)}</td>
            <td class="px-6 py-4">
                ${m.file ? 
                    `<a href="<?= PUBLIC_URL ?>/assets/uploads/modul/${m.file}" target="_blank" class="flex items-center gap-2 text-xs font-medium text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full hover:bg-blue-100 w-fit">
                        <i class="fas fa-file-pdf"></i> Lihat PDF
                    </a>` : 
                    '<span class="text-gray-400 text-xs italic">Tidak ada file</span>'}
            </td>
            <td class="px-6 py-4 text-center">
                <div class="flex justify-center gap-2">
                    <button onclick="openFormModal(${m.id_modul})" class="w-8 h-8 rounded bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center">
                        <i class="fas fa-pen text-xs"></i>
                    </button>
                    <button onclick="deleteData(${m.id_modul})" class="w-8 h-8 rounded bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function openFormModal(id = null) {
    const modal = document.getElementById('formModal');
    const form = document.getElementById('modulForm');
    modal.classList.remove('hidden');
    form.reset();
    document.getElementById('inputId').value = '';
    document.getElementById('currentFileBox').classList.add('hidden');

    if (id) {
        document.getElementById('formModalTitle').innerText = 'Edit Modul';
        const data = allModul.find(i => i.id_modul == id);
        if (data) {
            document.getElementById('inputId').value = data.id_modul;
            document.getElementById('inputJurusan').value = data.jurusan;
            document.getElementById('inputMk').value = data.nama_matakuliah;
            document.getElementById('inputJudul').value = data.judul;
            if (data.file) {
                document.getElementById('currentFileBox').classList.remove('hidden');
                document.getElementById('currentFileName').innerText = data.file;
            }
        }
    } else {
        document.getElementById('formModalTitle').innerText = 'Tambah Modul Baru';
    }
}

document.getElementById('modulForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const id = document.getElementById('inputId').value;
    const formData = new FormData(this);
    if (id) formData.append('_method', 'PUT');

    const btn = document.getElementById('btnSave');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    try {
        const res = await fetch(id ? API_MODUL + '/' + id : API_MODUL, { 
            method: 'POST', 
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        if (data.status === 'success') {
            closeModal();
            loadData();
            showSuccess(id ? 'Modul berhasil diperbarui!' : 'Modul baru berhasil ditambahkan!');
        } else {
            showError('Gagal: ' + (data.message || 'Terjadi kesalahan'));
        }
    } catch (err) {
        showError('Error System: ' + err.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});

async function deleteData(id) {
    confirmDelete(async () => {
        try {
            const res = await fetch(API_MODUL + '/' + id, { 
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const result = await res.json();
            if(result.status === 'success') {
                loadData();
                showSuccess('Modul berhasil dihapus!');
            } else {
                showError('Gagal menghapus modul');
            }
        } catch (err) {
            showError('Error: ' + err.message);
        }
    }, 'Yakin ingin menghapus modul ini?');
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