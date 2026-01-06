<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-file-alt text-blue-600"></i> 
        Format Penulisan Tugas
    </h1>
    
    <button onclick="openFormModal()" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5">
        <i class="fas fa-plus"></i> Tambah Format
    </button>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <span class="text-sm text-gray-500 font-medium">Daftar Pedoman & Unduhan</span>
        <span id="totalData" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Total: 0</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold w-64">Judul & Ikon</th>
                    <th class="px-6 py-4 font-semibold">Konten / Deskripsi</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-gray-200 text-gray-700 text-sm">
                <!-- Data loaded via JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
<div id="formModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-xl border border-gray-100">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 id="formModalTitle" class="text-xl font-bold text-gray-800"></h3>
                <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <form id="formatForm">
                    <input type="hidden" id="inputId" name="id_format">
                    
                    <div class="grid grid-cols-1 gap-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Judul / Nama <span class="text-red-500">*</span></label>
                                <input type="text" id="inputJudul" name="judul" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                                       placeholder="Contoh: Teknik Penulisan">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                                <select id="inputKategori" name="kategori" required onchange="toggleFormFields(this.value)"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                    <option value="pedoman">Pedoman (Informasi Card)</option>
                                    <option value="unduhan">Unduhan (File/Link)</option>
                                </select>
                            </div>
                        </div>

                        <div id="sectionPedoman" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        Icon (Remix Icon) 
                                        <a href="https://remixicon.com/" target="_blank" class="text-xs text-blue-500 hover:underline font-normal ml-1">
                                            <i class="ri-external-link-line"></i> Cari Icon
                                        </a>
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="inputIcon" name="icon" onkeyup="updateIconPreview(this.value)"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none pr-10"
                                               placeholder="ri-edit-2-line">
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                            <i id="previewIcon" class="ri-question-line"></i>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-1 italic">Contoh: ri-layout-line, ri-pencil-line</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Warna Icon</label>
                                    <select id="inputWarna" name="warna" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="icon-blue">Blue</option>
                                        <option value="icon-pink">Pink</option>
                                        <option value="icon-red">Red</option>
                                        <option value="icon-orange">Orange</option>
                                        <option value="icon-emerald">Emerald</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi / Konten (Ganti baris = poin bullet)</label>
                                <textarea id="inputDeskripsi" name="deskripsi" rows="4" 
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                                          placeholder="Tulis poin-poin pedoman di sini..."></textarea>
                            </div>
                        </div>

                        <div id="sectionUnduhan" class="space-y-4 hidden">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih File (PDF/Docs)</label>
                                    <input type="file" id="inputFile" name="file"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                    <p id="currentFile" class="text-xs text-blue-600 mt-1 hidden"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Atau Link Eksternal (Opsional)</label>
                                    <input type="url" id="inputLink" name="link_external"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                                           placeholder="https://gdrive.com/...">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Urutan Tampil</label>
                                <input type="number" id="inputUrutan" name="urutan" value="0"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                    </div>

                    <div id="formMessage" class="hidden mt-4"></div>

                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                        <button type="button" onclick="closeModal('formModal')" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-medium">Batal</button>
                        <button type="submit" id="btnSave" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium shadow-sm hover:bg-blue-700 transition-colors">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let allData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadData();
});

function loadData() {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-circle-notch fa-spin text-2xl mb-2"></i><p>Memuat data...</p></td></tr>`;
    
    fetch(API_URL + '/formatpenulisan')
    .then(res => res.json())
    .then(response => {
        if((response.status === 'success' || response.code === 200) && response.data) {
            allData = response.data;
            renderTable(allData);
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
    document.getElementById('totalData').innerText = `Total: ${data.length}`;
    
    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">Data tidak ditemukan</td></tr>`;
        return;
    }
    
    tbody.innerHTML = data.map((item, index) => {
        const isPedoman = item.kategori === 'pedoman';
        const linkDisplay = item.link_external ? `<a href="${item.link_external}" target="_blank" class="text-blue-500 underline ml-2">Link</a>` : '';
        const fileDisplay = item.file ? `<span class="bg-gray-100 px-2 py-0.5 rounded ml-2">📎 ${item.file}</span>` : '';

        return `
            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
                <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800">${escapeHtml(item.judul)}</div>
                    <div class="text-[10px] text-gray-500 italic">
                        <span class="px-1.5 py-0.5 rounded ${isPedoman ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600'}">
                            ${isPedoman ? 'Pedoman' : 'Unduhan'}
                        </span>
                        | Urutan: ${item.urutan} 
                        ${isPedoman ? `| <i class="${item.icon}"></i> ${item.warna}` : ''}
                    </div>
                </td>
                <td class="px-6 py-4 text-xs text-gray-600">
                    ${isPedoman ? (item.deskripsi ? item.deskripsi.substring(0, 100) + '...' : '-') : (fileDisplay + linkDisplay)}
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center gap-2">
                        <button onclick="openFormModal(${item.id_format})" class="w-8 h-8 rounded bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-colors flex items-center justify-center">
                            <i class="fas fa-pen text-xs"></i>
                        </button>
                        <button onclick="deleteData(${item.id_format})" class="w-8 h-8 rounded bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function openFormModal(id = null) {
    const modal = document.getElementById('formModal');
    const form = document.getElementById('formatForm');
    const title = document.getElementById('formModalTitle');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    form.reset();
    document.getElementById('inputId').value = '';
    document.getElementById('formMessage').classList.add('hidden');
    document.getElementById('currentFile').classList.add('hidden');

    if (id) {
        title.innerText = 'Edit Format Penulisan';
        const data = allData.find(i => i.id_format == id);
        if (data) {
            document.getElementById('inputId').value = data.id_format;
            document.getElementById('inputJudul').value = data.judul;
            document.getElementById('inputUrutan').value = data.urutan;
            document.getElementById('inputKategori').value = data.kategori || 'pedoman';
            document.getElementById('inputIcon').value = data.icon || '';
            document.getElementById('inputWarna').value = data.warna || 'icon-blue';
            document.getElementById('inputDeskripsi').value = data.deskripsi || '';
            document.getElementById('inputLink').value = data.link_external || '';
            
            if (data.file) {
                const cf = document.getElementById('currentFile');
                cf.innerText = 'File saat ini: ' + data.file;
                cf.classList.remove('hidden');
            }
            
            updateIconPreview(data.icon);
            toggleFormFields(data.kategori || 'pedoman');
        }
    } else {
        title.innerText = 'Tambah Format Baru';
        updateIconPreview('');
        toggleFormFields('pedoman');
    }
}

function toggleFormFields(val) {
    const sectionPedoman = document.getElementById('sectionPedoman');
    const sectionUnduhan = document.getElementById('sectionUnduhan');
    
    if (val === 'pedoman') {
        sectionPedoman.classList.remove('hidden');
        sectionUnduhan.classList.add('hidden');
    } else {
        sectionPedoman.classList.add('hidden');
        sectionUnduhan.classList.remove('hidden');
    }
}

function updateIconPreview(val) {
    const preview = document.getElementById('previewIcon');
    if (val && val.trim() !== '') {
        preview.className = val;
    } else {
        preview.className = 'ri-question-line';
    }
}

document.getElementById('formatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('inputId').value;
    const url = id ? API_URL + '/formatpenulisan/' + id : API_URL + '/formatpenulisan';
    
    const formData = new FormData(this);
    if (id) formData.append('_method', 'PUT');

    const btn = document.getElementById('btnSave');
    btn.disabled = true;
    btn.innerText = 'Menyimpan...';

    fetch(url, {
        method: 'POST', 
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.code === 200) {
            closeModal('formModal');
            loadData();
            if (typeof showSuccess === 'function') {
                showSuccess('Berhasil menyimpan data');
            } else {
                alert('Berhasil menyimpan data');
            }
        } else {
            throw new Error(data.message || 'Gagal menyimpan data');
        }
    })
    .catch(err => {
        if (typeof showError === 'function') {
            showError(err.message);
        } else {
            alert('Error: ' + err.message);
        }
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerText = 'Simpan Data';
    });
});

function deleteData(id) {
    if(confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        fetch(API_URL + '/formatpenulisan/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success' || res.code === 200) {
                loadData();
                if (typeof showSuccess === 'function') {
                    showSuccess('Data berhasil dihapus');
                } else {
                    alert('Data berhasil dihapus');
                }
            } else {
                if (typeof showError === 'function') {
                    showError(res.message);
                } else {
                    alert('Error: ' + res.message);
                }
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat menghapus data');
        });
    }
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function escapeHtml(text) {
    if (!text) return '';
    const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return text.replace(/[&<>"']/g, m => map[m]);
}
</script>
