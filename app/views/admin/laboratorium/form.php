<style>
/* Custom Button Styles */
.btn {
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white !important;
}

.btn-success {
    background-color: #27ae60;
}
.btn-success:hover {
    background-color: #219150;
    transform: translateY(-2px);
}

.btn-secondary {
    background-color: #95a5a6;
}
.btn-secondary:hover {
    background-color: #7f8c8d;
    transform: translateY(-2px);
}

.btn-success:disabled {
    background-color: #95a5a6;
    cursor: not-allowed;
    transform: none;
}
</style>

<div class="admin-header">
    <h1><i class="fas fa-desktop"></i> Formulir Laboratorium</h1>
    <a href="<?php echo BASE_URL; ?>/public/admin-laboratorium.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card" style="max-width: 800px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <form id="labForm" enctype="multipart/form-data">
        <input type="hidden" id="idLaboratorium">

        <div class="form-group">
            <label><i class="fas fa-tag"></i> Nama Laboratorium <span style="color:red">*</span></label>
            <input type="text" id="nama" placeholder="Contoh: Lab Pemrograman" required>
        </div>

        <div class="form-group">
            <label><i class="fas fa-user-tie"></i> Koordinator Asisten</label>
            <select id="idKordinatorAsisten">
                <option value="">-- Pilih Asisten --</option>
            </select>
        </div>

        <div class="form-group">
            <label><i class="fas fa-align-left"></i> Deskripsi</label>
            <textarea id="deskripsi" rows="3" placeholder="Deskripsi laboratorium..."></textarea>
        </div>

        <div class="form-group">
            <label><i class="fas fa-image"></i> Upload Gambar Lab</label>
            <input type="file" id="gambar" name="gambar" accept="image/*">
            <small style="color: #777;">Format: JPG, PNG. Max 2MB</small>
        </div>

        <div class="form-group" style="display:flex; gap:20px;">
            <div style="flex:1;">
                <label><i class="fas fa-desktop"></i> Jumlah PC</label>
                <input type="number" id="jumlahPc" placeholder="30" min="0">
            </div>
            <div style="flex:1;">
                <label><i class="fas fa-chair"></i> Jumlah Kursi</label>
                <input type="number" id="jumlahKursi" placeholder="40" min="0">
            </div>
        </div>

        <button type="submit" class="btn btn-success" id="btnSave" style="width: 100%; padding: 12px; font-size: 16px;">
            <i class="fas fa-save"></i> Simpan Data
        </button>
    </form>
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadAsisten();
    
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    if (id) {
        loadLaboratoriumData(id);
    }
});

function loadAsisten() {
    fetch(API_URL + '/asisten').then(res => res.json()).then(data => {
        if(data.status === 'success') {
            const select = document.getElementById('idKordinatorAsisten');
            data.data.forEach(ast => {
                const option = document.createElement('option');
                option.value = ast.idAsisten;
                option.textContent = ast.nama;
                select.appendChild(option);
            });
        }
    });
}

function loadLaboratoriumData(id) {
    fetch(API_URL + '/laboratorium/' + id)
    .then(res => res.json())
    .then(response => {
        if ((response.status === 'success' || response.code === 200) && response.data) {
            const data = response.data;
            document.getElementById('idLaboratorium').value = data.idLaboratorium;
            document.getElementById('nama').value = data.nama;
            document.getElementById('idKordinatorAsisten').value = data.idKordinatorAsisten || '';
            document.getElementById('deskripsi').value = data.deskripsi || '';
            document.getElementById('jumlahPc').value = data.jumlahPc || 0;
            document.getElementById('jumlahKursi').value = data.jumlahKursi || 0;
            
            document.querySelector('.admin-header h1').innerHTML = '<i class="fas fa-edit"></i> Edit Laboratorium';
            document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> Update Data';
        }
    })
    .catch(err => console.error('Error loading lab:', err));
}

document.getElementById('labForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    const formData = new FormData(this);
    formData.append('nama', document.getElementById('nama').value);
    formData.append('idKordinatorAsisten', document.getElementById('idKordinatorAsisten').value);
    formData.append('deskripsi', document.getElementById('deskripsi').value);
    formData.append('jumlahPc', document.getElementById('jumlahPc').value);
    formData.append('jumlahKursi', document.getElementById('jumlahKursi').value);

    fetch(API_URL + '/laboratorium', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.code === 201) {
            msg.innerHTML = '<div style="padding: 10px; background: #d4edda; color: #155724; border-radius: 4px; border: 1px solid #c3e6cb;"><i class="fas fa-check-circle"></i> Berhasil disimpan! Mengalihkan...</div>';
            setTimeout(() => { window.location.href = BASE_URL + '/public/admin-laboratorium.php'; }, 1500);
        } else {
            msg.innerHTML = '<div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px; border: 1px solid #f5c6cb;"><i class="fas fa-exclamation-circle"></i> Gagal: ' + (data.message || 'Error tidak diketahui') + '</div>';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        msg.innerHTML = '<div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px; border: 1px solid #f5c6cb;"><i class="fas fa-wifi"></i> Gagal koneksi ke server</div>';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
    });
});
</script>