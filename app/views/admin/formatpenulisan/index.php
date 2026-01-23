<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-file-alt text-blue-600"></i> 
        Format Penulisan Tugas
    </h1>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" id="searchInput" placeholder="Cari format / pedoman..." 
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow text-sm">
        </div>

        <button onclick="openFormModal()" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
            <i class="fas fa-plus"></i> Tambah Format
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500 font-medium">Daftar Pedoman & Unduhan</span>
            <select id="filterKategori" onchange="filterData()" class="text-xs border border-gray-300 rounded-lg px-3 py-1.5 outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="all">Semua Kategori</option>
                <option value="pedoman">Pedoman Penulisan</option>
                <option value="unduhan">Pusat Unduhan</option>
            </select>
        </div>
        <span id="totalData" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Total: 0</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold w-64">Judul</th>
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
                    
                    <div class="space-y-6">
                        <!-- Informasi Utama -->
                        <div class="bg-blue-50/30 p-4 rounded-xl border border-blue-100/50 space-y-4">
                            <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i> Informasi Utama
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul / Nama <span class="text-red-500">*</span></label>
                                    <input type="text" id="inputJudul" name="judul" required
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none bg-white shadow-sm"
                                           placeholder="Contoh: Teknik Penulisan Laporan">
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                                    <select id="inputKategori" name="kategori" required onchange="toggleFormFields(this.value)"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none bg-white shadow-sm font-medium">
                                        <option value="pedoman">Pedoman (Card Informasi)</option>
                                        <option value="unduhan">Unduhan (File/Link Eksternal)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Konfigurasi Pedoman -->
                        <div id="sectionPedoman" class="p-4 rounded-xl border border-gray-100 bg-gray-50/50 space-y-4">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-palette"></i> Konten Pedoman
                            </h4>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi / Konten <span class="text-xs font-normal text-gray-400 font-italic">(Enter = Poin baru)</span></label>
                                <textarea id="inputDeskripsi" name="deskripsi" rows="5" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none"
                                          placeholder="Tulis setiap poin pedoman di baris baru..."></textarea>
                            </div>
                        </div>

                        <!-- Konfigurasi Unduhan -->
                        <div id="sectionUnduhan" class="p-4 rounded-xl border border-amber-100 bg-amber-50/30 space-y-4 hidden">
                            <h4 class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-file-download"></i> Pengaturan File
                            </h4>
                            <div class="grid grid-cols-1 gap-4">
                                <div class="p-3 bg-white border border-amber-200/50 rounded-lg">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Unggah Berkas Baru</label>
                                    <input type="file" id="inputFile" name="file"
                                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-all">
                                    <div id="currentFile" class="mt-2 flex items-center gap-2 p-2 bg-blue-50 rounded-lg text-xs text-blue-700 hidden">
                                        <i class="fas fa-paperclip"></i>
                                        <span id="currentFileName"></span>
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-2 font-italic">* Biarkan kosong jika tidak ingin mengganti file yang sudah ada</p>
                                </div>
                                <div class="separator flex items-center gap-3 py-1">
                                    <div class="h-[1px] bg-amber-200 flex-1"></div>
                                    <span class="text-[10px] font-bold text-amber-400 italic">ATAU</span>
                                    <div class="h-[1px] bg-amber-200 flex-1"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tautan Eksternal (Google Drive/Lainnya)</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                            <i class="fas fa-link"></i>
                                        </div>
                                        <input type="url" id="inputLink" name="link_external"
                                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                                               placeholder="https://drive.google.com/...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="formMessage" class="hidden mt-4"></div>

                    <div class="flex justify-end gap-3 pt-6 mt-8 border-t border-gray-100">
                        <button type="button" onclick="closeModal('formModal')" 
                                class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-semibold hover:bg-gray-200 transition-all active:scale-95">
                            Batal
                        </button>
                        <button type="submit" id="btnSave" 
                                class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-semibold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all transform active:scale-95 flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
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

    // Live Search
    document.getElementById('searchInput').addEventListener('keyup', filterData);
});

function filterData() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const kategori = document.getElementById('filterKategori').value;
    
    const filtered = allData.filter(item => {
        const matchesSearch = (item.judul && item.judul.toLowerCase().includes(keyword)) ||
                            (item.kategori && item.kategori.toLowerCase().includes(keyword)) ||
                            (item.deskripsi && item.deskripsi.toLowerCase().includes(keyword));
        
        const matchesKategori = kategori === 'all' || item.kategori === kategori;
        
        return matchesSearch && matchesKategori;
    });
    
    renderTable(filtered);
}

function loadData() {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-circle-notch fa-spin text-2xl mb-2"></i><p>Memuat data...</p></td></tr>`;
    
    fetch(API_URL + '/formatpenulisan')
    .then(res => res.json())
    .then(response => {
        if((response.status === 'success' || response.code === 200) && response.data) {
            allData = response.data;
            filterData();
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
    
    let html = '';
    let lastCategory = '';
    let categoryIndex = 0;

    data.forEach((item) => {
        const isPedoman = item.kategori === 'pedoman';
        
        // Tambahkan Header Grup jika kategori berganti
        if (item.kategori !== lastCategory) {
            lastCategory = item.kategori;
            categoryIndex = 0; // Reset index per kategori
            html += `
                <tr class="bg-gray-100/80">
                    <td colspan="4" class="px-6 py-2 text-[11px] font-bold text-gray-600 uppercase tracking-widest">
                        <i class="fas ${isPedoman ? 'fa-book' : 'fa-download'} mr-1"></i> 
                        Kelompok: ${isPedoman ? 'Pedoman Penulisan' : 'Pusat Unduhan'}
                    </td>
                </tr>
            `;
        }

        categoryIndex++;
        const linkDisplay = item.link_external ? `<a href="${item.link_external}" target="_blank" class="text-blue-500 underline ml-2"><i class="fas fa-external-link-alt text-[10px]"></i> Link</a>` : '';
        const fileDisplay = item.file ? `<span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded ml-2 border border-blue-100 text-[10px]">📎 ${item.file}</span>` : '';

        html += `
            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
                <td class="px-6 py-4 text-center font-medium text-gray-400 text-xs">${categoryIndex}</td>
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800">${escapeHtml(item.judul)}</div>
                    <div class="text-[10px] text-gray-500 italic">
                        <span class="px-1.5 py-0.5 rounded ${isPedoman ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600'}">
                            ${isPedoman ? 'Pedoman' : 'Unduhan'}
                        </span>
                    </div>
                </td>
                <td class="px-6 py-4 text-xs text-gray-600">
                    ${isPedoman ? (item.deskripsi ? item.deskripsi.substring(0, 100) + '...' : '-') : (fileDisplay + linkDisplay)}
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center gap-2">
                        <button onclick="openFormModal(${item.id_format})" class="w-8 h-8 rounded bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-colors flex items-center justify-center shadow-sm">
                            <i class="fas fa-pen text-xs"></i>
                        </button>
                        <button onclick="deleteData(${item.id_format})" class="w-8 h-8 rounded bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center shadow-sm">
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
            document.getElementById('inputKategori').value = data.kategori || 'pedoman';
            document.getElementById('inputDeskripsi').value = data.deskripsi || '';
            document.getElementById('inputLink').value = data.link_external || '';
            
            if (data.file) {
                const cf = document.getElementById('currentFile');
                const cfn = document.getElementById('currentFileName');
                if (cfn) {
                    cfn.innerText = data.file;
                } else {
                    cf.innerText = 'File saat ini: ' + data.file;
                }
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
