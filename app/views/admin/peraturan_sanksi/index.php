<style>
    .desc-container {
        max-height: 80px;
        overflow-y: auto;
        padding-right: 8px;
    }
    .desc-container::-webkit-scrollbar {
        width: 4px;
    }
    .desc-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .desc-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    .desc-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-gavel text-blue-600"></i> 
        Peraturan & Sanksi Lab
    </h1>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" id="searchInput" placeholder="Cari peraturan / sanksi..." 
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow text-sm">
        </div>

        <button onclick="openFormModal()" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
            <i class="fas fa-plus"></i> Tambah Data
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500 font-medium">Daftar Aturan Kerja & Sanksi</span>
            <select id="filterTipe" onchange="filterData()" class="text-xs border border-gray-300 rounded-lg px-3 py-1.5 outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="all">Semua Tipe</option>
                <option value="peraturan">Peraturan Saja</option>
                <option value="sanksi">Sanksi Saja</option>
            </select>
        </div>
        <span id="totalData" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Total: 0</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold w-48">Judul Aturan</th>
                    <th class="px-6 py-4 font-semibold text-center w-24">Tipe</th>
                    <th class="px-6 py-4 font-semibold w-32">Kategori</th>
                    <th class="px-6 py-4 font-semibold">Deskripsi</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-gray-200 text-gray-700 text-sm">
                <!-- Data loaded via JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-xl border border-gray-100">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i> Detail Peraturan/Sanksi
                </h3>
                <button onclick="closeModal('detailModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div id="detailContent" class="space-y-4">
                    <!-- Loaded via JS -->
                </div>
                <div class="mt-6 pt-6 border-t border-gray-100 flex justify-end">
                    <button onclick="closeModal('detailModal')" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="formModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-xl border border-gray-100">
            
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 id="formModalTitle" class="text-xl font-bold text-gray-800 flex items-center gap-2"></h3>
                <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <form id="mainForm">
                    <input type="hidden" id="inputId" name="id">
                    <input type="hidden" id="oldTipe">

                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe Data <span class="text-red-500">*</span></label>
                                <select id="inputTipe" name="tipe" required onchange="toggleTipeFields(this.value)"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none bg-white">
                                    <option value="peraturan">Peraturan Lab</option>
                                    <option value="sanksi">Sanksi Pelanggaran</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Urutan Tampil</label>
                                <input type="number" id="inputUrutan" name="urutan" value="0"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                            </div>
                        </div>

                        <div id="groupKategori">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori Peraturan <span class="text-red-500">*</span></label>
                            <select id="inputKategori" name="kategori"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none bg-white">
                                <option value="Kehadiran & Akademik">Kehadiran & Akademik</option>
                                <option value="Standar Pakaian">Standar Pakaian</option>
                                <option value="Larangan Umum">Larangan Umum</option>
                                <option value="umum">Umum</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Format Tampilan <span class="text-red-500">*</span></label>
                            <select id="inputDisplayFormat" name="display_format" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none bg-white">
                                <option value="list">List / Poin-Poin (Dipisah Baris Baru)</option>
                                <option value="plain">Plain / Teks Biasa</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                💡 <strong>List:</strong> Akan menampilkan setiap baris sebagai poin. <strong>Plain:</strong> Akan menampilkan sebagai paragraf biasa.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Aturan <span class="text-red-500">*</span></label>
                            <input type="text" id="inputJudul" name="judul" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none"
                                   placeholder="Contoh: Terlambat Datang Sanksi">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi Detail <span class="text-red-500">*</span></label>
                            <textarea id="inputDeskripsi" name="deskripsi" rows="5" required
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none resize-none"
                                      placeholder="Tuliskan detail poin-poin di sini..."></textarea>
                        </div>
                    </div>

                    <div id="formMessage" class="hidden mt-4"></div>

                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                        <button type="button" onclick="closeModal('formModal')" 
                                class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 font-semibold transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit" id="btnSave" 
                                class="px-8 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold transition-all duration-200 flex items-center justify-center gap-2 shadow-lg shadow-blue-200">
                            <i class="fas fa-save"></i> <span>Simpan Data</span>
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
    loadAllData();
    
    // Live Search
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        filterData();
    });
});

async function loadAllData() {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-circle-notch fa-spin text-2xl mb-2"></i><p>Memuat data...</p></td></tr>`;
    
    try {
        const [resPeraturan, resSanksi] = await Promise.all([
            fetch(API_URL + '/peraturan-lab'),
            fetch(API_URL + '/sanksi-lab')
        ]);

        const dataPeraturan = await resPeraturan.json();
        const dataSanksi = await resSanksi.json();

        let listPeraturan = (dataPeraturan.status === 'success' || dataPeraturan.code === 200) ? dataPeraturan.data : [];
        let listSanksi = (dataSanksi.status === 'success' || dataSanksi.code === 200) ? dataSanksi.data : [];

        // Combine
        allData = [
            ...listPeraturan.map(item => ({ ...item, _tipe: 'peraturan' })),
            ...listSanksi.map(item => ({ ...item, _tipe: 'sanksi' }))
        ];

        // Sort by tipe (to group them) then urutan
        allData.sort((a, b) => {
            if (a._tipe !== b._tipe) return a._tipe.localeCompare(b._tipe);
            return (a.urutan || 0) - (b.urutan || 0);
        });

        renderTable(allData);

    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-red-500">Gagal memuat data</td></tr>`;
    }
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    const totalEl = document.getElementById('totalData');
    if(totalEl) totalEl.innerText = `Total: ${data.length}`;
    tbody.innerHTML = '';
    
    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Data tidak ditemukan</td></tr>`;
        return;
    }
    
    let html = '';
    let lastTipe = '';
    let tipeIndex = 0;

    data.forEach((item) => {
        // Simple grouping logic (assumes data is sorted by _tipe)
        if (item._tipe !== lastTipe) {
            lastTipe = item._tipe;
            tipeIndex = 0;
            const isPeraturan = item._tipe === 'peraturan';
            html += `
                <tr class="bg-gray-100/80">
                    <td colspan="6" class="px-6 py-2 text-[10px] font-bold text-gray-600 uppercase tracking-widest">
                        <i class="fas ${isPeraturan ? 'fa-book text-blue-500' : 'fa-exclamation-triangle text-amber-500'} mr-2"></i> 
                        Kelompok: ${isPeraturan ? 'Peraturan Lab' : 'Sanksi Pelanggaran'}
                    </td>
                </tr>
            `;
        }

        tipeIndex++;
        
        // Format deskripsi menjadi poin-poin (split by newline)
        const deskripsiRaw = item.deskripsi || '';
        const deskripsiPoin = deskripsiRaw.split('\n')
            .filter(line => line.trim() !== '')
            .map(line => `<li>${escapeHtml(line)}</li>`)
            .join('');
            
        const finalDeskripsi = deskripsiPoin 
            ? `<div class="desc-container">
                 <ul class="list-disc pl-4 space-y-1 text-[10px] italic font-serif">${deskripsiPoin}</ul>
               </div>` 
            : '<span class="text-gray-400">-</span>';

        const typeBadge = item._tipe === 'peraturan' 
            ? '<span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-blue-50 text-blue-600 uppercase border border-blue-100">Peraturan</span>'
            : '<span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-600 uppercase border border-amber-100">Sanksi</span>';
        
        html += `
            <tr class="hover:bg-blue-50/50 transition-colors border-b border-gray-100 cursor-pointer group/row" onclick="showDetail('${item._tipe}', ${item.id})">
                <td class="px-6 py-4 text-center font-medium text-gray-400 text-xs">${tipeIndex}</td>
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800 text-sm line-clamp-2 group-hover/row:text-blue-600 transition-colors" title="${escapeHtml(item.judul)}">${escapeHtml(item.judul)}</div>
                    ${item.gambar ? `<div class="text-[9px] text-blue-500 mt-0.5"><i class="fas fa-image mr-1"></i> Gambar: ${item.gambar}</div>` : ''}
                </td>
                <td class="px-6 py-4 text-center">
                    ${typeBadge}
                </td>
                <td class="px-6 py-4">
                    <span class="text-[10px] text-gray-500 font-semibold px-2 py-1 rounded-md bg-gray-100">${escapeHtml(item.kategori || (item._tipe === 'sanksi' ? 'Pelanggaran' : '-'))}</span>
                </td>
                <td class="px-6 py-4 text-gray-600">
                    ${finalDeskripsi}
                </td>
                <td class="px-6 py-4" onclick="event.stopPropagation()">
                    <div class="flex justify-center items-center gap-2">
                        <button onclick="editData('${item._tipe}', ${item.id})" class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm flex items-center justify-center group" title="Edit">
                            <i class="fas fa-pen text-[10px]"></i>
                        </button>
                        <button onclick="hapusData('${item._tipe}', ${item.id})" class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center justify-center group" title="Hapus">
                            <i class="fas fa-trash-alt text-[10px]"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

function filterData() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const tipeFilter = document.getElementById('filterTipe').value;
    
    const filtered = allData.filter(item => {
        const matchSearch = (item.judul && item.judul.toLowerCase().includes(searchTerm)) || 
                           (item.deskripsi && item.deskripsi.toLowerCase().includes(searchTerm));
        const matchTipe = (tipeFilter === 'all' || item._tipe === tipeFilter);
        return matchSearch && matchTipe;
    });
    
    renderTable(filtered);
}

function toggleTipeFields(tipe) {
    const groupKategori = document.getElementById('groupKategori');
    if (tipe === 'peraturan') {
        groupKategori.classList.remove('hidden');
    } else {
        groupKategori.classList.add('hidden');
    }
}

function openFormModal(tipe = 'peraturan', id = null) {
    const modal = document.getElementById('formModal');
    const form = document.getElementById('mainForm');
    const title = document.getElementById('formModalTitle');
    const btnSave = document.getElementById('btnSave');

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('formMessage').classList.add('hidden');
    form.reset();
    document.getElementById('inputId').value = '';
    document.getElementById('oldTipe').value = '';
    
    // Default visibility
    toggleTipeFields('peraturan');

    if (id) {
        title.innerHTML = '<i class="fas fa-edit text-blue-600"></i> Edit Data';
        btnSave.innerHTML = '<i class="fas fa-save"></i> Perbarui Data';
        
        const data = allData.find(i => i._tipe === tipe && i.id == id);
        if (data) {
            document.getElementById('inputId').value = data.id;
            document.getElementById('oldTipe').value = data._tipe;
            document.getElementById('inputTipe').value = data._tipe;
            document.getElementById('inputJudul').value = data.judul;
            document.getElementById('inputDeskripsi').value = data.deskripsi;
            document.getElementById('inputUrutan').value = data.urutan || 0;
            document.getElementById('inputDisplayFormat').value = data.display_format || 'list';
            
            if (data._tipe === 'peraturan') {
                document.getElementById('inputKategori').value = data.kategori || 'Larangan Umum';
                toggleTipeFields('peraturan');
            } else {
                toggleTipeFields('sanksi');
            }
        }
    } else {
        title.innerHTML = '<i class="fas fa-plus text-blue-600"></i> Tambah Peraturan & Sanksi';
        btnSave.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
        document.getElementById('inputTipe').value = tipe;
        document.getElementById('inputDisplayFormat').value = 'list';
        toggleTipeFields(tipe);
    }
}

function editData(tipe, id) {
    openFormModal(tipe, id);
}

document.getElementById('mainForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('formMessage');
    const id = document.getElementById('inputId').value;
    const tipe = document.getElementById('inputTipe').value;
    const oldTipe = document.getElementById('oldTipe').value;

    // Tentukan endpoint berdasarkan tipe
    const endpoint = tipe === 'peraturan' ? '/peraturan-lab' : '/sanksi-lab';
    
    // Jika update dan tipe sama, gunakan endpoint dengan ID
    // Jika create atau tipe berbeda, gunakan endpoint base
    const isUpdate = id && (tipe === oldTipe);
    const url = isUpdate ? (API_URL + endpoint + '/' + id) : (API_URL + endpoint);
    
    // Selalu gunakan POST untuk form submission dengan FormData
    // Router akan mengarahkan POST /endpoint/{id} ke update method
    const method = 'POST';

    // FormData untuk upload file dan data form
    const formData = new FormData(this);
    
    // Debug: log semua FormData entries
    console.log('=== FORM SUBMISSION DEBUG ===');
    console.log('URL:', url);
    console.log('Method:', method);
    console.log('FormData entries:');
    for (let [key, value] of formData.entries()) {
        console.log(`  ${key}:`, value);
    }
    
    // Jika mengubah tipe saat edit, delete data lama terlebih dahulu
    if (id && oldTipe && tipe !== oldTipe) {
        if (!confirm('Anda mengubah tipe data. Data lama akan dihapus dan dibuat baru. Lanjutkan?')) return;
        try {
            const delEndpoint = oldTipe === 'peraturan' ? '/peraturan-lab' : '/sanksi-lab';
            await fetch(API_URL + delEndpoint + '/' + id, { method: 'DELETE' });
        } catch (e) {
            console.error('Failed to delete old record during type change');
        }
    }

    btn.disabled = true; 
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Memproses...';

    try {
        const response = await fetch(url, { 
            method: method, 
            body: formData 
        });
        
        // Debug response
        const responseText = await response.text();
        console.log('Response Status:', response.status);
        console.log('Response Text:', responseText);
        
        // Try parse JSON
        const data = JSON.parse(responseText);
        console.log('Response JSON:', data);

        if (data.status === 'success' || data.code === 200 || data.code === 201) {
            closeModal('formModal');
            loadAllData();
            if (typeof showSuccess === 'function') showSuccess('Data berhasil disimpan!');
            else alert('Data berhasil disimpan!');
        } else {
            throw new Error(data.message || 'Gagal menyimpan data');
        }
    } catch (err) {
        msg.innerHTML = `<div class="bg-red-50 text-red-800 p-3 rounded text-sm mb-4 border border-red-200"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`;
        msg.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
    }
});

function hapusData(tipe, id) {
    // Assuming global confirmDelete function exists
    const doDelete = async () => {
        const endpoint = tipe === 'peraturan' ? '/peraturan-lab' : '/sanksi-lab';
        try {
            const res = await fetch(API_URL + endpoint + '/' + id, { method: 'DELETE' });
            const data = await res.json();
            if (data.status === 'success' || data.code === 200) {
                loadAllData();
                if (typeof showSuccess === 'function') showSuccess('Data berhasil dihapus!');
            }
        } catch (err) {
            console.error(err);
            alert('Gagal menghapus data');
        }
    };

    if (typeof confirmDelete === 'function') {
        confirmDelete(doDelete);
    } else {
        if (confirm('Yakin ingin menghapus data ini?')) doDelete();
    }
}

function showDetail(tipe, id) {
    const data = allData.find(i => i._tipe === tipe && i.id == id);
    if (!data) return;

    const modal = document.getElementById('detailModal');
    const content = document.getElementById('detailContent');
    
    // Format deskripsi berdasarkan display_format
    let deskripsiHtml = '';
    if (data.display_format === 'list') {
        // List format: tampilkan sebagai poin-poin
        const deskripsiPoin = (data.deskripsi || '').split('\n')
            .filter(line => line.trim() !== '')
            .map(line => `<li class="mb-2 font-serif italic">${escapeHtml(line)}</li>`)
            .join('');
        deskripsiHtml = `
            <ul class="list-disc pl-5 text-gray-700 text-sm space-y-2">
                ${deskripsiPoin || '<li class="text-gray-400 italic">Tidak ada deskripsi detail.</li>'}
            </ul>
        `;
    } else {
        // Plain format: tampilkan sebagai paragraf biasa
        deskripsiHtml = `<p class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap">${escapeHtml(data.deskripsi || 'Tidak ada deskripsi detail.')}</p>`;
    }

    // Logic path gambar
    let imgPath = data.gambar;
    if (imgPath && !imgPath.includes('/')) {
        imgPath = (data._tipe === 'peraturan' ? 'peraturan/' : 'sanksi/') + imgPath;
    }

    content.innerHTML = `
        <div class="space-y-5">
            <div class="flex justify-between items-start gap-4">
                <div class="flex-1">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Judul Aturan</h4>
                    <p class="text-gray-900 font-bold text-lg leading-tight">${escapeHtml(data.judul)}</p>
                </div>
                <div class="text-right">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tipe</h4>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold ${data._tipe === 'peraturan' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700'} border border-current uppercase">
                        ${data._tipe === 'peraturan' ? 'Peraturan' : 'Sanksi'}
                    </span>
                </div>
            </div>

            <div class="p-3 bg-blue-50/50 rounded-lg border border-blue-100/50">
                <h4 class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-1">Kategori</h4>
                <p class="text-blue-800 font-semibold text-sm">${escapeHtml(data.kategori || (data._tipe === 'sanksi' ? 'Pelanggaran' : 'Umum'))}</p>
            </div>

            <div class="p-3 bg-purple-50/50 rounded-lg border border-purple-100/50">
                <h4 class="text-[10px] font-bold text-purple-400 uppercase tracking-widest mb-1">Format Tampilan</h4>
                <p class="text-purple-800 font-semibold text-sm">
                    ${data.display_format === 'list' ? '📋 List / Poin-Poin' : '📄 Plain / Teks Biasa'}
                </p>
            </div>

            <div>
                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Isi Detail :</h4>
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 shadow-inner">
                    ${deskripsiHtml}
                </div>
            </div>

            ${data.gambar ? `
            <div>
                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Lampiran Digital</h4>
                <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                    <img src="${ASSETS_URL}/assets/uploads/${imgPath}" 
                         class="w-full h-auto object-cover max-h-64"
                         alt="Lampiran">
                </div>
            </div>` : ''}
        </div>
    `;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
