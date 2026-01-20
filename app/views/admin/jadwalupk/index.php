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
            <i class="fas fa-file-excel"></i> Upload Exel
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
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mata Kuliah <span class="text-red-500">*</span></label>
                            <select id="inputMK" name="mata_kuliah" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                <option value="">-- Pilih Mata Kuliah --</option>
                            </select>
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
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Ruangan <span class="text-red-500">*</span></label>
                            <select id="inputRuangan" name="ruangan" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                <option value="">-- Pilih Ruangan --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kelas</label>
                            <input type="text" id="inputKelas" name="kelas" placeholder="A1" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Frekuensi</label>
                            <input type="text" id="inputFreq" name="frekuensi" placeholder="TI_SD-1" readonly class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 focus:ring-2 focus:ring-blue-500 outline-none transition-all" title="Otomatis terisi dari data mata kuliah">
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
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full sm:max-w-lg border border-gray-100">
            
            <div class="bg-emerald-50 px-6 py-4 border-b border-emerald-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-emerald-800 flex items-center gap-2">
                    <i class="fas fa-file-excel"></i> Upload Jadwal UPK Excel
                </h3>
                <button onclick="closeModal('uploadModal')" class="text-emerald-600 hover:text-emerald-800 transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            
            <div class="p-6">
                <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-lg flex items-start gap-3">
                    <div class="shrink-0 text-blue-500 mt-0.5"><i class="fas fa-info-circle text-lg"></i></div>
                    <div class="text-sm text-blue-800">
                        <p class="font-bold mb-1">Panduan Upload:</p>
                        <ul class="list-disc ml-4 space-y-1 text-blue-700/80 text-xs">
                            <li>Gunakan template resmi agar format sesuai.</li>
                            <li>Pastikan kolom data lengkap (Prodi, Tanggal, Jam, dst).</li>
                            <li>Format Excel: <strong>.xlsx</strong> atau <strong>.xls</strong>.</li>
                        </ul>
                    </div>
                </div>

                <form id="uploadForm">
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih File Excel (.xlsx / .xls)</label>
                        
                        <div class="flex items-center justify-center w-full">
                            <label for="fileExcel" class="flex flex-col items-center justify-center w-full h-40 border-2 border-emerald-300 border-dashed rounded-xl cursor-pointer bg-emerald-50/30 hover:bg-emerald-50 transition-colors group relative">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                    <div class="w-12 h-12 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-cloud-upload-alt text-2xl"></i>
                                    </div>
                                    <p class="mb-1 text-sm text-gray-600"><span class="font-semibold text-emerald-600">Klik upload</span> atau drag file</p>
                                    <p class="text-xs text-gray-400">Format: .xlsx, .xls (Max 5MB)</p>
                                </div>
                                <input id="fileExcel" name="excel_file" type="file" class="hidden" accept=".xlsx, .xls" required />
                            </label>
                        </div>
                        
                        <div id="fileInfo" class="hidden mt-3 p-3 bg-emerald-50 border border-emerald-100 rounded-lg flex items-center justify-between">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <i class="fas fa-file-excel text-emerald-600 text-lg"></i>
                                <span id="fileNameDisplay" class="text-sm font-medium text-gray-700 truncate">filename.xlsx</span>
                            </div>
                            <span id="fileSizeDisplay" class="text-xs text-gray-500 whitespace-nowrap">0 MB</span>
                        </div>
                    </div>

                    <div id="uploadProgress" class="hidden mb-4">
                        <div class="flex justify-between text-xs font-semibold text-gray-600 mb-1">
                            <span id="progressText">Mengunggah...</span>
                            <span id="progressPercent">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div id="progressBar" class="bg-emerald-500 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>

                    <div id="uploadMessage" class="hidden mb-4 p-3 rounded-lg text-sm"></div>

                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                        <button type="button" onclick="closeModal('uploadModal')" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 font-medium transition-colors">Batal</button>
                        <button type="submit" id="btnUpload" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium flex items-center gap-2 shadow-sm shadow-emerald-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-upload"></i> Upload & Proses
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
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full max-w-lg border border-gray-100">
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-blue-800 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i> Detail Jadwal UPK
                </h3>
                <button onclick="closeModal('detailModal')" class="text-blue-400 hover:text-blue-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                    <span class="text-gray-500 text-sm">Mata Kuliah</span>
                    <span id="detailMK" class="col-span-2 font-bold text-gray-800"></span>
                </div>
                <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                    <span class="text-gray-500 text-sm">Dosen</span>
                    <span id="detailDosen" class="col-span-2 font-medium text-gray-700"></span>
                </div>
                <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                    <span class="text-gray-500 text-sm">Program Studi</span>
                    <span id="detailProdi" class="col-span-2 font-bold text-blue-600"></span>
                </div>
                <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                    <span class="text-gray-500 text-sm">Waktu & Tanggal</span>
                    <div class="col-span-2">
                        <span id="detailTanggal" class="block font-medium text-gray-700"></span>
                        <span id="detailJam" class="text-sm text-blue-500 font-bold"></span>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                    <span class="text-gray-500 text-sm">Kelas & Ruangan</span>
                    <span class="col-span-2 font-medium text-gray-700">
                        <span id="detailKelas" class="font-bold"></span> - <span id="detailRuangan"></span>
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <span class="text-gray-500 text-sm">Frekuensi</span>
                    <span id="detailFreq" class="col-span-2 font-mono text-xs bg-gray-100 px-2 py-1 rounded w-fit"></span>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button onclick="closeModal('detailModal')" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 transition-all">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * PERBAIKAN: CAKUPAN GLOBAL FUNGSI
 * Semua fungsi diletakkan di window agar bisa dipanggil oleh 'onclick' HTML
 */

let allJadwalData = [];

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

window.openUploadModal = function() { 
    openModal('uploadModal'); 
    // Reset form states
    const form = document.getElementById('uploadForm');
    if(form) form.reset();
    document.getElementById('fileInfo').classList.add('hidden');
    document.getElementById('uploadProgress').classList.add('hidden');
    document.getElementById('uploadMessage').classList.add('hidden');
};

// Handler Upload File
document.addEventListener('DOMContentLoaded', () => {
    const uploadForm = document.getElementById('uploadForm');
    if(uploadForm) {
        uploadForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const fileInput = document.getElementById('fileExcel');
            if(!fileInput.files.length) return;

            const formData = new FormData();
            formData.append('excel_file', fileInput.files[0]);

            const btn = document.getElementById('btnUpload');
            const progressDiv = document.getElementById('uploadProgress');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            const msgDiv = document.getElementById('uploadMessage');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Memproses...';
            progressDiv.classList.remove('hidden');
            msgDiv.classList.add('hidden');

            try {
                // Simulate progress
                let progress = 0;
                const interval = setInterval(() => {
                    if(progress < 90) {
                        progress += 10;
                        progressBar.style.width = progress + '%';
                        progressPercent.innerText = progress + '%';
                    }
                }, 100);

                const response = await fetch('<?= API_URL ?>/jadwal-upk/upload', {
                    method: 'POST',
                    body: formData
                });
                
                clearInterval(interval);
                const result = await response.json();
                
                progressBar.style.width = '100%';
                progressPercent.innerText = '100%';

                if(result.status === 'success') {
                    msgDiv.className = 'mb-4 p-3 rounded-lg text-sm bg-emerald-50 text-emerald-800 border border-emerald-100';
                    msgDiv.innerHTML = `<i class="fas fa-check-circle mr-1"></i> ${result.message}`;
                    msgDiv.classList.remove('hidden');
                    setTimeout(() => {
                        closeModal('uploadModal');
                        loadJadwal();
                    }, 1500);
                } else {
                    throw new Error(result.message);
                }
            } catch (err) {
                msgDiv.className = 'mb-4 p-3 rounded-lg text-sm bg-red-50 text-red-800 border border-red-100';
                msgDiv.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i> ${err.message}`;
                msgDiv.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-upload"></i> Upload & Proses';
            }
        });
    }

    const fileInput = document.getElementById('fileExcel');
    if(fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const info = document.getElementById('fileInfo');
            if(file) {
                document.getElementById('fileNameDisplay').innerText = file.name;
                document.getElementById('fileSizeDisplay').innerText = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                info.classList.remove('hidden');
            } else {
                info.classList.add('hidden');
            }
        });
    }
});

window.openDetailModal = function(id, event = null) {
    if(event) event.stopPropagation();
    const data = allJadwalData.find(i => i.id == id);
    if(data) {
        document.getElementById('detailMK').innerText = data.mata_kuliah;
        document.getElementById('detailDosen').innerText = data.dosen;
        document.getElementById('detailProdi').innerText = data.prodi;
        document.getElementById('detailTanggal').innerText = new Date(data.tanggal).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
        document.getElementById('detailJam').innerText = data.jam;
        document.getElementById('detailKelas').innerText = data.kelas;
        document.getElementById('detailRuangan').innerText = data.ruangan;
        document.getElementById('detailFreq').innerText = data.frekuensi || '-';
        openModal('detailModal');
    }
};

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
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch(`<?= API_URL ?>/jadwal-upk/${id}`, {
                    method: 'DELETE'
                });
                const res = await response.json();
                if(res.status === 'success') {
                    Swal.fire('Berhasil!', 'Data telah dihapus.', 'success');
                    loadJadwal();
                } else {
                    Swal.fire('Gagal', res.message || 'Gagal menghapus data.', 'error');
                }
            } catch (err) {
                console.error("Delete Error:", err);
                Swal.fire('Error', 'Gagal menghubungi server.', 'error');
            }
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

    if(!data || data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="px-6 py-20 text-center text-gray-500"><i class="fas fa-search text-2xl mb-2"></i><p>Tidak ada data ditemukan</p></td></tr>`;
        updateBulkActionsVisibility();
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
            </tr>`;
    });
    tbody.innerHTML = rowsHtml;
    
    // Panggil update visibility setelah DOM terupdate
    updateBulkActionsVisibility();
}

window.updateBulkActionsVisibility = function() {
    const checks = document.querySelectorAll('.row-checkbox');
    const checked = document.querySelectorAll('.row-checkbox:checked').length;
    const selectAll = document.getElementById('selectAll');
    const actions = document.getElementById('bulkActions');
    const countSpan = document.getElementById('selectedCount');
    
    if (countSpan) countSpan.innerText = `${checked} terpilih`;
    
    // Sinkronisasi checkbox Select All
    if (selectAll) {
        selectAll.checked = (checks.length > 0 && checked === checks.length);
    }

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
            Swal.fire({
                title: 'Sedang menghapus...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await fetch('<?= API_URL ?>/jadwal-upk/delete-multiple', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ids: selected })
                });
                
                const res = await response.json();
                if(res.status === 'success') {
                    Swal.fire('Berhasil!', res.message || 'Data pilihan telah dihapus.', 'success');
                    loadJadwal();
                } else {
                    Swal.fire('Gagal', res.message || 'Terjadi kesalahan saat menghapus data.', 'error');
                }
            } catch (err) {
                console.error("Delete Error:", err);
                Swal.fire('Error', 'Gagal menghubungi server.', 'error');
            }
        }
    });
};

// Load Mata Kuliah dan Laboratorium dari API
async function loadDropdownData() {
    try {
        // Load Matakuliah
        const mkRes = await fetch('<?= API_URL ?>/jadwal-praktikum');
        const mkData = await mkRes.json();
        const mkSelect = document.getElementById('inputMK');
        const mkMap = {}; // Store MK data for frekuensi lookup

        if(mkData.status === 'success' && Array.isArray(mkData.data)) {
            // Get unique mata kuliah
            const uniqueMK = [...new Map(mkData.data.map(item => [item.namaMatakuliah, item])).values()];
            uniqueMK.forEach(mk => {
                const option = document.createElement('option');
                option.value = mk.namaMatakuliah;
                option.textContent = `${mk.kodeMatakuliah || ''} - ${mk.namaMatakuliah}`;
                option.dataset.frekuensi = mk.frekuensi || '';
                mkSelect.appendChild(option);
                mkMap[mk.namaMatakuliah] = mk.frekuensi || '';
            });
        }

        // Load Laboratorium
        const labRes = await fetch('<?= API_URL ?>/laboratorium');
        const labData = await labRes.json();
        const labSelect = document.getElementById('inputRuangan');
        if(labData.status === 'success' && Array.isArray(labData.data)) {
            labData.data.forEach(lab => {
                const option = document.createElement('option');
                option.value = lab.nama;
                option.textContent = lab.nama;
                labSelect.appendChild(option);
            });
        }

        // Event listener untuk auto-fill frekuensi
        mkSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const frekuensi = selectedOption.dataset.frekuensi || '';
            document.getElementById('inputFreq').value = frekuensi;
        });
    } catch (err) {
        console.error("Error loading dropdown data:", err);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadDropdownData();
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