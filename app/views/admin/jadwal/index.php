<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-calendar-alt text-blue-600"></i> 
        Manajemen Jadwal Praktikum
    </h1>
    
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" id="searchInput" placeholder="Cari MK, Lab, Hari..." 
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow text-sm">
        </div>

        <button onclick="openUploadModal()" 
           class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5 whitespace-nowrap">
            <i class="fas fa-file-excel"></i> Upload Excel
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
            <span class="text-sm text-gray-500 font-medium">Daftar Jadwal Praktikum</span>
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
                    <th class="px-6 py-4 font-semibold">Mata Kuliah</th>
                    <th class="px-6 py-4 font-semibold">Laboratorium</th>
                    <th class="px-6 py-4 font-semibold min-w-[160px]">Hari & Waktu</th>
                    <th class="px-6 py-4 font-semibold text-center w-24">Kelas</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Status</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-gray-200 text-gray-700 text-sm">
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
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
                <h3 id="formModalTitle" class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    </h3>
                <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 max-h-[80vh] overflow-y-auto custom-scrollbar">
                <form id="jadwalForm" class="space-y-5">
                    <input type="hidden" id="inputId" name="idJadwal">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mata Kuliah <span class="text-red-500">*</span></label>
                            <select id="inputMatakuliah" name="idMatakuliah" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="">-- Pilih MK --</option>
                                </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Laboratorium <span class="text-red-500">*</span></label>
                            <select id="inputLab" name="idLaboratorium" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="">-- Pilih Lab --</option>
                                </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Hari <span class="text-red-500">*</span></label>
                            <select id="inputHari" name="hari" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kelas <span class="text-red-500">*</span></label>
                            <input type="text" id="inputKelas" name="kelas" placeholder="A1 / B2 / C..." required 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none uppercase font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Mulai <span class="text-red-500">*</span></label>
                            <input type="time" id="inputMulai" name="waktuMulai" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Selesai <span class="text-red-500">*</span></label>
                            <input type="time" id="inputSelesai" name="waktuSelesai" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Frekuensi</label>
                            <input type="text" id="inputFrekuensi" name="frekuensi" placeholder="Contoh: TI_PW-1" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Dosen Pengampu</label>
                            <input type="text" id="inputDosen" name="dosen" placeholder="Nama Dosen" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Asisten 1</label>
                            <select id="inputAsisten1" name="idAsisten1" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="">-- Pilih Asisten 1 --</option>
                                </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Asisten 2</label>
                            <select id="inputAsisten2" name="idAsisten2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="">-- Pilih Asisten 2 --</option>
                                </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status Jadwal</label>
                        <div class="flex gap-4 mt-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="Aktif" checked class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="Nonaktif" class="w-4 h-4 text-red-600 focus:ring-red-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Nonaktif</span>
                            </label>
                        </div>
                    </div>

                    <div id="formMessage" class="hidden mt-4"></div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 mt-2">
                        <button type="button" onclick="closeModal('formModal')" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 font-medium transition-colors border border-gray-200">Batal</button>
                        <button type="submit" id="btnSave" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors flex items-center gap-2 shadow-sm shadow-blue-200">
                            <i class="fas fa-save"></i> <span>Simpan Jadwal</span>
                        </button>
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
                    <i class="fas fa-file-excel"></i> Upload Jadwal Excel
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
                            <li>Pastikan nama Mata Kuliah & Lab sama persis dengan di sistem.</li>
                            <li>Format waktu: <strong>HH:MM</strong> (Contoh: 08:00).</li>
                        </ul>
                        <a href="<?= API_URL ?>/jadwal-praktikum/template" class="inline-flex items-center gap-1 mt-3 text-blue-600 font-bold hover:text-blue-800 hover:underline">
                            <i class="fas fa-download"></i> Download Template Excel
                        </a>
                    </div>
                </div>

                <form id="uploadForm" enctype="multipart/form-data">
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

<script>
// Pastikan BASE_URL dan API_URL sudah didefinisikan di layout utama/header
// window.BASE_URL = '...'; 

let allJadwalData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadJadwal();
    
    // Live Search
    const searchInput = document.getElementById('searchInput');
    if(searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            const keyword = e.target.value.toLowerCase();
            const filtered = allJadwalData.filter(item => 
                (item.namaMatakuliah && item.namaMatakuliah.toLowerCase().includes(keyword)) || 
                (item.namaLab && item.namaLab.toLowerCase().includes(keyword)) ||
                (item.hari && item.hari.toLowerCase().includes(keyword)) ||
                (item.kelas && item.kelas.toLowerCase().includes(keyword))
            );
            renderTable(filtered);
        });
    }

    // File Input Handler (Untuk Preview Nama File)
    const fileInput = document.getElementById('fileExcel');
    if(fileInput) {
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : '';
            const fileSize = e.target.files[0] ? (e.target.files[0].size / 1024 / 1024).toFixed(2) : 0;
            
            const displayDate = document.getElementById('fileNameDisplay');
            const displaySize = document.getElementById('fileSizeDisplay');
            const infoBox = document.getElementById('fileInfo');

            if(fileName) {
                displayDate.textContent = fileName;
                displaySize.textContent = fileSize + ' MB';
                infoBox.classList.remove('hidden');
            } else {
                infoBox.classList.add('hidden');
            }
        });
    }
});

// --- 1. LOAD DATA ---
function loadJadwal() {
    fetch(API_URL + '/jadwal')
    .then(res => res.json())
    .then(res => {
        if((res.status === 'success' || res.code === 200) && res.data) {
            allJadwalData = res.data;
            renderTable(allJadwalData);
        } else {
            renderTable([]);
        }
    }).catch(err => {
        console.error(err);
        document.getElementById('tableBody').innerHTML = `<tr><td colspan="8" class="px-6 py-12 text-center text-red-500">Gagal memuat data: ${err.message}</td></tr>`;
    });
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    const totalEl = document.getElementById('totalData');
    tbody.innerHTML = '';
    
    // Reset Select All
    const selectAll = document.getElementById('selectAll');
    if(selectAll) selectAll.checked = false;

    if(totalEl) totalEl.innerText = `Total: ${data.length}`;

    if (!data || data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-search text-2xl mb-2"></i><p>Tidak ada data ditemukan</p></td></tr>`;
        updateBulkActionsVisibility();
        return;
    }

    let rowsHtml = '';
    data.forEach((item, index) => {
        const statusClass = item.status === 'Aktif' 
            ? 'bg-emerald-100 text-emerald-700 border-emerald-200' 
            : 'bg-red-100 text-red-700 border-red-200';
        
        const waktuMulai = item.waktuMulai ? item.waktuMulai.substring(0, 5) : '--:--';
        const waktuSelesai = item.waktuSelesai ? item.waktuSelesai.substring(0, 5) : '--:--';

        rowsHtml += `
            <tr class="hover:bg-blue-50 transition-colors duration-150 group border-b border-gray-100">
                <td class="px-6 py-4 text-center">
                    <input type="checkbox" name="selectedIds[]" value="${item.idJadwal}" 
                           onchange="updateBulkActionsVisibility()"
                           onclick="event.stopPropagation()"
                           class="row-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                </td>
                <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                <td class="px-6 py-4 cursor-pointer" onclick="openFormModal(${item.idJadwal}, event)">
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-800 text-sm group-hover:text-blue-600 transition-colors">${item.namaMatakuliah || '-'}</span>
                        <span class="text-xs text-gray-400 font-mono mt-0.5">${item.kodeMatakuliah || '-'}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600 text-sm font-medium cursor-pointer" onclick="openFormModal(${item.idJadwal}, event)">${item.namaLab || '-'}</td>
                <td class="px-6 py-4 cursor-pointer min-w-[160px]" onclick="openFormModal(${item.idJadwal}, event)">
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-700 text-sm">${item.hari || '-'}</span>
                        <span class="text-xs text-gray-500 flex items-center gap-1">
                            <i class="far fa-clock"></i> ${waktuMulai} - ${waktuSelesai}
                        </span>
                    </div>
                </td>
                <td class="px-6 py-4 text-center cursor-pointer" onclick="openFormModal(${item.idJadwal}, event)">
                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold border border-gray-200">${item.kelas || '-'}</span>
                </td>
                <td class="px-6 py-4 text-center cursor-pointer" onclick="openFormModal(${item.idJadwal}, event)">
                    <span class="${statusClass} px-2.5 py-1 rounded-full text-xs font-semibold border">${item.status || 'Nonaktif'}</span>
                </td>
            </tr>`;
    });
    tbody.innerHTML = rowsHtml;
    
    // Panggil update visibility setelah DOM terupdate
    updateBulkActionsVisibility();
}

// --- BULK ACTION HELPERS ---
function updateBulkActionsVisibility() {
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const countSpan = document.getElementById('selectedCount');
    
    if (checkboxes.length > 0) {
        bulkActions.classList.remove('hidden');
        countSpan.innerText = `${checkboxes.length} terpilih`;
    } else {
        bulkActions.classList.add('hidden');
    }
}

// Event Listener Select All
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    if(selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkActionsVisibility();
        });
    }
});

function bulkDelete() {
    const checkboxes = document.querySelectorAll('.row-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) return;

    confirmDelete(() => {
        showLoading(`Menghapus ${ids.length} data...`);
        fetch(API_URL + '/jadwal/delete-multiple', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ids: ids })
        })
        .then(res => res.json())
        .then(data => {
            hideLoading();
            if (data.status === 'success') {
                loadJadwal();
                showSuccess(`${ids.length} data berhasil dihapus!`);
            } else {
                throw new Error(data.message || 'Gagal menghapus data');
            }
        })
        .catch(err => {
            hideLoading();
            showError(err.message || 'Gagal menghapus data terpilih');
        });
    }, `Apakah Anda yakin ingin menghapus ${ids.length} data yang dipilih?`);
}

// --- 2. MODAL FORM & OPTIONS ---
function openFormModal(id = null, event = null) {
    if(event) event.stopPropagation();
    document.getElementById('formModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('formMessage').classList.add('hidden');
    document.getElementById('jadwalForm').reset();
    document.getElementById('inputId').value = '';

    // Load Dropdown Options (MK, Lab, Asisten)
    Promise.all([
        fetch(API_URL + '/matakuliah').then(r => r.json()),
        fetch(API_URL + '/laboratorium').then(r => r.json()),
        fetch(API_URL + '/asisten').then(r => r.json()).catch(() => ({ data: [] }))
    ]).then(([mkData, labData, asistenData]) => {
        // Isi Select MK
        const mkSelect = document.getElementById('inputMatakuliah');
        mkSelect.innerHTML = '<option value="">-- Pilih MK --</option>';
        if(mkData.data) {
            mkData.data.forEach(m => {
                mkSelect.innerHTML += `<option value="${m.idMatakuliah}">${m.kodeMatakuliah} - ${m.namaMatakuliah}</option>`;
            });
        }

        // Isi Select Lab
        const labSelect = document.getElementById('inputLab');
        labSelect.innerHTML = '<option value="">-- Pilih Lab --</option>';
        if(labData.data) {
            labData.data.forEach(l => {
                labSelect.innerHTML += `<option value="${l.idLaboratorium}">${l.nama}</option>`;
            });
        }

        // Filter Asisten 1 (statusAktif = 'Asisten')
        const asisten1List = asistenData.data ? asistenData.data.filter(a => a.statusAktif === 'Asisten') : [];
        const asisten1Select = document.getElementById('inputAsisten1');
        asisten1Select.innerHTML = '<option value="">-- Pilih Asisten 1 --</option>';
        asisten1List.forEach(a => {
            asisten1Select.innerHTML += `<option value="${a.idAsisten}">${a.nama}</option>`;
        });

        // Filter Asisten 2 (statusAktif = 'CA')
        const asisten2List = asistenData.data ? asistenData.data.filter(a => a.statusAktif === 'CA' || a.statusAktif === 'Asisten') : [];
        const asisten2Select = document.getElementById('inputAsisten2');
        asisten2Select.innerHTML = '<option value="">-- Pilih Asisten 2 --</option>';
        asisten2List.forEach(a => {
            asisten2Select.innerHTML += `<option value="${a.idAsisten}">${a.nama}</option>`;
        })

        // Jika Mode Edit, Isi Data
        if (id) {
            document.getElementById('formModalTitle').innerHTML = '<i class="fas fa-edit text-blue-600"></i> Edit Jadwal';
            document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> Update Jadwal';
            
            const data = allJadwalData.find(i => i.idJadwal == id);
            if (data) {
                document.getElementById('inputId').value = data.idJadwal;
                document.getElementById('inputMatakuliah').value = data.idMatakuliah;
                document.getElementById('inputLab').value = data.idLaboratorium;
                document.getElementById('inputHari').value = data.hari;
                document.getElementById('inputKelas').value = data.kelas;
                document.getElementById('inputMulai').value = data.waktuMulai ? data.waktuMulai.substring(0, 5) : '';
                document.getElementById('inputSelesai').value = data.waktuSelesai ? data.waktuSelesai.substring(0, 5) : '';
                document.getElementById('inputFrekuensi').value = data.frekuensi || '';
                document.getElementById('inputDosen').value = data.dosen || '';
                // Set Select values safely (Handle ID or Name mismatch)
                const setSelectValue = (id, value) => {
                    const select = document.getElementById(id);
                    if(!select || !value) return;

                    // 1. Try direct assignment (for ID)
                    select.value = value;
                    
                    // 2. Retry with string cast
                    if(!select.value) select.value = String(value);

                    // 3. Fallback: Find by Option Text (for Legacy Names)
                    if(!select.value) {
                       const searchText = String(value).trim().toLowerCase();
                       for (let i = 0; i < select.options.length; i++) {
                           if (select.options[i].text.trim().toLowerCase() === searchText) {
                               select.value = select.options[i].value;
                               break;
                           }
                       }
                    }
                };

                setSelectValue('inputAsisten1', data.asisten1);
                setSelectValue('inputAsisten2', data.asisten2);
                
                // Radio Status
                const radios = document.getElementsByName('status');
                for(let r of radios) { if(r.value === data.status) r.checked = true; }
            }
        } else {
            document.getElementById('formModalTitle').innerHTML = '<i class="fas fa-plus text-emerald-600"></i> Tambah Jadwal Baru';
            document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> Simpan Jadwal';
        }
    });
}

// --- SUBMIT FORM TAMBAH/EDIT ---
document.getElementById('jadwalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('formMessage');
    
    const id = document.getElementById('inputId').value;
    const url = id ? API_URL + '/jadwal/' + id : API_URL + '/jadwal';
    const method = id ? 'PUT' : 'POST';

    const formData = new FormData(this);
    const dataObj = Object.fromEntries(formData.entries());

    if (!dataObj.idMatakuliah || !dataObj.idLaboratorium) {
        alert("Mohon pilih Mata Kuliah dan Laboratorium");
        return;
    }

    btn.disabled = true; 
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
    
    console.log('Form Data:', dataObj);
    console.log('Request URL:', url);
    console.log('Method:', method);

    fetch(url, { 
        method: method, 
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dataObj) 
    })
    .then(res => {
        console.log('Response Status:', res.status);
        return res.json();
    })
    .then(data => {
        console.log('Response Data:', data);
        hideLoading();
        if (data.status === true || data.status === 'success' || data.code === 200 || data.code === 201) {
            closeModal('formModal');
            loadJadwal();
            showSuccess(id ? 'Jadwal berhasil diperbarui!' : 'Jadwal baru berhasil ditambahkan!');
        } else {
            throw new Error(data.message || 'Gagal menyimpan data');
        }
    })
    .catch(err => {
        hideLoading();
        console.error(err);
        msg.innerHTML = `<div class="bg-red-50 text-red-800 p-3 rounded text-sm mb-4 border border-red-200 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`;
        msg.classList.remove('hidden');
        showError(err.message);
    })
    .finally(() => { 
        btn.disabled = false; 
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Jadwal'; 
    });
});

// --- 3. MODAL UPLOAD ---
function openUploadModal() {
    document.getElementById('uploadModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Reset Form UI
    document.getElementById('uploadForm').reset();
    document.getElementById('fileInfo').classList.add('hidden');
    document.getElementById('uploadProgress').classList.add('hidden');
    document.getElementById('uploadMessage').classList.add('hidden');
    document.getElementById('btnUpload').disabled = false;
    document.getElementById('btnUpload').innerHTML = '<i class="fas fa-upload"></i> Upload & Proses';
}

// Handler Submit Form Upload (PERBAIKAN UTAMA: DIJADIKAN SATU)
document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const fileInputEl = document.getElementById('fileExcel');
    if (!fileInputEl || fileInputEl.files.length === 0) {
        alert('Pilih file terlebih dahulu!');
        return;
    }
    
    const file = fileInputEl.files[0];

    // UI Elements
    const btn = document.getElementById('btnUpload');
    const msgDiv = document.getElementById('uploadMessage');
    const progressDiv = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const progressPercent = document.getElementById('progressPercent');

    // Reset UI State
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Memproses...';
    msgDiv.classList.add('hidden');
    progressDiv.classList.remove('hidden');
    progressBar.style.width = '0%';
    
    const formData = new FormData();
    formData.append('excel_file', file); 

    try {
        // Simulasi Progress
        let progress = 0;
        const interval = setInterval(() => {
            if(progress < 90) {
                progress += Math.random() * 10;
                const p = Math.min(progress, 90).toFixed(0);
                progressBar.style.width = p + '%';
                progressPercent.innerText = p + '%';
            }
        }, 300);

        // Request ke API
        const response = await fetch(API_URL + '/jadwal-praktikum/upload', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        
        clearInterval(interval);
        progressBar.style.width = '100%';
        progressPercent.innerText = '100%';
        progressText.innerText = 'Selesai!';

        if (result.status === 'success' || result.code === 200) {
            msgDiv.className = 'mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-800';
            let successMsg = `<div class="flex items-center gap-2 mb-1 font-bold text-emerald-700"><i class="fas fa-check-circle text-lg"></i> Upload Berhasil!</div>`;
            successMsg += `<p>${result.message}</p>`;
            
            if (result.data && result.data.errors && result.data.errors.length > 0) {
                successMsg += `<div class="mt-3 pt-3 border-t border-emerald-200">
                    <p class="font-bold text-xs text-orange-600 mb-1">Peringatan (${result.data.errors.length} data dilewati):</p>
                    <ul class="list-disc ml-4 text-xs text-orange-700 max-h-24 overflow-y-auto custom-scrollbar">`;
                result.data.errors.forEach(err => successMsg += `<li>${err}</li>`);
                successMsg += `</ul></div>`;
            }
            
            msgDiv.innerHTML = successMsg;
            msgDiv.classList.remove('hidden');

            setTimeout(() => {
                closeModal('uploadModal');
                loadJadwal();
            }, 3000);

        } else {
            throw new Error(result.message || 'Gagal upload');
        }

    } catch (error) {
        console.error('Upload Error:', error);
        
        msgDiv.className = 'mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800';
        msgDiv.innerHTML = `
            <div class="flex items-center gap-2 mb-2 font-bold text-red-700"><i class="fas fa-exclamation-triangle"></i> Upload Gagal</div>
            <p>${error.message}</p>
        `;
        msgDiv.classList.remove('hidden');
        progressText.innerText = 'Gagal';
        progressBar.classList.add('bg-red-500');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-upload"></i> Upload & Proses';
    }
});

// --- HELPER ---
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function hapusJadwal(id, event) {
    if(event) event.stopPropagation();
    confirmDelete(() => {
        showLoading('Menghapus data...');
        fetch(API_URL + '/jadwal/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(() => { 
            hideLoading();
            loadJadwal(); 
            showSuccess('Jadwal berhasil dihapus!'); 
        })
        .catch(err => {
            hideLoading();
            showError('Gagal menghapus data');
        });
    });
}

document.onkeydown = function(evt) {
    if (evt.keyCode == 27) { closeModal('formModal'); closeModal('uploadModal'); }
};
</script>