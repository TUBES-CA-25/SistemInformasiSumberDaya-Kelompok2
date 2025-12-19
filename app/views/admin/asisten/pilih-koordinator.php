<style>
.koordinator-container {
    max-width: 700px;
    margin: 0 auto;
}

.koordinator-current {
    background: #f0f4ff;
    border-left: 4px solid #667eea;
    padding: 20px;
    margin-bottom: 30px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.koordinator-current h3 {
    margin-top: 0;
    color: #667eea;
    display: flex;
    align-items: center;
    gap: 10px;
}

.koordinator-info {
    display: flex;
    gap: 15px;
    align-items: center;
    margin-top: 15px;
}

.koordinator-foto {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #667eea;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.koordinator-details {
    flex: 1;
}

.koordinator-details p {
    margin: 5px 0;
    font-size: 14px;
}

.koordinator-details strong {
    color: #333;
    font-size: 16px;
}

.asisten-list {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    max-height: 400px;
    overflow-y: auto;
}

.asisten-item {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    gap: 15px;
    align-items: center;
    cursor: pointer;
    transition: background-color 0.2s;
}

.asisten-item:hover {
    background-color: #f9f9f9;
}

.asisten-item input[type="radio"] {
    cursor: pointer;
    width: 18px;
    height: 18px;
    accent-color: #667eea;
}

.asisten-item.selected {
    background-color: #eef2ff;
    border-left: 3px solid #667eea;
}

.asisten-foto {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ddd;
}

.asisten-details {
    flex: 1;
}

.asisten-details p {
    margin: 3px 0;
    font-size: 13px;
    color: #666;
}

.asisten-details strong {
    display: block;
    color: #333;
}

.action-buttons {
    display: flex;
    gap: 10px;
    margin-top: 25px;
}

.btn {
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
}

.btn-success {
    background: #27ae60;
    color: white;
    flex: 1;
}

.btn-success:hover {
    background: #229954;
    transform: translateY(-2px);
}

.btn-success:disabled {
    background: #95a5a6;
    cursor: not-allowed;
    transform: none;
}

.btn-secondary {
    background: #95a5a6;
    color: white;
    flex: 1;
}

.btn-secondary:hover {
    background: #7f8c8d;
}

.message {
    padding: 12px;
    border-radius: 5px;
    margin-top: 15px;
    display: none;
}

.message.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    display: block;
}

.message.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    display: block;
}

.info-text {
    background: #e3f2fd;
    color: #1565c0;
    padding: 15px;
    border-radius: 5px;
    font-size: 14px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid #bbdefb;
}
</style>

<div class="admin-header">
    <h1><i class="fas fa-user-check"></i> Pilih Koordinator Lab</h1>
    <a href="javascript:void(0)" onclick="navigate('admin/asisten')" class="btn btn-secondary" style="flex: initial;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card koordinator-container">
    <div class="info-text">
        <i class="fas fa-info-circle" style="font-size: 20px;"></i>
        <div>Pilih satu asisten untuk menjadi koordinator lab. Koordinator sebelumnya akan otomatis dihapus statusnya.</div>
    </div>

    <div class="koordinator-current" id="currentKoordinator">
        <h3><i class="fas fa-crown"></i> Koordinator Saat Ini</h3>
        <div class="koordinator-info">
            <img src="https://placehold.co/60x60" class="koordinator-foto" id="currentFoto">
            <div class="koordinator-details">
                <p><strong id="currentName">-</strong></p>
                <p><span style="color: #7f8c8d;"><i class="fas fa-graduation-cap"></i> Jurusan:</span> <span id="currentJurusan">-</span></p>
                <p><span style="color: #7f8c8d;"><i class="fas fa-envelope"></i> Email:</span> <span id="currentEmail">-</span></p>
            </div>
        </div>
    </div>

    <h3 style="margin-top: 0; color: #333;"><i class="fas fa-list"></i> Pilih Koordinator Baru</h3>
    <form id="koordinatorForm">
        <div class="asisten-list" id="asistenList">
            <p style="padding: 20px; text-align: center; color: #999;">
                <i class="fas fa-spinner fa-spin"></i> Memuat data asisten...
            </p>
        </div>

        <div class="action-buttons">
            <button type="submit" class="btn btn-success" id="btnSave">
                <i class="fas fa-check"></i> Simpan Pilihan
            </button>
            <a href="javascript:void(0)" onclick="navigate('admin/asisten')" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>

    <div class="message" id="message"></div>
</div>

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
                document.getElementById('currentJurusan').innerText = current.jurusan || '—';
                document.getElementById('currentEmail').innerText = current.email;
                
                if (current.foto) {
                    const fotoUrl = current.foto.includes('http') 
                        ? current.foto 
                        : BASE_URL + '/assets/uploads/' + current.foto;
                    document.getElementById('currentFoto').src = fotoUrl;
                } else {
                    document.getElementById('currentFoto').src = 'https://placehold.co/60x60?text=Foto';
                }
            } else {
                document.getElementById('currentKoordinator').innerHTML = '<p style="color: #999; padding: 10px;"><i class="fas fa-info-circle"></i> Belum ada koordinator yang ditentukan</p>';
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
            const aktif = res.data.filter(a => a.statusAktif == 1);
            
            if (aktif.length === 0) {
                listDiv.innerHTML = '<p style="padding: 20px; text-align: center; color: #999;">Tidak ada asisten aktif</p>';
                return;
            }

            aktif.forEach(item => {
                const isSelected = item.isKoordinator == 1;
                const fotoUrl = item.foto 
                    ? (item.foto.includes('http') ? item.foto : BASE_URL + '/assets/uploads/' + item.foto) 
                    : 'https://placehold.co/50x50?text=Foto';
                
                const itemDiv = document.createElement('div');
                itemDiv.className = 'asisten-item' + (isSelected ? ' selected' : '');
                itemDiv.innerHTML = `
                    <input type="radio" name="idKoordinator" value="${item.idAsisten}" ${isSelected ? 'checked' : ''}>
                    <img src="${fotoUrl}" class="asisten-foto" onerror="this.src='https://placehold.co/50x50?text=Foto'">
                    <div class="asisten-details">
                        <strong>${item.nama}</strong>
                        <p>${item.jurusan || '—'}</p>
                        <p>${item.email}</p>
                    </div>
                `;

                itemDiv.addEventListener('click', function() {
                    document.querySelector('input[name="idKoordinator"][value="' + item.idAsisten + '"]').checked = true;
                    document.querySelectorAll('.asisten-item').forEach(el => el.classList.remove('selected'));
                    this.classList.add('selected');
                });

                listDiv.appendChild(itemDiv);
            });
        }
    })
    .catch(error => {
        console.error('Error loading asisten:', error);
        document.getElementById('asistenList').innerHTML = '<p style="padding: 20px; color: red; text-align: center;"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data asisten</p>';
    });
}

document.getElementById('koordinatorForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const selected = document.querySelector('input[name="idKoordinator"]:checked');
    if (!selected) {
        showMessage('<i class="fas fa-exclamation-circle"></i> Pilih satu asisten terlebih dahulu', 'error');
        return;
    }

    const idAsisten = selected.value;
    const btn = document.getElementById('btnSave');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    // Update: set isKoordinator untuk asisten yang dipilih
    fetch(API_URL + '/asisten/' + idAsisten + '/koordinator', {
        method: 'POST'
    })
    .then(response => response.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.status === 'success' || data.code === 200 || data.code === 201) {
                showMessage('<i class="fas fa-check-circle"></i> Koordinator berhasil diperbarui! Mengalihkan...', 'success');
                setTimeout(() => {
                    navigate('admin/asisten');
                }, 1500);
            } else {
                showMessage('<i class="fas fa-times-circle"></i> Gagal: ' + (data.message || 'Error'), 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check"></i> Simpan Pilihan';
            }
        } catch (e) {
            console.error('Parse error:', text);
            showMessage('Terjadi kesalahan: ' + text, 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Simpan Pilihan';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Gagal menyimpan. Cek koneksi internet.', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Simpan Pilihan';
    });
});

function showMessage(text, type) {
    const msgDiv = document.getElementById('message');
    msgDiv.className = 'message ' + type;
    msgDiv.innerHTML = text;
}

</script>
