<div class="admin-header">
    <h1 id="formTitle">Tambah Peraturan Baru</h1>
    <a href="javascript:void(0)" onclick="navigate('admin/peraturan')" class="btn" style="background: #95a5a6;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card" style="max-width: 800px;">
    <form id="peraturanForm" enctype="multipart/form-data">
        <input type="hidden" id="idTataTerib" name="idTataTerib">

        <div class="form-group">
            <label>Nama Peraturan <span style="color:red">*</span></label>
            <input type="text" id="namaFile" name="namaFile" placeholder="Contoh: Disiplin Waktu Kehadiran" required>
            <small class="form-text text-muted">Judul singkat peraturan yang akan ditampilkan.</small>
        </div>

        <div class="form-group">
            <label>Deskripsi / Uraian Peraturan</label>
            <textarea id="uraFile" name="uraFile" rows="6" placeholder="Jelaskan aturan dan konsekuensi yang berlaku..."></textarea>
            <small class="form-text text-muted">Penjelasan lengkap tentang peraturan ini.</small>
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
            <i class="fas fa-save"></i> Simpan Peraturan
        </button>
    </form>
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    if (id) {
        document.getElementById('formTitle').textContent = 'Edit Peraturan';
        document.getElementById('idTataTerib').value = id;
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
    fetch(API_URL + '/tata-tertib/' + id)
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success' || response.code === 200) {
            const data = response.data;
            document.getElementById('namaFile').value = data.namaFile;
            document.getElementById('uraFile').value = data.uraFile;
            
            // If there is an existing image, maybe show it? 
            // The API response structure for image path is unknown, skipping for now to avoid broken images.
        } else {
            alert('Data tidak ditemukan');
            navigate('admin/peraturan');
        }
    })
    .catch(err => console.error(err));
}

document.getElementById('peraturanForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('idTataTerib').value;
    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    const formData = new FormData(this);
    
    // If editing, we might need to handle method spoofing if the API expects PUT for updates
    // But FormData usually requires POST. Let's check if we need _method=PUT
    let url = API_URL + '/tata-tertib';
    if (id) {
        url += '/' + id;
        // Many PHP frameworks need this for PUT with FormData
        formData.append('_method', 'PUT'); 
    }

    // Note: If the backend strictly requires PUT method for updates, 
    // standard fetch with FormData (which sets multipart/form-data) might fail if the server doesn't parse multipart on PUT.
    // A common workaround in PHP is using POST with _method=PUT.
    // I'll use POST for both, adding _method if it's an update.
    
    fetch(url, { 
        method: 'POST', 
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' || data.code === 200 || data.code === 201) { 
            msg.innerHTML = '<div style="padding: 10px; background: #d4edda; color: #155724; border-radius: 4px;">Berhasil disimpan! Redirecting...</div>';
            setTimeout(() => { navigate('admin/peraturan'); }, 1000);
        } else {
            msg.innerHTML = '<div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px;">Gagal: ' + (data.message || 'Terjadi kesalahan') + '</div>';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan Peraturan';
        }
    })
    .catch(err => {
        console.error(err);
        msg.innerHTML = '<div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px;">Koneksi Error.</div>';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan Peraturan';
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
