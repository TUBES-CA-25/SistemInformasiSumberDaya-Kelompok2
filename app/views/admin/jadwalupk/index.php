<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4 animate__animated animate__fadeIn">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-calendar-check text-blue-600"></i> 
        Manajemen Jadwal UPK
    </h1>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" id="searchInput" placeholder="Cari MK, Dosen, Kelas..." 
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow text-sm">
        </div>

        <button onclick="openUploadModal()" 
           class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
            <i class="fas fa-file-csv"></i> Upload CSV
        </button>

        <button onclick="openFormModal()" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
            <i class="fas fa-plus"></i> Tambah Jadwal
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500 font-medium">Daftar Jadwal UPK</span>
            <div id="bulkActions" class="hidden flex items-center gap-2 animate-fade-in">
                <span class="text-xs text-gray-400">|</span>
                <span id="selectedCount" class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded">0 terpilih</span>
                <button onclick="bulkDelete()" class="text-xs font-bold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-lg transition-colors flex items-center gap-1">
                    <i class="fas fa-trash-alt"></i> Hapus Terpilih
                </button>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span id="totalData" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Total: 0</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold text-center w-8">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </th>
                    <th class="px-6 py-4 font-semibold text-center w-12">No</th>
                    <th class="px-6 py-4 font-semibold">Mata Kuliah & Dosen</th>
                    <th class="px-6 py-4 font-semibold text-center">Prodi</th>
                    <th class="px-6 py-4 font-semibold">Waktu & Tanggal</th>
                    <th class="px-6 py-4 font-semibold text-center w-24">Kelas</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Ruangan</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-gray-200 text-gray-700 text-sm">
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
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
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-2xl border border-gray-100">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 z-10">
                <h3 id="formModalTitle" class="text-xl font-bold text-gray-800 flex items-center gap-2">Tambah Jadwal Baru</h3>
                <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600 transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6">
                <form id="jadwalForm" class="space-y-5">
                    <input type="hidden" id="inputId" name="id">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Program Studi</label>
                            <select id="inputProdi" name="prodi" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                <option value="TI">Teknik Informatika</option>
                                <option value="SI">Sistem Informasi</option>
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mata Kuliah</label>
                            <input type="text" id="inputMK" name="mata_kuliah" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Dosen Pengampu</label>
                            <input type="text" id="inputDosen" name="dosen" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal</label>
                            <input type="date" id="inputTanggal" name="tanggal" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Waktu (Jam)</label>
                            <input type="text" id="inputJam" name="jam" placeholder="08.00 - 10.00" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Ruangan</label>
                            <input type="text" id="inputRuangan" name="ruangan" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kelas</label>
                            <input type="text" id="inputKelas" name="kelas" placeholder="A1" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Frekuensi</label>
                            <input type="text" id="inputFreq" name="frekuensi" placeholder="TI_SD-1" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-6">
                        <button type="button" onclick="closeModal('formModal')" class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-lg font-semibold hover:bg-gray-200 transition-all">Batal</button>
                        <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white rounded-lg font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="uploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full max-w-md border border-gray-100">
            <div class="bg-emerald-50 px-6 py-4 border-b border-emerald-100 flex justify-between items-center bg-emerald-50/50">
                <h3 class="text-xl font-bold text-emerald-800">Import Jadwal Excel / CSV</h3>
                <button onclick="closeModal('uploadModal')" class="text-emerald-400 hover:text-emerald-600 transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-8 text-center">
                <form action="<?= PUBLIC_URL ?>/admin/jadwalupk/upload" method="POST" enctype="multipart/form-data">
                    <label for="file_csv" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-emerald-200 rounded-3xl cursor-pointer bg-emerald-50/30 hover:bg-emerald-50 transition-all mb-6 group">
                        <div class="w-16 h-16 bg-emerald-100 text-emerald-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-cloud-upload-alt text-2xl"></i>
                        </div>
                        <p class="text-sm text-slate-600">Klik untuk pilih file <b>.csv</b></p>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-tighter">Gunakan format pemisah koma (,)</p>
                        <input id="file_csv" name="file_csv" type="file" class="hidden" accept=".csv" required />
                    </label>
                    <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 uppercase tracking-widest text-xs">Mulai Import Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * PERBAIKAN: CAKUPAN GLOBAL FUNGSI
 * Semua fungsi diletakkan di window agar bisa dipanggil oleh 'onclick' HTML
 */

let allJadwal = [];

window.openModal = function(id) {
    const modal = document.getElementById(id);
    if(modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
};

window.closeModal = function(id) {
    const modal = document.getElementById(id);
    if(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
};

window.openUploadModal = function() { openModal('uploadModal'); };

window.openFormModal = function(id = null, event = null) {
    if(event) event.stopPropagation();
    openModal('formModal');
    const title = document.getElementById('formModalTitle');
    const form = document.getElementById('jadwalForm');
    form.reset();
    document.getElementById('inputId').value = '';
    
    if(id) {
        title.innerHTML = '<i class="fas fa-edit text-blue-600 mr-2"></i> Edit Jadwal UPK';
        const data = allJadwalData.find(i => i.id == id);
        if(data) {
            document.getElementById('inputId').value = data.id;
            document.getElementById('inputProdi').value = data.prodi;
            document.getElementById('inputMK').value = data.mata_kuliah;
            document.getElementById('inputDosen').value = data.dosen;
            document.getElementById('inputTanggal').value = data.tanggal;
            document.getElementById('inputJam').value = data.jam;
            document.getElementById('inputRuangan').value = data.ruangan;
            document.getElementById('inputKelas').value = data.kelas;
            document.getElementById('inputFreq').value = data.frekuensi;
        }
    } else {
        title.innerHTML = '<i class="fas fa-plus text-blue-600 mr-2"></i> Tambah Jadwal Baru';
    }
};

window.hapusJadwal = function(id, event = null) {
    if(event) event.stopPropagation();
    Swal.fire({
        title: 'Hapus data?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `<?= PUBLIC_URL ?>/admin/jadwalupk/delete/${id}`;
        }
    });
};

async function loadJadwal() {
    const tbody = document.getElementById('tableBody');
    try {
        const response = await fetch('<?= PUBLIC_URL ?>/api.php/jadwal-upk');
        const result = await response.json();
        if(result.status === 'success') {
            allJadwalData = result.data;
            renderTable(allJadwalData);
        }
    } catch (err) {
        console.error("API Error:", err);
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-red-400 py-10 font-bold">Gagal sinkronisasi API.</td></tr>`;
    }
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    document.getElementById('totalData').innerText = `Total: ${data.length}`;
    
    // Reset Select All
    const selectAll = document.getElementById('selectAll');
    if(selectAll) selectAll.checked = false;
    updateBulkActionsVisibility();

    if(!data || data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="px-6 py-20 text-center text-gray-500"><i class="fas fa-search text-2xl mb-2"></i><p>Tidak ada data ditemukan</p></td></tr>`;
        return;
    }

    let rowsHtml = '';
    data.forEach((item, index) => {
        const tgl = new Date(item.tanggal).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
        rowsHtml += `
            <tr class="hover:bg-blue-50 transition-colors duration-150 group border-b border-gray-100">
                <td class="px-6 py-4 text-center">
                    <input type="checkbox" value="${item.id}" 
                           onchange="updateBulkActionsVisibility()" 
                           onclick="event.stopPropagation()"
                           class="row-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                </td>
                <td class="px-6 py-4 text-center text-gray-400 font-medium font-mono text-xs">${index + 1}</td>
                <td class="px-6 py-4 cursor-pointer" onclick="openFormModal(${item.id}, event)">
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-800 text-sm group-hover:text-blue-600 transition-colors">${item.mata_kuliah}</span>
                        <span class="text-xs text-gray-500 flex items-center gap-1"><i class="fas fa-user-tie text-[10px]"></i> ${item.dosen}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-center cursor-pointer" onclick="openFormModal(${item.id}, event)">
                    <span class="text-[10px] font-bold px-2 py-0.5 bg-gray-100 text-gray-600 rounded border border-gray-200 uppercase tracking-tight">${item.prodi}</span>
                </td>
                <td class="px-6 py-4 text-center cursor-pointer" onclick="openFormModal(${item.id}, event)">
                    <div class="flex flex-col items-center">
                        <span class="font-bold text-gray-700 text-sm">${tgl}</span>
                        <span class="text-xs text-blue-600 flex items-center gap-1 font-medium">
                            <i class="far fa-clock"></i> ${item.jam}
                        </span>
                    </div>
                </td>
                <td class="px-6 py-4 text-center cursor-pointer" onclick="openFormModal(${item.id}, event)">
                    <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold border border-blue-100">${item.kelas}</span>
                </td>
                <td class="px-6 py-4 text-center cursor-pointer" onclick="openFormModal(${item.id}, event)">
                    <span class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded text-xs font-bold border border-emerald-100">
                        ${item.ruangan}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center items-center gap-2">
                        <button onclick="openFormModal(${item.id}, event)" class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center shadow-sm" title="Edit">
                            <i class="fas fa-pen text-xs"></i>
                        </button>
                        <button onclick="hapusJadwal(${item.id}, event)" class="w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center shadow-sm" title="Hapus">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </td>
            </tr>`;
    });
    tbody.innerHTML = rowsHtml;
}

window.updateBulkActionsVisibility = function() {
    const checked = document.querySelectorAll('.row-checkbox:checked').length;
    const actions = document.getElementById('bulkActions');
    const countSpan = document.getElementById('selectedCount');
    
    if (countSpan) countSpan.innerText = `${checked} terpilih`;
    checked > 0 ? actions.classList.remove('hidden') : actions.classList.add('hidden');
};

window.bulkDelete = function() {
    const selected = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
    if(selected.length === 0) return;

    Swal.fire({
        title: `Hapus ${selected.length} data?`,
        text: "Data yang dipilih akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus Semua!',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch('<?= API_URL ?>/jadwal-upk/delete-multiple', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ids: selected })
                });
                const res = await response.json();
                if(res.status === 'success') {
                    Swal.fire('Berhasil!', 'Data pilihan telah dihapus.', 'success');
                    loadJadwal();
                    document.getElementById('selectAll').checked = false;
                    updateBulkActionsVisibility();
                }
            } catch (err) {
                Swal.fire('Error', 'Gagal menghapus data.', 'error');
            }
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    loadJadwal();
    
    // Search Handler
    document.getElementById('searchInput').addEventListener('keyup', (e) => {
        const key = e.target.value.toLowerCase();
        const filtered = allJadwalData.filter(i => 
            i.mata_kuliah.toLowerCase().includes(key) || 
            i.dosen.toLowerCase().includes(key)
        );
        renderTable(filtered);
    });

    // Select All Handler
    document.getElementById('selectAll').addEventListener('change', function() {
        const checks = document.querySelectorAll('.row-checkbox');
        checks.forEach(c => c.checked = this.checked);
        updateBulkActionsVisibility();
    });

    // Form Submit Handler
    document.getElementById('jadwalForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('inputId').value;
        const method = id ? 'PUT' : 'POST';
        const url = id ? `<?= API_URL ?>/jadwal-upk/${id}` : `<?= API_URL ?>/jadwal-upk`;
        
        const data = Object.fromEntries(new FormData(this));

        try {
            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const res = await response.json();
            if(res.status === 'success' || res.code === 200) {
                closeModal('formModal');
                loadJadwal();
                Swal.fire('Berhasil!', 'Data telah disimpan.', 'success');
            }
        } catch (err) {
            Swal.fire('Error', 'Gagal menyimpan data.', 'error');
        }
    });
});
</script>