<div class="admin-header">
    <h1>Formulir Asisten</h1>
    <a href="<?php echo BASE_URL; ?>/public/admin-asisten.php" class="btn" style="background: #95a5a6;">← Kembali</a>
</div>

<div class="card" style="max-width: 800px;">
    <form id="asistenForm" enctype="multipart/form-data">
        
        <input type="hidden" id="idAsisten">

        <div class="form-group">
            <label>Nama Lengkap <span style="color:red">*</span></label>
            <input type="text" id="nama" placeholder="Masukkan nama lengkap..." required>
        </div>

        <div class="form-group">
            <label>Email <span style="color:red">*</span></label>
            <input type="email" id="email" placeholder="email@umi.ac.id" required>
        </div>

        <div class="form-group">
            <label>Jurusan</label> <input type="text" id="jurusan" placeholder="Contoh: Teknik Informatika">
        </div>

        <div class="form-group">
            <label>Upload Foto</label>
            <input type="file" id="foto" name="foto" accept="image/*">
        </div>

        <div class="form-group">
            <label>Status Aktif</label>
            <select id="statusAktif">
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-add" id="btnSave">Simpan Data</button>
    </form>
    
    <div id="message" style="margin-top: 15px;"></div>
</div>

<script>
document.getElementById('asistenForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const btn = document.getElementById('btnSave');
    const msg = document.getElementById('message');
    btn.disabled = true;
    btn.innerText = 'Menyimpan...';

    // Pakai FormData agar bisa upload file
    const form = document.getElementById('asistenForm');
    const formData = new FormData(form);
    formData.append('nama', document.getElementById('nama').value);
    formData.append('email', document.getElementById('email').value);
    formData.append('jurusan', document.getElementById('jurusan').value);
    formData.append('statusAktif', document.getElementById('statusAktif').value);
    // file foto otomatis sudah masuk jika dipilih

    fetch(API_URL + '/asisten', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success' || data.code === 201) {
            msg.innerHTML = '<span style="color:green">Berhasil disimpan! Mengalihkan...</span>';
            setTimeout(() => { window.location.href = BASE_URL + '/public/admin-asisten.php'; }, 1000);
        } else {
            msg.innerHTML = '<span style="color:red">Gagal: ' + (data.message || 'Error validasi') + '</span>';
            btn.disabled = false;
            btn.innerText = 'Simpan Data';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        msg.innerHTML = '<span style="color:red">Terjadi kesalahan koneksi.</span>';
        btn.disabled = false;
        btn.innerText = 'Simpan Data';
    });
});
</script>