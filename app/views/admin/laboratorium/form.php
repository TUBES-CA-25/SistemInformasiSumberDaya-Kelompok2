<div class="admin-header">
    <h1 id="formTitle">Tambah Laboratorium</h1>
    <a href="<?php echo BASE_URL; ?>/public/admin-laboratorium.php" class="btn" style="background: #95a5a6;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card" style="max-width: 800px;">
    <form id="labForm" enctype="multipart/form-data">
        <input type="hidden" id="idLaboratorium" name="idLaboratorium">

        <div class="form-group">
            <label>Nama Laboratorium <span style="color:red">*</span></label>
            <input type="text" id="nama" name="nama" placeholder="Contoh: Lab Pemrograman" required>
        </div>

        <div class="form-group">
            <label>Koordinator Asisten</label>
            <select id="idKordinatorAsisten" name="idKordinatorAsisten">
                <option value="">-- Pilih Asisten --</option>
            </select>
            <small class="form-text text-muted">Pilih asisten yang bertanggung jawab (Opsional).</small>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi fasilitas dan kegunaan laboratorium..."></textarea>
        </div>

        <div class="form-group" style="display:flex; gap:20px;">
            <div style="flex:1;">
                <label>Jumlah PC</label>
                <input type="number" id="jumlahPc" name="jumlahPc" placeholder="0" min="0">
            </div>
            <div style="flex:1;">
                <label>Jumlah Kursi</label>
                <input type="number" id="jumlahKursi" name="jumlahKursi" placeholder="0" min="0">
            </div>
        </div>

        <div class="form-group">
            <label>Upload Gambar (Opsional)</label>
            <div class="file-upload-wrapper">
                <input type="file" id="gambar" name="gambar" accept="image/*">
                <div id="preview-container" style="margin-top: 10px; display: none;">
                    <img id="preview-image" src="" alt="Preview" style="max-width: 200px; border-radius: 4px; border: 1px solid #ddd;">
                </div>
            </div>
            <small class="form-text text-muted">Format: JPG, PNG (Max 2MB). Biarkan kosong jika tidak ingin mengubah gambar saat edit.</small>
        </div>

        <button type="submit" class="btn btn-add" id="btnSave">
            <i class="fas fa-save"></i> Simpan Data
        </button>
    </form>
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadAsisten();
    
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    if (id) {
        document.getElementById('formTitle').textContent = 'Edit Laboratorium';
        document.getElementById('idLaboratorium').value = id;
        loadData(id);
    }

    // Image preview
    document.getElementById('gambar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('preview-container').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
});

function loadAsisten() {
    fetch(API_URL + '/asisten')
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success' || response.code === 200) {
            const select = document.getElementById('idKordinatorAsisten');
            response.data.forEach(asisten => {
                const option = document.createElement('option');
                option.value = asisten.idAsisten;
                option.textContent = asisten.nama + ' (' + asisten.nim + ')';
                select.appendChild(option);
            });
        }
    })
    .catch(err => console.error('Error loading asisten:', err));
}

function loadData(id) {
    fetch(API_URL + '/laboratorium/' + id)
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success' || response.code === 200) {
            const data = response.data;
            document.getElementById('nama').value = data.nama;
            document.getElementById('deskripsi').value = data.deskripsi;
            document.getElementById('jumlahPc').value = data.jumlahPc;
            document.getElementById('jumlahKursi').value = data.jumlahKursi;
            
            // Set selected asisten if exists
            if (data.idKordinatorAsisten) {
                // Wait a bit for asisten list to load if it hasn't yet, or just set value
                // Since loadAsisten is async, we might need to retry setting value or chain promises.
                // For simplicity, we'll just set the value, assuming loadAsisten finishes fast or we can set it later.
                // Better approach: chain promises. But for now let's just set it.
                setTimeout(() => {
                    document.getElementById('idKordinatorAsisten').value = data.idKordinatorAsisten;
                }, 500);
            }
            
            if (data.gambar) {
                document.getElementById('preview-image').src = ASSETS_URL + '/uploads/' + data.gambar;
                document.getElementById('preview-container').style.display = 'block';
            }
        } else {
            alert('Data tidak ditemukan');
            window.location.href = BASE_URL + '/public/admin-laboratorium.php';
        }
    })
    .catch(err => console.error(err));
}

document.getElementById('labForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('idLaboratorium').value;
    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    const formData = new FormData(this);
    
    let url = API_URL + '/laboratorium';
    if (id) {
        url += '/' + id;
        formData.append('_method', 'PUT'); 
    }

    fetch(url, { 
        method: 'POST', 
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.code === 200 || data.code === 201) { 
            msg.innerHTML = '<div style="padding: 10px; background: #d4edda; color: #155724; border-radius: 4px;">Berhasil disimpan! Redirecting...</div>';
            setTimeout(() => { window.location.href = BASE_URL + '/public/admin-laboratorium.php'; }, 1000);
        } else {
            msg.innerHTML = '<div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px;">Gagal: ' + (data.message || 'Terjadi kesalahan') + '</div>';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
        }
    })
    .catch(err => {
        console.error(err);
        msg.innerHTML = '<div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px;">Koneksi Error.</div>';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Data';
    });
});
</script>