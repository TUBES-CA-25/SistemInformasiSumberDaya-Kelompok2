<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
        <i class="fas fa-crown text-blue-600"></i> 
        Pilih Koordinator Lab
    </h1>
    <a href="javascript:void(0)" onclick="navigate('admin/asisten')" 
       class="mt-4 sm:mt-0 bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all duration-200 flex items-center gap-2 font-medium">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3 text-blue-800">
        <i class="fas fa-info-circle text-xl mt-0.5"></i>
        <div>
            <p class="font-medium">Informasi</p>
            <p class="text-sm text-blue-700 mt-1">Pilih satu asisten untuk menjadi koordinator lab. Koordinator sebelumnya akan otomatis diturunkan menjadi asisten biasa.</p>
        </div>
    </div>

    <div id="currentKoordinator" class="bg-white rounded-xl shadow-md border border-gray-100 p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <i class="fas fa-crown text-8xl text-yellow-500 transform rotate-12"></i>
        </div>
        
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-crown text-yellow-500"></i> Koordinator Saat Ini
        </h3>
        
        <div class="flex items-center gap-4 relative z-10">
            <img src="https://placehold.co/70x70" id="currentFoto" class="w-16 h-16 rounded-full border-4 border-yellow-100 object-cover shadow-sm">
            <div>
                <p id="currentName" class="text-xl font-bold text-gray-800">-</p>
                <div class="flex flex-col sm:flex-row sm:gap-4 text-sm text-gray-500 mt-1">
                    <span class="flex items-center gap-1"><i class="fas fa-graduation-cap"></i> <span id="currentJurusan">-</span></span>
                    <span class="flex items-center gap-1"><i class="fas fa-envelope"></i> <span id="currentEmail">-</span></span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-list-ul text-blue-600"></i> Pilih Koordinator Baru
        </h3>

        <form id="koordinatorForm" class="space-y-6">
            <div id="asistenList" class="space-y-2 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar border border-gray-100 rounded-lg p-2">
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-circle-notch fa-spin text-2xl mb-2"></i>
                    <p>Memuat daftar asisten...</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-100">
                <button type="submit" id="btnSave" 
                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-lg shadow transition-all duration-200 flex justify-center items-center gap-2">
                    <i class="fas fa-check"></i> Simpan Pilihan
                </button>
                <button type="button" onclick="navigate('admin/asisten')" 
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-4 rounded-lg transition-all duration-200 flex justify-center items-center gap-2">
                    <i class="fas fa-times"></i> Batal
                </button>
            </div>

            <div id="message" class="hidden"></div>
        </form>
    </div>
</div>

<style>
    /* Custom Scrollbar untuk list asisten */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadKoordinatorInfo();
    loadAsistenList();
});

function loadKoordinatorInfo() {
    fetch(API_URL + '/asisten')
    .then(response => response.json())
    .then(res => {
        if ((res.status === 'success' || res.code === 200) && res.data) {
            const current = res.data.find(a => a.isKoordinator == 1);
            if (current) {
                document.getElementById('currentName').innerText = current.nama;
                document.getElementById('currentJurusan').innerText = current.jurusan || '-';
                document.getElementById('currentEmail').innerText = current.email || '-';
                
                if (current.foto) {
                    const fotoUrl = current.foto.includes('http') 
                        ? current.foto 
                        : ASSETS_URL + '/assets/uploads/' + current.foto;
                    document.getElementById('currentFoto').src = fotoUrl;
                } else {
                    document.getElementById('currentFoto').src = 'https://placehold.co/70x70?text=Foto';
                }
            } else {
                // Tampilan jika belum ada koordinator
                document.getElementById('currentKoordinator').innerHTML = `
                    <div class="text-center py-6 text-gray-500">
                        <i class="fas fa-crown text-4xl mb-2 text-gray-300"></i>
                        <p>Belum ada koordinator yang ditentukan</p>
                    </div>`;
            }
        }
    })
    .catch(error => console.error('Error loading koordinator:', error));
}

function loadAsistenList() {
    fetch(API_URL + '/asisten')
    .then(response => response.json())
    .then(res => {
        const listDiv = document.getElementById('asistenList');
        listDiv.innerHTML = '';

        if ((res.status === 'success' || res.code === 200) && res.data && res.data.length > 0) {
            // Filter hanya asisten aktif (statusAktif == 1 atau 'Asisten')
            // Sesuaikan logika filter ini dengan data API Anda
            const aktif = res.data.filter(a => a.statusAktif == 1 || a.statusAktif === 'Asisten');
            
            if (aktif.length === 0) {
                listDiv.innerHTML = `
                    <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <p>Tidak ada asisten aktif yang tersedia.</p>
                    </div>`;
                return;
            }

            aktif.forEach(item => {
                const isSelected = item.isKoordinator == 1;
                const fotoUrl = item.foto 
                    ? (item.foto.includes('http') ? item.foto : ASSETS_URL + '/assets/uploads/' + item.foto) 
                    : 'https://placehold.co/50x50?text=Foto';
                
                // --- ITEM LIST DENGAN TAILWIND ---
                const itemDiv = document.createElement('label'); // Gunakan label agar bisa diklik area luas
                itemDiv.className = `
                    flex items-center gap-4 p-3 rounded-lg border cursor-pointer transition-all duration-200
                    ${isSelected ? 'bg-blue-50 border-blue-500 ring-1 ring-blue-500' : 'bg-white border-gray-200 hover:bg-gray-50 hover:border-blue-300'}
                `;
                
                itemDiv.innerHTML = `
                    <div class="flex-shrink-0">
                        <input type="radio" name="idKoordinator" value="${item.idAsisten}" 
                               ${isSelected ? 'checked' : ''} 
                               class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                    </div>
                    <img src="${fotoUrl}" class="w-10 h-10 rounded-full object-cover border border-gray-200" onerror="this.src='https://placehold.co/50x50?text=Foto'">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate">${item.nama}</p>
                        <p class="text-xs text-gray-500 truncate">${item.jurusan || '-'} • ${item.email || ''}</p>
                    </div>
                    ${isSelected ? '<span class="text-xs font-semibold text-blue-600 bg-blue-100 px-2 py-1 rounded">Current</span>' : ''}
                `;

                // Event listener untuk styling visual saat diklik
                itemDiv.addEventListener('change', function() {
                    // Reset semua style item
                    document.querySelectorAll('#asistenList label').forEach(el => {
                        el.className = 'flex items-center gap-4 p-3 rounded-lg border cursor-pointer transition-all duration-200 bg-white border-gray-200 hover:bg-gray-50 hover:border-blue-300';
                    });
                    // Set style active
                    if(this.querySelector('input').checked) {
                        this.className = 'flex items-center gap-4 p-3 rounded-lg border cursor-pointer transition-all duration-200 bg-blue-50 border-blue-500 ring-1 ring-blue-500';
                    }
                });

                listDiv.appendChild(itemDiv);
            });
        }
    })
    .catch(error => {
        console.error('Error loading asisten:', error);
        document.getElementById('asistenList').innerHTML = `
            <div class="p-4 text-center text-red-500 bg-red-50 rounded-lg border border-red-200">
                <i class="fas fa-exclamation-triangle mr-2"></i> Gagal memuat data asisten.
            </div>`;
    });
}

document.getElementById('koordinatorForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const selected = document.querySelector('input[name="idKoordinator"]:checked');
    if (!selected) {
        showMessage('Pilih satu asisten terlebih dahulu!', 'error');
        return;
    }

    const idAsisten = selected.value;
    const btn = document.getElementById('btnSave');
    
    // Loading State
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
    btn.classList.add('opacity-75', 'cursor-not-allowed');

    // API Call
    fetch(API_URL + '/asisten/' + idAsisten + '/koordinator', {
        method: 'POST'
    })
    .then(response => response.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.status === 'success' || data.code === 200 || data.code === 201) {
                showMessage('Koordinator berhasil diperbarui! Mengalihkan...', 'success');
                setTimeout(() => {
                    navigate('admin/asisten');
                }, 1500);
            } else {
                showMessage('Gagal: ' + (data.message || 'Error tidak diketahui'), 'error');
                resetButton(btn);
            }
        } catch (e) {
            console.error('Parse error:', text);
            showMessage('Terjadi kesalahan server.', 'error');
            resetButton(btn);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Gagal menyimpan. Cek koneksi internet.', 'error');
        resetButton(btn);
    });
});

function resetButton(btn) {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-check"></i> Simpan Pilihan';
    btn.classList.remove('opacity-75', 'cursor-not-allowed');
}

function showMessage(text, type) {
    const msgDiv = document.getElementById('message');
    msgDiv.classList.remove('hidden');
    
    if (type === 'success') {
        msgDiv.innerHTML = `
            <div class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                <i class="fas fa-check-circle mr-2 text-lg"></i>
                <span class="font-medium">${text}</span>
            </div>`;
    } else {
        msgDiv.innerHTML = `
            <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
                <i class="fas fa-exclamation-circle mr-2 text-lg"></i>
                <span class="font-medium">${text}</span>
            </div>`;
    }
}
</script>