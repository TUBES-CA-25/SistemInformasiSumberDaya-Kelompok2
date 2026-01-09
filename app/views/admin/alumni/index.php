<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-user-graduate text-blue-600"></i> Data Alumni Asisten
    </h1>
    <button onclick="openFormModal()" 
       class="mt-4 sm:mt-0 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center gap-2 font-medium transform hover:-translate-y-0.5">
        <i class="fas fa-plus"></i> Tambah Alumni
    </button>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold text-center w-24">Foto</th>
                    <th class="px-6 py-4 font-semibold">Nama Alumni</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Angkatan</th>
                    <th class="px-6 py-4 font-semibold text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y divide-gray-200 text-gray-700 text-sm">
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-circle-notch fa-spin"></i> Memuat data...</td></tr>
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
                <form id="alumniForm" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" id="inputId" name="id">

                    <!-- Bagian Informasi Pribadi -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                            <i class="fas fa-id-card text-blue-500"></i>
                            <h4 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Identitas Alumni</h4>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="inputNama" name="nama" placeholder="Contoh: Budi Santoso, S.Kom" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400 bg-gray-50/30">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                <input type="email" id="inputEmail" name="email" placeholder="contoh@gmail.com"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400 bg-gray-50/30">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Tahun Angkatan <span class="text-red-500">*</span></label>
                                <input type="number" id="inputAngkatan" name="angkatan" placeholder="2020" min="2000" max="2030" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400 bg-gray-50/30">
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Riwayat Lab -->
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                            <i class="fas fa-flask text-indigo-500"></i>
                            <h4 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Riwayat Laboratorium</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Divisi / Posisi Terakhir</label>
                                <input type="text" id="inputDivisi" name="divisi" placeholder="Contoh: Koordinator Lab / Asisten"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400 bg-gray-50/30">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Keahlian Utama</label>
                                <input type="text" id="inputKeahlian" name="keahlian" placeholder="PHP, Laravel, React"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400 bg-gray-50/30">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mata Kuliah Pernah Diajar</label>
                            <input type="text" id="inputMataKuliah" name="mata_kuliah" placeholder="Contoh: Pemrograman Dasar, Basis Data"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400 bg-gray-50/30">
                            <p class="text-[10px] text-gray-400 mt-1 italic leading-tight"><i class="fas fa-info-circle mr-1"></i> Pisahkan dengan koma jika Anda pernah mengajar lebih dari satu mata kuliah.</p>
                        </div>
                    </div>

                    <!-- Bagian Media & Pesan -->
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                            <i class="fas fa-comment-dots text-emerald-500"></i>
                            <h4 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Media & Testimoni</h4>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Upload Foto Profil</label>
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <input type="file" id="inputFoto" name="foto" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                                </div>
                                <div id="fotoPreviewInfo" class="hidden">
                                    <span class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full text-[10px] font-bold border border-emerald-100 whitespace-nowrap"><i class="fas fa-image mr-1"></i> Foto Tersimpan</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Format: JPG/PNG/WEBP. Rekomendasi 1:1 (Square). Maksimal 2MB.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kesan & Pesan Selama Di Lab</label>
                            <textarea id="inputKesanPesan" name="kesan_pesan" rows="3" placeholder="Tuliskan pengalaman berkesan Anda..."
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400 bg-gray-50/30"></textarea>
                        </div>
                    </div>

                    <div id="formMessage" class="hidden"></div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" onclick="closeModal('formModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition-colors">Batal</button>
                        <button type="submit" id="btnSave" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors flex items-center gap-2"><i class="fas fa-save"></i> Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal('detailModal')"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl w-full sm:max-w-2xl border border-gray-100">
            <button onclick="closeModal('detailModal')" class="absolute top-4 right-4 z-20 bg-white/20 hover:bg-white/40 text-white rounded-full p-2 transition-colors"><i class="fas fa-times text-xl drop-shadow-md"></i></button>
            <div class="bg-white">
                <div class="h-24 bg-gradient-to-r from-blue-600 to-indigo-700 relative"></div>
                <div class="px-6 pb-6">
                    <div class="relative flex flex-col sm:flex-row items-center sm:items-end -mt-10 mb-6 gap-4">
                        <img id="dFoto" src="" class="w-24 h-24 rounded-full border-4 border-white shadow-md object-cover bg-gray-200">
                        <div class="text-center sm:text-left flex-1 pb-1">
                            <h2 id="dNama" class="text-2xl font-bold text-gray-900">Nama</h2>
                            <p id="dDivisi" class="text-blue-600 font-medium">Divisi</p>
                        </div>
                    </div>
                    <div class="space-y-4 text-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50/50 p-3.5 rounded-2xl border border-gray-100">
                                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">Angkatan</p>
                                <p id="dAngkatan" class="font-bold text-gray-800">-</p>
                            </div>
                            <div class="bg-gray-50/50 p-3.5 rounded-2xl border border-gray-100">
                                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">Email</p>
                                <p id="dEmail" class="font-bold text-gray-800 truncate">-</p>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50/30 p-3.5 rounded-2xl border border-blue-50 flex flex-col gap-1">
                             <p class="text-[10px] uppercase font-bold text-blue-400 tracking-wider flex items-center gap-2">
                                <i class="fas fa-book-reader text-[10px]"></i> Mata Kuliah Pernah Diajar
                             </p>
                             <p id="dMataKuliah" class="font-semibold text-gray-700 leading-relaxed">-</p>
                        </div>

                        <div class="space-y-2">
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider ml-1">Keahlian & Kompetensi</p>
                            <div id="dKeahlian" class="flex flex-wrap gap-2"></div>
                        </div>

                        <div class="bg-indigo-50/50 p-5 rounded-3xl border border-indigo-100 italic text-gray-700 relative overflow-hidden">
                            <i class="fas fa-quote-left text-indigo-100/50 text-5xl absolute -top-1 -left-1"></i>
                            <span id="dKesan" class="relative z-10 block"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    loadAlumni();
});

function loadAlumni() {
    fetch(API_URL + '/alumni').then(res => res.json()).then(res => {
        const tbody = document.getElementById('tableBody'); tbody.innerHTML = '';
        if((res.status === 'success' || res.code === 200) && res.data && res.data.length > 0) {
            res.data.forEach((item, index) => {
                const fotoUrl = item.foto ? (item.foto.includes('http') ? item.foto : ASSETS_URL + '/assets/uploads/' + item.foto) : 'https://placehold.co/50x50?text=Foto';
                const row = `
                    <tr onclick="openDetailModal(${item.id})" class="hover:bg-blue-50 transition-colors duration-150 cursor-pointer group border-b border-gray-100">
                        <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                        <td class="px-6 py-4"><div class="flex justify-center"><img src="${fotoUrl}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-md"></div></td>
                        <td class="px-6 py-4"><div class="flex flex-col"><span class="font-bold text-gray-800 text-sm">${item.nama}</span><span class="text-xs text-gray-500 uppercase mt-0.5">${item.divisi || 'Asisten'}</span></div></td>
                        <td class="px-6 py-4 text-center"><span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-semibold border border-blue-100">${item.angkatan || '-'}</span></td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center gap-2">
                                <button onclick="openFormModal(${item.id}, event)" class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all shadow-sm flex items-center justify-center"><i class="fas fa-pen text-xs"></i></button>
                                <button onclick="hapusAlumni(${item.id}, event)" class="w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-all shadow-sm flex items-center justify-center"><i class="fas fa-trash-alt text-xs"></i></button>
                            </div>
                        </td>
                    </tr>`;
                tbody.innerHTML += row;
            });
        } else { tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada data</td></tr>`; }
    });
}

function openDetailModal(id) {
    document.getElementById('detailModal').classList.remove('hidden'); document.body.style.overflow = 'hidden';
    document.getElementById('dNama').innerText = "Memuat..."; 
    fetch(API_URL + '/alumni/' + id).then(res => res.json()).then(res => {
        if((res.status === 'success' || res.code === 200) && res.data) {
            const d = res.data;
            document.getElementById('dNama').innerText = d.nama; document.getElementById('dDivisi').innerText = d.divisi || 'Alumni';
            document.getElementById('dAngkatan').innerText = d.angkatan;
            document.getElementById('dEmail').innerText = d.email || '-';
            document.getElementById('dMataKuliah').innerText = d.mata_kuliah || '-';
            document.getElementById('dKesan').innerText = d.kesan_pesan ? `"${d.kesan_pesan}"` : "-";
            const fotoUrl = d.foto ? (d.foto.includes('http') ? d.foto : ASSETS_URL + '/assets/uploads/' + d.foto) : `https://placehold.co/150x150?text=${d.nama.charAt(0)}`;
            document.getElementById('dFoto').src = fotoUrl;
            const sDiv=document.getElementById('dKeahlian'); sDiv.innerHTML=''; let skills=d.keahlian; try{if(skills.includes('['))skills=JSON.parse(skills);else skills=skills.split(',')}catch(e){if(skills)skills=skills.split(',')}
            if(Array.isArray(skills)){skills.forEach(s=>{if(s.trim())sDiv.innerHTML+=`<span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold border border-blue-100">${s.trim()}</span>`})}else{sDiv.innerHTML='-'}
        }
    });
}

function openFormModal(id = null, event = null) {
    if(event) event.stopPropagation();
    document.getElementById('formModal').classList.remove('hidden'); document.body.style.overflow = 'hidden';
    document.getElementById('formMessage').classList.add('hidden');
    document.getElementById('alumniForm').reset();
    document.getElementById('inputId').value = '';
    document.getElementById('fotoPreviewInfo').classList.add('hidden');

    if (id) {
        document.getElementById('formModalTitle').innerHTML = '<i class="fas fa-user-edit text-blue-600"></i> Edit Alumni';
        document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> Update Data';
        // Isi Form untuk Edit
        fetch(API_URL + '/alumni/' + id).then(res => res.json()).then(res => {
            if(res.data) {
                const d = res.data;
                document.getElementById('inputId').value = d.id;
                document.getElementById('inputNama').value = d.nama;
                document.getElementById('inputAngkatan').value = d.angkatan;
                document.getElementById('inputDivisi').value = d.divisi || '';
                document.getElementById('inputKeahlian').value = d.keahlian || '';
                document.getElementById('inputEmail').value = d.email || '';
                document.getElementById('inputKesanPesan').value = d.kesan_pesan || '';
                document.getElementById('inputMataKuliah').value = d.mata_kuliah || '';

                if(d.foto) document.getElementById('fotoPreviewInfo').classList.remove('hidden');
            }
        });
    } else {
        document.getElementById('formModalTitle').innerHTML = '<i class="fas fa-user-plus text-emerald-600"></i> Tambah Alumni Baru';
        document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> Simpan Data';
    }
}

document.getElementById('alumniForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSave'); const formMsg = document.getElementById('formMessage');
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
    
    const formData = new FormData(this);
    const id = document.getElementById('inputId').value;
    const url = id ? API_URL + '/alumni/' + id : API_URL + '/alumni';

    fetch(url, { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success' || data.code === 201 || data.code === 200) {
            closeModal('formModal'); 
            loadAlumni(); 
            showSuccess(id ? 'Data alumni berhasil diperbarui!' : 'Alumni baru berhasil ditambahkan!');
        } else { throw new Error(data.message || 'Gagal menyimpan'); }
    })
    .catch(err => {
        hideLoading();
        formMsg.innerHTML = `<div class="bg-red-50 text-red-800 p-3 rounded text-sm"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`;
        formMsg.classList.remove('hidden');
        showError(err.message);
    })
    .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data'; });
});

function closeModal(modalId) { document.getElementById(modalId).classList.add('hidden'); document.body.style.overflow = 'auto'; }
document.onkeydown = function(evt) { if (evt.keyCode == 27) { closeModal('detailModal'); closeModal('formModal'); } };
function hapusAlumni(id, event) { 
    if(event) event.stopPropagation();
    confirmDelete(() => {
        showLoading('Menghapus data...');
        fetch(API_URL + '/alumni/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(() => { 
            hideLoading();
            loadAlumni(); 
            showSuccess('Data alumni berhasil dihapus!'); 
        })
        .catch(err => {
            hideLoading();
            showError('Gagal menghapus data');
        });
    });
}
</script>