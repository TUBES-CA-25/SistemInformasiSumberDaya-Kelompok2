<div class="w-full animate__animated animate__fadeIn">
    
    <div class="flex flex-col lg:flex-row justify-between items-center mb-8 gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-200">
                <i class="fas fa-calendar-check text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Jadwal UPK</h1>
                <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Dashboard Administrasi Ujian</p>
            </div>
        </div>
        
        <div class="flex flex-wrap justify-center gap-3 w-full lg:w-auto">
            <div class="relative group w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="searchInput" placeholder="Cari Mata Kuliah..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none text-sm transition-all shadow-sm">
            </div>

            <button onclick="openUploadModal()" 
               class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl shadow-md shadow-emerald-200 transition-all flex items-center gap-2 font-bold text-xs uppercase tracking-wide">
                <i class="fas fa-file-csv"></i> Import CSV
            </button>

            <button onclick="openFormModal()" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-md shadow-blue-200 transition-all flex items-center gap-2 font-bold text-xs uppercase tracking-wide">
                <i class="fas fa-plus"></i> Tambah Jadwal
            </button>
        </div>
    </div>

    <div class="bg-white rounded-[1.5rem] shadow-sm border border-slate-200 overflow-hidden w-full">
        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-white">
            <div class="flex items-center gap-4">
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Daftar Jadwal</span>
                <div id="bulkActions" class="hidden">
                    <button onclick="bulkDelete()" class="text-xs font-bold text-red-500 bg-red-50 px-3 py-1.5 rounded-lg hover:bg-red-100 transition-colors">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus Terpilih (<span id="selectedCount">0</span>)
                    </button>
                </div>
            </div>
            <span id="totalData" class="text-[10px] font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100 uppercase tracking-tighter">Memuat...</span>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse table-auto">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-400 text-[10px] uppercase tracking-[0.15em] font-black border-b border-slate-100">
                        <th class="px-8 py-4 text-center w-8">
                            <input type="checkbox" id="selectAll" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="px-4 py-4 text-center w-12">No</th>
                        <th class="px-6 py-4">Mata Kuliah & Dosen</th>
                        <th class="px-6 py-4 text-center">Prodi</th>
                        <th class="px-6 py-4">Waktu & Tanggal</th>
                        <th class="px-6 py-4 text-center">Kelas</th>
                        <th class="px-6 py-4 text-center">Ruangan</th>
                        <th class="px-8 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-slate-50 text-slate-600 text-sm">
                    <tr>
                        <td colspan="8" class="px-6 py-20 text-center">
                            <i class="fas fa-circle-notch fa-spin text-blue-500 text-3xl mb-3"></i>
                            <p class="text-slate-400 font-medium">Menyinkronkan data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="formModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('formModal')"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-[2rem] bg-white shadow-2xl transition-all w-full max-w-2xl border border-slate-100">
            <div class="bg-slate-50/50 px-8 py-5 border-b border-slate-100 flex justify-between items-center">
                <h3 id="formModalTitle" class="text-xl font-bold text-slate-800">Tambah Jadwal Baru</h3>
                <button onclick="closeModal('formModal')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-8">
                <form id="jadwalForm" class="space-y-5">
                    <input type="hidden" id="inputId" name="id">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Program Studi</label>
                            <select id="inputProdi" name="prodi" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                                <option value="TI">Teknik Informatika</option>
                                <option value="SI">Sistem Informasi</option>
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Mata Kuliah</label>
                            <input type="text" id="inputMK" name="mata_kuliah" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Dosen Pengampu</label>
                            <input type="text" id="inputDosen" name="dosen" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal</label>
                            <input type="date" id="inputTanggal" name="tanggal" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Waktu (Jam)</label>
                            <input type="text" id="inputJam" name="jam" placeholder="08.00 - 10.00" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Ruangan</label>
                            <input type="text" id="inputRuangan" name="ruangan" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Kelas</label>
                            <input type="text" id="inputKelas" name="kelas" placeholder="A1" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Frekuensi</label>
                            <input type="text" id="inputFreq" name="frekuensi" placeholder="TI_SD-1" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-6">
                        <button type="button" onclick="closeModal('formModal')" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-all">Batal</button>
                        <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="uploadModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('uploadModal')"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-[2rem] bg-white shadow-2xl transition-all w-full max-w-md border border-slate-100">
            <div class="bg-emerald-50 px-8 py-5 border-b border-emerald-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-emerald-800">Import Jadwal CSV</h3>
                <button onclick="closeModal('uploadModal')" class="text-emerald-600 hover:text-emerald-800"><i class="fas fa-times text-xl"></i></button>
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

window.openFormModal = function(id = null) {
    openModal('formModal');
    const title = document.getElementById('formModalTitle');
    const form = document.getElementById('jadwalForm');
    form.reset();
    document.getElementById('inputId').value = '';
    
    if(id) {
        title.innerHTML = '<i class="fas fa-edit text-blue-600 mr-2"></i> Edit Jadwal UPK';
        const data = allJadwal.find(i => i.id == id);
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

window.hapusData = function(id) {
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

async function loadData() {
    const tbody = document.getElementById('tableBody');
    try {
        const response = await fetch('<?= PUBLIC_URL ?>/api.php/jadwal-upk');
        const result = await response.json();
        if(result.status === 'success') {
            allJadwal = result.data;
            renderTable(allJadwal);
        }
    } catch (err) {
        console.error("API Error:", err);
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-red-400 py-10 font-bold">Gagal sinkronisasi API.</td></tr>`;
    }
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    document.getElementById('totalData').innerText = `TOTAL: ${data.length} JADWAL`;
    tbody.innerHTML = '';

    if(data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center py-20 text-slate-300 font-bold uppercase tracking-widest text-xs">Belum ada data jadwal</td></tr>`;
        return;
    }

    data.forEach((item, index) => {
        const tgl = new Date(item.tanggal).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
        tbody.innerHTML += `
            <tr class="hover:bg-blue-50/40 transition-all duration-200">
                <td class="px-8 py-5 text-center">
                    <input type="checkbox" value="${item.id}" onchange="updateBulkActionsVisibility()" class="row-checkbox w-4 h-4 rounded border-slate-300 text-blue-600">
                </td>
                <td class="px-4 py-5 text-center text-slate-300 font-mono text-xs">${index + 1}</td>
                <td class="px-6 py-5">
                    <div class="flex flex-col">
                        <span class="font-bold text-slate-800 text-sm mb-0.5">${item.mata_kuliah}</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">${item.dosen}</span>
                    </div>
                </td>
                <td class="px-6 py-5 text-center">
                    <span class="text-[10px] font-black px-2 py-1 bg-slate-100 text-slate-500 rounded border border-slate-200">${item.prodi}</span>
                </td>
                <td class="px-6 py-5">
                    <div class="flex flex-col">
                        <span class="font-bold text-slate-700 text-sm">${tgl}</span>
                        <span class="text-xs text-blue-600 font-black mt-0.5 tracking-tighter"><i class="far fa-clock mr-1"></i>${item.jam}</span>
                    </div>
                </td>
                <td class="px-6 py-5 text-center">
                    <div class="flex flex-col items-center">
                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-xl text-[10px] font-black border border-blue-100">${item.kelas}</span>
                        <span class="text-[8px] text-slate-300 mt-1 font-bold">${item.frekuensi || '-'}</span>
                    </div>
                </td>
                <td class="px-6 py-5 text-center">
                    <span class="bg-emerald-50 text-emerald-600 px-4 py-2 rounded-2xl text-xs font-black border border-emerald-100 shadow-sm shadow-emerald-50">
                        ${item.ruangan}
                    </span>
                </td>
                <td class="px-8 py-5 text-center">
                    <div class="flex justify-center gap-2">
                        <button onclick="openFormModal(${item.id})" class="w-9 h-9 rounded-2xl bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center border border-amber-100 shadow-sm">
                            <i class="fas fa-pen text-[10px]"></i>
                        </button>
                        <button onclick="hapusData(${item.id})" class="w-9 h-9 rounded-2xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center border border-red-100 shadow-sm">
                            <i class="fas fa-trash-alt text-[10px]"></i>
                        </button>
                    </div>
                </td>
            </tr>`;
    });
}

window.updateBulkActionsVisibility = function() {
    const checked = document.querySelectorAll('.row-checkbox:checked').length;
    const actions = document.getElementById('bulkActions');
    document.getElementById('selectedCount').innerText = checked;
    checked > 0 ? actions.classList.remove('hidden') : actions.classList.add('hidden');
};

document.addEventListener('DOMContentLoaded', () => {
    loadData();
    
    // Search Handler
    document.getElementById('searchInput').addEventListener('keyup', (e) => {
        const key = e.target.value.toLowerCase();
        const filtered = allJadwal.filter(i => 
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
                loadData();
                Swal.fire('Berhasil!', 'Data telah disimpan.', 'success');
            }
        } catch (err) {
            Swal.fire('Error', 'Gagal menyimpan data.', 'error');
        }
    });
});
</script>