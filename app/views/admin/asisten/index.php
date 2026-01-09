<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-users text-blue-600"></i> Data Asisten Laboratorium
    </h1>
    <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-0 w-full sm:w-auto">
        <button onclick="openKoordinatorModal()" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5">
            <i class="fas fa-crown"></i> Pilih Koordinator
        </button>
        
        <button onclick="openFormModal()" 
           class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2 font-medium transform hover:-translate-y-0.5">
            <i class="fas fa-plus"></i> Tambah Asisten
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                    <th class="px-6 py-4 font-semibold text-center w-24">Foto</th>
                    <th class="px-6 py-4 font-semibold">Nama Lengkap</th>
                    <th class="px-6 py-4 font-semibold">Jurusan</th>
                    <th class="px-6 py-4 font-semibold text-center w-32">Status</th>
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
                    <i class="fas fa-user-edit text-emerald-600"></i> Form Asisten
                </h3>
                <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600 transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 max-h-[80vh] overflow-y-auto custom-scrollbar">
                <form id="asistenForm" enctype="multipart/form-data" class="space-y-5">
                    <input type="hidden" id="inputIdAsisten" name="idAsisten">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label><input type="text" id="inputNama" name="nama" placeholder="Contoh: Ahmad Fulan" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"></div>
                        <div><label class="block text-sm font-semibold text-gray-700 mb-1">Email <span class="text-red-500">*</span></label><input type="email" id="inputEmail" name="email" placeholder="email@umi.ac.id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-semibold text-gray-700 mb-1">Jurusan</label><select id="inputJurusan" name="jurusan" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none bg-white"><option value="Teknik Informatika">Teknik Informatika</option><option value="Sistem Informasi">Sistem Informasi</option></select></div>
                        <div><label class="block text-sm font-semibold text-gray-700 mb-1">Status Asisten</label><select id="inputStatus" name="statusAktif" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none bg-white"><option value="Asisten">Asisten</option><option value="CA">Calon Asisten</option></select></div>
                    </div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Upload Foto</label><input type="file" id="inputFoto" name="foto" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"><div id="fotoPreviewInfo" class="hidden mt-1 text-xs text-emerald-600"><i class="fas fa-check-circle"></i> Foto sudah ada.</div></div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Bio / Tentang Saya</label><textarea id="inputBio" name="bio" rows="3" placeholder="Deskripsi singkat..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"></textarea></div>
                    <div id="formMessage" class="hidden"></div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" onclick="closeModal('formModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition-colors">Batal</button>
                        <button type="submit" id="btnSave" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium transition-colors flex items-center gap-2"><i class="fas fa-save"></i> Simpan Data</button>
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
                <div class="h-24 bg-gradient-to-r from-emerald-600 to-teal-600 relative"></div>
                <div class="px-6 pb-6">
                    <div class="relative flex flex-col sm:flex-row items-center sm:items-end -mt-10 mb-6 gap-4">
                        <img id="mFoto" src="" class="w-24 h-24 rounded-full border-4 border-white shadow-md object-cover bg-gray-200">
                        <div class="text-center sm:text-left flex-1 pb-1">
                            <h2 id="mNama" class="text-2xl font-bold text-gray-900">Nama</h2>
                            <div id="mBadges" class="flex flex-wrap justify-center sm:justify-start gap-2 mt-1"></div>
                        </div>
                    </div>
                    <div class="space-y-4 text-sm">
                        <div class="bg-gray-50 p-3 rounded-lg flex justify-between">
                            <div><p class="text-xs text-gray-500">Jurusan</p><p id="mJurusan" class="font-bold">-</p></div>
                            <div class="text-right"><p class="text-xs text-gray-500">Email</p><p id="mEmail" class="font-bold">-</p></div>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg"><p class="text-xs text-gray-500 mb-1">Bio</p><p id="mBio" class="italic text-gray-700">-</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="koordinatorModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal('koordinatorModal')"></div>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl w-full sm:max-w-2xl border border-gray-100">
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-blue-800 flex items-center gap-2">
                    <i class="fas fa-crown text-yellow-500"></i> Pilih Koordinator Lab
                </h3>
                <button onclick="closeModal('koordinatorModal')" class="text-blue-400 hover:text-blue-600 transition-colors"><i class="fas fa-times text-xl"></i></button>
            </div>

            <div class="p-6">
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-100 rounded-xl flex items-center gap-4">
                    <div class="shrink-0 bg-yellow-200 text-yellow-700 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-check text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-yellow-800 font-bold uppercase tracking-wide">Koordinator Saat Ini</p>
                        <p id="currentCoordName" class="text-lg font-bold text-gray-800">Belum ditentukan</p>
                    </div>
                </div>

                <h4 class="text-sm font-bold text-gray-500 uppercase mb-3">Pilih Koordinator Baru</h4>
                
                <form id="koordinatorForm">
                    <div id="coordList" class="max-h-60 overflow-y-auto custom-scrollbar space-y-2 border border-gray-100 rounded-lg p-2">
                        <div class="text-center py-4 text-gray-400">Memuat data...</div>
                    </div>

                    <div id="coordMessage" class="hidden mt-4"></div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 mt-4">
                        <button type="button" onclick="closeModal('koordinatorModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">Batal</button>
                        <button type="submit" id="btnSaveCoord" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center gap-2 shadow-sm">
                            <i class="fas fa-save"></i> Simpan Pilihan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
window.BASE_URL = '<?= BASE_URL ?>';
document.addEventListener('DOMContentLoaded', loadAsisten);

// --- 1. LOAD DATA UTAMA ---
function loadAsisten() {
    fetch(API_URL + '/asisten').then(res => res.json()).then(res => {
        const tbody = document.getElementById('tableBody'); tbody.innerHTML = ''; 
        if((res.status === 'success' || res.code === 200) && res.data && res.data.length > 0) {
            res.data.forEach((item, index) => {
                let statusBadge = '';
                if (item.isKoordinator == 1) { statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200"><i class="fas fa-crown mr-1 text-xs"></i> Koordinator</span>`; } 
                else {
                    let st = item.statusAktif == '1' ? 'Asisten' : (item.statusAktif == '0' ? 'Tidak Aktif' : item.statusAktif);
                    let cls = st === 'Tidak Aktif' ? 'bg-red-100 text-red-800' : (st === 'CA' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800');
                    statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${cls}">${st}</span>`;
                }
                const fotoUrl = item.foto ? (item.foto.includes('http') ? item.foto : ASSETS_URL + '/assets/uploads/' + item.foto) : 'https://placehold.co/50x50?text=Foto';
                const row = `
                    <tr onclick="openDetailModal(${item.idAsisten})" class="hover:bg-gray-50 transition-colors duration-150 group border-b border-gray-100 cursor-pointer">
                        <td class="px-6 py-4 text-center font-medium text-gray-500">${index + 1}</td>
                        <td class="px-6 py-4"><div class="flex justify-center"><img src="${fotoUrl}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-md"></div></td>
                        <td class="px-6 py-4"><div class="flex flex-col"><span class="font-bold text-gray-800 text-base">${item.nama}</span><span class="text-xs text-gray-500 mt-0.5 flex items-center gap-1"><i class="fas fa-envelope text-gray-300"></i> ${item.email || '-'}</span></div></td>
                        <td class="px-6 py-4 text-gray-600 font-medium">${item.jurusan || '-'}</td>
                        <td class="px-6 py-4 text-center">${statusBadge}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center gap-2">
                                <button onclick="openFormModal(${item.idAsisten}, event)" class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white transition-all duration-200 flex items-center justify-center"><i class="fas fa-pen text-xs"></i></button>
                                <button onclick="hapusAsisten(${item.idAsisten}, event)" class="w-9 h-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-500 hover:text-white transition-all duration-200 flex items-center justify-center"><i class="fas fa-trash-alt text-xs"></i></button>
                            </div>
                        </td>
                    </tr>`;
                tbody.innerHTML += row;
            });
        } else { tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada data asisten</td></tr>`; }
    });
}

// --- 2. MODAL DETAIL ---
function openDetailModal(id) {
    document.getElementById('detailModal').classList.remove('hidden'); document.body.style.overflow = 'hidden';
    document.getElementById('mNama').innerText = "Memuat...";
    fetch(API_URL + '/asisten/' + id).then(res => res.json()).then(res => {
        if((res.status === 'success' || res.code === 200) && res.data) {
            const d = res.data;
            document.getElementById('mNama').innerText = d.nama; document.getElementById('mJurusan').innerText = d.jurusan || '-';
            document.getElementById('mEmail').innerText = d.email || '-'; document.getElementById('mBio').innerText = d.bio || 'Tidak ada bio.';
            const fotoUrl = d.foto ? (d.foto.includes('http') ? d.foto : ASSETS_URL + '/assets/uploads/' + d.foto) : `https://placehold.co/150x150?text=${d.nama.charAt(0)}`;
            document.getElementById('mFoto').src = fotoUrl;
            const bDiv=document.getElementById('mBadges'); bDiv.innerHTML=''; let st=d.statusAktif=='1'?'Asisten':(d.statusAktif=='0'?'Tidak Aktif':d.statusAktif); let color=st==='Asisten'?'bg-emerald-100 text-emerald-800':'bg-gray-100 text-gray-800'; bDiv.innerHTML+=`<span class="${color} px-3 py-1 rounded-full text-xs font-bold border border-transparent">${st}</span>`; if(d.isKoordinator==1){bDiv.innerHTML+=`<span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold border border-blue-700 shadow flex items-center gap-1"><i class="fas fa-crown text-yellow-300"></i> Koordinator</span>`}
            const lnk=document.getElementById('mLinkedin'); if(d.linkedin){lnk.href=d.linkedin;lnk.classList.remove('hidden');lnk.classList.add('flex')}else{lnk.classList.add('hidden')}
            const sDiv=document.getElementById('mSkills'); sDiv.innerHTML=''; let skills=d.skills; try{if(skills.includes('['))skills=JSON.parse(skills);else skills=skills.split(',')}catch(e){if(skills)skills=skills.split(',')}
            if(Array.isArray(skills)){skills.forEach(s=>{if(s.trim())sDiv.innerHTML+=`<span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-xs font-bold border border-gray-200">${s.trim()}</span>`})}else{sDiv.innerHTML='<span class="text-gray-400 text-xs italic">Tidak ada data</span>'}
        }
    });
}

// --- 3. MODAL FORM ---
function openFormModal(id = null, event = null) {
    if(event) event.stopPropagation();
    document.getElementById('formModal').classList.remove('hidden'); document.body.style.overflow = 'hidden';
    document.getElementById('formMessage').classList.add('hidden');
    document.getElementById('asistenForm').reset();
    document.getElementById('inputIdAsisten').value = '';
    document.getElementById('fotoPreviewInfo').classList.add('hidden');

    if (id) {
        document.getElementById('formModalTitle').innerHTML = '<i class="fas fa-user-edit text-emerald-600"></i> Edit Asisten';
        document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> Update Data';
        fetch(API_URL + '/asisten/' + id).then(res => res.json()).then(res => {
            if(res.data) {
                const d = res.data;
                document.getElementById('inputIdAsisten').value = d.idAsisten;
                document.getElementById('inputNama').value = d.nama;
                document.getElementById('inputEmail').value = d.email;
                document.getElementById('inputJurusan').value = d.jurusan || 'Teknik Informatika';
                document.getElementById('inputLinkedin').value = d.linkedin || '';
                document.getElementById('inputBio').value = d.bio || '';
                let status = d.statusAktif; if (status == '1') status = 'Asisten'; if (status == '0') status = 'Tidak Aktif';
                document.getElementById('inputStatus').value = status || 'Asisten';
                let skillsStr = ''; try { let s = JSON.parse(d.skills); if(Array.isArray(s)) skillsStr = s.join(', '); else skillsStr = d.skills; } catch(e) { skillsStr = d.skills || ''; }
                document.getElementById('inputSkills').value = skillsStr;
                if(d.foto) document.getElementById('fotoPreviewInfo').classList.remove('hidden');
            }
        });
    } else {
        document.getElementById('formModalTitle').innerHTML = '<i class="fas fa-user-plus text-emerald-600"></i> Tambah Asisten Baru';
        document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> Simpan Data';
    }
}

document.getElementById('asistenForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSave'); const formMsg = document.getElementById('formMessage');
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
    showLoading('Menyimpan data asisten...');
    const formData = new FormData(this);
    const id = document.getElementById('inputIdAsisten').value;
    const url = id ? API_URL + '/asisten/' + id : API_URL + '/asisten';
    fetch(url, { method: 'POST', body: formData }).then(res => res.json()).then(data => {
        hideLoading();
        if (data.status === 'success' || data.code === 201 || data.code === 200) { 
            closeModal('formModal'); 
            loadAsisten(); 
            showSuccess(id ? 'Data asisten berhasil diperbarui!' : 'Asisten baru berhasil ditambahkan!'); 
        } 
        else { throw new Error(data.message || 'Gagal menyimpan'); }
    }).catch(err => { 
        hideLoading();
        formMsg.innerHTML = `<div class="bg-red-50 text-red-800 p-3 rounded text-sm"><i class="fas fa-exclamation-circle"></i> ${err.message}</div>`; 
        formMsg.classList.remove('hidden'); 
        showError(err.message);
    })
    .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data'; });
});

// --- 4. LOGIKA MODAL PILIH KOORDINATOR (NEW!) ---
function openKoordinatorModal() {
    document.getElementById('koordinatorModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    const listDiv = document.getElementById('coordList');
    listDiv.innerHTML = '<div class="text-center py-4 text-gray-400"><i class="fas fa-circle-notch fa-spin"></i> Memuat daftar...</div>';
    
    fetch(API_URL + '/asisten').then(res => res.json()).then(res => {
        listDiv.innerHTML = '';
        if ((res.status === 'success' || res.code === 200) && res.data) {
            // Set Current Coordinator UI
            const current = res.data.find(a => a.isKoordinator == 1);
            if (current) {
                document.getElementById('currentCoordName').innerText = current.nama;
                document.getElementById('currentCoordName').classList.remove('text-gray-400', 'italic');
            } else {
                document.getElementById('currentCoordName').innerText = "Belum ditentukan";
                document.getElementById('currentCoordName').classList.add('text-gray-400', 'italic');
            }

            // Generate List
            // Filter hanya asisten aktif / 'Asisten'
            const aktif = res.data.filter(a => a.statusAktif == 1 || a.statusAktif === 'Asisten');
            if (aktif.length === 0) {
                listDiv.innerHTML = '<div class="text-center py-4 text-gray-400">Tidak ada asisten aktif.</div>';
                return;
            }

            aktif.forEach(item => {
                const isSelected = item.isKoordinator == 1;
                const fotoUrl = item.foto ? (item.foto.includes('http') ? item.foto : ASSETS_URL + '/assets/uploads/' + item.foto) : 'https://placehold.co/50x50?text=Foto';
                
                // Item List Style
                const div = document.createElement('label');
                div.className = `flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-all ${isSelected ? 'bg-blue-50 border-blue-500 ring-1 ring-blue-500' : 'bg-white border-gray-200 hover:bg-gray-50'}`;
                div.innerHTML = `
                    <input type="radio" name="idKoordinator" value="${item.idAsisten}" class="w-4 h-4 text-blue-600" ${isSelected ? 'checked' : ''}>
                    <img src="${fotoUrl}" class="w-10 h-10 rounded-full object-cover border border-gray-300">
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-800">${item.nama}</p>
                        <p class="text-xs text-gray-500">${item.jurusan || '-'} ${isSelected ? '<span class="ml-2 text-blue-600 font-bold">(Current)</span>' : ''}</p>
                    </div>
                `;
                // Simple toggle visual effect
                div.addEventListener('change', () => {
                    document.querySelectorAll('#coordList label').forEach(el => el.className = 'flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-all bg-white border-gray-200 hover:bg-gray-50');
                    div.className = 'flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-all bg-blue-50 border-blue-500 ring-1 ring-blue-500';
                });
                listDiv.appendChild(div);
            });
        }
    });
}

document.getElementById('koordinatorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const selected = document.querySelector('input[name="idKoordinator"]:checked');
    const msg = document.getElementById('coordMessage');
    const btn = document.getElementById('btnSaveCoord');

    if (!selected) {
        msg.innerHTML = '<div class="text-red-500 text-sm">Pilih salah satu asisten dulu!</div>';
        msg.classList.remove('hidden');
        return;
    }

    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    showLoading('Memperbarui koordinator...');
    fetch(API_URL + '/asisten/' + selected.value + '/koordinator', { method: 'POST' })
    .then(res => res.json())
    .then(data => {
        hideLoading();
        if(data.status === 'success' || data.code === 200 || data.code === 201) {
            closeModal('koordinatorModal');
            loadAsisten(); // Reload tabel utama
            showSuccess('Koordinator berhasil diperbarui!');
        } else {
            msg.innerHTML = `<div class="text-red-500 text-sm">${data.message || 'Gagal update'}</div>`;
            msg.classList.remove('hidden');
            showError(data.message || 'Gagal memperbarui koordinator');
        }
    })
    .catch(err => {
        hideLoading();
        console.error(err);
        showError('Kesalahan sistem saat update koordinator');
    })
    .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan Pilihan'; });
});

// --- HELPER FUNCTIONS ---
function closeModal(modalId) { document.getElementById(modalId).classList.add('hidden'); document.body.style.overflow = 'auto'; }
document.onkeydown = function(evt) { 
    if (evt.keyCode == 27) { 
        closeModal('detailModal'); closeModal('formModal'); closeModal('koordinatorModal'); 
    } 
};
function hapusAsisten(id, event) { 
    if(event) event.stopPropagation();
    confirmDelete(() => {
        showLoading('Menghapus data...');
        fetch(API_URL + '/asisten/' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(() => { 
            hideLoading();
            loadAsisten(); 
            showSuccess('Data asisten berhasil dihapus!'); 
        })
        .catch(err => {
            hideLoading();
            showError('Gagal menghapus data');
        });
    });
}
</script>