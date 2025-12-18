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
    <h1><i class="fas fa-user-edit"></i> Formulir Asisten</h1>
    <a href="<?php echo BASE_URL; ?>/public/admin-asisten.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card" style="max-width: 800px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <form id="asistenForm" enctype="multipart/form-data">
        
        <input type="hidden" id="idAsisten" name="idAsisten">

        <div class="form-group">
            <label><i class="fas fa-user"></i> Nama Lengkap <span style="color:red">*</span></label>
            <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap..." required>
        </div>

        <div class="form-group">
            <label><i class="fas fa-envelope"></i> Email <span style="color:red">*</span></label>
            <input type="email" id="email" name="email" placeholder="email@umi.ac.id" required>
        </div>

        <div class="form-group">
            <label><i class="fas fa-graduation-cap"></i> Jurusan</label> 
            <input type="text" id="jurusan" name="jurusan" placeholder="Contoh: Teknik Informatika">
        </div>

        <div class="form-group">
            <label><i class="fas fa-camera"></i> Upload Foto</label>
            <input type="file" id="foto" name="foto" accept="image/*">
            <small id="fotoInfo" style="color: #666; display: none; margin-top: 5px;">
                <i class="fas fa-info-circle"></i> Foto saat ini: <span id="fotoCurrent" style="font-weight: bold;"></span>
            </small>
        </div>

        <div class="form-group">
            <label><i class="fas fa-toggle-on"></i> Status Aktif</label>
            <select id="statusAktif" name="statusAktif">
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success" id="btnSave" style="width: 100%; padding: 12px; font-size: 16px;">
            <i class="fas fa-save"></i> Simpan Data
        </button>
    </form>
    
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cek apakah ada ID di URL (mode edit)
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    
    if (id) {
        // Mode edit - load data
        loadAsistenData(id);
    }
});

function loadAsistenData(id) {
    fetch(API_URL + '/asisten/' + id)
    .then(response => response.json())
    .then(data => {
        if ((data.status === 'success' || data.code === 200) && data.data) {
            const asisten = data.data;
            document.getElementById('idAsisten').value = asisten.idAsisten;
            document.getElementById('nama').value = asisten.nama || '';
            document.getElementById('email').value = asisten.email || '';
            document.getElementById('jurusan').value = asisten.jurusan || '';
            document.getElementById('statusAktif').value = asisten.statusAktif || '1';
            
            if (asisten.foto) {
                document.getElementById('fotoInfo').style.display = 'block';
                document.getElementById('fotoCurrent').innerText = asisten.foto;
            }
            
            document.querySelector('.admin-header h1').innerHTML = '<i class="fas fa-user-edit"></i> Edit Asisten: ' + asisten.nama;
            document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> Update Data';
        }
    })
    .catch(error => {
        console.error('Error loading asisten:', error);
    });
}

document.getElementById('asistenForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    btn.disabled = true;
    
    const idAsisten = document.getElementById('idAsisten').value;
    const isEdit = idAsisten ? true : false;
    
    btn.innerHTML = isEdit ? '<i class="fas fa-spinner fa-spin"></i> Mengupdate...' : '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    // Pakai FormData agar bisa upload file
    const form = document.getElementById('asistenForm');
    const formData = new FormData(form);
    // Field sudah otomatis masuk karena ada attribute name di HTML

    const url = isEdit ? (API_URL + '/asisten/' + idAsisten) : (API_URL + '/asisten');
    const method = 'POST'; // Always POST untuk FormData dengan file

    fetch(url, {
        method: method,
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.status === 'success' || data.code === 201 || data.code === 200) {
                msg.innerHTML = '<span style="color:green"><i class="fas fa-check-circle"></i> ' + (isEdit ? 'Data berhasil diupdate! ' : 'Berhasil disimpan! ') + 'Mengalihkan...</span>';
                setTimeout(() => { window.location.href = BASE_URL + '/public/admin-asisten.php'; }, 1000);
            } else {
                msg.innerHTML = '<span style="color:red"><i class="fas fa-exclamation-circle"></i> Gagal: ' + (data.message || 'Error validasi') + '</span>';
                btn.disabled = false;
                btn.innerHTML = isEdit ? '<i class="fas fa-save"></i> Update Data' : '<i class="fas fa-save"></i> Simpan Data';
            }
        } catch (e) {
            console.error('Parse error:', text);
            msg.innerHTML = '<span style="color:red">Terjadi kesalahan: ' + text + '</span>';
            btn.disabled = false;
            btn.innerHTML = isEdit ? '<i class="fas fa-save"></i> Update Data' : '<i class="fas fa-save"></i> Simpan Data';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        msg.innerHTML = '<span style="color:red">Terjadi kesalahan koneksi.</span>';
        btn.disabled = false;
        btn.innerText = isEdit ? 'Update Data' : 'Simpan Data';
    });
});
</script>