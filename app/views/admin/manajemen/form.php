<div class="admin-header">
    <h1>Formulir Manajemen Kepala Lab</h1>
    <a href="<?php echo BASE_URL; ?>/public/admin-manajemen.php" class="btn" style="background: #95a5a6;">← Kembali</a>
</div>

<div class="card" style="max-width: 800px;">
    <form id="manajemenForm" enctype="multipart/form-data">
        
        <input type="hidden" id="idManajemen">

        <div class="form-group">
            <label>Nama Lengkap <span style="color:red">*</span></label>
            <input type="text" id="nama" name="nama" placeholder="Contoh: Dr. Ahmad Rizki, S.Kom, M.Kom" required>
        </div>

        <div class="form-group">
            <label>Jabatan <span style="color:red">*</span></label>
            <input type="text" id="jabatan" name="jabatan" placeholder="Contoh: Kepala Lab Rekayasa Perangkat Lunak" required>
        </div>

        <div class="form-group">
            <label>Upload Foto</label>
            <input type="file" id="foto" name="foto" accept="image/*">
            <small id="fotoInfo" style="color: #666; display: none;">Foto saat ini: <span id="fotoCurrent"></span></small>
        </div>

        <button type="submit" class="btn btn-add" id="btnSave">Simpan Data</button>
    </form>
    
    <div id="message" style="margin-top: 15px;"></div>
</div>

<style>
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #ecf0f1;
}

.admin-header h1 {
    margin: 0;
    font-size: 28px;
    color: #2c3e50;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    background: #667eea;
    color: white;
}

.btn:hover {
    background: #5568d3;
}

.btn-add {
    background: #27ae60;
    color: white;
    padding: 12px 30px;
    font-size: 16px;
}

.btn-add:hover {
    background: #229954;
    transform: translateY(-2px);
}

.card {
    background: white;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="file"],
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #bdc3c7;
    border-radius: 5px;
    font-size: 14px;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
}

small {
    display: block;
    margin-top: 8px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    
    if (id) {
        loadManajemenData(id);
    }
});

function loadManajemenData(id) {
    fetch(API_URL + '/manajemen/' + id)
    .then(response => response.json())
    .then(data => {
        if ((data.status === 'success' || data.code === 200) && data.data) {
            const manajemen = data.data;
            document.getElementById('idManajemen').value = manajemen.idManajemen;
            document.getElementById('nama').value = manajemen.nama || '';
            document.getElementById('jabatan').value = manajemen.jabatan || '';
            
            if (manajemen.foto) {
                document.getElementById('fotoInfo').style.display = 'block';
                document.getElementById('fotoCurrent').innerText = manajemen.foto;
            }
            
            document.querySelector('.admin-header h1').innerText = 'Edit Manajemen: ' + manajemen.nama;
            document.getElementById('btnSave').innerText = 'Update Data';
        }
    })
    .catch(error => {
        console.error('Error loading manajemen:', error);
    });
}

document.getElementById('manajemenForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    btn.disabled = true;
    
    const idManajemen = document.getElementById('idManajemen').value;
    const isEdit = idManajemen ? true : false;
    
    btn.innerText = isEdit ? 'Mengupdate...' : 'Menyimpan...';

    const form = document.getElementById('manajemenForm');
    const formData = new FormData(form);
    
    // Debug: log FormData
    console.log('Form data:', {
        nama: formData.get('nama'),
        jabatan: formData.get('jabatan'),
        foto: formData.get('foto') ? 'File selected' : 'No file',
        idManajemen: idManajemen
    });

    const url = isEdit ? (API_URL + '/manajemen/' + idManajemen) : (API_URL + '/manajemen');
    const method = 'POST';

    fetch(url, {
        method: method,
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.status === 'success' || data.code === 201 || data.code === 200) {
                msg.innerHTML = '<span style="color:green">' + (isEdit ? 'Data berhasil diupdate! ' : 'Berhasil disimpan! ') + 'Mengalihkan...</span>';
                setTimeout(() => { window.location.href = BASE_URL + '/public/admin-manajemen.php'; }, 1000);
            } else {
                console.error('API Error:', data);
                msg.innerHTML = '<span style="color:red">Gagal: ' + (data.message || 'Error validasi') + '</span>';
                btn.disabled = false;
                btn.innerText = isEdit ? 'Update Data' : 'Simpan Data';
            }
        } catch (e) {
            console.error('Parse error:', text);
            msg.innerHTML = '<span style="color:red">Terjadi kesalahan: ' + text + '</span>';
            btn.disabled = false;
            btn.innerText = isEdit ? 'Update Data' : 'Simpan Data';
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
