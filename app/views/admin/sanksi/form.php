<div class="admin-header">
    <h1 id="formTitle">Tambah Sanksi Baru</h1>
    <a href="javascript:void(0)" onclick="navigate('admin/sanksi')" class="btn" style="background: #95a5a6;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card" style="max-width: 800px;">
    <form id="sanksiForm" enctype="multipart/form-data">
        <input type="hidden" id="id" name="id">

        <div class="form-group">
            <label>Judul Sanksi <span style="color:red">*</span></label>
            <input type="text" id="judul" name="judul" placeholder="Contoh: Keterlambatan Hadir" required>
            <small class="form-text text-muted">Nama singkat sanksi yang akan ditampilkan.</small>
        </div>

        <div class="form-group">
            <label>Deskripsi Sanksi</label>
            <textarea id="deskripsi" name="deskripsi" rows="6" placeholder="Jelaskan konsekuensi dan hukuman yang berlaku..."></textarea>
            <small class="form-text text-muted">Penjelasan lengkap tentang sanksi ini.</small>
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
            <i class="fas fa-save"></i> Simpan Sanksi
        </button>
    </form>
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    if (id) {
        document.getElementById('formTitle').textContent = 'Edit Sanksi';
        document.getElementById('id').value = id;
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

function loadData(id) {
    fetch(API_URL + '/sanksi-lab/' + id)
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success' || response.code === 200) {
            const data = response.data;
            document.getElementById('judul').value = data.judul;
            document.getElementById('deskripsi').value = data.deskripsi;
            
            // If there is an existing image, maybe show it? 
            // The API response structure for image path is unknown, skipping for now to avoid broken images.
        } else {
            alert('Data tidak ditemukan');
            navigate('admin/sanksi');
        }
    })
    .catch(err => console.error(err));
}

document.getElementById('sanksiForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('id').value;
    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    const formData = new FormData(this);
    
    let url = API_URL + '/sanksi-lab';
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
            setTimeout(() => { navigate('admin/sanksi'); }, 1000);
        } else {
            msg.innerHTML = '<div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px;">Gagal: ' + (data.message || 'Terjadi kesalahan') + '</div>';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan Sanksi';
        }
    })
    .catch(err => {
        console.error(err);
        msg.innerHTML = '<div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px;">Koneksi Error.</div>';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Sanksi';
    });
});

function navigate(route) {
    if (window.location.port === '8000') {
        window.location.href = '/index.php?route=' + route;
    } else {
        window.location.href = '/' + route;
    }
}
</script>
